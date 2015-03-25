<?php
/**
 * Author: xuqingfeng <js-xqf@hotmail.com>
 * Date: 15/2/27
 */

require_once __DIR__ . "/autoload.php";

$request = $_SERVER['REQUEST_URI'];
$request = urldecode($request);

if (!empty($_SERVER['HTTPS'])) {
    $home = 'https://' . $_SERVER['HTTP_HOST'];
} else {
    $home = 'http://' . $_SERVER['HTTP_HOST'];
}

$wikiView = new WikiView();
$wikiFile = new WikiFile();

// home
if ('/' == $request) {

    $path = __DIR__ . '/README.md';
    if (file_exists($path)) {
        $files = $wikiFile->getFilesByDir(WIKI_DIR);
        $fileContent = file_get_contents($path);
        $parsedContent = Parsedown::instance()->text($fileContent);
        $params = array(
            'home'         => $home,
            'appName'      => APP_NAME,
            'filePath'     => 'README.md',
            'files'        => $files,
            'content'      => $parsedContent,
            'lastModified' => date('Y-m-d H:i', filemtime($path)),
        );
        echo $wikiView->render('layout', $params);
    } else {
        echo $wikiView->render('404');
    }

} else if ('/getFiles' == $request) {
    // get files by dir
    // search file
    if (isset($_POST['link'])) {
        $link = $_POST['link'];
        $files = $wikiFile->getFilesByDir(WIKI_DIR . $link);
        $msg = array();
        $msg['files'] = $files;
        echo json_encode($msg);
        exit;
    } else if (isset($_POST['search'])) {
        $search = $_POST['search'];
        $wikiFile->getAllFiles(WIKI_DIR);
        $files = $wikiFile->allFiles;
        $filesQualified = array();
        foreach ($files as $f) {
            if (preg_match('/' . $search . '/', $f['name'])) {
                $filesQualified[] = $f;
            }
        }
        $msg = array();
        $msg['files'] = $filesQualified;
        echo json_encode($msg);
        exit;
    }

} else {

    $path = WIKI_DIR . $request . '.md';
    if (file_exists($path)) {
        $fileContent = file_get_contents($path);
        $parsedContent = Parsedown::instance()->text($fileContent);

        $files = $wikiFile->getFilesByDir(WIKI_DIR);
        $relativePath = str_replace(WIKI_DIR, '', $path);
        $params = array(
            'home'         => $home,
            'appName'      => APP_NAME,
            'filePath'     => $relativePath,
            'files'        => $files,
            'content'      => $parsedContent,
            'lastModified' => date('Y-m-d H:i', filemtime($path)),
        );
        echo $wikiView->render('layout', $params);
    } else {
        echo $wikiView->render('404');
    }
}







