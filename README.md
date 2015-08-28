##SimpleWiki
>a simple & small wiki service just as you need.

[Demo](https://demo4simplewiki.herokuapp.com/)

###Features

- Markdown support
- File name search
- Sweet page
- Beautiful url

###Requirements

- Markdown
- PHP `5.4+`
- Apache (`mod_rewrite`); Nginx (`try_files $uri $uri/ /index.php?$args;`) or use PHP built in Server (`php -S 0.0.0.0:8888`)
- Other dependence (`composer`)

```
{
    "require": {
        "erusev/parsedown": "1.5.*",
        "mustache/mustache": "~2.5"
    }
}
```

###Install

>with composer

``

>plain install

download zip file or git clone this project, then put them on your server and you are free to write your wiki in `wiki` folder.

###License

GPLv3
