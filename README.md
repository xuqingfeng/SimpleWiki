## SimpleWiki
>A simple & small wiki service just as you need.

[Demo](https://simplewiki.helldivers.cn/)

### Features

- Markdown support
- File name search
- Sweet page
- Beautiful url

### Requirements

- PHP `8.1+`
- Use PHP built-in Server (`php -S 0.0.0.0:8888`)
- Other dependence (`composer`)

```
{
    "require": {
        "php": "^8.1",
        "erusev/parsedown": "^1.8",
        "mustache/mustache": "^3.0"
    }
}
```

### Install

>with composer

- composer create-project xuqingfeng/simplewiki
- php -S 0.0.0.0:8888

>plain install

- git clone
- composer install
- php -S 0.0.0.0:8888

### License

GPL-3.0
