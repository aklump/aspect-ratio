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
    $this->dependencies = [450, 320];
    $this->createObj();
  }

  protected function createObj() {
    list($width, $height) = $this->dependencies;
    $this->obj = new AspectRatio($width, $height);
  }

  public function testReadMeExampleOne() {
    $this->dependencies = [768, 634];
    $this->createObj();
    $ratios = $this->obj->getAllRatios();

    $this->assertRatioRecord([
      'whole',
      384,
      317,
      768,
      634.0,
      0,
      '0%',
    ], $ratios[1]);

    $this->assertRatioRecord([
      'decimal',
      1.21,
      1,
      768,
      635.0,
      1.0,
      '0.157728706625%',
    ], $ratios[2]);

    $this->assertRatioRecord([
      'nearby',
      6,
      5,
      768,
      640.0,
      6.0,
      '0.946372239748%',
    ], $ratios[3]);
  }

  public function assertRatioRecord($control, $record) {
    $this->assertSame($record['type'], $control[0]);
    $this->assertSame($record['ratio_x'], $control[1]);
    $this->assertSame($record['ratio_y'], $control[2]);
    $this->assertSame($record['width'], $control[3]);
    $this->assertSame($record['height'], $control[4]);
    $this->assertSame($record['difference_y'], $control[5]);
    $this->assertSame($record['difference_y_percent'], $control[6]);
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

  /**
   * Provides data for testGetNearbyRatios.
   */
  public function dataForTestGetNearbyRatiosProvider() {
    $tests = array();
    $tests[] = array(
      [
        [6, 5, 6],
        [16, 13, -10],
        [24, 19, -26],
        [8, 7, 38],
        [4, 3, -58],
      ],
      768,
      634,
    );
    $tests[] = array(
      [
        [2, 1, 0],
        [8, 3, -1],
      ],
      8,
      4,
    );

    return $tests;
  }

  /**
   * @dataProvider dataForTestGetNearbyRatiosProvider
   */
  public function testGetNearbyRatios($ratios, $width, $height) {
    $nearbys = AspectRatio::getNearbyRatios($width, $height, count($ratios));

    // The floats cause problems so we trim them off.
    $nearbys = array_map(function ($item) {
      return array_slice($item, 0, 3);
    }, $nearbys);
    foreach ($ratios as $ratio) {
      $this->assertContains($ratio, $nearbys);
    }
  }

}
