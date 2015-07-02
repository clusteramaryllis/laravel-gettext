<?php

return [

    /*
     * List of valid generated locales.
     *
     * See Reference(s):
     * - List of locales : github.com/netson/l4gettext/blob/master/src/config/locales.php#L19-L228
     * - List of encoding : github.com/skooler/camellia/blob/master/converter.php#L66-L440
     * - List of plural forms : localization-guide.readthedocs.org/en/latest/l10n/pluralforms.html
     */
    'languages' => [
        'en' => [
            'locale' => 'en_US',
            'encoding' => 'utf-8',
            'plural_forms' => "nplurals=2; plural=(n != 1);",
        ],
    ],

    /**
     * Default text domain.
     */
    'domain' => 'messages',

    /**
     * Default translation project name.
     */
    'project_name' => 'Translation Project',

    /**
     * Default translator.
     */
    'translator' => 'Translation Team',

    /**
     * Default path to be scanned.
     */
    'source_paths' => [
        base_path('resources/views'),
        app_path('Http/Controllers'),
    ],

    /**
     * Default path for generated .po files to be stored.
     */
    'destination_path' => base_path('resources/locale'),

    /**
     * Default path for compiled .blade.php files to be temporary stored.
     */
    'storage_path' => storage_path('framework/views'),

    /**
     * List of keywords to scan in file.
     */
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

    /**
     * Rule to force emulates gettext api rather than native php-gettext
     * Related to bug php-gettext on windows : https://bugs.php.net/bug.php?id=66265
     */
    'forced_rule' => function() {
        return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && 
            version_compare(PHP_VERSION, '5.4.19') > 0);
    },
];
