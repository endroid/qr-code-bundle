# Upgrade to 7.x

This guide lists the breaking changes when upgrading from `6.x`.

## Requirements

The bundle no longer requires `endroid/installer` or `symfony/yaml` directly.

## Bundle configuration

Builder configuration must now be placed under the `builders` key.

Before:

```yaml
endroid_qr_code:
    default:
        writer: Endroid\QrCode\Writer\PngWriter
        size: 300
    custom:
        writer: Endroid\QrCode\Writer\SvgWriter
```

After:

```yaml
endroid_qr_code:
    builders:
        default:
            writer: Endroid\QrCode\Writer\PngWriter
            size: 300
        custom:
            writer: Endroid\QrCode\Writer\SvgWriter
```

## Routes

The QR code route is now registered by the bundle itself. Use bundle configuration:

```yaml
endroid_qr_code:
    route_enabled: true
    route_prefix: /qr-code
```

## Internal files

The `Resources/config/*.yaml` files were removed.

