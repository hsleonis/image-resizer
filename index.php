<?php
/**
 *  @author: Shahriar
 *  Resize Images proportionaly
 */

require_once ('class.imageresizer.php');

// Create thumbnails
$args = array(
    'height'    => 350,
    'width'     => 250,
    'is_crop_hard' => 1
);
$img = new ImageResizer($args);
$img->create();