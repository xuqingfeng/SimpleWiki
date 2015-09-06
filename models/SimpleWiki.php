<?php
/**
 * Author: xuqingfeng <js-xqf@hotmail.com>
 * Date: 15/8/28
 */
require_once __DIR__ . "/../autoload.php";

class SimpleWiki {

    private $config;
    private $mustache;

    private $allFiles;

    public function __construct() {

        global $config;
        $this->config = $config;

        $this->allFiles = [];

        $request = $_SERVER["REQUEST_URI"];

        $protocol = $this->getProtocol();
        $homepage = $protocol . $_SERVER["HTTP_HOST"];

        // mustache
        $options = ['extension' => '.html'];
        $this->mustache = new Mustache_Engine([
                // wtf
                'loader'           => new Mustache_Loader_FilesystemLoader($this->config["DEFAULT_VIEW"], $options),
                'partials_loader'  => new Mustache_Loader_FilesystemLoader($this->config["DEFAULT_VIEW"] . "/partials", $options),
                'escape'           => function ($value) {

                    return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                },
                'charset'          => 'UTF-8',
                'strict_callables' => true,
                'pragmas'          => array(Mustache_Engine::PRAGMA_FILTERS),
            ]
        );

        // router
        if ("/" == $request) {

            $path = $this->config["DEFAULT_WIKI"] . $this->config["WIKI_FILE_EXTENSION"];

            if (file_exists($path)) {
                $files = $this->getFilesInDir($this->config["WIKI_DIR"]);
                $content = $this->parseContent($this->config["DEFAULT_WIKI"] . $this->config["WIKI_FILE_EXTENSION"]);
//                $filePath = str_replace($this->config["WIKI_DIR"], "", $this->config["DEFAULT_WIKI"]);

                $params = [
                    "appName"      => $this->config["APP_NAME"],
                    "homepage"     => $homepage,
                    "filePath"     => "",
                    "files"        => $files,
                    "content"      => $content,
                    "lastModified" => date("Y-m-d H:i", filemtime($path)),
                ];
                echo $this->mustache->render("layout", $params);
            } else {
                echo $this->mustache->render("404");
            }
        } else if ("/getFiles" == $request) {

            if (isset($_POST["link"])) {
                // get files in dir
                $link = $_POST["link"];
                $files = $this->getFilesInDir($this->config["WIKI_DIR"] . "/$link");
                $msg = [
                    "files" => $files,
                ];
                echo json_encode($msg);
                exit;
            } else if (isset($_POST["search"])) {
                // search file
                $search = $_POST["search"];
                $this->getAllWikiFiles($this->config["WIKI_DIR"]);
                $filesQualified = [];
                foreach ($this->allFiles as $f) {
                    if (preg_match('/' . $search . '/', $f["name"])) {
                        $filesQualified[] = $f;
                    }
                }
                $msg = [];
                $msg["files"] = $filesQualified;
                echo json_encode($msg);
                exit;
            }

        } else {
            $path = $this->config["WIKI_DIR"] . $request . $this->config["WIKI_FILE_EXTENSION"];
            if (file_exists($path)) {
                $files = $this->getFilesInDir($this->config["WIKI_DIR"]);
                $content = $this->parseContent($path);
                $filePath = str_replace($this->config["WIKI_DIR"], "", $path);

                $params = [
                    "appName"      => $this->config["APP_NAME"],
                    "homepage"     => $homepage,
                    "filePath"     => $filePath,
                    "files"        => $files,
                    "content"      => $content,
                    "lastModified" => date("Y-m-d H:i", filemtime($path)),
                ];
                echo $this->mustache->render("layout", $params);
            } else {
                echo $this->mustache->render("404");
            }
        }
    }

    /*
     * @required file exist
     * */
    private function parseContent($filePath) {

        $content = "";
        if (file_exists($filePath)) {
            $fileContent = file_get_contents($filePath);
            $content = Parsedown::instance()->text($fileContent);
        }

        return $content;
    }

    private function getFilesInDir($path) {

        $files = [];
        if (is_dir($path)) {
            $filesInDir = scandir($path);
            $validFiles = array_diff($filesInDir, $this->config["IGNORE_FILES"]);
            $relativePath = str_replace($this->config["WIKI_DIR"], "", $path);
            foreach ($validFiles as $f) {
                if (is_dir($path . '/' . $f)) {
                    $file = [
                        "name"  => $f,
                        "link"  => $relativePath . "" . $f,
                        "type"  => "dir",
                        "isDir" => true,
                    ];
                    $files[] = $file;
                }
                if (is_file($path . "/" . $f)) {
                    $pathParts = pathinfo($path . "/" . $f);
                    $file = [
                        "name"  => $f,
                        "link"  => $relativePath . "/" . $pathParts["filename"],
                        "type"  => "file",
                        "isDir" => false,
                    ];
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    private function getAllWikiFiles($path) {

        if (is_dir($path)) {
            $files = scandir($path);
            $validFiles = array_diff($files, $this->config["IGNORE_FILES"]);
            foreach ($validFiles as $f) {
                if (is_dir($path . "/" . $f)) {
                    $this->getAllWikiFiles($path . "/" . $f);
                } else {
                    $relativePath = str_replace($this->config["WIKI_DIR"], "", $path);
                    $pathParts = pathinfo($path . "/" . $f);
                    $file = [
                        "name" => $f,
                        "link" => $relativePath . "/" . $pathParts["filename"],
                    ];
                    $this->allFiles[] = $file;
                }
            }
        }
    }

    /*
     * @return https; http
     * */
    private function getProtocol() {

        $protocol = "http";
        if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") {
            $protocol = "https";
        }

        return $protocol;
    }
} 