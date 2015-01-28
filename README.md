# laravel-gettext

[![Build Status](https://travis-ci.org/clusteramaryllis/laravel-gettext.svg?branch=master)](https://travis-ci.org/clusteramaryllis/laravel-gettext)

### Installation

Laravel 4.2 Installation

Add the composer repository to your *composer.json* file:

```json
"require": {
    "clusteramaryllis/gettext": "0.x"
}
```

And run `composer update`. Once finished, register via service provider in `app/config/app.php` in the `providers` array:

```php
'providers' => array(
  'Clusteramaryllis\Gettext\GettextServiceProvider',
)
```

You can also register `facade` in `aliases` key:
```php
'aliases' => array(
  'Gettext' => 'Clusteramaryllis\Gettext\Facade\Gettext',
)
```

Publish the configuration file (optional):

```bash
php artisan config:publish clusteramaryllis/gettext
```