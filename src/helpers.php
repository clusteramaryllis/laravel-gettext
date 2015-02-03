<?php

$gettextFallback = new \Clusteramaryllis\Gettext\Repositories\GettextFallback();

if (! function_exists('gettext')) {
    function bindtextdomain($domain, $path)
    {
        global $gettextFallback;
        return $gettextFallback->bindTextDomain($domain, $path);
    }

    function bind_textdomain_codeset($domain, $codeset)
    {
        global $gettextFallback;
        return $gettextFallback->bindTextDomainCodeset($domain, $codeset);
    }

    function textdomain($domain = null)
    {
        global $gettextFallback;
        return $gettextFallback->textDomain($domain);
    }

    function gettext($msgid)
    {
        global $gettextFallback;
        return $gettextFallback->getText($msgid);
    }

    function _($msgid)
    {
        global $gettextFallback;
        return $gettextFallback->getText($msgid);
    }

    function ngettext($singular, $plural, $number)
    {
        global $gettextFallback;
        return $gettextFallback->nGetText($singular, $plural, $number);
    }

    function dgettext($domain, $msgid)
    {
        global $gettextFallback;
        return $gettextFallback->dGetText($domain, $msgid);
    }

    function dngettext($domain, $singular, $plural, $number)
    {
        global $gettextFallback;
        return $gettextFallback->dNGetText($domain, $singular, $plural, $number);
    }

    function dcgettext($domain, $msgid, $category)
    {
        global $gettextFallback;
        return $gettextFallback->dCGetText($domain, $msgid, $category);
    }

    function dcngettext($domain, $singular, $plural, $number, $category)
    {
        global $gettextFallback;
        return $gettextFallback->dCNGetText($domain, $singular, $plural, $number, $category);
    }
}

if (! function_exists('T_setlocale')) {
    function T_setlocale($category, $locale)
    {
        global $gettextFallback;
        return $gettextFallback->setLocale($category, $locale);
    }
}

if (! function_exists('pgettext')) {
    function pgettext($context, $msgid)
    {
        $context_id = "{$context}\004{$msgid}";
        $translation = gettext($context_id);

        if ($translation == $context_id) {
            return $msgid;
        }

        return $translation;
    }
}

if (! function_exists('dpgettext')) {
    function dpgettext($domain, $context, $msgid)
    {
        $context_id = "{$context}\004{$msgid}";
        $translation = dgettext($domain, $context_id);

        if ($translation == $context_id) {
            return $msgid;
        }

        return $translation;
    }
}

if (! function_exists('dcpgettext')) {
    function dcpgettext($domain, $context, $msgid, $category)
    {
        $context_id = "{$context}\004{$msgid}";
        $translation = dcgettext($domain, $context_id, $category);

        if ($translation == $context_id) {
            return $msgid;
        }

        return $translation;
    }
}

if (! function_exists('npgettext')) {
    function npgettext($context, $msgid1, $msgid2, $n)
    {
        $context_id = "{$context}\004{$msgid1}";
        $translation = ngettext($context_id, $msgid2, $n);

        if ($translation == $context_id || $translation == $msgid2) {
            return $n == 1 ? $msgid1 : $msgid2;
        }

        return $translation;
    }
}

if (! function_exists('dnpgettext')) {
    function dnpgettext($domain, $context, $msgid1, $msgid2, $n)
    {
        $context_id = "{$context}\004{$msgid1}";
        $translation = dngettext($domain, $context_id, $msgid2, $n);

        if ($translation == $context_id || $translation == $msgid2) {
            return $n == 1 ? $msgid1 : $msgid2;
        }

        return $translation;
    }
}

if (! function_exists('dcnpgettext')) {
    function dcnpgettext($domain, $context, $msgid1, $msgid2, $n, $category)
    {
        $context_id = "{$context}\004{$msgid1}";
        $translation = dcngettext($domain, $context_id, $msgid2, $n, $category);

        if ($translation == $context_id || $translation == $msgid2) {
            return $n == 1 ? $msgid1 : $msgid2;
        }

        return $translation;
    }
}

if (! function_exists('set_locale_env')) {
    function set_locale_env($category)
    {
        return forward_static_call_array(
            array('Clusteramaryllis\Gettext\Facade\Gettext', 'setLocale'),
            func_get_args()
        );
    }
}
