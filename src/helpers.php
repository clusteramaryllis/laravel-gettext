<?php

use \Clusteramaryllis\Gettext\Facade\Gettext;

if (! function_exists('__dgettext')) {
    function __dgettext($message)
    {
        return Gettext::dGettext($message);
    }
}

if (! function_exists('__dngettext')) {
    function __dngettext($msgid1, $msgid2, $n)
    {
        return Gettext::dNGettext($msgid1, $msgid2, $n);
    }
}

if (! function_exists('__dcgettext')) {
    function __dcgettext($message, $category = LC_ALL)
    {
        return Gettext::dCGettext($message, $category);
    }
}

if (! function_exists('__dcngettext')) {
    function __dcngettext($msgid1, $msgid2, $n, $category = LC_ALL)
    {
        return Gettext::dCNGettext($msgid1, $msgid2, $n, $category);
    }
}

if (! function_exists('__bindtextdomain')) {
    function __bindtextdomain($domain, $directory, $codeset = 'UTF-8')
    {
        Gettext::setTextDomain($domain, $directory, $codeset);

        $domain = Gettext::getCurrentDomain();

        return $domain['directory'];
    }
}

if (! function_exists('__setlocale')) {
    function __setlocale($category, $locale, $locales = array())
    {
        Gettext::setLocale($category, $locale, $locales);

        return Gettext::getLocale();
    }
}
