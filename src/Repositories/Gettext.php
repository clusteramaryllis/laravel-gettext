<?php 

namespace Clusteramaryllis\Gettext\Repositories;

use FileReader;
use gettext_reader;
use InvalidArgumentException;

class Gettext
{
    /**
     * Emulate non-native php-gettext or not.
     * 
     * @var boolean
     */
    protected $emulateGettext = false;

    /**
     * Text domains.
     * 
     * @var array
     */
    protected $textDomains = [];

    /**
     * Default domain.
     * 
     * @var string
     */
    protected $defaultDomain = 'messages';

    /**
     * Current locale.
     * 
     * @var string
     */
    protected $currentLocale = '';

    /**
     * LC categories.
     * 
     * @var array
     */
    protected $lcCategories = [
        'LC_CTYPE',
        'LC_NUMERIC',
        'LC_TIME',
        'LC_COLLATE',
        'LC_MONETARY',
        'LC_MESSAGES',
        'LC_ALL',
    ];

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        defined('LC_MESSAGES') || define('LC_MESSAGES', 5);
    }

    /**
     * Figure out all possible locale names and start with the most
     * specific ones.  I.e. for sr_CS.UTF-8@latin, look through all of
     * sr_CS.UTF-8@latin, sr_CS@latin, sr@latin, sr_CS.UTF-8, sr_CS, sr.
     * 
     * @param  string $locale
     * @return string
     */
    public function getLocalesList($locale)
    {
        $localeNames = [];
        $pattern = "/^(?P<lang>[a-z]{2,3})"
           ."(?:_(?P<country>[A-Z]{2}))?"
           ."(?:\.(?P<charset>[-A-Za-z0-9_]+))?"
           ."(?:@(?P<modifier>[-A-Za-z0-9_]+))?$/";

        if (is_string($locale) && $locale !== '') {
            if (preg_match($pattern, $locale, $matches)) {
                if (array_key_exists("modifier", $matches)) {
                    if (array_key_exists("country", $matches)) {
                        if (array_key_exists("charset", $matches)) {
                            $localeNames[] = $matches["lang"]."_".$matches["country"].".".$matches["charset"]."@".$matches["modifier"];
                        }

                        $localeNames[] = $matches["lang"]."_".$matches["country"]."@".$matches["modifier"];
                    }

                    if (array_key_exists("charset", $matches)) {
                        $localeNames[] = $matches["lang"].".".$matches["charset"]."@".$matches["modifier"];
                    }

                    $localeNames[] = $matches["lang"]."@".$matches["modifier"];
                }

                if (array_key_exists("country", $matches)) {
                    if (array_key_exists("charset", $matches)) {
                        $localeNames[] = $matches["lang"]."_".$matches["country"].".".$matches["charset"];
                    }

                    $localeNames[] = $matches["lang"]."_".$matches["country"];
                }

                if (array_key_exists("charset", $matches)) {
                    $localeNames[] = $matches["lang"].".".$matches["charset"];
                }

                $localeNames[] = $matches["lang"];
            }

            if (! in_array($locale, $localeNames)) {
                $localeNames[] = $locale;
            }
        }

        return $localeNames;
    }

    /**
     * Get a StreamReader for the given text domain.
     * 
     * @param  string|null $domain
     * @param  int         $category
     * @param  bool        $cache
     * @return \gettext_reader
     */
    public function getReader($domain = null, $category = LC_MESSAGES, $cache = true)
    {
        if (! is_string($domain) || $domain === '') {
            $domain = $this->defaultDomain;
        }

        if (! array_key_exists($domain, $this->textDomains)) {
            $this->textDomains[$domain] = [];
        }

        if (! array_key_exists('l10n', $this->textDomains[$domain])) {
            $locale = $this->setLocale(LC_MESSAGES, 0);
            $boundPath = array_key_exists('path', $this->textDomains[$domain]) ?
                $this->textDomains[$domain]['path'] : './';
            $subPath = $this->lcCategories[$category]."/{$domain}.mo";
            $localeNames = $this->getLocalesList($locale);
            $input = null;

            foreach ($localeNames as $locale) {
                $fullPath = $boundPath.$locale."/".$subPath;

                if (file_exists($fullPath)) {
                    $input = new FileReader($fullPath);

                    break;
                }
            }

            $this->textDomains[$domain]['l10n'] = new gettext_reader($input, $cache);
        }

        return $this->textDomains[$domain]['l10n'];
    }

    /**
     * Return whether we are using our emulated gettext API or PHP built-in one.
     * 
     * @return bool
     */
    public function getEmulateGettext()
    {
        return $this->emulateGettext;
    }

    /**
     * Get the codeset for the given domain.
     * 
     * @param  string|null $domain
     * @return string
     */
    public function getCodeset($domain = null)
    {
        if (! is_string($domain) || $domain === '') {
            $domain = $this->defaultDomain;
        }

        if (array_key_exists('codeset', $this->textDomains[$domain])) {
            return $this->textDomains[$domain]['codeset'];
        }

        if (@ini_get('mbstring.internal_encoding')) {
            return @ini_get('mbstring.internal_encoding');
        }

        return 'UTF-8';
    }

    /**
     * Return passed in $locale, or environment variable if $locale == ''.
     * 
     * @param  string $locale
     * @param  string $category
     * @return string
     */
    public function getDefaultLocale($locale, $category = "LC_ALL")
    {
        if (! is_string($locale) || $locale === '') {
            if (getenv('LANG')) {
                return getenv('LANG');
            }

            if (getenv('LANGUAGE')) {
                return getenv('LANGUAGE');
            }

            if (getenv($category)) {
                return getenv($category);
            }
        }

        return $locale;
    }

    /**
     * Check if the current locale is supported on this system.
     * 
     * @param  callable|null $func
     * @return bool
     */
    public function hasLocaleAndFunction($func = null)
    {
        if ($func && function_exists($func)) {
            return false;
        }

        return ! $this->emulateGettext;
    }

    /**
     * Set a requested locale, if needed emulates it.
     * 
     * @param  mixed $category
     * @return string
     */
    public function setLocale($category)
    {
        $argsCount = func_num_args();

        if ($argsCount < 2) {
            throw new InvalidArgumentException(
                __CLASS__."::setLocale() expects at least 2 parameters, {$argsCount} given"
            );
        }

        $strCategory = $this->checkCategory($category);
        $preLocales  = func_get_args();

        array_shift($preLocales);

        if (! is_array($preLocales[0]) && $preLocales[0] === 0) {
            if ($this->currentLocale === '') {
                return $this->setLocale($category, $this->currentLocale);
            }

            return $this->currentLocale;
        }

        if (is_array($preLocales[0])) {
            $locales = $preLocales[0];
        } else {
            $locales = $preLocales;
        }

        $result = setlocale($category, $locales);

        if (! $result) {
            @putenv("{$strCategory}={$locales[0]}");
            @putenv("LANG={$locales[0]}");
            @putenv("LANGUAGE={$locales[0]}");

            $this->currentLocale = $this->getDefaultLocale($result, $category);
            $this->emulateGettext = true;
        } else {
            $this->currentLocale = $result;
            $this->emulateGettext = false;
        }

        if (array_key_exists($this->defaultDomain, $this->textDomains)) {
            unset($this->textDomains[$this->defaultDomain]['l10n']);
        }

        return $this->currentLocale;
    }

    /**
     * Convert the given string to the encoding set by bind_textdomain_codeset.
     * 
     * @param  string      $text
     * @param  string|null $domain
     * @return string
     */
    public function encode($text, $domain = null)
    {
        $targetEncoding = $this->getCodeset($domain);

        if (function_exists('mb_detect_encoding')) {
            $sourceEncoding = mb_detect_encoding($text);

            if ($sourceEncoding !== $targetEncoding) {
                $text = mb_convert_encoding($text, $targetEncoding, $sourceEncoding);
            }
        }

        return $text;
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
        $path = rtrim($path, "/\\")."/";

        if (! array_key_exists($domain, $this->textDomains)) {
            $this->textDomains[$domain] = [];
        }

        return $this->textDomains[$domain]['path'] = $path;
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
        if (! array_key_exists($domain, $this->textDomains)) {
            $this->textDomains[$domain] = [];
        }

        return $this->textDomains[$domain]['codeset'] = $codeset;
    }

    /**
     * Set the default domain.
     * 
     * @param  string|null $domain
     * @return string         
     */
    public function textDomain($domain = null)
    {
        if (is_string($domain) && $domain !== '') {
            return $this->defaultDomain = $domain;
        }

        return $this->defaultDomain;
    }

    /**
     * Lookup a message in the current domain.
     * 
     * @param  string $msgid
     * @return string        
     */
    public function getText($msgid)
    {
        $l10n = $this->getReader();

        return $this->encode($l10n->translate($msgid));
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
        $l10n = $this->getReader();

        return $this->encode($l10n->ngettext($msgid1, $msgid2, $n));
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
        $l10n = $this->getReader($domain);

        return $this->encode($l10n->translate($msgid), $domain);
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
        $l10n = $this->getReader($domain);

        return $this->encode($l10n->ngettext($msgid1, $msgid2, $n), $domain);
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
        $l10n = $this->getReader($domain, $category);

        return $this->encode($l10n->translate($msgid), $domain);
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
        $l10n = $this->getReader($domain, $category);

        return $this->encode($l10n->ngettext($msgid1, $msgid2, $n), $domain);
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
        $l10n = $this->getReader();

        return $this->encode($l10n->pgettext($context, $msgid));
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
        $l10n = $this->getReader($domain);

        return $this->encode($l10n->pgettext($context, $msgid), $domain);
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
        $l10n = $this->getReader($domain, $category);

        return $this->encode($l10n->pgettext($context, $msgid), $domain);
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
        $l10n = $this->getReader();

        return $this->encode($l10n->npgettext($context, $msgid1, $msgid2, $n));
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
        $l10n = $this->getReader($domain);

        return $this->encode($l10n->npgettext($context, $msgid1, $msgid2, $n), $domain);
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
        $l10n = $this->getReader($domain, $category);

        return $this->encode($l10n->npgettext($context, $msgid1, $msgid2, $n), $domain);
    }

    /**
     * Determine the correct category.
     * 
     * @param  int $category
     * @return string
     */
    protected function checkCategory($category)
    {
        if (in_array($category, array_keys($this->lcCategories))) {
            return $this->lcCategories[$category];
        }

        return 'LC_ALL';
    }
}
