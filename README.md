##SimpleWiki
>a simple & small wiki app

[Demo](https://demo4simplewiki.herokuapp.com/)

###Features

- Markdown support
- File name search
- Sweet page
- Beautiful url

###Requirements

- Markdown
- PHP `5.3+`
- Apache (`mod_rewrite`) or Nginx (`try_files $uri $uri/ /index.php?$args;`)
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

download zip file or git clone this project, then put them on your server and you are free to write your wiki in `wiki` folder.

---

**other things you may want to check out**

[vhosts setup](https://github.com/xuqingfeng/SimpleWiki/wiki/Setup-virtual-host)

[dropbox sync](https://github.com/xuqingfeng/SimpleWiki/wiki/Sync-to-Dropbox-with-symbolic-link)

###License

GPLv3
