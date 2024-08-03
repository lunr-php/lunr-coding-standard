<?php

$base = __DIR__;

if (file_exists($base . '/vendor/autoload.php') == TRUE)
{
    // Load composer autoloader.
    $autoload_file = $base . '/vendor/autoload.php';
}
else
{
    // Load decomposer autoloader.
    $autoload_file = $base . '/decomposer.autoload.inc.php';
}

require_once $autoload_file;
