# QR Code Bundle

*By [endroid](https://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)
[![Build Status](http://img.shields.io/travis/endroid/qr-code-bundle.svg)](http://travis-ci.org/endroid/qr-code-bundle)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)
[![License](http://img.shields.io/packagist/l/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)

This Symfony lets you generate QR Codes using the [endroid/qr-code](https://github.com/endroid/QrCode)
library. It provides the following features.

* Configure your defaults (like image size, default writer etc.)
* Generate QR codes quickly from anywhere via the factory service
* Generate QR codes directly by typing an URL like /qr-code/\<text>.png?size=300
* Generate QR codes or URLs directly from Twig using dedicated functions

## Installation

Use [Composer](https://getcomposer.org/) to install the library. Symfony Flex
will set up the configuration and routing for you.

``` bash
$ composer require endroid/qr-code-bundle
```

## Configuration

The bundle makes use of a factory to create QR codes. The default parameters
applied by the factory can optionally be overridden via the configuration.

```yaml
endroid_qr_code:
    writer: 'png'
    size: 300
    margin: 10
    foreground_color: { r: 0, g: 0, b: 0 }
    background_color: { r: 255, g: 255, b: 255 }
    error_correction_level: low # low, medium, quartile or high
    encoding: UTF-8
    label: Scan the code
    label_font_size: 20
    label_alignment: left # left, center or right
    label_margin: { b: 20 }
    logo_path: '%kernel.root_dir%/../vendor/endroid/qr-code/assets/images/symfony.png'
    logo_width: 150
    validate_result: false # checks if the result is readable
```

## Generate via factory

Now you can retrieve the factory from the service container and create a QR
code. You can also pass options to override defaults set by your configuration.

```php
$qrCode = $qrCodeFactory->create('QR Code', ['size' => 200]);
```

## Generate via URL

The bundle provides a controller that allows you to generate QR codes simply
by opening an URL like /qr-code/\<text>.png?size=300. You can configure the
prefix in your routing file and pass any of the existing options via query string.

## Generate via Twig

The bundle provides a Twig extension for generating a QR code URL, path or data
URI. You can use the second argument of any of these functions to override any
defaults defined by the bundle or set via your configuration.

``` twig
<img src="{{ qr_code_path(message) }}" />
<img src="{{ qr_code_url(message, { writer: 'eps' }) }}" />
<img src="{{ qr_code_data_uri(message, { writer: 'svg', size: 150 }) }}" />
```

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This source code is subject to the MIT license bundled in the file LICENSE.