# Aspect Ratio

A project to work with aspect ratios.  While the aspect ratio is a simple calculation, the nearest "nice" aspect ratio is not, and that's where this project stands out.  You may enter any dimension set and the actual aspect ratio will be calculated in both whole numbers and decimal, in addition you will obtain the closest "nice" aspect ratios, e.g. 16:9, 3:4, etc.  As a bonus, the golden ratio is also calculated against the width.

Here is an example of the output of the CLI.

    $ ./aspratio 768,634
    Type         Ratio    Dimensions    Variance    Variance %
    whole      384:317     768 x 634           0            0%
    decimal     1.21:1     768 x 635           1          0.2%
    nearby         6:5     768 x 640           6          0.9%
    nearby       16:13     768 x 624         -10         -1.6%
    nearby       24:19     768 x 608         -26         -4.1%
    nearby         8:7     768 x 672          38            6%
    nearby         4:3     768 x 576         -58         -9.1%
    golden      1.62:1     768 x 474        -160        -25.2%

* The nearby ratios are listed in the order of variance from the original height with the least variance first.

## Install Globally Using Composer

To be able to use `aspratio` from any directory in your CLI you may want to install this globally.

    composer global require aklump/aspect-ratio

Make sure you have the composer bin dir in your `PATH`. The default value is _~/.composer/vendor/bin_, but you can check the value that you need to use by running `composer global config bin-dir --absolute`.
    
To check this you must open _~/.bash_profile_ (or _~/.bashrc_); you're looking for a line that looks like the following, if you can't find, you'll need to add it.
                                 
    export PATH=~/.composer/vendor/bin:$PATH

## Quick Start

1. To determine the aspect ratios of a dimension set type any of the following in your terminal:
        
        $ aspratio 768x634
        $ aspratio 768 634
        $ aspratio 768,634
        
1. Use the `--precision={number}` to control the rounding precision.

        $ ./aspratio 444x187 --nearby=2 --precision=4
        Type          Ratio    Dimensions    Variance    Variance %
        whole       444:187     444 x 187           0            0%
        decimal    2.3743:1     444 x 187           0            0%
        nearby         12:5     444 x 185          -2         -1.1%
        nearby        37:16     444 x 192           5          2.7%
        golden      1.618:1     444 x 274          87         46.5%



1. Use `--json` to return the output in JSON.

        $ ./aspratio 768x512 -i --json
        [{"type":"original","ratio_x":512,"ratio_y":768,"width":512,"height":768,"difference_y":0,"difference_y_percent":"0%"},{"type":"whole","ratio_x":2,"ratio_y":3,"width":512,"height":768,"difference_y":0,"difference_y_percent":"0%"},{"type":"nearby","ratio_x":2,"ratio_y":3,"width":512,"height":768,"difference_y":0,"difference_y_percent":"0%"},{"type":"decimal","ratio_x":0.67000000000000003996802888650563545525074005126953125,"ratio_y":1,"width":512,"height":764,"difference_y":-4,"difference_y_percent":"-0.520833333333%"},{"type":"nearby","ratio_x":16,"ratio_y":25,"width":512,"height":800,"difference_y":32,"difference_y_percent":"4.16666666667%"},{"type":"nearby","ratio_x":16,"ratio_y":23,"width":512,"height":736,"difference_y":-32,"difference_y_percent":"-4.16666666667%"},{"type":"nearby","ratio_x":8,"ratio_y":13,"width":512,"height":832,"difference_y":64,"difference_y_percent":"8.33333333333%"},{"type":"nearby","ratio_x":8,"ratio_y":11,"width":512,"height":704,"difference_y":-64,"difference_y_percent":"-8.33333333333%"},{"type":"golden","ratio_x":1.62000000000000010658141036401502788066864013671875,"ratio_y":1,"width":512,"height":316,"difference_y":-452,"difference_y_percent":"-58.8541666667%"}]

## Contributing

If you find this project useful... please consider [making a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4E5KZHDQCEUV8&item_name=Gratitude%20for%20aklump%2Faspect-ratio).

## Usage

### Determine height based on aspect ratio and width

1. Determine the height by entering aspect ratio and width.
1. Use the dimensions listed as _original_

        $ ./aspratio 16:9 --width=1080
        Type         Ratio    Dimensions    Variance    Variance %
        original      16:9    1080 x 608           1          0.2%
        whole         16:9    1080 x 608           1          0.2%
        decimal     1.78:1    1080 x 607          -1         -0.2%
        nearby         2:1    1080 x 540          -1        -11.1%
        nearby         8:5    1080 x 675           1         11.1%
        golden      1.62:1    1080 x 667          60          9.9%

### Scale dimensions based on Aspect Ratio

1. To get new dimensions enter existing dimensions and the new width.

        $ ./aspratio 1080x520 --width=320
        Type        Ratio    Dimensions    Variance    Variance %
        whole       81:39     320 x 154           0            0%
        decimal    2.08:1     320 x 154           0            0%
        nearby        2:1     320 x 160           6          3.9%
        nearby       15:7     320 x 149          -5         -3.2%
        nearby      24:11     320 x 147          -7         -4.5%
        nearby       20:9     320 x 144         -10         -6.5%
        nearby        9:4     320 x 142         -12         -7.8%
        golden     1.62:1     320 x 198          44         28.6%


### Inversion

1. To invert the width, height use the `-i` flag.  This is like going from landscape to portrait.

        $ ./aspratio 768x512
        Type        Ratio    Dimensions    Variance    Variance %
        whole         3:2     768 x 512           0            0%
        decimal     1.5:1     768 x 512           0            0%
        nearby        3:2     768 x 512           0            0%
        nearby      32:21     768 x 504          -8         -1.6%
        nearby      16:11     768 x 528          16          3.1%
        nearby        8:5     768 x 480         -32         -6.3%
        nearby      24:17     768 x 544          32          6.3%
        golden     1.62:1     768 x 474         -38         -7.4%
        
        imac-aaron:aspect_ratio aklump$ ./aspratio 768x512 -i
        Type        Ratio    Dimensions    Variance    Variance %
        whole         2:3     512 x 768           0            0%
        nearby        2:3     512 x 768           0            0%
        decimal    0.67:1     512 x 764          -4         -0.5%
        nearby      16:25     512 x 800          32          4.2%
        nearby      16:23     512 x 736         -32         -4.2%
        nearby       8:13     512 x 832          64          8.3%
        nearby       8:11     512 x 704         -64         -8.3%
        golden     1.62:1     512 x 316        -452        -58.9%


### Fine Tuning

1. Use `--nearby` and `--variance` to fine tune the nearby calculations:
1. `--nearby` limits the maximum number of nearby values calculated.
1. `--variance` sets the maximum variance allowed.

        $ ./aspratio 768x634  --variance=50% --nearby=10
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal     1.21:1     768 x 635           1          0.2%
        nearby         6:5     768 x 640           6          0.9%
        nearby         8:7     768 x 672          38            6%
        nearby         4:3     768 x 576         -58         -9.1%
        nearby         3:2     768 x 512        -122        -19.2%
        nearby         1:1     768 x 768         134         21.1%
        nearby         8:5     768 x 480        -154        -24.3%
        golden      1.62:1     768 x 474        -160        -25.2%
        nearby         8:9     768 x 864         230         36.3%
        nearby         2:1     768 x 384        -250        -39.4%
        nearby         6:7     768 x 896         262         41.3%
        nearby        12:5     768 x 320        -314        -49.5%
        
        imac-aaron:aspect_ratio aklump$ ./aspratio 768x634  --variance=50% --nearby=5
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal     1.21:1     768 x 635           1          0.2%
        nearby         6:5     768 x 640           6          0.9%
        nearby         4:3     768 x 576         -58         -9.1%
        nearby         3:2     768 x 512        -122        -19.2%
        nearby         1:1     768 x 768         134         21.1%
        golden      1.62:1     768 x 474        -160        -25.2%
        nearby         2:1     768 x 384        -250        -39.4%
        
        imac-aaron:aspect_ratio aklump$ ./aspratio 768x634  --variance=10% --nearby=5
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal     1.21:1     768 x 635           1          0.2%
        nearby         6:5     768 x 640           6          0.9%
        nearby       16:13     768 x 624         -10         -1.6%
        nearby       24:19     768 x 608         -26         -4.1%
        nearby         8:7     768 x 672          38            6%
        nearby         4:3     768 x 576         -58         -9.1%
        golden      1.62:1     768 x 474        -160        -25.2%

1. `--variance` may also be entered as a maximum difference in height:

        $ ./aspratio 768x634  --variance=20 --nearby=5
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal     1.21:1     768 x 635           1          0.2%
        nearby       64:53     768 x 636           2          0.3%
        nearby         6:5     768 x 640           6          0.9%
        nearby       16:13     768 x 624         -10         -1.6%
        nearby       32:27     768 x 648          14          2.2%
        nearby       96:77     768 x 616         -18         -2.8%
        golden      1.62:1     768 x 474        -160        -25.2%
        
        imac-aaron:aspect_ratio aklump$ ./aspratio 768x634  --variance=100 --nearby=5
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal     1.21:1     768 x 635           1          0.2%
        nearby         6:5     768 x 640           6          0.9%
        nearby       16:13     768 x 624         -10         -1.6%
        nearby         8:7     768 x 672          38            6%
        nearby         4:3     768 x 576         -58         -9.1%
        nearby       12:11     768 x 704          70           11%
        golden      1.62:1     768 x 474        -160        -25.2%
        
        imac-aaron:aspect_ratio aklump$ ./aspratio 768x634  --variance=5 --nearby=5
        Type         Ratio    Dimensions    Variance    Variance %
        whole      384:317     768 x 634           0            0%
        decimal     1.21:1     768 x 635           1          0.2%
        nearby     256:211     768 x 633          -1         -0.2%
        nearby       64:53     768 x 636           2          0.3%
        nearby       96:79     768 x 632          -2         -0.3%
        nearby     128:105     768 x 630          -4         -0.6%
        nearby     256:213     768 x 639           5          0.8%
        golden      1.62:1     768 x 474        -160        -25.2%
