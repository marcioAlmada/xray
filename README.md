X-Ray
=====

**X-Ray** allows declaration of *Zend Engine* `include()` and `eval()` hooks.:

> Warning: not ready for prime time yet!

## How to use:

```php
// adding a compiler hook:
xray\set_compiler_hook(function(string $source, string $filename = null) : string {
    if ($filename === null)  {
        // here we intercept source included through eval()
        // do transformations on $source and return the new $source to be included        
    }
    else {
        // here we intercept source included from a *.php file
        // do transformations on $source and return the new $source to be included
    }
});

// removing the compiler hook:
$hook = xray\restore_compiler_hook();

```

## How to install:

```
git clone https://github.com/marcioAlmada/xray
cd xray
phpize
./configure
make
sudo make install
```
Finally add `extension=xray.so` to your `/etc/php.ini`

## Why?

A compiler hook API was needed in order to have a decent infrastructure for [YAY](https://github.com/marcioAlmada/yay).
But this ended up as a more general purpose internal framework so [others projects](https://github.com/phplang/phack) can benefit too.

<img width="500px" src="https://i.imgflip.com/1b4fjb.jpg" title="PHP WITH BLACKJACK AND HOOKERS"/>
<!---
<img width="500px" src="https://i.imgflip.com/1b4fgz.jpg" title="PHP WITH BLACKJACK AND HOOKERS"/>)
<img width="500px" src="https://i.imgflip.com/1b4fkq.jpg" title="PHP WITH BLACKJACK AND HOOKERS"/>
-->

## Copyright

Copyright (c) 2015-* Márcio Almada. Distributed under the terms of an MIT-style license.
See LICENSE for details.
