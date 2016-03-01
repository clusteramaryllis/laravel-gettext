<?php

namespace Clusteramaryllis\Gettext;

use Clusteramaryllis\Gettext\Contracts\GettextDriver;

class Gettext
{
    /**
     * GettextApi contracts.
     *
     * @var \Clusteramaryllis\Gettext\Contracts\GettextDriver
     */
    protected $driver;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(GettextDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Get driver object.
     *
     * @return \Clusteramaryllis\Gettext\Contracts\GettextDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set a requested locale, if needed emulates it.
     *
     * @param  mixed $category
     * @return string
     */
    public function setLocale($category)
    {
        return call_user_func_array([$this->driver, 'setLocale'], func_get_args());
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
        if ($this->driver->hasLocaleAndFunction('bindtextdomain')) {
            return bindtextdomain($domain, $path);
        }
        
        return $this->driver->bindTextDomain($domain, $path);
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
        if ($this->driver->hasLocaleAndFunction('bind_textdomain_codeset')) {
            return bind_textdomain_codeset($domain, $codeset);
        }

        return $this->driver->bindTextDomainCodeset($domain, $codeset);
    }

    /**
     * Set the default domain.
     *
     * @param  string|null $domain
     * @return string
     */
    public function textDomain($domain = null)
    {
        if ($this->driver->hasLocaleAndFunction('textdomain')) {
            return textdomain($domain);
        }

        return $this->driver->textDomain($domain);
    }

    /**
     * Lookup a message in the current domain.
     *
     * @param  string $msgid
     * @return string
     */
    public function getText($msgid)
    {
        if ($this->driver->hasLocaleAndFunction('gettext')) {
            return gettext($msgid);
        }

        return $this->driver->getText($msgid);
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
        if ($this->driver->hasLocaleAndFunction('ngettext')) {
            return ngettext($msgid1, $msgid2, $n);
        }

        return $this->driver->nGetText($msgid1, $msgid2, $n);
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
        if ($this->driver->hasLocaleAndFunction('dgettext')) {
            return dgettext($domain, $msgid);
        }

        return $this->driver->dGetText($domain, $msgid);
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
        if ($this->driver->hasLocaleAndFunction('dngettext')) {
            return dngettext($domain, $msgid1, $msgid2, $n);
        }

        return $this->driver->dNGetText($domain, $msgid1, $msgid2, $n);
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
        if ($this->driver->hasLocaleAndFunction('dcgettext')) {
            return dcgettext($domain, $msgid, $category);
        }

        return $this->driver->dCGetText($domain, $msgid, $category);
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
        if ($this->driver->hasLocaleAndFunction('dcngettext')) {
            return dcngettext($domain, $msgid1, $msgid2, $n, $category);
        }

        return $this->driver->dCNGetText($domain, $msgid1, $msgid2, $n, $category);
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
        if ($this->driver->hasLocaleAndFunction('gettext')) {
            $context_id = "{$context}\004{$msgid}";
            $translation = gettext($context_id);

            if ($translation == $context_id) {
                return $msgid;
            }

            return $translation;
        }

        return $this->driver->pGetText($context, $msgid);
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
        if ($this->driver->hasLocaleAndFunction('dgettext')) {
            $context_id = "{$context}\004{$msgid}";
            $translation = dgettext($domain, $context_id);

            if ($translation == $context_id) {
                return $msgid;
            }

            return $translation;
        }

        return $this->driver->dPGetText($domain, $context, $msgid);
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
        if ($this->driver->hasLocaleAndFunction('dcgettext')) {
            $context_id = "{$context}\004{$msgid}";
            $translation = dcgettext($domain, $context_id, $category);

            if ($translation == $context_id) {
                return $msgid;
            }

            return $translation;
        }

        return $this->driver->dCPGetText($domain, $context, $msgid, $category);
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
        if ($this->driver->hasLocaleAndFunction('ngettext')) {
            $context_id = "{$context}\004{$msgid1}";
            $translation = ngettext($context_id, $msgid2, $n);

            if ($translation == $context_id || $translation == $msgid2) {
                return $n == 1 ? $msgid1 : $msgid2;
            }

            return $translation;
        }

        return $this->driver->nPGetText($context, $msgid1, $msgid2, $n);
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
        if ($this->driver->hasLocaleAndFunction('dngettext')) {
            $context_id = "{$context}\004{$msgid1}";
            $translation = dngettext($domain, $context_id, $msgid2, $n);

            if ($translation == $context_id || $translation == $msgid2) {
                return $n == 1 ? $msgid1 : $msgid2;
            }

            return $translation;
        }

        return $this->driver->dNPGetText($domain, $context, $msgid1, $msgid2, $n);
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
        if ($this->driver->hasLocaleAndFunction('dcngettext')) {
            $context_id = "{$context}\004{$msgid1}";
            $translation = dcngettext($domain, $context_id, $msgid2, $n, $category);

            if ($translation == $context_id || $translation == $msgid2) {
                return $n == 1 ? $msgid1 : $msgid2;
            }

            return $translation;
        }

        return $this->driver->dCNPGetText($domain, $context, $msgid1, $msgid2, $n, $category);
    }
}
