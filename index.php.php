<?php
/**
 *  @author: Shahriar
 *  Resize Images proportionaly
 */

require_once ('class.imageresizer.php');

// Create thumbnails
$args = array(
    'height'    => 700,
);
$img = new ImageResizer($args);
$img->create();