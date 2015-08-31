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

        $request = $_SERVER["REQUEST_URI"];
        $request = urlencode($request);

        $protocol = $this->getProtocol();
        $homepage = $protocol . $_SERVER["HTTP_HOST"];

        // mustache
        $options = ['extension' => '.html'];
        $this->mustache = new Mustache_Engine([
                'loader'           => new Mustache_Loader_FilesystemLoader($this->config["DEFAULT_VIEW"], $options),
                'partials_loader'  => new Mustache_Loader_FilesystemLoader($this->config["DEFAULT_VIEW"] . '/partials', $options),
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
                $filePath = str_replace($this->config["WIKI_DIR"], "", $this->config["DEFAULT_WIKI"]);

                $params = [
                    "appName"      => $this->config["APP_NAME"],
                    "homepage"     => $homepage,
                    "filePath"     => $filePath,
                    "files"        => $files,
                    "content"      => $content,
                    "lastModified" => date("Y-m-d H:i", filemtime($path)),
                ];
                $this->mustache->render("layout", $params);
            } else {
                $this->mustache->render("404");
            }
        } else if ("getFiles" == $request) {

            if ($_POST["link"]) {
                // get files in dir
                $link = $_POST["link"];
                $files = $this->getFilesInDir($this->config["WIKI_DIR"] . $link);
                $msg = [
                    "files" => $files,
                ];
                echo json_encode($msg);
                exit;
            } else if ($_POST["search"]) {
                // search file
                $search = $_POST["search"];

            }

        } else {

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
                    $this->allFiles[] = $f;
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