<?php

if (! function_exists('gettext')) {
    function bindtextdomain($domain, $path)
    {
        return app('gettext.repository')->bindTextDomain($domain, $path);
    }

    function bind_textdomain_codeset($domain, $codeset)
    {
        return app('gettext.repository')->bindTextDomainCodeset($domain, $codeset);
    }

    function textdomain($domain = null)
    {
        return app('gettext.repository')->textDomain($domain);
    }

    function gettext($msgid)
    {
        return app('gettext.repository')->getText($msgid);
    }

    function _($msgid)
    {
        return app('gettext.repository')->getText($msgid);
    }

    function ngettext($singular, $plural, $number)
    {
        return app('gettext.repository')->nGetText($singular, $plural, $number);
    }

    function dgettext($domain, $msgid)
    {
        return app('gettext.repository')->dGetText($domain, $msgid);
    }

    function dngettext($domain, $singular, $plural, $number)
    {
        return app('gettext.repository')->dNGetText($domain, $singular, $plural, $number);
    }

    function dcgettext($domain, $msgid, $category)
    {
        return app('gettext.repository')->dCGetText($domain, $msgid, $category);
    }

    function dcngettext($domain, $singular, $plural, $number, $category)
    {
        return app('gettext.repository')->dCNGetText($domain, $singular, $plural, $number, $category);
    }
}

if (! function_exists('set_locale')) {
    function set_locale($category)
    {
        return call_user_func_array([app('gettext.repository'), 'setLocale'], func_get_args());
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
