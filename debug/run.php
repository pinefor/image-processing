<?php
use ColorAnalyzer\Analyzer;
$loader = require __DIR__.'/../vendor/autoload.php';

if  ( $_SERVER['SCRIPT_NAME'] != '/' ) {
    $file = __DIR__ . '/../resources/' . $_SERVER['SCRIPT_NAME'];
    if ( !file_exists($file) ) return false;

    $ca = new Analyzer();
    $ca->setImage(new Imagick($file));
    $ca->getColors();
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
    border:1px solid white;
}

.container > img {
    max-height:400px;
    max-width:400px;

    vertical-align: middle;
}
</style>
CSS;


$directory = realpath(__DIR__ . '/../resources/');

$it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($directory)
);

while($it->valid()) {
    if (!$it->isDot()) {
        printf(
            '<div class="container"><img src="%s" /></div>', 
            str_replace($directory, '', $it->key())
        );
    }

    $it->next();
}


