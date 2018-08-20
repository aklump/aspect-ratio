<?php

namespace AKlump\AspectRatio;

use AKlump\LoftLib\Bash\Bash;

/**
 * The path to the CLI executable.
 *
 * @var cmd
 */
define('CMD', realpath(__DIR__ . '/../../../../aspratio'));


/**
 * @coversDefaultClass AKlump\AspectRatio/AspectRatio
 * @group ${test_group}
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class AspectRatioIntegrationTest extends \PHPUnit_Framework_TestCase {

  public function testConsistencyOfJsonDataWithoutModificationParameters() {
    $json = Bash::exec([CMD, '960x555', '--json']);
    $this->assertSame('[{"type":"original","ratio_x":960,"ratio_y":555,"width":960,"height":555,"difference_y":0,"difference_y_percent":"0%"},{"type":"whole","ratio_x":64,"ratio_y":37,"width":960,"height":555,"difference_y":0,"difference_y_percent":"0%"},{"type":"decimal","ratio_x":1.729999999999999982236431605997495353221893310546875,"ratio_y":1,"width":960,"height":554.9132947976878540430334396660327911376953125,"difference_y":0,"difference_y_percent":"0%"},{"type":"nearby","ratio_x":12,"ratio_y":7,"width":960,"height":560,"difference_y":5,"difference_y_percent":"0.900900900901%"},{"type":"nearby","ratio_x":7,"ratio_y":4,"width":960,"height":548.5714285714285551875946111977100372314453125,"difference_y":-6,"difference_y_percent":"-1.08108108108%"},{"type":"nearby","ratio_x":5,"ratio_y":3,"width":960,"height":576,"difference_y":21,"difference_y_percent":"3.78378378378%"},{"type":"nearby","ratio_x":9,"ratio_y":5,"width":960,"height":533.3333333333333712289459072053432464599609375,"difference_y":-22,"difference_y_percent":"-3.96396396396%"},{"type":"nearby","ratio_x":11,"ratio_y":6,"width":960,"height":523.6363636363636260284692980349063873291015625,"difference_y":-31,"difference_y_percent":"-5.58558558559%"},{"type":"golden","ratio_x":1.6180000000000001048050535246147774159908294677734375,"ratio_y":1,"width":960,"height":593.3250927070456555156852118670940399169921875,"difference_y":38,"difference_y_percent":"6.84684684685%"}]', $json);
  }

  /**
   * @expectedException \AKlump\LoftLib\Bash\FailedExecException
   * @expectedExceptionMessage Unknown flag: z
   */
  public function testInvalidFlag() {
    Bash::exec([CMD, '-z']);
  }

  /**
   * @expectedException \AKlump\LoftLib\Bash\FailedExecException
   * @expectedExceptionMessage Unknown parameter: bogus
   */
  public function testInvalidParameter() {
    Bash::exec([CMD, '--bogus']);
  }

  /**
   * @expectedException \AKlump\LoftLib\Bash\FailedExecException
   * @expectedExceptionMessage Argument must be width height in one of these
   *   formats: "WIDTHxHEIGHT" or "ASPECT:RATIO"
   */
  public function testNoArguments() {
    Bash::exec(CMD);
  }

  public function testJsonParameterReturnsJsonString() {
    $json = Bash::exec([CMD, '960x555', '--json']);
    $this->assertInternalType('array', json_decode($json));
  }

}
