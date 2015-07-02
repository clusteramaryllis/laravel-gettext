<?php

if (! function_exists('_setlocale')) {
    /**
     * Set a requested locale.
     * 
     * @param  mixed $category
     * @return string
     */
    function _setlocale($category)
    {
        return call_user_func_array([app('gettext'), '::setLocale'], func_get_args());
    }
}

if (! function_exists('_bindtextdomain')) {
    /**
     * Set the path for a domain.
     * 
     * @param  string $domain
     * @param  string $path
     * @return string
     */
    function _bindtextdomain($domain, $path)
    {
        return app('gettext')->bindTextDomain($domain, $path);
    }
}

if (! function_exists('_bind_textdomain_codeset')) {
    /**
     * Specify the character encoding in which the messages 
     * from the DOMAIN message catalog will be returned.
     * 
     * @param  string $domain  
     * @param  string $codeset 
     * @return string          
     */
    function _bind_textdomain_codeset($domain, $codeset)
    {
        return app('gettext')->bindTextDomainCodeset($domain, $codeset);
    }
}

if (! function_exists('_textdomain')) {
    /**
     * Set the default domain.
     * 
     * @param  string|null $domain
     * @return string         
     */
    function _textdomain($domain = null)
    {
        return app('gettext')->textDomain($domain);
    }
}

if (! function_exists('__')) {
    /**
     * Lookup a message in the current domain.
     * 
     * @param  string $msgid
     * @return string        
     */
    function __($msgid)
    {
        return app('gettext')->getText($msgid);
    }
}

if (! function_exists('_n')) {
    /**
     * Plural version of gettext.
     * 
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @return string
     */
    function _n($msgid1, $msgid2, $n)
    {
        return app('gettext')->nGetText($msgid1, $msgid2, $n);
    }
}

if (! function_exists('_d')) {
    /**
     * Override the current domain.
     * 
     * @param  string $domain
     * @param  string $msgid 
     * @return string        
     */
    function _d($domain, $msgid)
    {
        return app('gettext')->dGetText($domain, $msgid);
    }
}

if (! function_exists('_dn')) {
    /**
     * Plural version of dgettext.
     * 
     * @param  string $domain
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @return string
     */
    function _dn($domain, $msgid1, $msgid2, $n)
    {
        return app('gettext')->dNGetText($domain, $msgid1, $msgid2, $n);
    }
}

if (! function_exists('_dc')) {
    /**
     * Override the domain for a single lookup.
     * 
     * @param  string $domain   
     * @param  string $msgid    
     * @param  int    $category 
     * @return string
     */
    function _dc($domain, $msgid, $category)
    {
        return app('gettext')->dCGetText($domain, $msgid, $category);
    }
}

if (! function_exists('_dcn')) {
    /**
     * Plural version of dcgettext.
     * 
     * @param  string $domain
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @param  int    $category 
     * @return string
     */
    function _dcn($domain, $msgid1, $msgid2, $n, $category)
    {
        return app('gettext')->dCNGetText($domain, $msgid1, $msgid2, $n, $category);
    }
}

if (! function_exists('_p')) {
    /**
     * Context version of gettext.
     * 
     * @param  string $context
     * @param  string $msgid
     * @return string
     */
    function _p($context, $msgid)
    {
        return app('gettext')->pGetText($context, $msgid);
    }
}

if (! function_exists('_dp')) {
    /**
     * Override the current domain in a context gettext call.
     * 
     * @param  string $domain
     * @param  string $context
     * @param  string $msgid 
     * @return string        
     */
    function _dp($domain, $context, $msgid)
    {
        return app('gettext')->dPGetText($domain, $context, $msgid);
    }
}

if (! function_exists('_dcp')) {
    /**
     * Override the domain and category for a single context-based lookup.
     * 
     * @param  string $domain   
     * @param  string $context
     * @param  string $msgid    
     * @param  int    $category 
     * @return string
     */
    function _dcp($domain, $context, $msgid, $category)
    {
        return app('gettext')->dCPGetText($domain, $context, $msgid, $category);
    }
}

if (! function_exists('_np')) {
    /**
     * Context version of ngettext.
     * 
     * @param  string $context
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @return string
     */
    function _np($context, $msgid1, $msgid2, $n)
    {
        return app('gettext')->nPGetText($context, $msgid1, $msgid2, $n);
    }
}

if (! function_exists('_dnp')) {
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
    function _dnp($domain, $context, $msgid1, $msgid2, $n)
    {
        return app('gettext')->dNPGetText($domain, $context, $msgid1, $msgid2, $n);
    }
}

if (! function_exists('_dcnp')) {
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
    function _dcnp($domain, $context, $msgid1, $msgid2, $n, $category)
    {
        return app('gettext')->dCNPGetText($domain, $context, $msgid1, $msgid2, $n, $category);
    }
}
