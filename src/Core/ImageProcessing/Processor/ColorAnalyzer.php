<?php
namespace Core\ImageProcessing\Processor;

use Core\ImageProcessing\Processor;
use Core\ImageProcessing\Processor\ColorAnalyzer\Method;
use Imagick, ImagickPixel;

class ColorAnalyzer extends Processor
{
    const DEFAULT_HEX_FORMAT = true;
    const DEFAULT_NUMBER_COLORS = 3;

    protected $hexFormat = self::DEFAULT_HEX_FORMAT;
    protected $numberColors = self::DEFAULT_NUMBER_COLORS;

    public function __construct(Method $method)
    {
        $this->method = $method;
    }

    public function setHEXFormat($value)
    {
        $this->hexFormat = $value;
    }

    public function setNumberColors($value)
    {
        $this->numberColors = $value;
    }

    public function process(Imagick $originalImage)
    {
        $image = clone $originalImage;
        $colors = $this->method->get($image);

        if ($this->hexFormat) {
            $colors = $this->convertColorsToHEXCode($colors);
        }

        return array_slice($colors, 0, $this->numberColors);
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
}
