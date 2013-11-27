<?php

namespace ColorAnalyzer\Method;
use Imagick;

class BorderMethod
{
    const COLORS = 15;

    private $image;

    public function __construct(Imagick $image)
    {
        $this->image = $image;
        $this->quantizeImage(self::COLORS);
    }

    public function get()
    {
        $histograms = [];
        $geometry = $this->image->getImageGeometry();

        $top = clone $this->image;
        $top->cropImage($geometry['width'], 10, 0, 0);
        $histograms['top'] = $this->getHistorgram($top);
        unset($top);

        $bottom = clone $this->image;
        $bottom->cropImage($geometry['width'], 10, 0, $geometry['height']-10);
        $histograms['bottom'] = $this->getHistorgram($bottom);
        unset($bottom);

        $left = clone $this->image;
        $left->cropImage(10, $geometry['height'], 0, 0);
        $histograms['left'] = $this->getHistorgram($left);
        unset($left);

        $right = clone $this->image;
        $right->cropImage(10, $geometry['height'], $geometry['width']-10, 0);
        $histograms['right'] = $this->getHistorgram($right);
        unset($right);

        $final = $this->getHistorgram($this->image);
        foreach ($histograms as $histogram) {
            foreach ($histogram as $color => $value) {
                unset($final[$color]);
            }
        }

        arsort($final);

        return $final;
    }

    private function getHistorgram(Imagick $image, $discardUnder = 10)
    {
        $geometry = $this->image->getImageGeometry();
        $total = $geometry['width'] * $geometry['height'];

        $histogram = [];
        foreach ($image->getImageHistogram() as $pixel) {
            $color = $pixel->getColorAsString();
            $count = $pixel->getColorCount();
            if ($count > 1000) {
                $histogram[$color] = $count;
            }
            //var_dump($total, $count,    $total/$count);
        }

        return $histogram;
    }

    private function quantizeImage($numberColors)
    {
        return $this->image->quantizeImage(
            $numberColors,
            Imagick::COLORSPACE_RGB,
            5, false, true
        );
    }
}
