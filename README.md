# QR Code Bundle

*By [endroid](https://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)
[![Build Status](https://github.com/endroid/qr-code-bundle/workflows/CI/badge.svg)](https://github.com/endroid/qr-code-bundle/actions)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)
[![License](http://img.shields.io/packagist/l/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)

This Symfony bundle lets you generate QR Codes using the [endroid/qr-code](https://github.com/endroid/QrCode)
library. It provides the following features:

* Configure your defaults (like image size, default writer etc.)
* Support for multiple configurations and injection via aliases
* Generate QR codes for defined configurations via URL like /qr-code/<config>/Hello
* Generate QR codes or URLs directly from Twig using dedicated functions

## Installation

Use [Composer](https://getcomposer.org/) to install the library.

``` bash
$ composer require endroid/qr-code-bundle
```

When you use Symfony, the [installer](https://github.com/endroid/installer)
makes sure that services are automatically wired. If this is not the case you
can find the configuration files in the `.install/symfony` folder.

If you don't want the installer to create the auto-configuration files, it can
be disabled as described [here](https://github.com/endroid/installer#configuration).

## Configuration

The bundle makes use of builders to create QR codes. The default parameters
applied by the builder can optionally be overridden via the configuration. and
multiple configurations (thus builders) can be defined.

```yaml
endroid_qr_code:
    default:
        writer: Endroid\QrCode\Writer\PngWriter
        data: 'This is customized QR code'
        # Label is not implemented for SvgWriter
        labelText: 'This is the label'
        labelFontPath: '%kernel.project_dir%/vendor/endroid/qr-code/assets/noto_sans.otf'
        labelFontSize: 20
        labelAlignment: 'center'
    custom:
        writer: Endroid\QrCode\Writer\SvgWriter
        writerOptions:
            exclude_xml_declaration: true # default: false
        data: 'This is customized QR code'
        size: 300
        encoding: 'UTF-8'
        errorCorrectionLevel: 'low' # 'low', 'medium', 'quartile', or 'high'
        roundBlockSizeMode: 'margin'
        logoPath: '%kernel.project_dir%/vendor/endroid/qr-code/tests/assets/symfony.png'
        logoResizeToWidth: 150
        validateResult: false
```

## Using builders

Each configuration results in a builder which can be injected in your classes.
For instance the custom builder from the example above can be injected like this
and you can override the default configuration as follows.

```php
use Endroid\QrCode\Builder\BuilderInterface;

public function __construct(BuilderInterface $customQrCodeBuilder)
{
    $result = $customQrCodeBuilder
        ->size(400)
        ->margin(20)
        ->build();
}
```

## QR Code Response

The bundle also provides a response object to ease rendering of the resulting
image by automatically saving to contents and setting the correct content type.

```php
use Endroid\QrCodeBundle\Response\QrCodeResponse;

$response = new QrCodeResponse($result);
```

## Generate via URL

The bundle provides a controller that allows you to generate QR codes simply
by opening an URL like /qr-code/{builder}/{data}. You can configure the prefix
in your routing file and pass any of the existing options via query string.

## Generate via Twig

The bundle provides a Twig extension for generating a QR code URL, path or data
URI. You can use the second argument to specify the builder to use.

```twig
<img src="{{ qr_code_path('My QR Code') }}" />
<img src="{{ qr_code_url('My QR Code') }}" />
<img src="{{ qr_code_data_uri('My QR Code') }}" />

{# You can specify the builder via the second parameter #}
<img src="{{ qr_code_data_uri('My QR Code', 'custom') }}" />
```
    
## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This source code is subject to the MIT license bundled in the file LICENSE.
