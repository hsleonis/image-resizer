<?php
/**
 *  @author: Shahriar
 *  Resize Images proportionaly
 */

require_once ('class.imageresizer.php');

// Create thumbnails
$args = array(
    'height'    => 300,
    'width'     => 200
);
$img = new ImageResizer($args);
$img->create();