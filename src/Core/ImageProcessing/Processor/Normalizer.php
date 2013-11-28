<?php

namespace Core\ImageProcessing\Processor;

use Core\ImageProcessing\Processor;
use Imagick, ImagickDraw, ImagickPixel;

class Normalizer extends Processor
{
    const DEFAULT_FORMAT = 'jpg';
    const DEFAULT_COMPRESSION = Imagick::COMPRESSION_JPEG;
    const DEFAULT_COMPRESSION_QUALITY = 80;
    const DEFAULT_INTERLACE_SCHEME = Imagick::INTERLACE_PLANE;

    protected $format = self::DEFAULT_FORMAT;
    protected $compression = self::DEFAULT_COMPRESSION;
    protected $compressionQuality = self::DEFAULT_COMPRESSION_QUALITY;
    protected $interlaceScheme = self::DEFAULT_INTERLACE_SCHEME;

    public function setFormat($value)
    {
        $this->format = $value;
    }

    public function setCompression($value)
    {
        $this->compression = $value;
    }

    public function setCompressionQuality($value)
    {
        $this->compressionQuality = $value;
    }

    public function setInterlaceScheme($value)
    {
        $this->interlaceScheme = $value;
    }

    public function process(Imagick $image)
    {
        $image->setInterlaceScheme($this->interlaceScheme);
        $image->setImageFormat($this->format);
        $image->setCompression($this->compression); 
        $image->setCompressionQuality($this->compressionQuality); 
    }

}
