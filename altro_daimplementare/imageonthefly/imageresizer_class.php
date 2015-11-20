<?php

class image_resizer
{
    // *** Class variables
    private $image;
    private $width;
    private $height;
    private $imageResized;
 
    function __construct($fileName)
    {
        // *** Open up the file
        $this->image = $this->openImage($fileName);
 
        $info = @getimagesize($fileName);
        // *** Get width and height
        $this->width  = $info[0];
        $this->height = $info[1];
    }
    
    private function openImage($file)
    {
        $info = @getimagesize($file);
        // *** Get extension
        $extension = $info[2];
        switch($extension)
        {
            case IMAGETYPE_JPEG:
                $img = @imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_GIF:
                $img = @imagecreatefromgif($file);
                break;
            case IMAGETYPE_PNG:
                $img = @imagecreatefrompng($file);
                break;
            default:
                $img = false;
                break;
        }
        return $img;
    }
    
    public function resizeImage($newWidth, $newHeight, $option="auto")
    {
     
        // *** Get optimal width and height - based on $option
        $optionArray = $this->getDimensions($newWidth, $newHeight, strtolower($option));
     
        $optimalWidth  = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];
     
        // *** Resample - create image canvas of x, y size
        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        /*var_dump($this->imageResized);
        echo "<br>";
        var_dump($this->image);
        echo "<br>";
        var_dump($optimalWidth);
        echo "<br>";
        var_dump($optimalHeight);
        echo "<br>";
        var_dump($this->width);
        echo "<br>";
        var_dump($this->height);
        echo "<br>";*/
        imagealphablending($this->imageResized, false);
        imagesavealpha($this->imageResized,true);
        $transparent = imagecolorallocatealpha($this->imageResized, 255, 255, 255, 127);
        imagefilledrectangle($this->imageResized, 0, 0, $optimalWidth, $optimalHeight, $transparent);
        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
     
        // *** if option is 'crop', then crop too
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        }
    }
    
    public function printImg()
    {
        imagejpeg($this->imageResized);
    }
    
    private function getDimensions($newWidth, $newHeight, $option)
    {
     
       switch ($option)
        {
            case 'exact':
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
                break;
            case 'portrait':
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
                break;
            case 'landscape':
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
                break;
            case 'auto':
                $optionArray = $this->getSizeByAuto($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            case 'crop':
                $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                $optimalWidth = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }
    
    private function getSizeByFixedHeight($newHeight)
    {
        $ratio = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;
        return $newWidth;
    }
     
    private function getSizeByFixedWidth($newWidth)
    {
        $ratio = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;
        return $newHeight;
    }
     
    private function getSizeByAuto($newWidth, $newHeight)
    {
        if ($this->height < $this->width)
        // *** Image to be resized is wider (landscape)
        {
            $optimalWidth = $newWidth;
            $optimalHeight= $this->getSizeByFixedWidth($newWidth);
        }
        elseif ($this->height > $this->width)
        // *** Image to be resized is taller (portrait)
        {
            $optimalWidth = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight= $newHeight;
        }
        else
        // *** Image to be resizerd is a square
        {
            if ($newHeight < $newWidth) {
                $optimalWidth = $newWidth;
                $optimalHeight= $this->getSizeByFixedWidth($newWidth);
            } else if ($newHeight > $newWidth) {
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight= $newHeight;
            } else {
                // *** Sqaure being resized to a square
                $optimalWidth = $newWidth;
                $optimalHeight= $newHeight;
            }
        }
     
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }
     
    private function getOptimalCrop($newWidth, $newHeight)
    {
     
        $heightRatio = $this->height / $newHeight;
        $widthRatio  = $this->width /  $newWidth;
     
        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }
     
        $optimalHeight = $this->height / $optimalRatio;
        $optimalWidth  = $this->width  / $optimalRatio;
     
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }
    
    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
    {
        // *** Find center - this will be used for the crop
        $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
        $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );
     
        $crop = $this->imageResized;
        //imagedestroy($this->imageResized);
     
        // *** Now crop from center to exact requested size
        $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
        imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
    }
    
    public function saveImage(/*$savePath, $imageQuality="100"*/$extension)
    {
        // *** Get extension
        /*$extension = strrchr($savePath, '.');
        $extension = strtolower($extension);
        $extension = ".jpg";*/
     
        switch($extension)
        {
            case 'jpg':
            case 'jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->imageResized/*, "./2.jpg", 100*/);
                }
                break;
     
            case 'gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->imageResized/*, $savePath*/);
                }
                break;
     
            case 'png':
                // *** Scale quality from 0-100 to 0-9
               /* $scaleQuality = round(($imageQuality/100) * 9);
     
                // *** Invert quality setting as 0 is best, not 9
                $invertScaleQuality = 9 - $scaleQuality;*/
     
                if (imagetypes() & IMG_PNG) {
                    //imagecolortransparent($this->imageResized, 0);
                    //imagealphablending($this->imageResized, false);
                    //imagesavealpha($this->imageResized, true);
                    imagepng($this->imageResized/*, $savePath, $invertScaleQuality*/);
                }
                break;
     
            // ... etc
     
            default:
                // *** No extension - No save.
                break;
        }
     
        imagedestroy($this->imageResized);
    }
    
    
}

?>