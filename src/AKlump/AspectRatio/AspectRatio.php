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
  const NEAR_MARGIN = .1;

  /**
   * The maximium variance to allow for nearby ratios.
   */
  const MAX_NEAR_VARIANCE_RATIO = .20;

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
   * The max near variance ratio, when overridden.
   *
   * @var float|int|null
   */
  protected $maxNearVarianceRatio;

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
   * @param int $max_near_variance_ratio
   *   The max number variance ratio.
   */
  public function __construct(
    $width,
    $height,
    $precision = NULL,
    $total_near = NULL,
    $max_near_variance_ratio = NULL
  ) {
    $this->width = $width;
    $this->height = $height;
    $this->precision = isset($precision) ? $precision : static::PRECISION;
    $this->totalNear = isset($total_near) ? $total_near : static::TOTAL_NEAR;
    $this->maxNearVarianceRatio = isset($max_near_variance_ratio) ? $max_near_variance_ratio : static::MAX_NEAR_VARIANCE_RATIO;
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
      round(static::GOLDEN, $this->precision),
      1,
    ];

    // Add in variances.
    $ratios = array_map(function ($item) use ($original_height) {
      $ratio = $this->getDecimalRatio($item[1], $item[2]);
      $calculated_height = round($this->getTargetWidth() / $ratio, 0);
      array_push($item, static::getHeightVariance($calculated_height, $original_height));
      array_push($item, static::getHeightVarianceRatio($calculated_height, $original_height));

      return $item;
    }, $ratios);

    $ratios = array_merge($ratios, array_map(function ($item) {
      array_unshift($item, 'nearby');

      return $item;
    }, $this->getNearbyRatios($this->width, $this->height, $this->totalNear)));

    // Add in target dimensions.
    $ratios = array_map(function ($item) {
      $ratio = $this->getDecimalRatio($item[1], $item[2]);

      return array_merge($item, [
        $this->getTargetWidth() * 1,
        round($this->getTargetWidth() / $ratio, 0),
      ]);
    }, $ratios);

    // Sort the ratios from closest to original dimensions first.
    uasort($ratios, function ($a, $b) {
      return abs($a[4]) - abs($b[4]);
    });

    // Add labels.
    return array_values(array_map(function ($item) use ($original_height) {
      return [
        'type' => $item[0],
        'ratio_x' => $item[1],
        'ratio_y' => $item[2],
        'width' => $item[5],
        'height' => $item[6],
        'difference_y' => $item[3],
        'difference_y_percent' => $item[4] * 100 . '%',
      ];
    }, $ratios));
  }

  /**
   * Return the height variance as a value..
   *
   * @param int $height
   *   The calculated height.
   * @param int $original_height
   *   The original height against which to measure variance.
   *
   * @return float|int
   *   The variance as a value.
   */
  private static function getHeightVariance($height, $original_height) {
    $variance = round($height - $original_height, 0);

    return $variance ? $variance : 0;
  }

  /**
   * Return the height variance ratio.
   *
   * @param int $height
   *   The calculated height.
   * @param int $original_height
   *   The original height against which to measure variance.
   *
   * @return float|int
   *   The variance as a ratio.
   */
  private static function getHeightVarianceRatio($height, $original_height) {
    return static::getHeightVariance($height, $original_height) / $original_height;
  }

  /**
   * Get the height from an aspect ratio.
   *
   * @param $ratio_x
   * @param $ratio_y
   * @param $width
   *
   * @return float|int
   */
  public static function calculateHeightFromAspectRatio($ratio_x, $ratio_y, $width) {
    return $width * ($ratio_y / $ratio_x);
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
   * @param int $max_variance_ratio
   *   The maximum variance ratio to allow.
   *
   * @return array
   *   An array of ratios, where each element is an array with width and height
   *   as separate elements.  These are ordered based on the first number of
   *   the ratio lowest to highest and not by the variance.
   */
  public static function getNearbyRatios($width, $height, $count) {
    $variant = max(1, round($height * self::NICE_MARGIN, 0));
    $candidates = [];
    for ($candidate_height = $height - $variant; $candidate_height < $height + $variant; ++$candidate_height) {
      $candidate = static::getWholeNumberRatio($width, $candidate_height);
      array_push($candidate, static::getHeightVariance($candidate_height, $height));
      array_push($candidate, static::getHeightVarianceRatio($candidate_height, $height));
      $candidates[] = $candidate;
    }
    uasort($candidates, function ($a, $b) {
      if ($a[0] !== $b[0]) {
        return $a[0] - $b[0];
      }

      return $a[2] - $b[2];
    });

    $candidates = array_map(function ($item) {
      list($item[0], $item[1]) = static::getWholeNumberRatio($item[0], $item[1]);

      return $item;
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

  /**
   * Get the target height for conversions.
   *
   * @return int
   *   The target height.
   */
  public function getTargetHeight() {
    $height = $this->height;
    if ($this->width !== ($target_width = $this->getTargetWidth())) {
      $height = static::calculateHeightFromAspectRatio($this->width, $this->height, $target_width);
    }

    return $height;
  }

}
