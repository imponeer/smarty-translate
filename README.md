[![License](https://img.shields.io/github/license/imponeer/smarty-translate.svg)](LICENSE)
[![GitHub release](https://img.shields.io/github/release/imponeer/smarty-translate.svg)](https://github.com/imponeer/smarty-translate/releases)
[![PHP](https://img.shields.io/packagist/php-v/imponeer/smarty-translate.svg)](http://php.net)
[![Packagist](https://img.shields.io/packagist/dm/imponeer/smarty-translate.svg)](https://packagist.org/packages/imponeer/smarty-translate)
[![Smarty version requirement](https://img.shields.io/packagist/dependency-v/imponeer/smarty-translate/smarty%2Fsmarty)](https://smarty-php.github.io)

# Smarty Translate

> Seamlessly integrate Symfony Translation with Smarty templates

This library adds a new Smarty block function and variable modifier called `trans` that integrates with any [Symfony Translation Contracts](https://github.com/symfony/translation-contracts) compatible library. It allows you to easily translate your Smarty templates using the powerful Symfony translation system.

## Installation

The recommended way to install this package is through [Composer](https://getcomposer.org):

```bash
composer require imponeer/smarty-translate
```

Alternatively, you can manually include the files from the `src/` directory in your project.

## Setup

### Basic Setup

To register the translation extension with Smarty, add the extension class to your Smarty instance:

```php
// Create a Symfony Translator instance
$translator = new \Symfony\Component\Translation\Translator('en');
// ... configure your translator ...

// Create a Smarty instance
$smarty = new \Smarty();

// Register the translation extension
$smarty->addExtension(
    new \Imponeer\Smarty\Extensions\Translate\TranslationSmartyExtension($translator)
);
```

### Using with Symfony Container

To integrate with Symfony, you can leverage autowiring, which is the recommended approach for modern Symfony applications:

```yaml
# config/services.yaml
services:
    # Enable autowiring and autoconfiguration
    _defaults:
        autowire: true
        autoconfigure: true

    # Register your application's services
    App\:
        resource: '../src/*'
        exclude: '../src/{DependencyInjection,Entity,Tests,Kernel.php}'

    # Configure Smarty with the extension
    # The TranslationSmartyExtension will be autowired automatically
    \Smarty\Smarty:
        calls:
            - [addExtension, ['@Imponeer\Smarty\Extensions\Translate\TranslationSmartyExtension']]
```

Then in your application code, you can simply retrieve the pre-configured Smarty instance:

```php
// Get the Smarty instance with the translation extension already added
$smarty = $container->get(\Smarty\Smarty::class);
```

For more information about Symfony's Dependency Injection Container, see the [official documentation](https://symfony.com/doc/current/service_container.html).

### Using with PHP-DI

With PHP-DI container, you can take advantage of autowiring for a very simple configuration:

```php
use function DI\create;
use function DI\get;

return [
    // Register the translator (assuming you have a translator factory elsewhere)
    // \Symfony\Contracts\Translation\TranslatorInterface::class => factory(...),

    // The Translation Extension is autowired by default when using class names

    // Configure Smarty with the extension
    \Smarty\Smarty::class => create()
        ->method('addExtension', get(\Imponeer\Smarty\Extensions\Translate\TranslationSmartyExtension::class))
];
```

Then in your application code, you can retrieve the Smarty instance:

```php
// Get the configured Smarty instance
$smarty = $container->get(\Smarty\Smarty::class);
```

For more information about PHP-DI Container, see the [official documentation](https://php-di.org/doc/).

### Using with League Container

If you're using League Container, you can register the extension like this:

```php
// Create the container
$container = new \League\Container\Container();

// Register the translator
$container->add(\Symfony\Contracts\Translation\TranslatorInterface::class, function() {
    $translator = new \Symfony\Component\Translation\Translator('en');
    // Configure translator...
    return $translator;
});

// Register Smarty with the translation extension
$container->add(\Smarty\Smarty::class, function() use ($container) {
    $smarty = new \Smarty\Smarty();
    // Configure Smarty...

    // Create and add the translation extension directly
    $extension = new \Imponeer\Smarty\Extensions\Translate\TranslationSmartyExtension(
        $container->get(\Symfony\Contracts\Translation\TranslatorInterface::class)
    );
    $smarty->addExtension($extension);

    return $smarty;
});

```

Then in your application code, you can retrieve the Smarty instance:

```php
// Get the configured Smarty instance
$smarty = $container->get(\Smarty\Smarty::class);
```

For more information about League Container, see the [official documentation](https://container.thephpleague.com/).

## Usage

Once the extension is registered, you can use it in your Smarty templates in two ways:

### 1. Using the Block Function

The block function allows you to translate blocks of text:

```smarty
{* Basic usage *}
<{trans}>Hello, world!<{/trans}>

{* With a specific domain *}
<{trans domain='admin'}>_ADMIN_WELCOME<{/trans}>

{* With parameters *}
<{trans parameters=['name' => 'John']}>Hello, {name}!<{/trans}>

{* With domain and locale *}
<{trans domain='messages' locale='fr' parameters=['name' => 'John']}>Hello, {name}!<{/trans}>
```

### 2. Using the Variable Modifier

The variable modifier allows for inline translations:

```smarty
{* Basic usage *}
<{"Hello, world!"|trans}>

{* With parameters *}
<{"Hello, {name}!"|trans:["name" => "John"]}>

{* With domain *}
<{"_ADMIN_WELCOME"|trans:[]:'admin'}>

{* With domain and locale *}
<{"Hello, {name}!"|trans:["name" => "John"]:'messages':'fr'}>
```

### Supported Attributes

Both the block function and variable modifier support the following attributes:

| Attribute  | Description                                                   | Default Value         |
|------------|---------------------------------------------------------------|----------------------|
| parameters | Key/value pairs to replace placeholders in translated strings  | `[]`                 |
| domain     | Translation domain (usually corresponds to translation file)   | *system default*     |
| locale     | Specific locale to use for translation                         | *current system locale* |

For the variable modifier, the syntax is: `trans:PARAMETERS:DOMAIN:LOCALE`

## Testing

This package includes a comprehensive test suite. To run the tests:

```bash
composer test
```

## Documentation

API documentation is automatically generated and available in the project's wiki. For more detailed information about the classes and methods, please refer to the [project wiki](https://github.com/imponeer/smarty-translate/wiki).

## Contributing

Contributions are welcome! Here's how you can contribute:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

Please make sure your code follows the PSR-12 coding standard and include tests for any new features or bug fixes.

If you find a bug or have a feature request, please create an issue in the [issue tracker](https://github.com/imponeer/smarty-translate/issues).