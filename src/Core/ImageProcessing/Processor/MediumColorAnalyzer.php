<?php
namespace Core\ImageProcessing\Processor;

use Core\ImageProcessing\Processor;
use Core\ImageProcessing\Processor\ColorAnalyzer\MediumMethod;
use Imagick, ImagickPixel;

class MediumColorAnalyzer extends ColorAnalyzer
{
    public function __construct()
    {
        $this->method = new MediumMethod();
    }
}
