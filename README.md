[![License](https://img.shields.io/github/license/imponeer/smarty-translate.svg)](LICENSE)
[![GitHub release](https://img.shields.io/github/release/imponeer/smarty-translate.svg)](https://github.com/imponeer/smarty-translate/releases) [![Maintainability](https://api.codeclimate.com/v1/badges/79f89e2fe21c0076c29a/maintainability)](https://codeclimate.com/github/imponeer/smarty-translate/maintainability) [![PHP](https://img.shields.io/packagist/php-v/imponeer/smarty-translate.svg)](http://php.net) 
[![Packagist](https://img.shields.io/packagist/dm/imponeer/smarty-translate.svg)](https://packagist.org/packages/imponeer/smarty-translate)

# Smarty Translate

This library adds new smarty block and var modifier `trans` for using with [Symfony Translation](https://github.com/symfony/translation) compatible library.

## Installation

To install and use this package, we recommend to use [Composer](https://getcomposer.org):

```bash
composer require imponeer/smarty-translate
```

Otherwise, you need to include manually files from `src/` directory. 

## Registering in Smarty

If you want to use these extensions from this package in your project you need register them with [`registerPlugin` function](https://www.smarty.net/docs/en/api.register.plugin.tpl) from [Smarty](https://www.smarty.net). For example:
```php
$smarty = new \Smarty();
$transBlockPlugin = new \Imponeer\Smarty\Extensions\Translate\TransBlock($translator);
$transModifierPlugin = new \Imponeer\Smarty\Extensions\Translate\TransVarModifier($translator);
$smarty->registerPlugin('block', $transBlockPlugin->getName(), [$transBlockPlugin, 'execute']);
$smarty->registerPlugin('modifier', $transModifierPlugin->getName(), [$transModifierPlugin, 'execute']);
```

## Using from templates

Translations can be done from templates...

....with block function:

```smarty
<{trans domain='admin'}>_AD_INSTALLEDMODULES<{/trans}>
```
...with modifier:
```smarty
<{"_AD_INSTALLEDMODULES"|trans:[]:'admin'}>
```

Block function supports such attributes:

| Atribute | What it does? | Default value |
|----------|-----------------|----------------|
| parameters | Key/value lists that should be replaced in translated string |  [] |
| domain | Domain from where to get translation string (in most cases same as translation file) | *(system default value)* |
| locale | Translate in specific locale | *(current system locale)* |

Var modifier also supports these attributes, but syntax is a bit different - `trans:PARAMETERS:DOMAIN:LOCALE`

## How to contribute?

If you want to add some functionality or fix bugs, you can fork, change and create pull request. If you not sure how this works, try [interactive GitHub tutorial](https://skills.github.com).

If you found any bug or have some questions, use [issues tab](https://github.com/imponeer/smarty-translate/issues) and write there your questions.
