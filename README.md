# PHP Image Resizer

Create bulk image thumbnails or scale to eaxct size instantly with PHP and the awesome GD library.
GD library is builtin with most PHP build. To make sure, use `phpinfo()`.

This library will create thumbnails of all images from the given directory path and store them wherever you want.
You can just resize proportionally, crop to exact dimension after resizing proportionally and compress to reduce image size keeping good quality.

This library comes with default HTML resized image list.

# How to use
Require the `class.imageresizer.php` from your file.
````php
require_once ('class.imageresizer.php');
````

### With options
Now pass an argument associative array with options
````php
// Create thumbnails
$args = array(
    'height'    => 975,
    'width'     => 650,
    'is_crop_hard' => 1
);
$img = new ImageResizer($args);
$img->create();
````

### Without options
You can just use default properties by just:

````php
$img = new ImageResizer();
$img->create();
````

### Single image
`create()` function will resize all image files from the folder. To resize only one image, use `createThumbnail()`. You have to pass the image filename with extension, width in pixel and height in pixel as arguments:

````php
$args = array(
    'compress'     => 0.8,
    'is_crop_hard' => 1
);
$img = new ImageResizer($args);
$img->createThumbnail('Desires_LB_MF16_7290.jpg',300,450);
````

### Prevent HTML resize list
To prevent the class from printing image resize list as HTML, use `resize_list` property:
````php
$args = array(
    'height'    => 400,
    'width'     => 270,
    'is_crop_hard' => 1,
    'resize_list'  => false
);
$img = new ImageResizer($args);
$msg = $img->create();

// Now we can do whatever we want, maybe JSON
print_r(json_encode($msg));
````

# Agruments

Key | Type | Value | Default
--- | --- | --- | ---
height | int/float | Thumbnail height in px | 200
width | int/float | Thumbnail width in px | 200
img_dir | string | Full size image directory path | '/img'
thumb_dir | string | Thumbnail image directory path (Remember to add extra backslash after. You can use file name prefix :) ) | '/thumb/'
compress | int/float | Image compression (0~1, 0.15 is 15% ) | 0.8
is_crop_hard | boolean | Crops the image with exact height & width proportionally from the center of the image | false
resize_list | boolean | Prevents default HTML resized image list | true

# Example
This is how it works:
````php

require_once ('class.imageresizer.php');

// Create thumbnails
$args = array(
    'height'    => 975,
    'width'     => 650,
    'is_crop_hard' => 1
);
$img = new ImageResizer($args);
$img->create();

````

# Author
Md. Hasan Shahriar

Github: https://github.com/hsleonis

Email: hsleonis2@gmail.com

2016

# License
Copyright (c) 2016 Md. Hasan Shahriar Licensed under the The [MIT License (MIT)](http://opensource.org/licenses/MIT).
