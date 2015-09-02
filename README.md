##SimpleWiki
>a simple & small wiki service just as you need.

[Demo](https://demo4simplewiki.herokuapp.com/)

###Features

- Markdown support
- File name fuzzy search
- Sweet page
- Beautiful url

###Requirements

- Markdown
- PHP `5.4+`
- Use PHP built-in Server (`php -S 0.0.0.0:8888`)

  Apache (`mod_rewrite`)

  Nginx (`try_files $uri $uri/ /index.php?$args;`)

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

`composer create-project xuqingfeng/simplewiki`

>plain install

Download zip file OR git clone this project

###License

GPL-3.0
