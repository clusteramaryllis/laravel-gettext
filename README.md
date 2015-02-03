# laravel-gettext

[![Build Status](https://travis-ci.org/clusteramaryllis/laravel-gettext.svg?branch=0.3)](https://travis-ci.org/clusteramaryllis/laravel-gettext)

### Installation

Laravel 5.0 Installation

Add the composer repository to your *composer.json* file:

```json
"require": {
    "clusteramaryllis/gettext": "1.0.x"
}
```

For Laravel 4.2, checkout [0.2 branch](https://github.com/clusteramaryllis/laravel-gettext/tree/0.2)

And run `composer update`. Once finished, register via service provider in `app/config/app.php` in the `providers` array:

```php
'providers' => [

    // ...

    'Clusteramaryllis\Gettext\GettextServiceProvider',

]
```

Publish the configuration file (optional) (will create on `config/gettext.php`):

```bash
php artisan vendor:publish
```

### Command

**Available commands**

`gettext:create` => Generate new .po file

`gettext:update` => Update existing .po file

**Available options**

Check with `php artisan gettext:create --help` or `php artisan gettext:update --help`

**Example**

```bash
php artisan gettext:create --sources="app/controllers, app/views" --destination="app/locale" --locale="en_US" 
```

This will generate .po files in `app/locale/en_US/LC_MESSAGES/messages.po` & will scan any string that utilize [php-gettext](http://php.net/manual/en/ref.gettext.php) function on `app/controllers` & `app/views`

Once done, you can easily translate your application using tools such as PoEdit.

### Acknowledgements

This package is inspired by [laravel-gettext](https://github.com/xinax/laravel-gettext/) by Nicolás Daniel Palumbo for .po files creation & utilize [php-gettext](https://launchpad.net/php-gettext/) package by Danilo Segan.
