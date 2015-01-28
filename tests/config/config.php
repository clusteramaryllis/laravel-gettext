<?php

$config = array(

    /*
     * List of valid generated locales.
     *
     * See Reference: https://github.com/netson/l4gettext/blob/master/src/config/locales.php#L19-L228
     */
    'locales' => array(
        'en_US',
    ),

    /*
     * Valid character encoding.
     *
     * See Reference: https://github.com/skooler/camellia/blob/master/converter.php#L66-L440
     */
    'encoding' => 'UTF-8',

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
     * Default Poedit software version.
     */
    'poedit_version' => 'Poedit 1.7.1',

    /**
     * Default path to be scanned.
     */
    'paths' => array(
        __DIR__.'/../views'
    ),

    /**
     * Default path for generated .po files to be stored.
     */
    'destination_path' => __DIR__.'/../locale',

    /**
     * Default path for compiled .blade.php files to be temporary stored.
     */
    'storage_path' => __DIR__.'/../storages/test',

    /**
     * Base path
     */
    'base_path' => __DIR__.'/..',

    /**
     * List of keywords to scan in file.
     *
     * '_', shorthand for gettext
     * 'gettext', the default php gettext function
     * 'dgettext:2', accepts plurals, uses the second argument passed to dgettext as a translation string
     * 'dcgettext:2', accepts plurals, uses the second argument passed to dcgettext as a translation string
     * 'ngettext:1,2', accepts plurals, uses the first and second argument passed to ngettext as a translation string
     * 'dngettext:2,3', accepts plurals, used the second and third argument passed to dngettext as a translation string
     * 'dcngettext:2,3', accepts plurals, used the second and third argument passed to dcngettext as a translation string
     */
    'keywords' => array(
        '_',
        'gettext',
        'dgettext:2',
        'dcgettext:2',
        'ngettext:1,2',
        'dngettext:2,3',
        'dcngettext:2,3',
        '__dgettext',
        '__dcgettext',
        '__dngettext:1,2',
        '__dcngettext:1,2',
    ),
);
