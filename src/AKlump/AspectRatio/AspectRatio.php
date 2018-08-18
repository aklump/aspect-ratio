<?php

namespace AKlump\AspectRatio;

/**
 * A class that handles calculating aspect ratios.
 */
class AspectRatio {

  /**
   * The Golden ratio.
   *
   * @var float
   */
  const GOLDEN = 1.61803398874989484820458683436563811772030917980576286213544862270526046281890244970720720;

  /**
   * The nice aspect ratio margin.
   *
   * A number between 0 and 1 indicating the portion of the input height to use
   * when determining the closest nice ratio.  The lower the number the faster
   * the processing.  Too low and you won't achieve the nicest ratio.
   *
   * @var float
   */
  const NICE_MARGIN = .10;

  /**
   * The default precision for decimal.
   *
   * @var int
   */
  const PRECISION = 2;

  /**
   * The total number of near ratios to return.
   *
   * @var int
   */
  const TOTAL_NEAR = 5;

  /**
   * The target width (if converting).
   *
   * @var int
   */
  protected $width;

  /**
   * The initial height.
   *
   * @var int
   */
  protected $height;

  /**
   * The decimal precision, when overridden.
   *
   * @var int
   */
  protected $precision;

  /**
   * The total near ratio count, when overridden.
   *
   * @var int
   */
  protected $totalNear;

  /**
   * The target width (if converting).
   *
   * @var int
   */
  protected $targetWidth;

  /**
   * AspectRatio constructor.
   *
   * @param int|float $width
   *   The width.
   * @param int|float $height
   *   The height.
   * @param int $precision
   *   The decimal precision.
   * @param int $total_near
   *   The total number of near ratios to calculate.
   */
  public function __construct($width, $height, $precision = NULL, $total_near = NULL) {
    $this->width = $width;
    $this->height = $height;
    $this->precision = isset($precision) ? $precision : self::PRECISION;
    $this->totalNear = isset($total_near) ? $total_near : self::TOTAL_NEAR;
  }

  /**
   * Return all ratios based on the width and height used in constructor.
   *
   * @return array
   *   An array of all ratios keyed by type.
   */
  public function getAllRatios() {
    $ratios = [];
    $ratios[] = ['original', $this->width * 1, $this->height * 1];
    $ratios[] = array_merge(['whole'], $this->getWholeNumberRatio($this->width, $this->height));

    $ratio = $this->getDecimalRatio($this->width, $this->height);
    $original_height = $this->getTargetWidth() / $ratio;
    $ratios[] = ['decimal', round($ratio, $this->precision), 1];

    $ratios[] = [
      'golden',
      round(self::GOLDEN, $this->precision),
      1,
    ];

    $ratios = array_merge($ratios, array_map(function ($item) {
      array_unshift($item, 'nearby');

      return $item;
    }, $this->getNearbyRatios($this->width, $this->height, $this->totalNear)));

    // Add in extra info.
    $ratios = array_map(function ($item) {
      $ratio = $this->getDecimalRatio($item[1], $item[2]);

      return array_merge($item, [
        $this->getTargetWidth() * 1,
        round($this->getTargetWidth() / $ratio, 0),
      ]);
    }, $ratios);

    // Sort the ratios from closest to original dimensions first.
    uasort($ratios, function ($a, $b) use ($original_height) {
      $a = abs($a[4] - $original_height);
      $b = abs($b[4] - $original_height);

      return $a - $b;
    });

    $ratios = array_map(function ($item) use ($original_height) {
      $difference = static::getHeightVariance($item[1], $original_height);
      array_push($item, $difference);
      $difference = static::getHeightVarianceRatio($item[1], $original_height);
      array_push($item, $difference);

      return $item;
    }, $ratios);

    // Add labels.
    return array_values(array_map(function ($item) use ($original_height) {
      return [
        'type' => $item[0],
        'ratio_x' => $item[1],
        'ratio_y' => $item[2],
        'width' => $item[3],
        'height' => $item[4],
        'difference_y' => $item[5],
        'difference_y_percent' => $item[6] * 100 . '%',
      ];
    }, $ratios));
  }

  private static function getHeightVariance($height, $original_height) {
    $variance = round($height - $original_height, 0);

    return $variance ? $variance : 0;
  }

  private static function getHeightVarianceRatio($height, $original_height) {
    return static::getHeightVariance($height, $original_height) / $original_height;
  }

  /**
   * Return the aspect ratio based on width and height.
   *
   * @param int|float $width
   *   The width.
   * @param int|float $height
   *   The height.
   *
   * @return float|int
   *   The aspect ratio as a decimal.
   */
  public static function getDecimalRatio($width, $height) {
    return $width / $height;
  }

  /**
   * Return the lowest number ratio that is close to the actual.
   *
   * @param int|float $width
   *   The width.
   * @param int|float $height
   *   The height.
   * @param int $count
   *   The total number of nearby ratios to return.
   *
   * @return array
   *   An array of ratios, where each element is an array with width and height
   *   as separate elements.
   */
  public static function getNearbyRatios($width, $height, $count) {
    $variant = max(1, round($height * self::NICE_MARGIN, 0));
    $candidates = [];
    for ($range_h = $height - $variant; $range_h < $height + $variant; ++$range_h) {
      $ratio = static::getWholeNumberRatio($width, $range_h);
      $candidates[] = [abs($height - $range_h), $ratio[0], $ratio[1]];
    }
    uasort($candidates, function ($a, $b) {
      if ($a[1] !== $b[1]) {
        return $a[1] - $b[1];
      }

      return $a[0] - $b[0];
    });

    $candidates = array_map(function ($item) {
      return static::getWholeNumberRatio($item[1], $item[2]);
    }, array_slice($candidates, 0, $count));

    return $candidates;
  }

  /**
   * Convert dimension from floats to integers.
   *
   * @param int|float $width
   *   The width dimension.
   * @param int|float $height
   *   The height dimension.
   *
   * @return array
   *   0 int The new width.
   *   1 int The new height.
   */
  public static function ensureWholeRatio($width, $height = 1) {
    while (intval($width) != $width || intval($height) != $height) {
      $width *= 10;
      $height *= 10;
    }

    return [intval($width), intval($height)];
  }

  /**
   * Return the lowest whole number ratio that is exact.
   *
   * @param int|float $width
   *   The width.
   * @param int|float $height
   *   The height.
   *
   * @return array
   *   Width and height as separate elements.
   */
  public static function getWholeNumberRatio($width, $height) {
    list($width, $height) = static::ensureWholeRatio($width, $height);

    $ratio = $width / $height;
    $denominator = 1;
    $i = 1;
    while (($numerator = $ratio * $i) != round($ratio * $i)) {
      ++$i;
    }
    $numerator = intval($numerator) == floatval($numerator) ? intval($numerator) : $numerator;
    $numerator = min($width, $numerator);
    $denominator = $denominator * $i;
    $denominator = min($height, $denominator);

    return [$numerator, $denominator];
  }

  /**
   * Set the target width for conversions.
   *
   * @param int $width
   *   The target width.
   *
   * @return \AKlump\AspectRatio\AspectRatio
   *   Self for chaining.
   */
  public function setTargetWidth($width) {
    $this->targetWidth = $width;

    return $this;
  }

  /**
   * Get the target width for conversions.
   *
   * @return int
   *   The target width.
   */
  public function getTargetWidth() {
    return $this->targetWidth ? $this->targetWidth : $this->width;
  }

}
