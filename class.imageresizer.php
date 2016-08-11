<?php
/**
 *  @author: Shahriar
 *  @url: http://github.com/hsleonis
 *  Resize Bulk Images using GD Library
 */

class ImageResizer{
    private $path;
    private $height;
    private $width;
    private $img_dir;
    private $thumb_dir;
    private $compress;
    private $is_crop_hard;

    /**
     * ImageResizer constructor.
     * @param array $arr
     */
    function __construct($arr=array()){

        if(is_array($arr)) {
            // Default value
            $this->path         = isset($arr['path'])? $arr['path'] : dirname(__FILE__).'/img';
            $this->height       = isset($arr['height'])? $arr['height'] : 200;
            $this->width        = isset($arr['width'])? $arr['width'] : 200;
            $this->img_dir      = isset($arr['img_dir'])? $arr['img_dir']: dirname(__FILE__).'/img';
            $this->thumb_dir    = isset($arr['thumb_dir'])? $arr['thumb_dir'].'/': dirname(__FILE__).'/thumb/';
            $this->compress     = isset($arr['compress'])? ($arr['compress']>=0 && $arr['compress']<=1)? $arr['compress']:1:0.8;
            $this->is_crop_hard = isset($arr['is_crop_hard'])?(bool)$arr['is_crop_hard']:false;
        }
        else return false;
    }

    /**
     * Create directory for images
     */
    private function set_directory(){
        if(!is_dir($this->img_dir)) mkdir($this->img_dir);
        if(!is_dir($this->thumb_dir)) mkdir($this->thumb_dir);
    }

    /**
     * Show output in HTML
     */
    private function show_result_before() {
        ?>
            <!DOCTYPE html>
                <html>
                    <head>
                        <title>Image Resizer $ Shahriar</title>
                        <style>
                            *{
                                margin: 0;
                                padding: 0;
                                box-sizing: border-box;
                            }
                            body{
                                background: #FAFAFA;
                            }
                            .img-box{
                                margin: auto;
                                margin-top: 5vw;
                                width: 90%;
                                min-height: 90%;
                                padding: 50px;
                                display: table;
                                background: #FFF;
                                border: 1px dashed rgba(0,0,0,0.1);
                                border-radius: 2px;
                            }
                            .img-list {
                                list-style: decimal inside;
                            }
                            .img-list li {
                                border: 1px solid rgba(0,0,0,0.1);
                                border-radius: 2px;
                                padding: 10px 15px;
                                margin: 5px;
                            }
                            .msg-success {
                                color: #30AA30;
                            }
                            .msg-error {
                                color: #F25959;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="img-box">
                            <ol class="img-list">
        <?php
    }

    /**
     * Show output in HTML
     */
    private function show_result_after(){
        ?>
                    </ol>
                </div>
            </body>
        </html>
        <?php
    }

    /**
     * Increase memory limit, execution time to work on lots of images
     */
    private function set_env(){
        ini_set("memory_limit", "256M");
        ini_set("max_execution_time", 3000);
    }

    /**
     * Check image type and parse data
     * @param $path
     * @return bool|resource
     */
    private function image_data($path, $mime){

        if (!strstr($mime, 'image/')) {
            return false;
        }

        if($mime=='image/png'){ $src_img = imagecreatefrompng($path); }
        else if($mime=='image/jpeg' or $mime=='image/jpg' or $mime=='image/pjpeg') {
            $src_img = imagecreatefromjpeg($path);
        }
        else $src_img = false;
        return $src_img;
    }

    /**
     * Save new image to new_thumb_loc
     * @param $path
     * @param $new_thumb_loc
     * @param $mime
     * @return bool
     */
    private function save($dst_src, $new_thumb_loc, $mime){
        if($mime=='image/png'){ $result = imagepng($dst_src,$new_thumb_loc,$this->compress*10); }
        else if($mime=='image/jpeg' or $mime=='image/jpg' or $mime=='image/pjpeg') {
            $result = imagejpeg($dst_src,$new_thumb_loc,$this->compress*100);
        }
        return $result;
    }

    /**
     * Create thumbnail from larger image using GD library
     *
     * @param $imageName
     * @param $newWidth
     * @param $newHeight
     * @param $uploadDir
     * @param $moveToDir
     * @return bool
     */
    private function createThumbnail($imageName,$newWidth,$newHeight,$uploadDir,$moveToDir) {
        $path       = $uploadDir . '/' . $imageName;
        $mime_info  = getimagesize($path);
        $mime       = $mime_info['mime'];

        $src_img    = $this->image_data($path, $mime);
        if($src_img===false) return false;

        $old_w = imageSX($src_img);
        $old_h = imageSY($src_img);

        $source_aspect_ratio = $old_w / $old_h;
        $desired_aspect_ratio = $newWidth / $newHeight;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $thumb_h = $newHeight;
            $thumb_w = ( int ) ($newHeight * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $thumb_w = $newWidth;
            $thumb_h = ( int ) ($newWidth / $source_aspect_ratio);
        }

        $dst_img     =   ImageCreateTrueColor($thumb_w,$thumb_h);

        $color = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
        imagefill($dst_img,0,0,$color);
        imagesavealpha($dst_img, true);

        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_w, $old_h);

        if($this->is_crop_hard) {
            $x = ($thumb_w - $newWidth) / 2;
            $y = ($thumb_h - $newHeight) / 2;

            $tmp_img = imagecreatetruecolor($newWidth, $newHeight);
            $color = imagecolorallocatealpha($tmp_img, 0, 0, 0, 127);
            imagefill($tmp_img,0,0,$color);
            imagesavealpha($tmp_img, true);

            imagecopy($tmp_img, $dst_img, 0, 0, $x, $y, $newWidth, $newHeight);
            $dst_img = $tmp_img;
        }

        $new_thumb_loc = $moveToDir . $imageName;
        $result = $this->save($dst_img, $new_thumb_loc, $mime);
        
        imagedestroy($dst_img);
        imagedestroy($src_img);
        return $result;
    }

    /**
     * Generate thumbnails
     */
    public function create(){

        // Set environment
        $this->set_env();

        // check directory location
        $this->set_directory();

        // result view
        $this->show_result_before();

        // get all images from the directory
        $dir = new \DirectoryIterator($this->path);

        // check if there are files
        if($dir->valid()) {
            foreach ($dir as $fileinfo) {
                if (!$fileinfo->isDot()) {

                    $i = $this->createThumbnail($fileinfo->getFilename(), $this->width, $this->height, $this->img_dir, $this->thumb_dir);
                    if ($i) echo '<li class="msg-success" ><b>' . $fileinfo->getFilename() . '</b> resized.</li>';
                    else echo '<li class="msg-error">Resizing error on <b>' . $fileinfo->getFilename() . '</b></li>';
                }
            }
        }
        else echo '<li class="msg-error">No files found</li>';

        // result view end
        $this->show_result_after();
    }
}