<?php

namespace AKlump\AspectRatio;

/**
 * @coversDefaultClass AKlump\AspectRatio/AspectRatio
 * @group ${test_group}
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class AspectRatioTest extends \PHPUnit_Framework_TestCase {

  protected $dependencies;

  public function setUp() {
    $this->dependencies = [450, 320, 2, 10, .2];
    $this->createObj();
  }

  protected function createObj() {
    list($width, $height, $precision, $total_near, $max_near_ratio) = $this->dependencies;
    $this->obj = new AspectRatio($width, $height, $precision, $total_near, $max_near_ratio);
  }

  /**
   * @dataProvider dataForTestNearbyVarianceIsCorrectComparedToGoldenProvider
   */
  public function testNearbyVarianceIsCorrectComparedToGolden($control, $needle) {
    $this->dependencies[0] = 16;
    $this->dependencies[1] = 9;
    $this->createObj();
    $this->obj->setTargetWidth(1080);
    $ratios = $this->obj->getAllRatios();
    $this->assertRatioData($control, $needle, $ratios);
  }

  /**
   * Provides data for testGetNearbyRatios.
   */
  public function dataForTestGetNearbyRatiosProvider() {
    $tests = array();
    $tests[] = array(
      [
        [2, 1],
        [3, 1],
      ],
      8,
      4,
    );
    $tests[] = array(
      [
        [2, 1],
        [3, 1],
      ],
      8,
      4,
    );
    $tests[] = array(
      [
        [5, 4],
        [6, 5],
        [7, 6],
        [8, 7],
        [9, 8],
      ],
      768,
      634,
    );

    return $tests;
  }

  /**
   * @dataProvider dataForTestGetNearbyRatiosProvider
   */
  public function testGetNearbyRatios($ratios, $width, $height) {
    $nearbys = AspectRatio::getNearbyRatios($width, $height, count($ratios), .25, $width, $height);

    // The floats cause problems so we trim them off.
    $nearbys = array_map(function ($item) {
      array_pop($item);

      return $item;
    }, $nearbys);

    foreach ($ratios as $ratio) {
      $this->assertContains($ratio, $nearbys);
    }
  }

  /**
   * Provides data for testNearbyVarianceIsCorrectComparedToGolden.
   */
  public function dataForTestNearbyVarianceIsCorrectComparedToGoldenProvider() {
    $tests = array();
    $tests[] = array(
      [
        'height' => 540,
        'difference_y' => -68,
      ],
      ['nearby', 2, 1],
    );
    $tests[] = array(
      [
        'height' => 607.5,
        'difference_y' => 0,
      ],
      ['original', 16, 9],
    );

    return $tests;
  }

  public function assertRatioData($control, $needle, $ratios) {
    $item = array_filter($ratios, function ($item) use ($needle) {
      return $item['type'] === $needle[0] && $item['ratio_x'] == $needle[1] && $item['ratio_y'] == $needle[2];
    });
    $this->assertCount(1, $item);
    $item = reset($item);
    foreach ($control as $key => $value) {
      $this->assertEquals($control[$key], $item[$key]);
    }
  }

  /**
   * Provides data for testCalculateHeightFromAspectRatio.
   */
  public function dataForTestCalculateHeightFromAspectRatioProvider() {
    $tests = array();
    $tests[] = array(
      607.5,
      1080,
      16,
      9,
    );

    return $tests;
  }

  /**
   * @dataProvider dataForTestCalculateHeightFromAspectRatioProvider
   */
  public function testCalculateHeightFromAspectRatio($height, $width, $ratio_x, $ratio_y) {
    $this->assertSame($height, AspectRatio::calculateHeightFromAspectRatio($ratio_x, $ratio_y, $width));
  }

  public function testReadMeExampleOne() {
    $this->dependencies = [768, 634, 2, 10, .2];
    $this->createObj();
    $ratios = $this->obj->getAllRatios();

    $this->assertRatioRecord([
      'whole',
      384,
      317,
      768,
      634,
      0,
      '0%',
    ], $ratios[1]);

    $this->assertRatioRecord([
      'decimal',
      1.21,
      1,
      768,
      635,
      1.0,
      '0.157728706625%',
    ], $ratios[2]);

    $this->assertRatioRecord([
      'nearby',
      6,
      5,
      768,
      640,
      6.0,
      '0.946372239748%',
    ], $ratios[3]);
  }

  public function assertRatioRecord($control, $record) {
    $this->assertEquals($record['type'], $control[0]);
    $this->assertEquals($record['ratio_x'], $control[1]);
    $this->assertEquals($record['ratio_y'], $control[2]);
    $this->assertEquals(round($record['width'], 0), $control[3]);
    $this->assertEquals(round($record['height'], 0), $control[4]);
    $this->assertEquals($record['difference_y'], $control[5]);
    $this->assertEquals($record['difference_y_percent'], $control[6]);
  }

  public function testSetGetTargetWidth() {
    $this->assertSame(450, $this->obj->getTargetWidth());
    $this->assertSame($this->obj, $this->obj->setTargetWidth(1080));
    $this->assertSame(1080, $this->obj->getTargetWidth());
  }

  public function testGetAllRatiosReturnsArrayWithKeys() {
    $ratios = $this->obj->getAllRatios();
    $this->assertInternalType('array', $ratios);
    $this->assertNotEmpty($ratios);

    $this->assertArrayHasKey('type', $ratios[0]);
    $this->assertArrayHasKey('ratio_x', $ratios[0]);
    $this->assertArrayHasKey('ratio_y', $ratios[0]);
    $this->assertArrayHasKey('width', $ratios[0]);
    $this->assertArrayHasKey('height', $ratios[0]);
    $this->assertArrayHasKey('difference_y', $ratios[0]);
    $this->assertArrayHasKey('difference_y_percent', $ratios[0]);

    // Format of values.
    foreach ($ratios as $ratio) {
      $this->assertInternalType('string', $ratio['type']);
      $this->assertRegExp('/^\d+(\.\d+)*$/', strval($ratio['ratio_x']));
      $this->assertRegExp('/^\d+(\.\d+)*$/', strval($ratio['ratio_y']));
      $this->assertRegExp('/^\d+(\.\d+)*$/', strval($ratio['width']));
      $this->assertRegExp('/^\d+(\.\d+)*$/', strval($ratio['height']));
      $this->assertRegExp('/^-?\d+(\.\d+)*$/', strval($ratio['difference_y']));
      $this->assertRegExp('/^-?\d+(\.\d+)*%$/', $ratio['difference_y_percent']);
    }
  }

  /**
   * Provides data for testGetDecimalRatio.
   */
  public function dataForTestGetDecimalRatioProvider() {
    $tests = array();
    $tests[] = array(768 / 634, 768, 634);
    $tests[] = array(0.46875, 450, 960);

    return $tests;
  }

  /**
   * @dataProvider dataForTestGetDecimalRatioProvider
   */
  public function testGetDecimalRatio($ratio, $width, $height) {
    $this->assertSame($ratio, AspectRatio::getDecimalRatio($width, $height));
  }

  /**
   * Provides data for testGetWholeNumberRatio.
   */
  public function dataForTestGetWholeNumberRatioProvider() {
    $tests = array();
    $tests[] = array([463, 100], 4.63, 1);
    $tests[] = array([384, 317], 768, 634);
    $tests[] = array([444, 187], 444, 187);
    $tests[] = array([2, 1], 8, 4);
    $tests[] = array([17, 6], 340, 120);

    return $tests;
  }

  /**
   * @dataProvider dataForTestGetWholeNumberRatioProvider
   */
  public function testGetWholeNumberRatio($ratio, $width, $height) {
    $this->assertSame($ratio, AspectRatio::getWholeNumberRatio($width, $height));
  }

}
