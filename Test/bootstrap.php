<?php

if (file_exists($file = __DIR__ . '/../vendor/autoload.php')) {
    $autoload = require_once $file;
} else {
    throw new RuntimeException('Dependencies not installed. Try running "composer install".');
}

return $autoload;