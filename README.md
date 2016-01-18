Contao Short URLs
===================

Simple extension to provide "Short URLs", i.e. redirects to existing resources which otherwise have a longer URL. For instance you can make redirects like

* `example.org/foo` » `http://www.example.org/lorem/ipsum/dolor.html`
* `example.org/123pdf` » `http://www.example.org/files/lorem/ipsum/dolor.pdf`
* `example.org/abcd` » `http://www.some-other-website.com/foo.html`

without editing the .htaccess file (to insert these redirects as Redirect, RedirectMatch or RewriteRule directives) or changing the server configuration. These redirects can be created in the backend under _Content » Short URLs_.

![Backend screenshot](https://raw.githubusercontent.com/fritzmg/contao-short-urls/master/screenshot.png)


### Requirements

You need to edit Contao's default `.htaccess` file and change it as if you are using URLs without an `.html` suffix. i.e. you have to change these lines:

```
RewriteCond %{REQUEST_FILENAME} !\.(htm|php|js|css|htc|png|gif|jpe?g|ico|xml|csv|txt|swf|flv|mp4|webm|ogv|mp3|ogg|oga|eot|otf|tt[cf]|woff2?|svgz?|pdf|gz)$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule .*\.html$ index.php [L]
```

to

```
RewriteCond %{REQUEST_FILENAME} !\.(htm|php|js|css|htc|png|gif|jpe?g|ico|xml|csv|txt|swf|flv|mp4|webm|ogv|mp3|ogg|oga|eot|otf|tt[cf]|woff2?|svgz?|pdf|gz)$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule .* index.php [L]
```

so that any request (which does not point to a file or directory) is processed by Contao.


### Acknowledgements

Development funded by [Jaeggi & Tschui Grafik Webdesign GmbH](http://www.jaeggitschui.ch/) and [KASTNER Gruppe](http://www.kastner.at/).
