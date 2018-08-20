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


  public function testConsistencyOfJsonDataWithRatioAndWidth16_9() {
    $json = Bash::exec([CMD, '16:9', '--width=1080', '--json']);
    $this->assertSame('[{"type":"original","ratio_x":16,"ratio_y":9,"width":1080,"height":607.5,"difference_y":0,"difference_y_percent":"0%"},{"type":"whole","ratio_x":16,"ratio_y":9,"width":1080,"height":607.5,"difference_y":0,"difference_y_percent":"0%"},{"type":"decimal","ratio_x":1.778000000000000024868995751603506505489349365234375,"ratio_y":1,"width":1080,"height":607.4240719910011421234230510890483856201171875,"difference_y":0,"difference_y_percent":"0%"},{"type":"nearby","ratio_x":16,"ratio_y":9,"width":1080,"height":607.5,"difference_y":0,"difference_y_percent":"0%"},{"type":"nearby","ratio_x":17,"ratio_y":10,"width":1080,"height":635.2941176470588970914832316339015960693359375,"difference_y":28,"difference_y_percent":"4.60905349794%"},{"type":"nearby","ratio_x":15,"ratio_y":8,"width":1080,"height":576,"difference_y":-32,"difference_y_percent":"-5.26748971193%"},{"type":"nearby","ratio_x":17,"ratio_y":9,"width":1080,"height":571.764705882352927801548503339290618896484375,"difference_y":-36,"difference_y_percent":"-5.92592592593%"},{"type":"nearby","ratio_x":5,"ratio_y":3,"width":1080,"height":648,"difference_y":41,"difference_y_percent":"6.74897119342%"},{"type":"golden","ratio_x":1.6180000000000001048050535246147774159908294677734375,"ratio_y":1,"width":1080,"height":667.4907292954263766660005785524845123291015625,"difference_y":60,"difference_y_percent":"9.87654320988%"},{"type":"nearby","ratio_x":2,"ratio_y":1,"width":1080,"height":540,"difference_y":-68,"difference_y_percent":"-11.1934156379%"},{"type":"nearby","ratio_x":8,"ratio_y":5,"width":1080,"height":675,"difference_y":68,"difference_y_percent":"11.1934156379%"},{"type":"nearby","ratio_x":17,"ratio_y":8,"width":1080,"height":508.235294117647072198451496660709381103515625,"difference_y":-99,"difference_y_percent":"-16.2962962963%"},{"type":"nearby","ratio_x":3,"ratio_y":2,"width":1080,"height":720,"difference_y":113,"difference_y_percent":"18.6008230453%"}]', $json);
  }

  public function testConsistencyOfJsonDataWithoutModificationParameters768x634() {
    $json = Bash::exec([CMD, '768x634', '--json']);
    $this->assertSame('[{"type":"original","ratio_x":768,"ratio_y":634,"width":768,"height":634,"difference_y":0,"difference_y_percent":"0%"},{"type":"whole","ratio_x":384,"ratio_y":317,"width":768,"height":634,"difference_y":0,"difference_y_percent":"0%"},{"type":"decimal","ratio_x":1.2110000000000000763833440942107699811458587646484375,"ratio_y":1,"width":768,"height":634.1866226259289760491810739040374755859375,"difference_y":0,"difference_y_percent":"0%"},{"type":"nearby","ratio_x":6,"ratio_y":5,"width":768,"height":640,"difference_y":6,"difference_y_percent":"0.946372239748%"},{"type":"nearby","ratio_x":11,"ratio_y":9,"width":768,"height":628.3636363636363739715307019650936126708984375,"difference_y":-6,"difference_y_percent":"-0.946372239748%"},{"type":"nearby","ratio_x":5,"ratio_y":4,"width":768,"height":614.40000000000009094947017729282379150390625,"difference_y":-20,"difference_y_percent":"-3.15457413249%"},{"type":"nearby","ratio_x":7,"ratio_y":6,"width":768,"height":658.28571428571422075037844479084014892578125,"difference_y":24,"difference_y_percent":"3.78548895899%"},{"type":"nearby","ratio_x":9,"ratio_y":7,"width":768,"height":597.3333333333333712289459072053432464599609375,"difference_y":-37,"difference_y_percent":"-5.83596214511%"},{"type":"nearby","ratio_x":8,"ratio_y":7,"width":768,"height":672,"difference_y":38,"difference_y_percent":"5.99369085174%"},{"type":"nearby","ratio_x":9,"ratio_y":8,"width":768,"height":682.6666666666666287710540927946567535400390625,"difference_y":49,"difference_y_percent":"7.72870662461%"},{"type":"nearby","ratio_x":10,"ratio_y":9,"width":768,"height":691.200000000000045474735088646411895751953125,"difference_y":57,"difference_y_percent":"8.9905362776%"},{"type":"nearby","ratio_x":4,"ratio_y":3,"width":768,"height":576,"difference_y":-58,"difference_y_percent":"-9.14826498423%"},{"type":"nearby","ratio_x":11,"ratio_y":10,"width":768,"height":698.1818181818181301423464901745319366455078125,"difference_y":64,"difference_y_percent":"10.094637224%"},{"type":"golden","ratio_x":1.6180000000000001048050535246147774159908294677734375,"ratio_y":1,"width":768,"height":474.6600741656365016751806251704692840576171875,"difference_y":-159,"difference_y_percent":"-25.0788643533%"}]', $json);
  }

  public function testConsistencyOfJsonDataWithoutModificationParameters320x199() {
    $json = Bash::exec([CMD, '320x199', '--json']);
    $this->assertSame('[{"type":"original","ratio_x":320,"ratio_y":199,"width":320,"height":199,"difference_y":0,"difference_y_percent":"0%"},{"type":"whole","ratio_x":320,"ratio_y":199,"width":320,"height":199,"difference_y":0,"difference_y_percent":"0%"},{"type":"decimal","ratio_x":1.608000000000000095923269327613525092601776123046875,"ratio_y":1,"width":320,"height":199.004975124378091777543886564671993255615234375,"difference_y":0,"difference_y_percent":"0%"},{"type":"golden","ratio_x":1.6180000000000001048050535246147774159908294677734375,"ratio_y":1,"width":320,"height":197.775030902348561312464880757033824920654296875,"difference_y":-1,"difference_y_percent":"-0.502512562814%"},{"type":"nearby","ratio_x":8,"ratio_y":5,"width":320,"height":200,"difference_y":1,"difference_y_percent":"0.502512562814%"},{"type":"nearby","ratio_x":13,"ratio_y":8,"width":320,"height":196.923076923076934008349780924618244171142578125,"difference_y":-2,"difference_y_percent":"-1.00502512563%"},{"type":"nearby","ratio_x":11,"ratio_y":7,"width":320,"height":203.6363636363636260284692980349063873291015625,"difference_y":5,"difference_y_percent":"2.51256281407%"},{"type":"nearby","ratio_x":5,"ratio_y":3,"width":320,"height":192,"difference_y":-7,"difference_y_percent":"-3.5175879397%"},{"type":"nearby","ratio_x":14,"ratio_y":9,"width":320,"height":205.71428571428572240620269440114498138427734375,"difference_y":7,"difference_y_percent":"3.5175879397%"},{"type":"nearby","ratio_x":12,"ratio_y":7,"width":320,"height":186.66666666666668561447295360267162322998046875,"difference_y":-12,"difference_y_percent":"-6.03015075377%"},{"type":"nearby","ratio_x":3,"ratio_y":2,"width":320,"height":213.33333333333331438552704639732837677001953125,"difference_y":14,"difference_y_percent":"7.0351758794%"},{"type":"nearby","ratio_x":7,"ratio_y":4,"width":320,"height":182.85714285714283278139191679656505584716796875,"difference_y":-16,"difference_y_percent":"-8.04020100503%"},{"type":"nearby","ratio_x":16,"ratio_y":9,"width":320,"height":180,"difference_y":-19,"difference_y_percent":"-9.54773869347%"},{"type":"nearby","ratio_x":16,"ratio_y":11,"width":320,"height":220,"difference_y":21,"difference_y_percent":"10.5527638191%"}]', $json);
  }

  public function testConsistencyOfJsonDataWithoutModificationParameters960x555() {
    $json = Bash::exec([CMD, '960x555', '--json']);
    $this->assertSame('[{"type":"original","ratio_x":960,"ratio_y":555,"width":960,"height":555,"difference_y":0,"difference_y_percent":"0%"},{"type":"whole","ratio_x":64,"ratio_y":37,"width":960,"height":555,"difference_y":0,"difference_y_percent":"0%"},{"type":"decimal","ratio_x":1.729999999999999982236431605997495353221893310546875,"ratio_y":1,"width":960,"height":554.9132947976878540430334396660327911376953125,"difference_y":0,"difference_y_percent":"0%"},{"type":"nearby","ratio_x":12,"ratio_y":7,"width":960,"height":560,"difference_y":5,"difference_y_percent":"0.900900900901%"},{"type":"nearby","ratio_x":7,"ratio_y":4,"width":960,"height":548.5714285714285551875946111977100372314453125,"difference_y":-6,"difference_y_percent":"-1.08108108108%"},{"type":"nearby","ratio_x":5,"ratio_y":3,"width":960,"height":576,"difference_y":21,"difference_y_percent":"3.78378378378%"},{"type":"nearby","ratio_x":9,"ratio_y":5,"width":960,"height":533.3333333333333712289459072053432464599609375,"difference_y":-22,"difference_y_percent":"-3.96396396396%"},{"type":"nearby","ratio_x":11,"ratio_y":6,"width":960,"height":523.6363636363636260284692980349063873291015625,"difference_y":-31,"difference_y_percent":"-5.58558558559%"},{"type":"nearby","ratio_x":13,"ratio_y":8,"width":960,"height":590.7692307692308304467587731778621673583984375,"difference_y":36,"difference_y_percent":"6.48648648649%"},{"type":"golden","ratio_x":1.6180000000000001048050535246147774159908294677734375,"ratio_y":1,"width":960,"height":593.3250927070456555156852118670940399169921875,"difference_y":38,"difference_y_percent":"6.84684684685%"},{"type":"nearby","ratio_x":13,"ratio_y":7,"width":960,"height":516.923076923076905586640350520610809326171875,"difference_y":-38,"difference_y_percent":"-6.84684684685%"},{"type":"nearby","ratio_x":8,"ratio_y":5,"width":960,"height":600,"difference_y":45,"difference_y_percent":"8.10810810811%"},{"type":"nearby","ratio_x":11,"ratio_y":7,"width":960,"height":610.9090909090908780854078941047191619873046875,"difference_y":56,"difference_y_percent":"10.0900900901%"},{"type":"nearby","ratio_x":14,"ratio_y":9,"width":960,"height":617.1428571428572240620269440114498138427734375,"difference_y":62,"difference_y_percent":"11.1711711712%"}]', $json);
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
