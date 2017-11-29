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

Now you can generate a QR Code via /qrcode/<text>.png and the QR code factory
is exposed as a service.

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This source code is subject to the MIT license bundled in the file LICENSE.