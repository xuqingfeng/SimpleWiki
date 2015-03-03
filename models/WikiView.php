<?php
/**
 * Author: xuqingfeng <js-xqf@hotmail.com>
 * Date: 15/2/27
 */

require_once __DIR__ . "/../autoload.php";

class WikiView {

    private static $mustache;

    public function __construct() {

        $options = array('extension' => '.html');
        self::$mustache = new Mustache_Engine(array(
                'loader'           => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../views/default', $options),
                'partials_loader'  => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../views/default/partials', $options),
                'escape'           => function ($value) {
                    return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                },
                'charset'          => 'UTF-8',
                'strict_callables' => true,
                'pragmas'          => array(Mustache_Engine::PRAGMA_FILTERS),
            )
        );
    }

    public function render($template, $data = null) {

        return self::$mustache->render($template, $data);
    }
}
