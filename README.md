# Yii LessPhp

[![Latest Stable Version](https://poser.pugx.org/dotzero/yii-less/version)](https://packagist.org/packages/dotzero/yii-less)
[![License](https://poser.pugx.org/dotzero/yii-less/license)](https://packagist.org/packages/dotzero/yii-less)

**ELessPhp** is an extension for the **Yii PHP framework** that allows developers to compile LESS files into CSS on the fly, using the [LessPhp](http://leafo.net/lessphp/) compiler.

## Requirements:

Yii Framework 1.1.0 or later

## Installation:

- Extract the release folder `ELessPhp` under `protected/extensions`
- Download and extract [LessPhp](http://leafo.net/lessphp/) under 'protected/vendor'
- Add the following to your **config file** `preload` section:

```php
<?php
    //...
    'preload' => array(
        'less',
    ),
```

- Add the following to your **config file** `components` section:

```php
<?php
    'less' => array(
        'class' => 'ext.ELessPhp.ELessCompiler',
        'lessphpDir' => 'application.vendors.lessphp', // Path alias of lessc.inc.php directory
        'forceCompile' => false, // Force recompile LESS into CSS every initializes the component
        'files' => array( // Files to compile (relative from your base path)
            'css/style.less' => 'css/style.css',
            'css/userstyle.less' => 'css/userstyle.css',
        ),
    ),
```
