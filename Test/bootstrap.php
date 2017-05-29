<?php

/**
 * For functional tests explaination see:
 * http://stackoverflow.com/questions/27501321/functional-tests-inside-a-standalone-symfony2s-bundle
 */

if (file_exists($file = __DIR__ . '/../vendor/autoload.php')) {
    $autoload = require_once $file;
} else {
    throw new RuntimeException('Dependencies not installed. Try running "composer install".');
}

return $autoload;