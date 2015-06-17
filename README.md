# laravel-gettext

[![Build Status](https://travis-ci.org/clusteramaryllis/laravel-gettext.svg?branch=master)](https://travis-ci.org/clusteramaryllis/laravel-gettext)

### Installation

Laravel 5.1 Installation

Add the composer repository to your *composer.json* file:

```json
"require": {
    "clusteramaryllis/gettext": "1.1.x"
}
```

* For Laravel 5.0, checkout [1.0 branch](https://github.com/clusteramaryllis/laravel-gettext/tree/1.0)
* For Laravel 4.2, checkout [0.3 branch](https://github.com/clusteramaryllis/laravel-gettext/tree/0.3)

And run `composer update`. Once finished, register via service provider in `config/app.php` in the `providers` array:

```php
'providers' => [

    // ...

    Clusteramaryllis\Gettext\GettextServiceProvider::class,

]
```

Publish the configuration file (optional) (will create on `config/gettext.php`) :

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
php artisan gettext:create --sources="app/Http/Controllers, resources/views" --destination="resources/locale" --locale="en_US" 
```

This will generate .po files in `resources/locale/en_US/LC_MESSAGES/messages.po` & will scan any string that utilize [php-gettext](http://php.net/manual/en/ref.gettext.php) function on `app/Http/controllers` & `resources/views`

Once done, you can easily translate your application using tools such as PoEdit.

### Acknowledgements

This package is inspired by [laravel-gettext](https://github.com/xinax/laravel-gettext/) by Nicolás Daniel Palumbo for .po files creation & utilize [php-gettext](https://launchpad.net/php-gettext/) package by Danilo Segan.
