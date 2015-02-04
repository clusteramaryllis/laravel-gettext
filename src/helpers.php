<?php

$_gettext_fallback = new \Clusteramaryllis\Gettext\Repositories\Gettext();

if (! function_exists('gettext')) {
    function bindtextdomain($domain, $path)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->bindTextDomain($domain, $path);
    }

    function bind_textdomain_codeset($domain, $codeset)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->bindTextDomainCodeset($domain, $codeset);
    }

    function textdomain($domain = null)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->textDomain($domain);
    }

    function gettext($msgid)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->getText($msgid);
    }

    function _($msgid)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->getText($msgid);
    }

    function ngettext($singular, $plural, $number)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->nGetText($singular, $plural, $number);
    }

    function dgettext($domain, $msgid)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->dGetText($domain, $msgid);
    }

    function dngettext($domain, $singular, $plural, $number)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->dNGetText($domain, $singular, $plural, $number);
    }

    function dcgettext($domain, $msgid, $category)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->dCGetText($domain, $msgid, $category);
    }

    function dcngettext($domain, $singular, $plural, $number, $category)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->dCNGetText($domain, $singular, $plural, $number, $category);
    }
}

if (! function_exists('set_locale')) {
    function set_locale($category, $locale)
    {
        global $_gettext_fallback;
        return $_gettext_fallback->setLocale($category, $locale);
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
