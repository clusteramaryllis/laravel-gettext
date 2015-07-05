<?php

namespace Clusteramaryllis\Gettext\Contracts;

interface GettextDriver
{
    /**
     * Figure out all possible locale names and start with the most
     * specific ones.  I.e. for sr_CS.UTF-8@latin, look through all of
     * sr_CS.UTF-8@latin, sr_CS@latin, sr@latin, sr_CS.UTF-8, sr_CS, sr.
     *
     * @param  string $locale
     * @return string
     */
    public function getLocalesList($locale);

    /**
     * Get a StreamReader for the given text domain.
     *
     * @param  string|null $domain
     * @param  int         $category
     * @param  bool        $cache
     * @return \gettext_reader
     */
    public function getReader($domain = null, $category = LC_MESSAGES, $cache = true);

    /**
     * Get the codeset for the given domain.
     *
     * @param  string|null $domain
     * @return string
     */
    public function getCodeset($domain = null);

    /**
     * Return passed in $locale, or environment variable if $locale == ''.
     *
     * @param  string $locale
     * @param  string $category
     * @return string
     */
    public function getDefaultLocale($locale, $category = "LC_ALL");

    /**
     * Get the current locale.
     *
     * @return string
     */
    public function getCurrentLocale();

    /**
     * Check if the current locale is supported on this system.
     *
     * @param  string|null $func
     * @return bool
     */
    public function hasLocaleAndFunction($func = null);

    /**
     * Set a requested locale, if needed emulates it.
     *
     * @param  mixed $category
     * @return string
     */
    public function setLocale($category);

    /**
     * Convert the given string to the encoding set by bind_textdomain_codeset.
     *
     * @param  string      $text
     * @param  string|null $domain
     * @return string
     */
    public function encode($text, $domain = null);

    /**
     * Set the path for a domain.
     *
     * @param  string $domain
     * @param  string $path
     * @return string
     */
    public function bindTextDomain($domain, $path);

    /**
     * Specify the character encoding in which the messages
     * from the DOMAIN message catalog will be returned.
     *
     * @param  string $domain
     * @param  string $codeset
     * @return string
     */
    public function bindTextDomainCodeset($domain, $codeset);

    /**
     * Set the default domain.
     *
     * @param  string|null $domain
     * @return string
     */
    public function textDomain($domain = null);

    /**
     * Lookup a message in the current domain.
     *
     * @param  string $msgid
     * @return string
     */
    public function getText($msgid);

    /**
     * Plural version of gettext.
     *
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @return string
     */
    public function nGetText($msgid1, $msgid2, $n);

    /**
     * Override the current domain.
     *
     * @param  string $domain
     * @param  string $msgid
     * @return string
     */
    public function dGetText($domain, $msgid);

    /**
     * Plural version of dgettext.
     *
     * @param  string $domain
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @return string
     */
    public function dNGetText($domain, $msgid1, $msgid2, $n);

    /**
     * Override the domain for a single lookup.
     *
     * @param  string $domain
     * @param  string $msgid
     * @param  int    $category
     * @return string
     */
    public function dCGetText($domain, $msgid, $category);

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
    public function dCNGetText($domain, $msgid1, $msgid2, $n, $category);

    /**
     * Context version of gettext.
     *
     * @param  string $context
     * @param  string $msgid
     * @return string
     */
    public function pGetText($context, $msgid);

    /**
     * Override the current domain in a context gettext call.
     *
     * @param  string $domain
     * @param  string $context
     * @param  string $msgid
     * @return string
     */
    public function dPGetText($domain, $context, $msgid);

    /**
     * Override the domain and category for a single context-based lookup.
     *
     * @param  string $domain
     * @param  string $context
     * @param  string $msgid
     * @param  int    $category
     * @return string
     */
    public function dCPGetText($domain, $context, $msgid, $category);

    /**
     * Context version of ngettext.
     *
     * @param  string $context
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @return string
     */
    public function nPGetText($context, $msgid1, $msgid2, $n);

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
    public function dNPGetText($domain, $context, $msgid1, $msgid2, $n);

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
    public function dCNPGetText($domain, $context, $msgid1, $msgid2, $n, $category);
}
