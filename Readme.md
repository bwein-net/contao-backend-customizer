# Backend Customizer for Contao Open Source CMS

This Bundle allows to set some params via config and generates a custom backend stylesheet before symfony command ``assets:install``.

## Installation

Install the bundle via Composer:

```
composer require bwein-net/contao-backend-customizer
```

Add the config parameters and generate the stylesheet.

## Configuration

In the ``config/config.yml`` you can add the following parameters - all parameters are empty by default:

```yaml
# config/config.yml
bwein_backend_customizer:
    header_title: 'bwein.net'
    header_color: '#006B7A'
```

The ``header_title`` is shown on the left side of the backend header next to the contao logo and on the backend login form.
With the ``header_color`` you can override the typical contao color of the backend header.


Ideally in the ``config/parameters.yml`` you can set the environment parameters, so that it can differentiate between the deployed webspaces.

```yml
# config/parameters.yml
bwein_backend_customizer:
    env_title: 'local'
    env_color: ''
```

The ``env_title`` is shown on the right side of the backend header before the notification icon and on the backend login form.

You can set the following ``env_title``-Values - and then the environment highlight will be shown in the color in brackets:
 * ``'local'`` (red)
 * ``'dev'`` (orange)
 * ``'staging'`` (green)
 * ``'prod'`` (none - same as default '')

To override the default environment color, you can set the param ``env_color`` with a color value.
Besides, if you use a different ``env_title``, the ``env_color`` is mandatory to show the environment highlight!

## Stylesheet Generation

The backend stylesheet ``/bundles/bweinbackendcustomizer/css/backend.css`` will be generated before each ``assets:install``!
Afterwards the backend should show the custom title, color + environment highlight.

After manipulating the configuration you first have to clear the cache to adopt new parameters.
The following commands are recommended for generation:

```
vendor/bin/contao-console cache:clear --no-warmup
vendor/bin/contao-console cache:warmup
vendor/bin/contao-console assets:install web --symlink --relative
```

> If you specified `symfony-web-dir`, you have to replace the target directory `web` for `assets:install` with your definition!

## Screenshots

![Backend header](screenshot-backend-header.png)

![Backend login](screenshot-backend-login.png)
