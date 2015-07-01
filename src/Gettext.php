<?php 

namespace Clusteramaryllis\Gettext;

class Gettext
{
    /**
     * Set a requested locale, if needed emulates it.
     * 
     * @param  mixed $category
     * @return string
     */
    public function setLocale($category)
    {
        return call_user_func_array('set_locale', func_get_args());
    }

    /**
     * Set the path for a domain.
     * 
     * @param  string $domain
     * @param  string $path
     * @return string
     */
    public function bindTextDomain($domain, $path)
    {
        return bindtextdomain($domain, $path);
    }

    /**
     * Specify the character encoding in which the messages 
     * from the DOMAIN message catalog will be returned.
     * 
     * @param  string $domain  
     * @param  string $codeset 
     * @return string          
     */
    public function bindTextDomainCodeset($domain, $codeset)
    {
        return bind_textdomain_codeset($domain, $codeset);
    }

    /**
     * Set the default domain.
     * 
     * @param  string|null $domain
     * @return string         
     */
    public function textDomain($domain = null)
    {
        return textdomain($domain);
    }

    /**
     * Lookup a message in the current domain.
     * 
     * @param  string $msgid
     * @return string        
     */
    public function getText($msgid)
    {
        return gettext($msgid);
    }

    /**
     * Plural version of gettext.
     * 
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @return string
     */
    public function nGetText($msgid1, $msgid2, $n)
    {
        return ngettext($msgid1, $msgid2, $n);
    }

    /**
     * Override the current domain.
     * 
     * @param  string $domain
     * @param  string $msgid 
     * @return string        
     */
    public function dGetText($domain, $msgid)
    {
        return dgettext($domain, $msgid);
    }

    /**
     * Plural version of dgettext.
     * 
     * @param  string $domain
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @return string
     */
    public function dNGetText($domain, $msgid1, $msgid2, $n)
    {
        return dngettext($domain, $msgid1, $msgid2, $n);
    }

    /**
     * Override the domain for a single lookup.
     * 
     * @param  string $domain   
     * @param  string $msgid    
     * @param  int    $category 
     * @return string
     */
    public function dCGetText($domain, $msgid, $category)
    {
        return dcgettext($domain, $msgid, $category);
    }

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
    public function dCNGetText($domain, $msgid1, $msgid2, $n, $category)
    {
        return dcngettext($domain, $msgid1, $msgid2, $n, $category);
    }

    /**
     * Context version of gettext.
     * 
     * @param  string $context
     * @param  string $msgid
     * @return string
     */
    public function pGetText($context, $msgid)
    {
        return pgettext($context, $msgid);
    }

    /**
     * Override the current domain in a context gettext call.
     * 
     * @param  string $domain
     * @param  string $context
     * @param  string $msgid 
     * @return string        
     */
    public function dPGetText($domain, $context, $msgid)
    {
        return dpgettext($domain, $context, $msgid);
    }

    /**
     * Override the domain and category for a single context-based lookup.
     * 
     * @param  string $domain   
     * @param  string $context
     * @param  string $msgid    
     * @param  int    $category 
     * @return string
     */
    public function dCPGetText($domain, $context, $msgid, $category)
    {
        return dcpgettext($domain, $context, $msgid, $category);
    }

    /**
     * Context version of ngettext.
     * 
     * @param  string $context
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @return string
     */
    public function nPGetText($context, $msgid1, $msgid2, $n)
    {
        return npgettext($context, $msgid1, $msgid2, $n);
    }

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
    public function dNPGetText($domain, $context, $msgid1, $msgid2, $n)
    {
        return dnpgettext($domain, $context, $msgid1, $msgid2, $n);
    }

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
    public function dCNPGetText($domain, $context, $msgid1, $msgid2, $n, $category)
    {
        return dcnpgettext($domain, $context, $msgid1, $msgid2, $n, $category);
    }
}
