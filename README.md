# PHP Image Resizer

Create image thumbnails or scale to eaxct size instantly with PHP and the awesome GD library.
GD library is builtin with mosth PHP build. To make sure, use `phpinfo()`

# How to use
Require the `class.imageresizer.php` from your file.
````php
require_once ('class.imageresizer.php');
````

Now pass an argument associative array if you want or just `create()`
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

# Agruments

Key | Type | Value | Default
--- | --- | --- | ---
height | int/float | Thumbnail height in px | 200
width | int/float | Thumbnail width in px | 200
img_dir | string | Full size image directory path | /img
thumb_dir | string | Thumbnail image directory path | /thumb
compress | int/float | Image compression (0~1) | 0.8
is_crop_hard | boolean | Crops the image with exact height & width proportionally from the center of the image | false

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
MIT
Do whatever you want and if possible, keep me in your prayers!

Thank you.