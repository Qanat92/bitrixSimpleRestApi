<?php
// Ядро рест апи
require_once 'CHelper.php';

// Автоподключения классов из папки classes
$classes = glob(__DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . '*.php');
if (is_array($classes) && !empty($classes)) {
    foreach ($classes as $file) {
        include_once ($file);
    }
}