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
            $this->compress     = isset($arr['compress'])? ($arr['compress']>=0 && $arr['compress']<=10)? $arr['compress']:10:8;
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
        $path = $uploadDir . '/' . $imageName;

        $type = mime_content_type($path);
        if (!strstr($type, 'image/')) {
            return false;
        }

        $mime = getimagesize($path);

        if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
        else if($mime['mime']=='image/jpeg' or $mime['mime']=='image/jpg' or $mime['mime']=='image/pjpeg') {
            $src_img = imagecreatefromjpeg($path);
        }

        $old_x = imageSX($src_img);
        $old_y = imageSY($src_img);

        if($old_x > $old_y) {
            $thumb_w    =   $newWidth;
            $thumb_h    =   $old_y/$old_x*$newWidth;
        }
        else if($old_x < $old_y) {
            $thumb_w    =   $old_x/$old_y*$newHeight;
            $thumb_h    =   $newHeight;
        }
        else {
            $thumb_w    =   $newWidth;
            $thumb_h    =   $newHeight;
        }

        $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

        $color = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
        imagefill($dst_img,0,0,$color);
        imagesavealpha($dst_img, true);
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

        // New save location
        $new_thumb_loc = $moveToDir . $imageName;

        if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,$this->compress); }
        else if($mime['mime']=='image/jpeg' or $mime['mime']=='image/jpg' or $mime['mime']=='image/pjpeg') {
            $result = imagejpeg($dst_img,$new_thumb_loc,$this->compress*10);
        }

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