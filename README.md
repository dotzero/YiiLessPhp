# Yii LessPhp

[![Latest Stable Version](https://poser.pugx.org/dotzero/yii-less/version)](https://packagist.org/packages/dotzero/yii-less)
[![License](https://poser.pugx.org/dotzero/yii-less/license)](https://packagist.org/packages/dotzero/yii-less)

**ELessPhp** is an extension for the **Yii PHP framework** that allows developers to compile LESS files into CSS on the fly, using the [LessPhp](http://leafo.net/lessphp/) compiler.

## Requirements

- [Yii Framework](https://github.com/yiisoft/yii) 1.1.14 or above
- [Composer](http://getcomposer.org/doc/)

## Install

### Via composer:

```bash
$ composer require dotzero/yii-less
```

- Add vendor path to your configuration file, attach component and set properties:

```php
'aliases' => array(
    ...
    'vendor' => realpath(__DIR__ . '/../../vendor'),
),
'components' => array(
    ...
    'less' => array(
        'class' => 'vendor.dotzero.yii-less.ELessCompiler',
        'lessphpDir' => 'vendor.leafo.lessphp', // Path alias of lessc.inc.php directory
        'forceCompile' => false, // Force recompile LESS into CSS every initializes the component
        'files' => array( // Files to compile (relative from your base path)
            'css/style.less' => 'css/style.css',
            'css/userstyle.less' => 'css/userstyle.css',
        ),
    ),
),
```

- Add the following to your config file `preload` section:

```php
'preload' => array(
    ...
    'less',
),
```

## License

Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
