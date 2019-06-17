<?php
ob_start(); 
class images_to_sprite {
    function images_to_sprite($folder,$output,$x,$y) {
        $this->folder = $folder; // Folder name to get images from, i.e. C:\myfolder or /home/user/Desktop/folder
        $this->filetypes = array('jpg'=>true,'png'=>true,'jpeg'=>true,'gif'=>true); // Acceptable file extensions to consider
        $this->output = ($output ? $output : 'mysprite'); // Output filenames, mysprite.png and mysprite.css
        $this->x = $x; // Width of images to consider
        $this->y = $y; // Heigh of images to consider
        $this->files = array();
    }
 
    function create_sprite() {
        
        $ver = time();
        $basedir = $this->folder;
        
        $files = scandir(".");
        foreach($files as $key => $value){
            if (strpos($value, '.png') !== false){
                echo "Deleted ".$value."<br>";
                unlink($value);
            }
        }
        $files = array();
        // Read through the directory for suitable images
        if($handle = @opendir($this->folder)) {
            while (false !== ($file = readdir($handle))) {
                $split = explode('.',$file);
                // Ignore non-matching file extensions
                if($file[0] == '.' || !isset($this->filetypes[$split[count($split)-1]]))
                    continue;
                // Get image size and ensure it has the correct dimensions
                $output = getimagesize($this->folder.'/'.$file);
                if($output[0] != $this->x && $output[1] != $this->y)
                    continue;
                // Image will be added to sprite, add to array
                $this->files[$file] = $file;
            }
            closedir($handle);
        }
 
        // yy is the height of the sprite to be created, basically X * number of images
        $this->yy = $this->y * count($this->files);
        $im = imagecreatetruecolor($this->x,$this->yy);
 
        // Add alpha channel to image (transparency)
        imagesavealpha($im, true);
        $alpha = imagecolorallocatealpha($im, 0, 0, 0, 127);
        imagefill($im,0,0,$alpha);
 
        // Append images to sprite and generate CSS lines  
        $i = $ii = 0;
        $fp = fopen($this->output.'.css','w');
        fwrite($fp,".".$this->output." { width: ".$this->x."px; height: ".$this->y."px; background-image: url('".$this->output.$ver.".png'); background-repeat:no-repeat; display: none; }"."\n");
            foreach($this->files as $key => $file) {
            $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
            fwrite($fp,'.'.$this->output."-".$withoutExt.' { display: block; background-position: -0px -'.($this->y*$i).'px; }');
            $im2 = imagecreatefrompng($this->folder.$file);
            imagecopy($im,$im2,0,($this->y*$i),0,0,$this->x,$this->y);
            $i++;
            }
        fclose($fp);
        imagepng($im,$this->output.$ver.'.png'); // Save image to file
        imagedestroy($im);
    }
}
if(isset($_POST['create_sprite'])){
    $name = $_FILES['file']['name'];
    $extension = strtolower(substr($name,strpos($name,'.')+1));
    $type = $_FILES['file']['type'];
    $size = $_FILES['file']['size'];
    $max_size = 500000;
    $tmp_name = $_FILES['file']['tmp_name'];
    $image_info = getimagesize($_FILES["file"]["tmp_name"]);
    $image_width = $image_info[0];
    $image_height = $image_info[1];
    $uploadOk = 0;
    if(!empty($name)){
        if(($extension=='jpg' || $extension=='jpeg' || $extension=='png') && $type='image/jpeg'){
            $location='uploads/';
            $uploadOk = 1;
            if( $size <= $max_size ) {
                $uploadOk = 1;
                if( $image_width <= 16 && $image_height <= 16 ) {
                    $uploadOk = 1;
                }
                else {                    
                    $uploadOk = 0;
                    $error_msg = $image_width." ".$image_height;
                    fn_error_msg($error_msg);
                }
            }
            else {
                $uploadOk = 0;
                $error_msg = "Size too big";
                fn_error_msg($error_msg);
            }
        }
        else {
            $error_msg = "Invalid format. Please upload a jpg, jped or png file.";
            fn_error_msg($error_msg);
        }
        if ( $uploadOk == 1 ) {
            move_uploaded_file($tmp_name,$_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/Sprite-Generator/'.$location.$name);
            $class = new images_to_sprite($_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/Sprite-Generator/'.$location,'sprite',16,16);
            $class->create_sprite();
            $success_msg = "Upload Successful!!! Sprite image and css are created.";
            fn_success_msg($success_msg);
        }
    }      
    else {
        $error_msg = "File upload error.... Try Again";
        fn_error_msg($error_msg);
    }     
}
function fn_error_msg($msg) {
    header('Location: ' . $_SERVER['HTTP_REFERER']."&error_msg=".$msg);
    exit();
}
function fn_success_msg($msg) {
    header('Location: ' . $_SERVER['HTTP_REFERER']."&success_msg=".$msg);
    exit();
}