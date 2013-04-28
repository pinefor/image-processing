<?php
/*
 * This file is part of the CLIArrayEditor package.
 *
 * (c) Máximo Cuadros <mcuadros@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ColorAnalyzer;
use ColorAnalyzer\Method\BorderMethod;
use Imagick;

class Analyzer {
    private $originalImage;
    private $image;

    public function setImage(Imagick $image) {
        $this->image = $image;
        $this->image->thumbnailImage(800, 800, true);
    }

    public function getColors() {
        $method = new BorderMethod(clone $this->image);
        $colors = $method->get();

        $this->getDebug($colors);
    }

    public function getDebug($colors) {
        $position = 1;
        foreach($colors as $color => $count ) {
            $this->addLegend($this->image, $color, $position++);    
        }

        header('Content-type: image/png');
        echo $this->image;
    }

    private function addLegend(Imagick $image, $color, $position = 1) {
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