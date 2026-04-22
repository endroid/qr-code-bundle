# QR Code Bundle

*By [endroid](https://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)
[![Build Status](https://github.com/endroid/qr-code-bundle/workflows/CI/badge.svg)](https://github.com/endroid/qr-code-bundle/actions)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)
[![License](http://img.shields.io/packagist/l/endroid/qr-code-bundle.svg)](https://packagist.org/packages/endroid/qr-code-bundle)

This Symfony bundle lets you generate QR Codes using the [endroid/qr-code](https://github.com/endroid/qr-code)
library. It provides the following features:

* Configure your defaults (like image size, default writer etc.)
* Support for multiple configurations and injection via aliases
* Generate QR codes for defined configurations via URL like /qr-code/<config>/Hello
* Generate QR codes or URLs directly from Twig using dedicated functions

## Installation

Use [Composer](https://getcomposer.org/) to install the library. Also make sure you have enabled and configured the
[GD extension](https://www.php.net/manual/en/book.image.php) if you want to generate images.

``` bash
composer require endroid/qr-code-bundle
```

### Route Configuration

By default the bundle registers a controller that generates QR codes via the URL
`/qr-code/{builder}/{data}`. If you only use QR codes programmatically or via Twig
and don't need the route, you can disable it. You can also set a custom prefix.

```yaml
endroid_qr_code:
    route_enabled: true
    route_prefix: '/my-custom-prefix'
```

This makes the route available at `/my-custom-prefix/{builder}/{data}`.

## Builder Configuration

The bundle makes use of builders to create QR codes. The default parameters
applied by the builder can optionally be overridden via the configuration. And
multiple configurations (thus builders) can be defined.

Each entry under `builders` defines a named builder with its own defaults.
When no builders are configured the bundle registers a single `default` builder.

```yaml
endroid_qr_code:
    builders:
        default:
            writer: Endroid\QrCode\Writer\PngWriter
            data: 'This is customized QR code'
            logo_path: '%kernel.project_dir%/vendor/endroid/qr-code/tests/assets/symfony.png'
            logo_resize_to_width: 150
            logo_punchout_background: true # only supported by PngWriter
            label_text: 'This is the label'
            label_font_path: '%kernel.project_dir%/vendor/endroid/qr-code/assets/noto_sans.otf'
            label_font_size: 20
            label_alignment: 'center'
        custom:
            writer: Endroid\QrCode\Writer\SvgWriter
            writer_options:
                exclude_xml_declaration: true # default: false
            data: 'This is customized QR code'
            size: 300
            encoding: 'UTF-8'
            error_correction_level: 'low' # 'low', 'medium', 'quartile', or 'high'
            round_block_size_mode: 'margin'
            validate_result: false
```

## Using Named Builders

Each builder is available for injection using its name suffixed with
`QrCodeBuilder`. For example the `custom` builder above can be injected as
`$customQrCodeBuilder`. You can override the configured defaults at build time:

```php
use Endroid\QrCode\Builder\BuilderInterface;

public function __construct(BuilderInterface $customQrCodeBuilder)
{
    $result = $customQrCodeBuilder->build(
        size: 400,
        margin: 20
    );
}
```

## QR Code Response

The bundle also provides a response object to ease rendering of the resulting
image by automatically saving to contents and setting the correct content type.

```php
use Endroid\QrCodeBundle\Response\QrCodeResponse;

$response = new QrCodeResponse($result);
```

## Twig Extension

The bundle provides a Twig extension for generating a QR code URL, path or data
URI. You can use the second argument to specify the builder to use.

```twig
<img src="{{ qr_code_path('My QR Code') }}" />
<img src="{{ qr_code_url('My QR Code') }}" />
<img src="{{ qr_code_data_uri('My QR Code') }}" />

{# You can specify the builder via the second parameter #}
<img src="{{ qr_code_data_uri('My QR Code', 'custom') }}" />

{# You can access the width and height via the matrix #}
{% set qrCode = qr_code_result('My QR Code') %}
<img src="{{ qrCode.dataUri }}" width="{{ qrCode.matrix.outerSize }}" />
```

Please note that some Twig functions need the route to be enabled to function.
    
## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This source code is subject to the MIT license bundled in the file LICENSE.
