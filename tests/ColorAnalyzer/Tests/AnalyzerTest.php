<?php

namespace ColorAnalyzer\Tests;

use ColorAnalyzer\Analyzer;
use Imagick;

class AnalyzerTest extends \PHPUnit_Framework_TestCase
{
    const EXAMPLE_IMAGE = '/../../Resources/france1.jpg';
    public function testLibrary()
    {
        $ca = new Analyzer();
        $ca->setImage(new Imagick(__DIR__ .self::EXAMPLE_IMAGE));
        $result = $ca->getColors(3, true);

        $this->assertSame([
            '#edecf6' => 54293,
            '#f7f5fc' => 28657,
            '#f1ecee' => 23829
        ], $result);
    }
}
