<?php

namespace Core\ImageProcessing;

use Imagick, ImagickDraw, ImagickPixel;

abstract class Processor
{
    abstract public function process(Imagick $image);

    public function debug(Imagick $image)
    {
        return $this->process($image);
    }

    public function printDebug($string)
    {
        file_put_contents('php://stdout', json_encode($string, JSON_PRETTY_PRINT));
    }
    
}
