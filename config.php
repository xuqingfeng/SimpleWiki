<?php
/**
 * Author: xuqingfeng <js-xqf@hotmail.com>
 * Date: 15/2/27
 */


return [
    'APP_NAME'       => 'SimpleWiki',
    'WIKI_DIR'       => __DIR__ . '/wiki',
    'WIKI_FILE_EXTENSION' => '.md',
    'DEFAULT_VIEW'   => __DIR__ . '/views/default',
    'DEFAULT_WIKI'   => __DIR__ . '/README',
    'TIMEZONE'       => 'Asia/Shanghai',
    'IGNORE_FILES'   => [
        '.gitignore',
        '.',
        '..'
    ]
];