<?php

namespace lib\util;

class ImageObject {

    private $image;
    private $type;
    private $completeType;
    private $filename;
    private $tempFilename;

    function __construct($file) {
        $this->tempFilename = $file['tmp_name'];
        $image_info = getimagesize($this->tempFilename);
        $this->filename = $file['name'];
        $this->type = $image_info[2];
        $this->completeType = $file['type'];
        if ($this->type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($this->tempFilename);
        } else if ($this->type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($this->tempFilename);
        } else if ($this->type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($this->tempFilename);
        }
    }

    function getTempFilename() {
        return $this->tempFilename;
    }

    function getWidth() {
        return imagesx($this->image);
    }

    function getHeight() {
        return imagesy($this->image);
    }

    function getType() {
        return $this->type;
    }

    function getExtension() {
        $infoImage = explode('.', $this->filename);
        return array_pop($infoImage);
    }

    function toBase64() {
        ob_start();
        $this->output();
        $stringdata = ob_get_contents(); // read from buffer
        ob_end_clean(); // delete buffer
        return base64_encode($stringdata);
    }

    function save($path, $compression = 75, $permission = null) {
        if ($this->type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $path, $compression);
        } elseif ($this->type == IMAGETYPE_GIF) {
            imagegif($this->image, $path);
        } elseif ($this->type == IMAGETYPE_PNG) {
            imagealphablending($this->image, true);
            imagesavealpha($this->image, true);
            imagepng($this->image, $path);
        }
        if ($permission != null) {
            chmod($path, $permission);
        }
    }

    function printIt($imageType = IMAGETYPE_JPEG) {
        header("Content-Type: {$this->completeType}");
        if ($imageType == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($imageType == IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($imageType == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height) {
        $newImage = imagecreatetruecolor($width, $height);
        imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $newImage;
    }

}
