<?php
/**
 * Author: xuqingfeng <xuqingfeng@tuniu.com>
 * Date: 15/8/31
 */

require_once __DIR__ . "/../autoload.php";

class SimpleWikiTest extends \PHPUnit_Framework_TestCase{

    private $config;

    public function setup(){
        global $config;
        $this->config = $config;
    }

    public function testGetAllWikiFiles(){

        $files = scandir($this->config["WIKI_DIR"]);
        var_dump($files);
    }
} 