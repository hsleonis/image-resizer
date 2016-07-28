<?php

$dir = new DirectoryIterator(dirname(__FILE__).'/img');
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        $i = createThumbnail($fileinfo->getFilename(), 200, 251, dirname(__FILE__).'/img',  dirname(__FILE__).'/thumb/tn_');
        if($i) echo $fileinfo->getFilename().' resized.';
    }
}

function createThumbnail($imageName,$newWidth,$newHeight,$uploadDir,$moveToDir)
{
    $path = $uploadDir . '/' . $imageName;

    $mime = getimagesize($path);

    if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
    if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
    if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
    if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }

    $old_x = imageSX($src_img);
    $old_y = imageSY($src_img);

    if($old_x > $old_y)
    {
        $thumb_w    =   $newWidth;
        $thumb_h    =   $old_y/$old_x*$newWidth;
    }

    if($old_x < $old_y)
    {
        $thumb_w    =   $old_x/$old_y*$newHeight;
        $thumb_h    =   $newHeight;
    }

    if($old_x == $old_y)
    {
        $thumb_w    =   $newWidth;
        $thumb_h    =   $newHeight;
    }

    $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);


    // New save location
    $new_thumb_loc = $moveToDir . $imageName;

    if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,8); }
    if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
    if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }
    if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,80); }

    imagedestroy($dst_img);
    imagedestroy($src_img);
    return $result;
}