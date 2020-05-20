Abandoned, use [terminal42/contao-url-rewrite](https://github.com/terminal42/contao-url-rewrite/) instead.

---

[![](https://img.shields.io/maintenance/yes/2019.svg)](https://github.com/fritzmg/contao-short-urls)
[![](https://img.shields.io/packagist/v/fritzmg/contao-short-urls.svg)](https://packagist.org/packages/fritzmg/contao-short-urls)
[![](https://img.shields.io/packagist/dt/fritzmg/contao-short-urls.svg)](https://packagist.org/packages/fritzmg/contao-short-urls)

Contao Short URLs
===================

Simple extension to provide "Short URLs", i.e. redirects to existing resources which otherwise have a longer URL. For instance you can make redirects like

* `example.org/foo` » `http://www.example.org/lorem/ipsum/dolor.html`
* `example.org/123pdf` » `http://www.example.org/files/lorem/ipsum/dolor.pdf`
* `example.org/abcd` » `http://www.some-other-website.com/foo.html`

without editing the .htaccess file (to insert these redirects as Redirect, RedirectMatch or RewriteRule directives) or changing the server configuration. These redirects can be created in the backend under _Content » Short URLs_.

![Backend screenshot](https://raw.githubusercontent.com/fritzmg/contao-short-urls/master/screenshot.png)


## Requirements

You need to edit Contao 3's default `.htaccess` file and change it as if you are using URLs without an `.html` suffix. i.e. you have to change these lines:

```
RewriteCond %{REQUEST_FILENAME} !\.(htm|php|js|css|map|htc|png|gif|jpe?g|ico|xml|csv|txt|swf|flv|mp4|webm|ogv|mp3|ogg|oga|eot|otf|tt[cf]|woff2?|svgz?|pdf|gz)$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule .*\.html$ index.php [L]
```

to

```
RewriteCond %{REQUEST_FILENAME} !\.(htm|php|js|css|map|htc|png|gif|jpe?g|ico|xml|csv|txt|swf|flv|mp4|webm|ogv|mp3|ogg|oga|eot|otf|tt[cf]|woff2?|svgz?|pdf|gz)$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule .* index.php [L]
```

so that any request (which does not point to a file or directory) is processed by Contao.

## Contao 4

No changes to the `.htaccess` are necessary for Contao 4. Also you need to require at least version `^1.3.3` of this extension.


## Acknowledgements

Development funded by [Jaeggi & Tschui Grafik Webdesign GmbH](http://www.jaeggitschui.ch/) and [KASTNER Gruppe](http://www.kastner.at/).
