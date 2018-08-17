# Aspect Ratio

A project to work with aspect ratios.  While the aspect ratio is a simple calculation, the nearest "nice" aspect ratio is not, and that's where this project stands out.  You may enter any dimension set and the actual aspect ratio will be calculated in both whole numbers and decimal, in addition you will obtain the closest "nice" aspect ratios, e.g. 16:9, 3:4, etc.  As a bonus, the golden ratio is also calculated against the width.

Here is an example of the output of the CLI.

    $ ./aspratio 768,634
    type         ratio    dimensions    variance
    whole      384:317     768 x 634           0
    decimal     1.21:1     768 x 635           1
    nearby         6:5     768 x 640           6
    nearby       16:13     768 x 624         -10
    nearby       24:19     768 x 608         -26
    nearby         8:7     768 x 672          38
    nearby         4:3     768 x 576         -58
    golden      1.62:1     768 x 474        -160

## Install Globally Using Composer

To be able to use `aspratio` from any directory in your CLI you may want to install this globally.

    composer global require aklump/aspect-ratio

Make sure you have the composer bin dir in your `PATH`. The default value is _~/.composer/vendor/bin_, but you can check the value that you need to use by running `composer global config bin-dir --absolute`.
    
To check this you must open _~/.bash_profile_ (or _~/.bashrc_); you're looking for a line that looks like the following, if you can't find, you'll need to add it.
                                 
    export PATH=~/.composer/vendor/bin:$PATH

## Usage

1. To determine the aspect ratio of a dimension set type any of the following in your terminal:
        
        aspratio 768x634
        aspratio 768 634
        aspratio 768,634
        
1. Use the `--precision={number}` to control the rounding precision.

1. Use the `--nearby={number}` to control the total number of nearby ratios to calculate.
1. Use `--json` to return the output in JSON.

        $ ./aspratio 768x512 -i --json
        [{"type":"original","ratio_x":512,"ratio_y":768,"width":512,"height":768,"difference_y":0},{"type":"whole","ratio_x":2,"ratio_y":3,"width":512,"height":768,"difference_y":0},{"type":"nearby","ratio_x":2,"ratio_y":3,"width":512,"height":768,"difference_y":0},{"type":"decimal","ratio_x":0.67000000000000003996802888650563545525074005126953125,"ratio_y":1,"width":512,"height":764,"difference_y":-4},{"type":"nearby","ratio_x":16,"ratio_y":25,"width":512,"height":800,"difference_y":32},{"type":"nearby","ratio_x":16,"ratio_y":23,"width":512,"height":736,"difference_y":-32},{"type":"nearby","ratio_x":8,"ratio_y":13,"width":512,"height":832,"difference_y":64},{"type":"nearby","ratio_x":8,"ratio_y":11,"width":512,"height":704,"difference_y":-64},{"type":"golden","ratio_x":1.62000000000000010658141036401502788066864013671875,"ratio_y":1,"width":512,"height":316,"difference_y":-452}]

### Get New Dimensions

1. To get a new dimension based on a dimension set do the following:

        $ ./aspratio 1080x520 --width=320
        type        ratio    dimensions    variance
        whole       81:39     320 x 154           0
        decimal    2.08:1     320 x 154           0
        nearby        2:1     320 x 160           6
        nearby       15:7     320 x 149          -5
        nearby      24:11     320 x 147          -7
        nearby       20:9     320 x 144         -10
        nearby        9:4     320 x 142         -12
        golden     1.62:1     320 x 198          44


### Inversion

1. To invert the width, height use the `-i` flag.  This is like going from landscape to portrait.

        $ ./aspratio 768x512
        type        ratio    dimensions    variance
        whole         3:2     768 x 512           0
        decimal     1.5:1     768 x 512           0
        nearby        3:2     768 x 512           0
        nearby      32:21     768 x 504          -8
        nearby      16:11     768 x 528          16
        nearby        8:5     768 x 480         -32
        nearby      24:17     768 x 544          32
        golden     1.62:1     768 x 474         -38
        
        $ ./aspratio 768x512 -i
        type        ratio    dimensions    variance
        whole         2:3     512 x 768           0
        nearby        2:3     512 x 768           0
        decimal    0.67:1     512 x 764          -4
        nearby      16:25     512 x 800          32
        nearby      16:23     512 x 736         -32
        nearby       8:13     512 x 832          64
        nearby       8:11     512 x 704         -64
        golden     1.62:1     512 x 316        -452

## Contributing

If you find this project useful... please consider [making a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4E5KZHDQCEUV8&item_name=Gratitude%20for%20aklump%2Faspect_ratio).
