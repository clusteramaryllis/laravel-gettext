<?php

use Clusteramaryllis\Gettext\Facade\Gettext;

if (! function_exists('pgettext')) {
    function pgettext($context, $message)
    {
        return Gettext::pGetText($context, $message);
    }
}

if (! function_exists('dpgettext')) {
    function pgettext($domain, $context, $message)
    {
        return Gettext::dPGetText($domain, $context, $message);
    }
}

if (! function_exists('npgettext')) {
    function npgettext($context, $msgid1, $msgid2, $n = 1)
    {
        return Gettext::nPGettext($context, $msgid1, $msgid2, $n);
    }
}

if (! function_exists('dcpgettext')) {
    function dcpgettext($domain, $context, $message, $category = LC_ALL)
    {
        return Gettext::dCPGetText($domain, $context, $message, $category);
    }
}

if (! function_exists('dcnpgettext')) {
    function dcnpgettext($domain, $context, $msgid1, $msgid2, $n = 1, $category = LC_ALL)
    {
        return Gettext::dCNPGettext($domain, $context, $msgid1, $msgid2, $n, $category);
    }
}

if (! function_exists('__bindtextdomain')) {
    function __bindtextdomain($domain, $directory, $codeset = 'UTF-8')
    {
        return Gettext::bindTextDomain($domain, $directory, $codeset);
    }
}

if (! function_exists('__setlocale')) {
    function __setlocale($category)
    {
        return forward_static_call_array(
            array('Clusteramaryllis\Gettext\Facade\Gettext', 'setLocale'),
            func_get_args()
        );
    }
}
