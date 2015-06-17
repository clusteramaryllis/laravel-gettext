<?php

if (! function_exists('gettext')) {
    /**
     * Set the path for a domain.
     * 
     * @param  string $domain
     * @param  string $path
     * @return string
     */
    function bindtextdomain($domain, $path)
    {
        return app('gettext.repository')->bindTextDomain($domain, $path);
    }

    /**
     * Specify the character encoding in which the messages 
     * from the DOMAIN message catalog will be returned.
     * 
     * @param  string $domain  
     * @param  string $codeset 
     * @return string          
     */
    function bind_textdomain_codeset($domain, $codeset)
    {
        return app('gettext.repository')->bindTextDomainCodeset($domain, $codeset);
    }

    /**
     * Set the default domain.
     * 
     * @param  string|null $domain
     * @return string         
     */
    function textdomain($domain = null)
    {
        return app('gettext.repository')->textDomain($domain);
    }

    /**
     * Lookup a message in the current domain.
     * 
     * @param  string $msgid
     * @return string        
     */
    function gettext($msgid)
    {
        return app('gettext.repository')->getText($msgid);
    }

    /**
     * Alias for gettext.
     * 
     * @param  string $msgid
     * @return string        
     */
    function _($msgid)
    {
        return gettext($msgid);
    }

    /**
     * Plural version of gettext.
     * 
     * @param  string $singular 
     * @param  string $plural 
     * @param  int    $number      
     * @return string
     */
    function ngettext($singular, $plural, $number)
    {
        return app('gettext.repository')->nGetText($singular, $plural, $number);
    }

    /**
     * Override the current domain.
     * 
     * @param  string $domain
     * @param  string $msgid 
     * @return string        
     */
    function dgettext($domain, $msgid)
    {
        return app('gettext.repository')->dGetText($domain, $msgid);
    }

    /**
     * Plural version of dgettext.
     * 
     * @param  string $domain
     * @param  string $singular 
     * @param  string $plural 
     * @param  int    $number      
     * @return string
     */
    function dngettext($domain, $singular, $plural, $number)
    {
        return app('gettext.repository')->dNGetText($domain, $singular, $plural, $number);
    }

    /**
     * Override the domain for a single lookup.
     * 
     * @param  string $domain   
     * @param  string $msgid    
     * @param  int    $category 
     * @return string
     */
    function dcgettext($domain, $msgid, $category)
    {
        return app('gettext.repository')->dCGetText($domain, $msgid, $category);
    }

    /**
     * Plural version of dcgettext.
     * 
     * @param  string $domain
     * @param  string $singular 
     * @param  string $plural 
     * @param  int    $number      
     * @param  int    $category 
     * @return string
     */
    function dcngettext($domain, $singular, $plural, $number, $category)
    {
        return app('gettext.repository')->dCNGetText($domain, $singular, $plural, $number, $category);
    }
}

if (! function_exists('set_locale')) {
    /**
     * Set a requested locale.
     * 
     * @param  mixed $category
     * @return string
     */
    function set_locale($category)
    {
        return call_user_func_array([app('gettext.repository'), 'setLocale'], func_get_args());
    }
}

if (! function_exists('pgettext')) {
    /**
     * Context version of gettext.
     * 
     * @param  string $context
     * @param  string $msgid
     * @return string
     */
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
    /**
     * Override the current domain in a context gettext call.
     * 
     * @param  string $domain
     * @param  string $context
     * @param  string $msgid 
     * @return string        
     */
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
    /**
     * Override the domain and category for a single context-based lookup.
     * 
     * @param  string $domain   
     * @param  string $context
     * @param  string $msgid    
     * @param  int    $category 
     * @return string
     */
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
    /**
     * Context version of ngettext.
     * 
     * @param  string $context
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @return string
     */
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
    /**
     * Override the current domain in a context ngettext call.
     * 
     * @param  string $domain
     * @param  string $context
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @return string
     */
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
    /**
     * Override the domain and category for a plural context-based lookup.
     * 
     * @param  string $domain
     * @param  string $context
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @param  int    $category 
     * @return string
     */
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
