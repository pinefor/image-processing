<?php

namespace Core\ImageProcessing\Processor\ColorAnalyzer;
use Imagick;

abstract class Method
{
    abstract public function get(Imagick $image);

    protected function getHistorgram(Imagick $image, $discardUnder = 10)
    {
        $geometry = $image->getImageGeometry();
        $total = $geometry['width'] * $geometry['height'];

        $histogram = [];
        foreach ($image->getImageHistogram() as $pixel) {
            $color = $pixel->getColorAsString();
            $count = $pixel->getColorCount();
            if ($count > 1000) {
                $histogram[$color] = $count;
            }
        }

        return $histogram;
    }

    protected function quantizeImage(Imagick $image, $numberColors)
    {
        return $image->quantizeImage(
            $numberColors,
            Imagick::COLORSPACE_RGB,
            5, false, true
        );
    }
}
