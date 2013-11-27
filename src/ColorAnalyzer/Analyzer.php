<?php

namespace ColorAnalyzer;
use ColorAnalyzer\Method\BorderMethod;
use Imagick, ImagickPixel;

class Analyzer
{
    private $originalImage;
    private $image;

    public function setImage(Imagick $image)
    {
        $this->image = $image;
    }

    public function getColors($numberColors, $hexFormat = true)
    {
        $image = clone $this->image;
        $image->thumbnailImage(800, 800, true);

        $method = new BorderMethod($image);
        $colors = $method->get();

        if ($hexFormat) {
            $colors = $this->convertColorsToHEXCode($colors);
        }

        return array_slice($colors, 0, $numberColors);
    }

    protected function convertColorsToHEXCode(array $colors)
    {
        $output = [];
        foreach ($colors as $color => $count) {
            $hex = $this->iMagickColorToHEX($color);
            if (!isset($output[$hex])) {
                $output[$hex] = 0;
            }

            $output[$hex] += $count;
        }

        return $output;
    }

    protected function iMagickColorToHEX($string)
    {
        $pixel = new ImagickPixel($string);
        $color = $pixel->getColor();

        return sprintf('#%s%s%s',
            dechex($color['r']),
            dechex($color['g']),
            dechex($color['b'])
        );
    }

    public function getDebug(Array $colors)
    {
        $position = 1;
        foreach ($colors as $color => $count) {
            $this->addLegend($this->image, $color, $position++);
        }

        header('Content-type: image/png');
        echo $this->image;
    }

    private function addLegend(Imagick $image, $color, $position = 1)
    {
        $color = new \ImagickPixel($color);
        $rectangle = new \ImagickDraw();
        $rectangle->setfillcolor($color);

        $x = 20 * $position;
        $y = 20;
        $rectangle->rectangle($x, $y, $x + 60, $y + 60);
        # draw rectangle
        $image->drawimage($rectangle);
    }
}
