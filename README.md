##SimpleWiki
>a simple & small wiki app

[Demo](http://demo4simplewiki.herokuapp.com/)

###Features

- Markdown support
- File name search (for now ?)
- Sweet page

###Requirements

- PHP `5.3+`
- Apache (`mod_rewrite`) or Nginx (`try_files $uri $uri/ /index.php?$args`)
- Other dependence (`composer`)

```php
{
    "require": {
        "erusev/parsedown": "1.5.*",
        "mustache/mustache": "~2.5"
    }
}
```

###Install

download zip file or git clone this project, then put them on your server and you are free to write your wiki in `wiki` folder.

###License

GPLv3
