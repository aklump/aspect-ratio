# Aspect Ratio

A project to work with aspect ratios.  While the aspect ratio is a simple calculation, the nearest "nice" aspect ratio is not, and that's where this project stands out.  You may enter any dimension set and the actual aspect ratio will be calculated in both whole numbers and decimal, in addition you will obtain the closest "nice" aspect ratios, e.g. 16:9, 3:4, etc.  As a bonus, the golden ratio is also calculated against the width.

Here is an example of the output of the CLI.

    $ aspratio 768,634
    Type         Ratio    Dimensions    Variance    Variance %
    whole      384:317     768 x 634           0            0%
    decimal       1.21     768 x 635           1          0.2%
    nearby         6:5     768 x 640           6          0.9%
    nearby       16:13     768 x 624         -10         -1.6%
    nearby       24:19     768 x 608         -26         -4.1%
    nearby         8:7     768 x 672          38            6%
    nearby         4:3     768 x 576         -58         -9.1%
    golden        1.62     768 x 474        -160        -25.2%

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
        nearby      16:9     960 x 540         -15         -2.7%
        nearby       5:3     960 x 576          21          3.8%
        golden      1.62     960 x 593          38          6.8%
        nearby       8:5     960 x 600          45          8.1%
        nearby      15:8     960 x 512         -43         -7.7%
        
1. Use the `--precision` to control the rounding precision of the _decimal_ version.

        $ aspratio 960x555 --precision=4
        Type        Ratio    Dimensions    Variance    Variance %
        ...
        decimal    1.7297     960 x 555           0            0%
        ...
        golden      1.618     960 x 593          38          6.8%
        ...

1. Use `--json` to return the output in JSON.

        $ aspratio 960x555 --json
        [{"type":"original","ratio_x":960,"ratio_y":555,"width":960,"height":555,"difference_y":0,"difference_y_percent":"0%"},{"type":"whole","ratio_x":64,"ratio_y":37,"width":960,"height":555,"difference_y":0,"difference_y_percent":"0%"},{"type":"decimal","ratio_x":1.729999999999999982236431605997495353221893310546875,"ratio_y":1,"width":960,"height":554.9132947976878540430334396660327911376953125,"difference_y":0,"difference_y_percent":"0%"},{"type":"nearby","ratio_x":12,"ratio_y":7,"width":960,"height":560,"difference_y":5,"difference_y_percent":"0.900900900901%"},{"type":"nearby","ratio_x":16,"ratio_y":9,"width":960,"height":540,"difference_y":-15,"difference_y_percent":"-2.7027027027%"},{"type":"nearby","ratio_x":5,"ratio_y":3,"width":960,"height":576,"difference_y":21,"difference_y_percent":"3.78378378378%"},{"type":"golden","ratio_x":1.62000000000000010658141036401502788066864013671875,"ratio_y":1,"width":960,"height":592.59259259259260943508706986904144287109375,"difference_y":38,"difference_y_percent":"6.84684684685%"},{"type":"nearby","ratio_x":8,"ratio_y":5,"width":960,"height":600,"difference_y":45,"difference_y_percent":"8.10810810811%"},{"type":"nearby","ratio_x":15,"ratio_y":8,"width":960,"height":512,"difference_y":-43,"difference_y_percent":"-7.74774774775%"}]

1. See _Usage_ for more examples.

## Contributing

If you find this project useful... please consider [making a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4E5KZHDQCEUV8&item_name=Gratitude%20for%20aklump%2Faspect-ratio).

## Usage

### Determine height based on aspect ratio and width

1. Determine the height by entering aspect ratio and width.
1. Use the dimensions listed as _original_

        $ aspratio 16:9 --width=1080
        Type        Ratio    Dimensions    Variance    Variance %
        whole        16:9    1080 x 608           0            0%
        decimal    1.78:1    1080 x 607          -1         -0.2%
        nearby       16:9    1080 x 608           0            0%
        golden     1.62:1    1080 x 667          59          9.7%
        nearby        2:1    1080 x 540         -68        -11.1%

### Scale dimensions maintaining aspect ratio

1. To get new dimensions enter existing dimensions and the new width.

        $ aspratio 1080x520 --width=320
        Type        Ratio    Dimensions    Variance    Variance %
        whole       81:39     320 x 154           0            0%
        decimal    2.08       320 x 154           0            0%
        nearby        2       320 x 160           6          3.8%
        nearby       15:7     320 x 149          -5         -3.1%
        nearby      24:11     320 x 147          -7         -4.8%
        nearby       20:9     320 x 144         -10         -6.5%
        nearby        9:4     320 x 142         -12         -7.7%
        golden     1.62       320 x 198          43         27.9%

### Inversion

1. To invert the width, height use the `-i` flag.  This is like going from landscape to portrait.

        $ aspratio 768x512
        Type        Ratio    Dimensions    Variance    Variance %
        whole         3:2     768 x 512           0            0%
        decimal       1.5     768 x 512           0            0%
        nearby        3:2     768 x 512           0            0%
        nearby      32:21     768 x 504          -8         -1.6%
        nearby      16:11     768 x 528          16          3.1%
        nearby        8:5     768 x 480         -32         -6.3%
        nearby      24:17     768 x 544          32          6.3%
        golden       1.62     768 x 474         -38         -7.4%
        
        $ aspratio 768x512 -i
        Type        Ratio    Dimensions    Variance    Variance %
        whole         2:3     512 x 768           0            0%
        nearby        2:3     512 x 768           0            0%
        decimal      0.67     512 x 764          -4         -0.5%
        nearby      16:25     512 x 800          32          4.2%
        nearby      16:23     512 x 736         -32         -4.2%
        nearby       8:13     512 x 832          64          8.3%
        nearby       8:11     512 x 704         -64         -8.3%
        golden       1.62     512 x 316        -452        -58.9%


### Fine Tuning

1. Use `--nearby` and `--variance` to fine tune the nearby calculations:
1. `--nearby` limits the maximum number of nearby values calculated.
1. `--variance` sets the maximum variance allowed.  This may be percentage or a number.  This first example shows the maximum entered as a percentage.

        $ aspratio 768x634  --variance=50% --nearby=10
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal       1.21     768 x 635           1          0.2%
        nearby         6:5     768 x 640           6          0.9%
        nearby       16:13     768 x 624         -10         -1.6%
        nearby       32:27     768 x 648          14          2.2%
        nearby       24:19     768 x 608         -26         -4.1%
        nearby       48:41     768 x 656          22          3.5%
        nearby         8:7     768 x 672          38            6%
        nearby       32:25     768 x 600         -34         -5.4%
        nearby       48:37     768 x 592         -42         -6.6%
        nearby         4:3     768 x 576         -58         -9.1%
        nearby       32:29     768 x 696          62          9.8%
        golden        1.62     768 x 474        -160        -25.2%
        
        $ ./aspratio 768x634  --variance=50% --nearby=5
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal       1.21     768 x 635           1          0.2%
        nearby         6:5     768 x 640           6          0.9%
        nearby       16:13     768 x 624         -10         -1.6%
        nearby       24:19     768 x 608         -26         -4.1%
        nearby         8:7     768 x 672          38            6%
        nearby         4:3     768 x 576         -58         -9.1%
        golden        1.62     768 x 474        -160        -25.2%
        
        $ ./aspratio 768x634  --variance=10% --nearby=5
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal       1.21     768 x 635           1          0.2%
        nearby         6:5     768 x 640           6          0.9%
        nearby       16:13     768 x 624         -10         -1.6%
        nearby       24:19     768 x 608         -26         -4.1%
        nearby         8:7     768 x 672          38            6%
        nearby         4:3     768 x 576         -58         -9.1%
        golden        1.62     768 x 474        -160        -25.2%
          
1. Here is `--variance` entered as a maximum difference in height:

        $ aspratio 768x634  --variance=20 --nearby=5
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal     1.21:1     768 x 635           1          0.2%
        nearby         6:5     768 x 640           6          0.9%
        nearby       16:13     768 x 624         -10         -1.6%
        nearby       24:19     768 x 608         -26         -4.1%
        nearby         8:7     768 x 672          38            6%
        nearby         4:3     768 x 576         -58         -9.1%
        golden      1.62:1     768 x 474        -160        -25.2%
        
        $ aspratio 768x634  --variance=100 --nearby=5
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal     1.21:1     768 x 635           1          0.2%
        nearby         6:5     768 x 640           6          0.9%
        nearby       16:13     768 x 624         -10         -1.6%
        nearby       24:19     768 x 608         -26         -4.1%
        nearby         8:7     768 x 672          38            6%
        nearby         4:3     768 x 576         -58         -9.1%
        golden      1.62:1     768 x 474        -160        -25.2%
        
        $ aspratio 768x634  --variance=5 --nearby=10
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal     1.21:1     768 x 635           1          0.2%
        nearby         6:5     768 x 640           6          0.9%
        nearby       16:13     768 x 624         -10         -1.6%
        nearby       32:27     768 x 648          14          2.2%
        nearby       48:41     768 x 656          22          3.5%
        nearby       24:19     768 x 608         -26         -4.1%
        nearby       32:25     768 x 600         -34         -5.4%
        nearby         8:7     768 x 672          38            6%
        nearby       48:37     768 x 592         -42         -6.6%
        nearby         4:3     768 x 576         -58         -9.1%
        nearby       32:29     768 x 696          62          9.8%
        golden      1.62:1     768 x 474        -160        -25.2%
