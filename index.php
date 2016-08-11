<?php
/**
 *  @author: Shahriar
 *  Resize Images proportionaly
 */

require_once ('class.imageresizer.php');

// Create thumbnails
$args = array(
    'height'    => 400,
    'width'     => 270,
    'is_crop_hard' => 1
);
$img = new ImageResizer($args);
//$img->create();

$img->createThumbnail('Desires_LB_MF16_7290.jpg',300,450);