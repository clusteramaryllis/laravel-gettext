<?php

$config = array(

    'languages' => [
        'en' => [
            'locale' => 'en_US',
            'encoding' => 'utf-8',
            'plural_forms' => "nplurals=2; plural=(n != 1);"
        ]
    ],

    'domain' => 'messages',

    'project_name' => 'Translation Project',

    'translator' => 'Translation Team',

    'paths' => [
        __DIR__.'/../views'
    ],

    'destination_path' => __DIR__.'/../locale',

    'storage_path' => __DIR__.'/../storages/test',

    'base_path' => __DIR__.'/..',

    'keywords' => [
        '_',
        'gettext', '__',
        'dgettext:2', '_d:2',
        'dcgettext:2', '_dc:2',
        'ngettext:1,2', '_n:1,2',
        'dngettext:2,3', '_dn:2,3',
        'dcngettext:2,3', '_dcn:2,3',
        'pgettext:1c,2', '_p:1c,2',
        'dpgettext:2c,3', '_dp:1c,2,3',
        'npgettext:1c,2,3', '_np:1c,2,3',
        'dnpgettext:2c,3,4', '_dnp:2c,3,4',
        'dcnpgettext:2c,3,4', '_dcnp:2c,3,4',
    ],

    'forced_rule' => function() {
        return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && 
            version_compare(PHP_VERSION, '5.4.19') > 0);
    },
);
