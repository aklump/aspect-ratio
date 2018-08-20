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
   * A number between 0 and 1 indicating the percentage of dimension to use
   * when determining the closest nice ratio.  The lower the number the faster
   * the processing.  Too low and you won't achieve the nicest ratios.
   *
   * @var float
   */
  const NEAR_MARGIN = .06;

  /**
   * The maximium variance to allow for nearby ratios.
   */
  const MAX_NEAR_VARIANCE_RATIO = .2;

  /**
   * The default precision for decimal.
   *
   * @var int
   */
  const PRECISION = 3;

  /**
   * The total number of near ratios to return.
   *
   * @var int
   */
  const TOTAL_NEAR = 10;

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
      $calculated_height = static::calculateHeightFromAspectRatio($item[1], $item[2], $this->getTargetWidth());
      array_push($item, static::getHeightVarianceRatio($calculated_height, $original_height));

      return $item;
    }, $ratios);

    $ratios = array_merge($ratios, array_map(function ($item) {
      array_unshift($item, 'nearby');

      return $item;
    }, $this->getNearbyRatios($this->width, $this->height, $this->totalNear, $this->maxNearVarianceRatio, $this->getTargetWidth(), $this->getTargetHeight())));

    // Add in target dimensions.
    $ratios = array_map(function ($item) use ($original_height) {
      return array_merge($item, [
        ($width = $this->getTargetWidth() * 1),
        ($height = static::calculateHeightFromAspectRatio($item[1], $item[2], $width)),
        static::getHeightVariance($height, $original_height),
      ]);
    }, $ratios);

    // Sort the ratios from closest to original dimensions first, based on percentage.
    uasort($ratios, function ($a, $b) {
      return abs($a[6]) - abs($b[6]);
    });

    // Add labels.
    return array_values(array_map(function ($item) use ($original_height) {
      return [
        'type' => $item[0],
        'ratio_x' => $item[1],
        'ratio_y' => $item[2],
        'width' => $item[4],
        'height' => $item[5],
        'difference_y' => $item[6],
        'difference_y_percent' => $item[3] * 100 . '%',
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
   * @param int $ratio_x
   *   The width component of the aspect ratio.
   * @param int $ratio_y
   *   The height component of the aspect ratio.
   * @param int|float $width
   *   The target width.
   *
   * @return float|int
   *   The target height.
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
  public static function getNearbyRatios($width, $height, $count, $max_variance_ratio, $target_width, $target_height) {
    $height_variant = max(1, round($height * static::NEAR_MARGIN, 0));
    $width_variant = max(1, round($width * static::NEAR_MARGIN, 0));
    $candidates = [];

    for ($candidate_width = $width - $width_variant; $candidate_width <= $width + $width_variant; ++$candidate_width) {
      for ($candidate_height = $height - $height_variant; $candidate_height <= $height + $height_variant; ++$candidate_height) {
        if (!$candidate_height || !$candidate_width) {
          continue;
        }
        $candidate_ratio = static::getWholeNumberRatio($candidate_width, $candidate_height);
        $computed_height = static::calculateHeightFromAspectRatio($candidate_width, $candidate_height, $target_width);
        $variance_ratio = static::getHeightVarianceRatio($computed_height, $target_height);
        if (abs($variance_ratio) <= $max_variance_ratio) {
          $hash = implode(':', $candidate_ratio);
          if (!isset($candidates[$hash])) {
            array_push($candidate_ratio, $variance_ratio);
            $candidates[$hash] = $candidate_ratio;
          }
        }
      }
    }

    uasort($candidates, function ($a, $b) {
      if ($a[0] !== $b[0]) {
        return $a[0] - $b[0];
      }

      return abs($a[2]) - abs($b[2]);
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
    if (($width = $this->getTargetWidth()) != $this->width) {
      $height = static::calculateHeightFromAspectRatio($this->width, $this->height, $width);
    }

    return $height;
  }

}
