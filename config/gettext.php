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
    ],
];
