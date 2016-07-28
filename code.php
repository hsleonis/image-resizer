<?php

$dir = new DirectoryIterator(dirname(__FILE__).'/img');
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        img_info($fileinfo->getFilename());
    }
}

function img_info($path) {
        $title = explode(".",$path)[0];
        $ext = explode("-",$title);
        $tags = array();

        $name = preg_split('/(?=[A-Z])/', $ext[0], -1, PREG_SPLIT_NO_EMPTY);
        //print_r($name);
        if(isset($ext[1]))
        {
            $tags = preg_split('/(?=[A-Z])/', $ext[1], -1, PREG_SPLIT_NO_EMPTY);
            //print_r($tags);
        }
        if(isset($name[1])){
            if($name[1]=='Tee') $name[1] = 'Tshirt';
            array_unshift($tags, $name[1]);
        }
    ?>
   <div class="nf-item tshirts spacing">
        <div class="item-box">
            <a class="cbox-gallary1" href="img/portfolio/<?php echo $path; ?>" title="<?php echo $name[0]; ?>">
                <img class="item-container" src="img/portfolio/tn_<?php echo $path; ?>" alt="6" />
                <div class="item-mask">
                    <div class="item-caption">
                        <h5 class="white"><?php echo $name[0].((isset($tags[0]))?' '.$tags[0]:''); ?></h5>
                        <p class="white"><?php foreach ($tags as $index => $t) {echo ($index==0)?''.$t:', '.$t;} ?></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
<?php
}