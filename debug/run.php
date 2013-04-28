<?php
use ColorAnalyzer\Analyzer;

$image = new Imagick(__DIR__ . '/../resources/elegant-dresses-3.jpg');

$ca = new Analyzer();
$ca->setImage($image);
$ca->getColors();

exit();
