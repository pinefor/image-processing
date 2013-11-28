<?php

namespace Core\ImageProcessing\Processor\ColorAnalyzer;
use Imagick;

class MediumMethod extends Method
{
    const BORDER_SIZE = 10;
    const COLOR_THRESHOLD = .35;
    const COLORS = 15;

    public function get(Imagick $image)
    {
        $this->quantizeImage($image, 1);

        return  $this->getHistorgram($image);
    }
}
