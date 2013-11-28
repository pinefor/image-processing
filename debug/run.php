<?php
use Core\ImageProcessing\Processor;
use Core\ImageProcessing\Workshop;
use Core\ImageProcessing\Debugger;
use Core\ImageProcessing\Processor\ColorAnalyzer\BorderMethod;

$loader = require __DIR__.'/../vendor/autoload.php';

if ($_SERVER['SCRIPT_NAME'] != '/') {
    $file = __DIR__ . '/../tests/Resources/' . $_SERVER['SCRIPT_NAME'];
    if ( !file_exists($file) ) return false;

    $workshop = new Workshop();

    $color = new Processor\ColorAnalyzer(new BorderMethod());
    $workshop->add($color);

    $trim = new Processor\Trimmer();
    $workshop->add($trim);

    $crop = new Processor\Cropper();
    $crop->setHeight(400);
    $crop->setWidth(400);
    $workshop->add($crop);

    $normalizer = new Processor\Normalizer();
    $workshop->add($normalizer);

    $medium = new Processor\MediumColorAnalyzer();
    $workshop->add($medium);

    $image = new Imagick($file);
    $debugger = new Debugger($workshop);
    $debugger->debug($image);

    header('Content-type: image/png');
    echo $image;
    exit();
}

echo <<<CSS
<style>
body {
    background-color:black;
}
.container {
    float: left;
    width: 400px; /* or whatever you want */
    height: 400px; /* or whatever you want */
    line-height: 400px; /* or whatever you want, should match height */
    text-align: center;
    border:0px solid white;
}

.container > img {
    max-height:400px;
    max-width:400px;

    vertical-align: middle;
}
</style>
CSS;

$directory = realpath(__DIR__ . '/../tests/Resources/');

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($directory)
);

while ($it->valid()) {
    if (!$it->isDot()) {
        printf(
            '<div class="container"><img src="%s" /></div>',
            str_replace($directory, '', $it->key())
        );
    }

    $it->next();
}
