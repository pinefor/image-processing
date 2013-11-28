<?php

namespace Core\ImageProcessing;

use Imagick, ImagickDraw, ImagickPixel;

class Debugger
{
    protected $workshop;

    public function __construct(Workshop $workshop)
    {
        $this->workshop = $workshop;
    }

    public function debug(Imagick $image)
    {
        $before = $this->getImageInfo($image);
        
        $start = microtime(true);
        $result = $this->workshop->process($image, true);
        $duration = microtime(true) - $start;

        $after = $this->getImageInfo($image);

        $timeLegend = sprintf(
            'Executed in %f sec(s)',
            $duration
        );

        $sizeLegend = sprintf(
            'Filesize: %s bytes / %s bytes', 
            number_format($before['size']),
            number_format($after['size'])
        );

        $formatLegend = sprintf(
            'Format: %s / %s, Geometry %dx%d / %dx%d', 
            $before['format'], $after['format'],
            $before['width'], $before['height'],
            $after['width'], $after['height']
        );

        $this->addText($image, $timeLegend, 'red', 15, 10, $image->height - 46);
        $this->addText($image, $formatLegend, 'red', 15, 10, $image->height - 28);
        $this->addText($image, $sizeLegend, 'red', 15, 10, $image->height - 10);
    
        if (isset($result['ColorAnalyzer'])) {
            $this->addColorLegends($image, $result['ColorAnalyzer'], 20);
        }

        if (isset($result['MediumColorAnalyzer'])) {
            $this->addColorLegends($image, $result['MediumColorAnalyzer'], 100);
        }

        return $result;
    }

    private function getImageInfo(Imagick $image)
    {
        return [
            'format' => $image->getImageFormat(),
            'height' => $image->height,
            'width' => $image->width,
            'size' => $this->getImageSize($image)
        ];
    }

    private function getImageSize($image)
    {
        $filename = tempnam(sys_get_temp_dir(), 'debugCropper');
        $image->writeImage($filename);

        $size = filesize($filename);
        unlink($filename);

        return $size;
    }

    public function addColorLegends(Imagick $image, Array $colors, $y)
    {
        asort($colors);

        $position = 1;
        foreach ($colors as $color => $count) {
            $this->addColorLegend($image, $color, $y, $position++);
        }
    }

    private function addColorLegend(Imagick $image, $color, $y, $position = 1)
    {
        $color = new \ImagickPixel($color);
        $rectangle = new \ImagickDraw();
        $rectangle->setfillcolor($color);

        $x = 20 * $position;

        $rectangle->rectangle($x, $y, $x + 60, $y + 60);
        # draw rectangle
        $image->drawimage($rectangle);
    }

    public function addText($image, $text, $color, $size, $x, $y)
    {
        $draw = new ImagickDraw();
        $color = new ImagickPixel($color);

        /* Font properties */
        $draw->setFont(__DIR__ . '/../../../tests/Resources/font.ttf');
        $draw->setFontSize($size);
        $draw->setFillColor($color);
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true);

        /* Get font metrics */
        $metrics = $image->queryFontMetrics($draw, $text);

        /* Create text */
        $draw->annotation($x, $y, $text);

        $image->drawimage($draw);
    }

}
