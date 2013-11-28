<?php

namespace Core\ImageProcessing\Processor\ColorAnalyzer;
use Imagick;

class BorderMethod extends Method
{
    const BORDER_SIZE = 10;
    const COLOR_THRESHOLD = .35;
    const COLORS = 15;

    public function get(Imagick $image)
    {
        $image->thumbnailImage(800, 800, true);

        $fuzz = current($image->getQuantumRange()) * self::COLOR_THRESHOLD; 
        $image->trimImage($fuzz);
        $image->setImagePage(0, 0, 0, 0);

        $this->quantizeImage($image, self::COLORS);

        $histograms = [];
        $geometry = $image->getImageGeometry();

        $top = clone $image;
        $top->cropImage($geometry['width'], self::BORDER_SIZE, 0, 0);
        $histograms['top'] = $this->getHistorgram($top);
        unset($top);

        $bottom = clone $image;
        $bottom->cropImage($geometry['width'], self::BORDER_SIZE, 0, $geometry['height']-self::BORDER_SIZE);
        $histograms['bottom'] = $this->getHistorgram($bottom);
        unset($bottom);

        $left = clone $image;
        $left->cropImage(self::BORDER_SIZE, $geometry['height'], 0, 0);
        $histograms['left'] = $this->getHistorgram($left);
        unset($left);

        $right = clone $image;
        $right->cropImage(self::BORDER_SIZE, $geometry['height'], $geometry['width']-self::BORDER_SIZE, 0);
        $histograms['right'] = $this->getHistorgram($right);
        unset($right);

        $final = $this->getHistorgram($image);
        foreach ($histograms as $histogram) {
            foreach ($histogram as $color => $value) {
                unset($final[$color]);
            }
        }

        arsort($final);
        return $final;
    }
}
