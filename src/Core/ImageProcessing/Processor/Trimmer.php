<?php

namespace Core\ImageProcessing\Processor;

use Core\ImageProcessing\Processor;
use Imagick;

class Trimmer extends Processor
{
    const DEFAULT_COLOR_THRESHOLD = .35;
    const DEFAULT_TRIM_MAGNIFY = .4;

    protected $colorThreshold = self::DEFAULT_COLOR_THRESHOLD;
    protected $trimMagnify = self::DEFAULT_TRIM_MAGNIFY;

    public function setColorThreshold($value)
    {
        $this->colorThreshold = $value;
    }

    public function setTrimMagnify($value)
    {
        $this->trimMagnify = $value;
    }

    public function process(Imagick $image, $debug = false)
    {
        $result = $this->calculateTrimPage($image, $this->trimMagnify);
        if ($debug) {
            $this->doDebug($image, $result);
        }

        $page = $result['crop'];
        $image->cropImage($page['width'], $page['height'], $page['x'], $page['y']);

        return $result;
    }

    public function debug(Imagick $image)
    {
        return $this->process($image, true);
    }

    private function doDebug(Imagick $image, $result)
    {
        $this->addBox($image, $result['crop'], 'red');
        $this->addBox($image, $result['detected'], 'green');
    }

    private function calculateTrimPage(Imagick $image, $percent)
    {
        $trimmed = clone $image;
        $fuzz = current($trimmed->getQuantumRange()) * $this->colorThreshold; 
        $trimmed->trimImage($fuzz);

        $current = $this->getImagePage($trimmed);        

        $spaceWidth = (int) ($current['width'] * $percent);
        $spaceHeight = (int) ($current['height'] * $percent);

        $page = $current;
        $page['x'] -= $spaceWidth;
        $page['y'] -= $spaceHeight; 

        $page['width'] += $spaceWidth * 2;
        $page['height'] += $spaceHeight * 2;

        if ($page['x'] < 0) {
            $page['width'] += $page['x'] * -1;
            $page['x'] = 0;
        }

        if ($page['y'] < 0) {
            $page['height'] += $page['y'] * -1;
            $page['y'] = 0;
        }

        if ($page['width'] > $image->width) {
            $page['width'] = $image->width;
        }

        if ($page['height'] > $image->height) {
            $page['height'] = $image->height;
        }

        return ['crop' => $page, 'detected' => $current];
    }


    public function addBox(Imagick $image, $page, $color)
    {
        $color = new \ImagickPixel($color);
        $rectangle = new \ImagickDraw();
        $rectangle->setfillcolor($color);
        $rectangle->setFillOpacity(.1);


        $rectangle->rectangle(
            $page['x'], $page['y'], 
            $page['x']+$page['width'], $page['y']+$page['height']
        );
        # draw rectangle
        $image->drawimage($rectangle);
    }

    private function getImagePage(Imagick $image)
    {
        $page = $image->getImagePage();
        $image->setImagePage(0, 0, 0, 0);

        $page['width'] = $image->width;
        $page['height'] = $image->height;

        return $page;
    }
}
