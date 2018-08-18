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

  public function setUp() {
    $this->dependencies = [];
    $this->createObj();
  }

  protected function createObj() {
    $this->obj = new AspectRatio(450, 320);
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
        [6, 5],
        [16, 13],
        [24, 19],
        [8, 7],
        [4, 3],
      ],
      768,
      634,
    );
    $tests[] = array(
      [
        [2, 1],
        [8, 3],
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
    foreach ($ratios as $ratio) {
      $this->assertContains($ratio, $nearbys);
    }
  }
}
