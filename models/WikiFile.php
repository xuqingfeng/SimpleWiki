<?php

/**
 * Author: xuqingfeng <js-xqf@hotmail.com>
 * Date: 15/2/27
 */

require_once __DIR__ . "/../autoload.php";

class WikiFile {

    public $allFiles;
    public $config;

    public function __construct() {

        $this->allFiles = [];
        global $config;
        $this->config = $config;
    }

    public function getFilesByDir($path) {

        $relativePath = str_replace($this->config['wiki_dir'], '', $path);
        $files = [];
        if (is_dir($path)) {
            $fileNames = scandir($path);
            $fileNamesWithoutDot = array_diff($fileNames, ['.', '..']);
            $fileNamesRemained = array_diff($fileNamesWithoutDot, $this->config['ignore_files']);
            foreach ($fileNamesRemained as $p) {
                if (is_dir($path . "/" . $p)) {
                    $file = [];
                    $file['name'] = $p;
                    $file['link'] = $relativePath . "/" . $p;
                    $file['type'] = 'dir';
                    $file['isDir'] = true;
                    $files[] = $file;
                }

                if (is_file($path . "/" . $p)) {
                    $pathParts = pathinfo($path . "/" . $p);
                    $file = [];
                    $file['name'] = $pathParts['basename'];
                    $file['link'] = $relativePath . "/" . $pathParts['filename'];
                    $file['type'] = 'file';
                    $file['isDir'] = false;
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    // get all files in WIKI_DIR
    public function getAllFiles($path) {

        if (is_dir($path)) {
            $this->getFilesInDir($path);
            $fileNames = scandir($path);
            $fileNamesWithoutDot = array_diff($fileNames, ['.', '..']);
            $fileNamesRemained = array_diff($fileNamesWithoutDot, $this->config['ignore_files']);
            foreach ($fileNamesRemained as $p) {
                if (is_dir($path . "/" . $p)) {
                    $this->getAllFiles($path . "/" . $p);
                }
            }
        }

    }

    public function getFilesInDir($path) {

        $relativePath = str_replace($this->config['wiki_dir'], '', $path);
        if (is_dir($path)) {
            $fileNames = scandir($path);
            $fileNamesWithoutDot = array_diff($fileNames, ['.', '..']);
            $fileNamesRemained = array_diff($fileNamesWithoutDot, $this->config['ignore_files']);
            foreach ($fileNamesRemained as $p) {
                if (is_file($path . "/" . $p)) {
                    $pathParts = pathinfo($path . "/" . $p);
                    $file = [];
                    $file['name'] = $pathParts['basename'];
                    $file['link'] = $relativePath . "/" . $pathParts['filename'];
                    $file['type'] = 'file';
                    $file['isDir'] = false;
                    $this->allFiles[] = $file;
                }
            }
        }
    }

} 