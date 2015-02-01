<?php

$config = array(

    'languages' => array(
        'en' => array(
            'locale' => 'en_US',
            'encoding' => 'utf-8',
            'plural_forms' => "nplurals=2; plural=(n != 1);"
        )
    ),

    'domain' => 'messages',

    'project_name' => 'Translation Project',

    'translator' => 'Translation Team',

    'paths' => array(
        __DIR__.'/../views'
    ),

    'destination_path' => __DIR__.'/../locale',

    'storage_path' => __DIR__.'/../storages/test',

    'base_path' => __DIR__.'/..',

    'keywords' => array(
        '_',
        'gettext',
        'dgettext:2',
        'dcgettext:2',
        'ngettext:1,2',
        'dngettext:2,3',
        'dcngettext:2,3',
        'pgettext:1c,2',
        'dpgettext:2c,3',
        'npgettext:1c,2,3',
        'dnpgettext:2c,3,4',
        'dcnpgettext:2c,3,4',
    ),
);
