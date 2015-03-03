<?php

/**
 * Author: xuqingfeng <js-xqf@hotmail.com>
 * Date: 15/2/27
 */

require_once __DIR__ . "/../autoload.php";

class WikiFile {

    public $allFiles;

    public function __construct() {

        $this->allFiles = array();
    }

    public function getFilesByDir($path) {

        $relativePath = str_replace(WIKI_DIR, '', $path);
        $files = array();
        if (is_dir($path)) {
            $fileNames = scandir($path);
            $fileNamesWithoutDot = array_diff($fileNames, array('.', '..'));
            foreach ($fileNamesWithoutDot as $p) {
                if (is_dir($path . "/" . $p)) {
                    $file = array();
                    $file['name'] = $p;
                    $file['link'] = $relativePath . "/" . $p;
                    $file['type'] = 'dir';
                    $file['isDir'] = true;
                    $files[] = $file;
                }

                if (is_file($path . "/" . $p)) {
                    $pathParts = pathinfo($path . "/" . $p);
                    $file = array();
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
            $fileNamesWithoutDot = array_diff($fileNames, array('.', '..'));
            foreach ($fileNamesWithoutDot as $p) {
                if (is_dir($path . "/" . $p)) {
                    $this->getAllFiles($path . "/" . $p);
                }
            }
        }

    }

    public function getFilesInDir($path) {

        $relativePath = str_replace(WIKI_DIR, '', $path);
        if (is_dir($path)) {
            $fileNames = scandir($path);
            $fileNamesWithoutDot = array_diff($fileNames, array('.', '..'));
            foreach ($fileNamesWithoutDot as $p) {
                if (is_file($path . "/" . $p)) {
                    $pathParts = pathinfo($path . "/" . $p);
                    $file = array();
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