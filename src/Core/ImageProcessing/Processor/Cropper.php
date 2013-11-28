<?php

namespace Core\ImageProcessing\Processor;

use Core\ImageProcessing\Processor;
use Imagick, Exception;

class Cropper extends Processor
{
    protected $width;
    protected $height;

    public function setWidth($value)
    {
        $this->width = $value;
    }

    public function setHeight($value)
    {
        $this->height = $value;
    }

    public function process(Imagick $image)
    {
        if  (!$this->width || !$this->height) {
            throw new Exception('Please set height and width');
        }

        $image->cropThumbnailImage($this->width, $this->height);
    }

}
