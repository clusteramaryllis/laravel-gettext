<?php 

namespace Clusteramaryllis\Gettext;

use Clusteramaryllis\Gettext\Driver\GettextApi;

class Gettext
{
    /**
     * GettextApi contracts.
     * 
     * @var \Clusteramaryllis\Gettext\Contracts\GettextApi
     */
    protected $api;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(GettextApi $api)
    {
        $this->api = $api;

        // initiate to default
        $this->setLocale(LC_MESSAGES, 0);
    }

    /**
     * Set a requested locale, if needed emulates it.
     * 
     * @param  mixed $category
     * @return string
     */
    public function setLocale($category)
    {
        return call_user_func_array([$this->api, 'setLocale'], func_get_args());
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
        if ($this->api->hasLocaleAndFunction('bindtextdomain')) {
            return bindtextdomain($domain, $path);
        }
        
        return $this->api->bindTextDomain($domain, $path);
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
        if ($this->api->hasLocaleAndFunction('bind_textdomain_codeset')) {
            return bind_textdomain_codeset($domain, $codeset);
        }

        return $this->api->bindTextDomainCodeset($domain, $codeset);
    }

    /**
     * Set the default domain.
     * 
     * @param  string|null $domain
     * @return string         
     */
    public function textDomain($domain = null)
    {
        if ($this->api->hasLocaleAndFunction('textdomain')) {
            return textdomain($domain);
        }

        return $this->api->textDomain($domain);
    }

    /**
     * Lookup a message in the current domain.
     * 
     * @param  string $msgid
     * @return string        
     */
    public function getText($msgid)
    {
        if ($this->api->hasLocaleAndFunction('gettext')) {
            return gettext($msgid);
        }

        return $this->api->getText($msgid);
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
        if ($this->api->hasLocaleAndFunction('ngettext')) {
            return ngettext($msgid1, $msgid2, $n);
        }

        return $this->api->nGetText($msgid1, $msgid2, $n);
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
        if ($this->api->hasLocaleAndFunction('dgettext')) {
            return dgettext($domain, $msgid);
        }

        return $this->api->dGetText($domain, $msgid);
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
        if ($this->api->hasLocaleAndFunction('dngettext')) {
            return dngettext($domain, $msgid1, $msgid2, $n);
        }

        return $this->api->dNGetText($domain, $msgid1, $msgid2, $n);
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
        if ($this->api->hasLocaleAndFunction('dcgettext')) {
            return dcgettext($domain, $msgid, $category);
        }

        return $this->api->dCGetText($domain, $msgid, $category);
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
        if ($this->api->hasLocaleAndFunction('dcngettext')) {
            return dcngettext($domain, $msgid1, $msgid2, $n, $category);
        }

        return $this->api->dCNGetText($domain, $msgid1, $msgid2, $n, $category);
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
        if ($this->api->hasLocaleAndFunction('gettext')) {
            $context_id = "{$context}\004{$msgid}";
            $translation = gettext($context_id);

            if ($translation == $context_id) {
                return $msgid;
            }

            return $translation;
        }

        return $this->api->pGetText($context, $msgid);
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
        if ($this->api->hasLocaleAndFunction('dgettext')) {
            $context_id = "{$context}\004{$msgid}";
            $translation = dgettext($domain, $context_id);

            if ($translation == $context_id) {
                return $msgid;
            }

            return $translation;
        }

        return $this->api->dPGetText($domain, $context, $msgid);
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
        if ($this->api->hasLocaleAndFunction('dcgettext')) {
            $context_id = "{$context}\004{$msgid}";
            $translation = dcgettext($domain, $context_id, $category);

            if ($translation == $context_id) {
                return $msgid;
            }

            return $translation;
        }

        return $this->api->dCPGetText($domain, $context, $msgid, $category);
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
        if ($this->api->hasLocaleAndFunction('ngettext')) {
            $context_id = "{$context}\004{$msgid1}";
            $translation = ngettext($context_id, $msgid2, $n);

            if ($translation == $context_id || $translation == $msgid2) {
                return $n == 1 ? $msgid1 : $msgid2;
            }

            return $translation;
        }

        return $this->api->nPGetText($context, $msgid1, $msgid2, $n);
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
        if ($this->api->hasLocaleAndFunction('dngettext')) {
            $context_id = "{$context}\004{$msgid1}";
            $translation = dngettext($domain, $context_id, $msgid2, $n);

            if ($translation == $context_id || $translation == $msgid2) {
                return $n == 1 ? $msgid1 : $msgid2;
            }

            return $translation;
        }

        return $this->api->dNPGetText($domain, $context, $msgid1, $msgid2, $n);
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
        if ($this->api->hasLocaleAndFunction('dcngettext')) {
            $context_id = "{$context}\004{$msgid1}";
            $translation = dcngettext($domain, $context_id, $msgid2, $n, $category);

            if ($translation == $context_id || $translation == $msgid2) {
                return $n == 1 ? $msgid1 : $msgid2;
            }

            return $translation;
        }

        return $this->api->dCNPGetText($domain, $context, $msgid1, $msgid2, $n, $category);
    }
}
