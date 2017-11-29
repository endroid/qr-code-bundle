EndroidQrCodeBundle
===================

*By [endroid](http://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/qrcode-bundle.svg)](https://packagist.org/packages/endroid/qrcode-bundle)
[![Build Status](http://img.shields.io/travis/endroid/EndroidQrCodeBundle.svg)](http://travis-ci.org/endroid/EndroidQrCodeBundle)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/qrcode-bundle.svg)](https://packagist.org/packages/endroid/qrcode-bundle)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/qrcode-bundle.svg)](https://packagist.org/packages/endroid/qrcode-bundle)
[![License](http://img.shields.io/packagist/l/endroid/qrcode-bundle.svg)](https://packagist.org/packages/endroid/qrcode-bundle)

This Symfony lets you generate QR Codes using the [endroid/qrcode library](https://github.com/endroid/QrCode).

## Installation

Use [Composer](https://getcomposer.org/) to install the library. Symfony Flex
will set up the configuration and routing for you.

``` bash
$ composer require endroid/qrcode-bundle
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
    logo_path: '%kernel.root_dir%/../vendor/endroid/qrcode/assets/symfony.png'
    logo_width: 150
    validate_result: false # checks if the result is readable
```

## Using the factory

Now you can retrieve the factory from the service container and create a QR
code. You can also pass options to override defaults set by your configuration.

```php
$qrCode = $qrCodeFactory->create('QR Code', ['size' => 200]);
```

## Using URLs

The bundle provides a controller that allows you to generate QR codes simply
by opening an URL like https://\<yourdomain>/qrcode/\<text>.png?size=300. You
can configure the prefix in your routing file and pass any of the existing
options via query string.

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This source code is subject to the MIT license bundled in the file LICENSE.