<?php namespace Clusteramaryllis\Gettext;

use Clusteramaryllis\Gettext\Exception\DomainNotSetException;

class Gettext
{
    /**
     * List of domains.
     *
     * @var array
     */
    protected $domains = array();

    /**
     * Localse.
     * 
     * @var string
     */
    protected $locale = '';

    /**
     * Array of current domain.
     *
     * @var array
     */
    protected $currentDomain = array();

    /**
     * Set textdomain (with optional codeset).
     *
     * @param  string $domain
     * @param  string $directory
     * @param  string $codeset
     * @return $this
     */
    public function setTextDomain($domain, $directory, $codeset = 'UTF-8')
    {
        if (! array_key_exists($domain, $this->domains)) {
            $this->domains[$domain] = array(
                'domain'    => $domain,
                'directory' => $directory,
                'codeset'   => $codeset,
            );

            bindtextdomain($this->domains[$domain]['domain'], $this->domains[$domain]['directory']);
            bind_textdomain_codeset($this->domains[$domain]['domain'], $this->domains[$domain]['codeset']);
        } else {
            if ($this->domains[$domain]['directory'] !== $directory) {
                $this->domains[$domain]['directory'] = $directory;

                bindtextdomain($this->domains[$domain]['domain'], $this->domains[$domain]['directory']);
            }

            if ($this->domains[$domain]['codeset'] !== $codeset) {
                $this->domains[$domain]['codeset'] = $codeset;

                bind_textdomain_codeset($this->domains[$domain]['domain'], $this->domains[$domain]['codeset']);
            }
        }

        $this->currentDomain = $this->domains[$domain];

        return $this;
    }

    /**
     * Set category.
     *
     * @param  string $category
     * @return $this
     */
    public function setCategory($category)
    {
        if (! array_key_exists('category', $this->domains[$this->currentDomain['domain']]) ||
            $this->domains[$this->currentDomain['domain']]['category'] !== $category) {
            $this->domains[$this->currentDomain['domain']]['category'] = $category;
        }

        $this->currentDomain = $this->domains[$this->currentDomain['domain']];

        return $this;
    }

    /**
     * Set locale.
     * 
     * @param int    $category 
     * @param string $locale   
     * @param array  $locales
     */
    public function setLocale($category, $locale, $locales = array(), $codeset = 'UTF-8')
    {
        $locale      = $locale.".{$codeset}";
        $strCategory = $this->checkCategory($category);

        if (empty($locales)) {
            $locales = array($locale);
        }

        @putenv("{$strCategory}={$locale}");
        @putenv("LANG={$locale}");

        if (function_exists('T_setlocale')) {
            $this->locale = T_setlocale($category, $locale);
        } else {
            $this->locale = setlocale($category, $locales);
        }

        return $this;
    }

    /**
     * Get locale.
     * 
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Get domain.
     * 
     * @param  string $name
     * @return mixed
     */
    public function getDomain($name = null)
    {
        if (is_null($name)) {
            return $this->domains;
        }

        if (array_key_exists($name, $this->domains)) {
            return $this->domains[$name];
        }

        return;
    }

    /**
     * Get current domain.
     *
     * @return array
     */
    public function getCurrentDomain()
    {
        return $this->currentDomain;
    }

    /**
     * dgettext wrapper.
     *
     * @param  string $message
     * @return string
     */
    public function dGettext($message)
    {
        $this->checkCurrentDomain();

        return dgettext($this->currentDomain['domain'], $message);
    }

    /**
     * dngettext wrapper.
     *
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @return string
     */
    public function dNGettext($msgid1, $msgid2, $n)
    {
        $this->checkCurrentDomain();

        return dngettext($this->currentDomain['domain'], $msgid1, $msgid2, $n);
    }

    /**
     * dcgettext wrapper.
     *
     * @param  string $message
     * @param  int    $category
     * @return string
     */
    public function dCGettext($message, $category = LC_ALL)
    {
        $this->checkCurrentDomain();

        $this->setCategory($category);

        return dcgettext($this->currentDomain['domain'], $message, $this->currentDomain['category']);
    }

    /**
     * dcngettext wrapper.
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @param  int    $category
     * @return string
     */
    public function dCNGettext($msgid1, $msgid2, $n, $category = LC_ALL)
    {
        $this->checkCurrentDomain();

        $this->setCategory($category);

        return dcngettext($this->currentDomain['domain'], $msgid1, $msgid2, $n, $this->currentDomain['category']);
    }

    /**
     * Check the existence of current domain.
     *
     * @return void
     * @throws \Clusteramaryllis\Gettext\Exception\CurrentDomainNotSetException
     */
    protected function checkCurrentDomain()
    {
        if (empty($this->currentDomain)) {
            throw new DomainNotSetException("Current domain must be set by calling ".__CLASS__."::setTextDomain(\$domain, \$directory, \$codeset)");
        }
    }

    /**
     * Check category.
     * 
     * @param  int    $category
     * @return string           
     */
    protected function checkCategory($category)
    {
        switch ($category) {
            case 0:
                return "LC_TYPE";

            case 1:
                return "LC_NUMERIC";

            case 2:
                return "LC_TIME";
            
            case 3:
                return "LC_COLLATE";

            case 4:
                return "LC_MONETARY";

            case 5:
                return "LC_MESSAGES";

            default:
                return "LC_ALL";
        }
    }
}
