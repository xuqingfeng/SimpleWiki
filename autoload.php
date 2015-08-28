<?php
/**
 * Author: xuqingfeng <js-xqf@hotmail.com>
 * Date: 15/3/3
 */

require_once __DIR__ . "/vendor/autoload.php";

$config = require_once(__DIR__ . "/config.php");

date_default_timezone_set($config['TIMEZONE']);

spl_autoload_register(function ($class) {

    $prefix = '';
    $base_dir = __DIR__ . '/models/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {

        require_once "$file";
    }
});