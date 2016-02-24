# laravel-gettext

[![Build Status](https://travis-ci.org/clusteramaryllis/laravel-gettext.svg?branch=master)](https://travis-ci.org/clusteramaryllis/laravel-gettext)

### Installation

Laravel 5.x Installation

Add the composer repository to your *composer.json* file:

```json
"require": {
    "clusteramaryllis/gettext": "1.2.x"
}
```

* For Laravel 4.2, checkout [0.3 branch](https://github.com/clusteramaryllis/laravel-gettext/tree/0.3)

And run `composer update`. Once finished, register via service provider in `config/app.php` in the `providers` array:

```php
'providers' => [

    // ...

    Clusteramaryllis\Gettext\GettextServiceProvider::class,

]
```

You can also provide static syntax via facade in the `aliases` array:
```php
'aliases' => [

    // ...

    'Gettext' => Clusteramaryllis\Gettext\Facades\Gettext::class,

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

This will generate .po files in `resources/locale/en_US/LC_MESSAGES/messages.po` & will scan any string that utilize [php-gettext](http://php.net/manual/en/ref.gettext.php) function on `app/Http/Controllers` & `resources/views`

Once done, you can easily translate your application using tools such as PoEdit.

### How To

**Simple usage**

1) Prepare `view` with strings wrapped with `Gettext` method or helper

```html
<!-- resources\views\welcome.blade.php -->
{!! __('Welcome to main page') !!}
``` 

2) Add your language preferences via `config/gettext.php` on languages array

```php
languages => [

    // ...,

    'sv' => [
        'locale' => 'sv_SE',
        'encoding' => 'utf-8',
        'plural_forms' => "nplurals=2; plural=(n != 1);",
    ]      
]
```

3) Run `php artisan gettext:create`. This will generate .po file in

```
resources\locale\sv_SE\LC_MESSAGES\messages.po
```

& ready to scan translated string in `app\Http\Controllers` & `resources\views` (Default option).

4) Open the .po file with PoEdit or any similar editors. In PoEdit you need to click update to populate
the table with the scanned strings. After that, you can start begin translating.

5) Simple routes test

```php
Route::get('/', function() {

    Gettext::bindTextDomain('messages', base_path('resources/locale'));
    Gettext::textDomain('messages');

    Gettext::setLocale(LC_ALL, 'sv_SE.utf-8');

    return view('welcome');
});
```

**Available methods**

Methods | Helper shortcut
------- | ---------------
Gettext::setLocale | _setlocale
Gettext::bindTextDomain | _bindtextdomain
Gettext::bindTextDomainCodeset | _bind_text_domain_codeset
Gettext::textDomain | _textdomain
Gettext::getText | __
Gettext::nGetText | _n
Gettext::dGetText | _d
Gettext::dNGetText | _dn
Gettext::dCGetText | _dc
Gettext::dCNGetText | _dcn
Gettext::pGetText | _p
Gettext::dPGetText | _dp
Gettext::dCPGetText | _dcp
Gettext::nPGetText | _np
Gettext::dNPGetText | _dnp
Gettext::dCNPGetText | _dcnp

More detailed method & their parameters can be seen [here](https://github.com/clusteramaryllis/laravel-gettext/blob/master/src/Gettext.php).

### Acknowledgements

This package is inspired by [laravel-gettext](https://github.com/xinax/laravel-gettext/) by Nicolás Daniel Palumbo for .po files creation & utilize [php-gettext](https://launchpad.net/php-gettext/) package by Danilo Segan.
