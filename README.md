# Aspect Ratio

A project to work with aspect ratios.  While the aspect ratio is a simple calculation, the nearest "nice" aspect ratio is not, and that's where this project stands out.  You may enter any dimension set and the actual aspect ratio will be calculated in both whole numbers and decimal, in addition you will obtain the closest "nice" aspect ratios, e.g. 16:9, 3:4, etc.  As a bonus, the golden ratio is also calculated against the width.

Here is an example of the output of the CLI.

    $ aspratio 768x634
    Type         Ratio    Dimensions    Variance    Variance %
    whole      384:317     768 x 634           0            0%
    decimal      1.211     768 x 634           0            0%
    nearby         6:5     768 x 640           6          0.9%
    nearby        11:9     768 x 628          -6         -0.9%
    nearby         5:4     768 x 614         -20         -3.2%
    nearby         7:6     768 x 658          24          3.8%
    nearby         9:7     768 x 597         -37         -5.8%
    nearby         8:7     768 x 672          38            6%
    nearby         9:8     768 x 683          49          7.7%
    nearby        10:9     768 x 691          57            9%
    nearby         4:3     768 x 576         -58         -9.1%
    nearby       11:10     768 x 698          64         10.1%
    golden       1.618     768 x 475        -159        -25.1%

* The nearby ratios are listed in the order of variance from the original height with the least variance first.

## Install Globally Using Composer

To be able to use `aspratio` from any directory in your CLI you may want to install this globally.

    composer global require aklump/aspect-ratio

Make sure you have the composer bin dir in your `PATH`. The default value is _~/.composer/vendor/bin_, but you can check the value that you need to use by running `composer global config bin-dir --absolute`.
    
To check this you must open _~/.bash_profile_ (or _~/.bashrc_); you're looking for a line that looks like the following, if you can't find, you'll need to add it.
                                 
    export PATH=~/.composer/vendor/bin:$PATH

## Quick Start

1. To determine the _aspect ratio_, _nearby aspect ratios_, and _golden ratio_ of a dimension set type the following in your terminal; the variance shows you the extent to which the height varies for a nearby ratio:
        
        $ aspratio 960x555
        Type       Ratio    Dimensions    Variance    Variance %
        whole      64:37     960 x 555           0            0%
        decimal     1.73     960 x 555           0            0%
        nearby      12:7     960 x 560           5          0.9%
        nearby       7:4     960 x 549          -6         -1.1%
        nearby       5:3     960 x 576          21          3.8%
        nearby       9:5     960 x 533         -22           -4%
        nearby      11:6     960 x 524         -31         -5.6%
        nearby      13:8     960 x 591          36          6.5%
        golden     1.618     960 x 593          38          6.8%
        nearby      13:7     960 x 517         -38         -6.8%
        nearby       8:5     960 x 600          45          8.1%
        nearby      11:7     960 x 611          56         10.1%
        nearby      14:9     960 x 617          62         11.2%

        
1. Use the `--precision` to control the rounding precision of the _decimal_ version.

        $ aspratio 960x555 --precision=6
        Type          Ratio    Dimensions    Variance    Variance %
        ...
        decimal     1.72973     960 x 555           0            0%
        ...
        golden     1.618034     960 x 593          38          6.8%
        ...

1. Use `--json` to return the output in JSON.

        $ aspratio 960x555 --json
        [{"type":"original","ratio_x":960,"ratio_y":555,"width":960,"height":555,"difference_y":0,"difference_y_percent":"0%"},{"type":"whole","ratio_x":64,"ratio_y":37,"width":960,"height":555,"difference_y":0,"difference_y_percent":"0%"},{"type":"decimal","ratio_x":1.729999999999999982236431605997495353221893310546875,"ratio_y":1,"width":960,"height":554.9132947976878540430334396660327911376953125,"difference_y":0,"difference_y_percent":"0%"},{"type":"nearby","ratio_x":12,"ratio_y":7,"width":960,"height":560,"difference_y":5,"difference_y_percent":"0.900900900901%"},{"type":"nearby","ratio_x":7,"ratio_y":4,"width":960,"height":548.5714285714285551875946111977100372314453125,"difference_y":-6,"difference_y_percent":"-1.08108108108%"},{"type":"nearby","ratio_x":5,"ratio_y":3,"width":960,"height":576,"difference_y":21,"difference_y_percent":"3.78378378378%"},{"type":"nearby","ratio_x":9,"ratio_y":5,"width":960,"height":533.3333333333333712289459072053432464599609375,"difference_y":-22,"difference_y_percent":"-3.96396396396%"},{"type":"nearby","ratio_x":11,"ratio_y":6,"width":960,"height":523.6363636363636260284692980349063873291015625,"difference_y":-31,"difference_y_percent":"-5.58558558559%"},{"type":"nearby","ratio_x":13,"ratio_y":8,"width":960,"height":590.7692307692308304467587731778621673583984375,"difference_y":36,"difference_y_percent":"6.48648648649%"},{"type":"golden","ratio_x":1.6180000000000001048050535246147774159908294677734375,"ratio_y":1,"width":960,"height":593.3250927070456555156852118670940399169921875,"difference_y":38,"difference_y_percent":"6.84684684685%"},{"type":"nearby","ratio_x":13,"ratio_y":7,"width":960,"height":516.923076923076905586640350520610809326171875,"difference_y":-38,"difference_y_percent":"-6.84684684685%"},{"type":"nearby","ratio_x":8,"ratio_y":5,"width":960,"height":600,"difference_y":45,"difference_y_percent":"8.10810810811%"},{"type":"nearby","ratio_x":11,"ratio_y":7,"width":960,"height":610.9090909090908780854078941047191619873046875,"difference_y":56,"difference_y_percent":"10.0900900901%"},{"type":"nearby","ratio_x":14,"ratio_y":9,"width":960,"height":617.1428571428572240620269440114498138427734375,"difference_y":62,"difference_y_percent":"11.1711711712%"}]



1. See _Usage_ for more examples.

## Contributing

If you find this project useful... please consider [making a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4E5KZHDQCEUV8&item_name=Gratitude%20for%20aklump%2Faspect-ratio).

## Usage

### Determine height based on aspect ratio and width

1. Determine the height by entering aspect ratio and width.
1. Use the dimensions listed as _original_

        $ aspratio 16:9 --width=1080
        Type       Ratio    Dimensions    Variance    Variance %
        whole       16:9    1080 x 608           0            0%
        decimal    1.778    1080 x 607           0            0%
        nearby      16:9    1080 x 608           0            0%
        nearby     17:10    1080 x 635          28          4.6%
        nearby      15:8    1080 x 576         -32         -5.3%
        nearby      17:9    1080 x 572         -36         -5.9%
        nearby       5:3    1080 x 648          41          6.7%
        golden     1.618    1080 x 667          60          9.9%
        nearby       2:1    1080 x 540         -68        -11.2%
        nearby       8:5    1080 x 675          68         11.2%
        nearby      17:8    1080 x 508         -99        -16.3%
        nearby       3:2    1080 x 720         113         18.6%


### Scale dimensions maintaining aspect ratio

1. To get new dimensions enter existing dimensions and the new width.

        $ aspratio 1080x520 --width=320
        Type       Ratio    Dimensions    Variance    Variance %
        whole      81:39     320 x 154           0            0%
        decimal    2.077     320 x 154           0            0%
        nearby      17:8     320 x 151          -3         -1.9%
        nearby      15:7     320 x 149          -5         -3.2%
        nearby       2:1     320 x 160           6          3.9%
        nearby      13:6     320 x 148          -6         -3.9%
        nearby      11:5     320 x 145          -9         -5.8%
        nearby       9:4     320 x 142         -12         -7.8%
        nearby      16:7     320 x 140         -14         -9.1%
        nearby      17:9     320 x 169          15          9.7%
        nearby       7:3     320 x 137         -17          -11%
        nearby      15:8     320 x 171          17           11%
        golden     1.618     320 x 198          44         28.6%


### Inversion

1. To invert the width, height use the `-i` flag.  This is like going from landscape to portrait.

        $ aspratio 768x512
        Type       Ratio    Dimensions    Variance    Variance %
        whole        3:2     768 x 512           0            0%
        decimal      1.5     768 x 512           0            0%
        nearby       3:2     768 x 512           0            0%
        nearby      13:9     768 x 532          20          3.9%
        nearby      11:7     768 x 489         -23         -4.5%
        nearby      10:7     768 x 538          26          5.1%
        nearby       8:5     768 x 480         -32         -6.3%
        golden     1.618     768 x 475         -37         -7.2%
        nearby       7:5     768 x 549          37          7.2%
        nearby      13:8     768 x 473         -39         -7.6%
        nearby      11:8     768 x 559          47          9.2%
        nearby       5:3     768 x 461         -51          -10%
        nearby       4:3     768 x 576          64         12.5%
        
        $ aspratio 768x512 -i
        Type       Ratio    Dimensions    Variance    Variance %
        whole        2:3     512 x 768           0            0%
        decimal    0.667     512 x 768           0            0%
        nearby       2:3     512 x 768           0            0%
        nearby      9:14     512 x 796          28          3.6%
        nearby      7:10     512 x 731         -37         -4.8%
        nearby      7:11     512 x 805          37          4.8%
        nearby       5:7     512 x 717         -51         -6.6%
        nearby       5:8     512 x 819          51          6.6%
        nearby      8:13     512 x 832          64          8.3%
        nearby      8:11     512 x 704         -64         -8.3%
        nearby       3:4     512 x 683         -85        -11.1%
        nearby       3:5     512 x 853          85         11.1%
        golden     1.618     512 x 316        -452        -58.9%



### Fine Tuning

1. Use `--nearby` and `--variance` to fine tune the nearby calculations:
1. `--nearby` returns how many nearby values are returned from the result set sorted by the width portion of the ratio, low to high.  Setting this lower will reduce the number of more accurate nearby ratios, which have a higher numerator.
1. `--variance` sets the maximum variance allowed.  This may be a percentage or a number.  This first example shows the maximum entered as a percentage.  Because this is lower than the default, the ratio values are more accurate, but the ratios are not as nice, that is to say the numbers are higher.  Compare that to the default variance where the ratios are "nicer".

        $ aspratio 370x111 --variance=2%
        Type       Ratio    Dimensions    Variance    Variance %
        whole       10:3     370 x 111           0            0%
        decimal    3.333     370 x 111           0            0%
        nearby      10:3     370 x 111           0            0%
        nearby      27:8     370 x 110          -1         -0.9%
        nearby     33:10     370 x 112           1          0.9%
        nearby     37:11     370 x 110          -1         -0.9%
        nearby     43:13     370 x 112           1          0.9%
        nearby     47:14     370 x 110          -1         -0.9%
        nearby      17:5     370 x 109          -2         -1.8%
        nearby      23:7     370 x 113           2          1.8%
        nearby     36:11     370 x 113           2          1.8%
        nearby     44:13     370 x 109          -2         -1.8%
        golden     1.618     370 x 229         118        106.3%

        $ aspratio 370x111 --variance=80%
        Type       Ratio    Dimensions    Variance    Variance %
        whole       10:3     370 x 111           0            0%
        decimal    3.333     370 x 111           0            0%
        nearby      10:3     370 x 111           0            0%
        nearby      17:5     370 x 109          -2         -1.8%
        nearby      13:4     370 x 114           3          2.7%
        nearby       7:2     370 x 106          -5         -4.5%
        nearby      16:5     370 x 116           5          4.5%
        nearby      19:6     370 x 117           6          5.4%
        nearby      18:5     370 x 103          -8         -7.2%
        nearby      11:3     370 x 101         -10           -9%
        nearby       3:1     370 x 123          12         10.8%
        nearby      15:4      370 x 99         -12        -10.8%
        golden     1.618     370 x 229         118        106.3%

