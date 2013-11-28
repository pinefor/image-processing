<?php

namespace Core\ImageProcessing;

use Imagick, ImagickDraw, ImagickPixel;

class Workshop
{
    const PRIORITY_LOW = 10;
    const PRIORITY_NORMAL = 20;
    const PRIORITY_HIGH = 30;

    private $processors = [];

    public function add(Processor $processor, $priority = self::PRIORITY_NORMAL)
    {
        $this->processors[$priority][] = $processor;
    }

    public function all()
    {
        $result = [];

        ksort($this->processors);
        foreach ($this->processors as $priority => $processors) {
            $result = array_merge($result, $processors);
        }

        return $result;
    }

    public function process(Imagick $image, $debug = false)
    {
        $method = 'process';
        if ($debug) {
            $method = 'debug';
        }

        $result = [];
        foreach ($this->all() as $processor) {
            $class = explode('\\', get_class($processor));
            $result[end($class)] = $processor->$method($image);
        }

        return $result;
    }
}
