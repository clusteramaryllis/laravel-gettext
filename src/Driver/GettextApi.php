<?php 

namespace Clusteramaryllis\Gettext\Driver;

use FileReader;
use gettext_reader;
use InvalidArgumentException;

class GettextApi
{
    /**
     * Emulate non-native php-gettext or not.
     * 
     * @var boolean
     */
    protected static $emulateGettext = false;

    /**
     * Text domains.
     * 
     * @var array
     */
    protected static $textDomains = [];

    /**
     * Default domain.
     * 
     * @var string
     */
    protected static $defaultDomain = 'messages';

    /**
     * Current locale.
     * 
     * @var string
     */
    protected static $currentLocale = '';

    /**
     * LC categories.
     * 
     * @var array
     */
    protected static $lcCategories = [
        'LC_CTYPE',
        'LC_NUMERIC',
        'LC_TIME',
        'LC_COLLATE',
        'LC_MONETARY',
        'LC_MESSAGES',
        'LC_ALL',
    ];

    /**
     * Figure out all possible locale names and start with the most
     * specific ones.  I.e. for sr_CS.UTF-8@latin, look through all of
     * sr_CS.UTF-8@latin, sr_CS@latin, sr@latin, sr_CS.UTF-8, sr_CS, sr.
     * 
     * @param  string $locale
     * @return string
     */
    public static function getLocalesList($locale)
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
    public static function getReader($domain = null, $category = LC_MESSAGES, $cache = true)
    {
        if (! is_string($domain) || $domain === '') {
            $domain = static::$defaultDomain;
        }

        if (! array_key_exists($domain, static::$textDomains)) {
            static::$textDomains[$domain] = [];
        }

        if (! array_key_exists('l10n', static::$textDomains[$domain])) {
            $locale = static::setLocale(LC_MESSAGES, 0);
            $boundPath = array_key_exists('path', static::$textDomains[$domain]) ?
                static::$textDomains[$domain]['path'] : './';
            $subPath = static::$lcCategories[$category]."/{$domain}.mo";
            $localeNames = static::getLocalesList($locale);
            $input = null;

            foreach ($localeNames as $locale) {
                $fullPath = $boundPath.$locale."/".$subPath;

                if (file_exists($fullPath)) {
                    $input = new FileReader($fullPath);

                    break;
                }
            }

            static::$textDomains[$domain]['l10n'] = new gettext_reader($input, $cache);
        }

        return static::$textDomains[$domain]['l10n'];
    }

    /**
     * Return whether we are using our emulated gettext API or PHP built-in one.
     * 
     * @return bool
     */
    public static function getEmulateGettext()
    {
        return static::$emulateGettext;
    }

    /**
     * Get the codeset for the given domain.
     * 
     * @param  string|null $domain
     * @return string
     */
    public static function getCodeset($domain = null)
    {
        if (! is_string($domain) || $domain === '') {
            $domain = static::$defaultDomain;
        }

        if (array_key_exists('codeset', static::$textDomains[$domain])) {
            return static::$textDomains[$domain]['codeset'];
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
    public static function getDefaultLocale($locale, $category = "LC_ALL")
    {
        if (! is_string($locale) || $locale === '') {
            if (@getenv('LANG')) {
                return @getenv('LANG');
            }

            if (@getenv('LANGUAGE')) {
                return @getenv('LANGUAGE');
            }

            if (@getenv($category)) {
                return @getenv($category);
            }
        }

        return $locale;
    }

    /**
     * Check if the current locale is supported on this system.
     * 
     * @param  string|null $func
     * @return bool
     */
    public static function hasLocaleAndFunction($func = null)
    {
        if ($func && function_exists($func)) {
            return false;
        }

        return ! static::$emulateGettext;
    }

    /**
     * Set a requested locale, if needed emulates it.
     * 
     * @param  mixed $category
     * @return string
     */
    public static function setLocale($category)
    {
        $argsCount = func_num_args();

        if ($argsCount < 2) {
            throw new InvalidArgumentException(
                __CLASS__."::setLocale() expects at least 2 parameters, {$argsCount} given"
            );
        }

        $strCategory = static::checkCategory($category);
        $preLocales  = func_get_args();

        array_shift($preLocales);

        if (! is_array($preLocales[0]) && $preLocales[0] === 0) {
            if (static::$currentLocale === '') {
                return static::setLocale($category, static::$currentLocale);
            }

            return static::$currentLocale;
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

            static::$currentLocale = static::getDefaultLocale($result, $category);
            static::$emulateGettext = true;
        } else {
            static::$currentLocale = $result;
            static::$emulateGettext = false;
        }

        if (array_key_exists(static::$defaultDomain, static::$textDomains)) {
            unset(static::$textDomains[static::$defaultDomain]['l10n']);
        }

        return static::$currentLocale;
    }

    /**
     * Convert the given string to the encoding set by bind_textdomain_codeset.
     * 
     * @param  string      $text
     * @param  string|null $domain
     * @return string
     */
    public static function encode($text, $domain = null)
    {
        $targetEncoding = static::getCodeset($domain);

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
    public static function bindTextDomain($domain, $path)
    {
        $path = rtrim($path, "/\\")."/";

        if (! array_key_exists($domain, static::$textDomains)) {
            static::$textDomains[$domain] = [];
        }

        return static::$textDomains[$domain]['path'] = $path;
    }

    /**
     * Specify the character encoding in which the messages 
     * from the DOMAIN message catalog will be returned.
     * 
     * @param  string $domain  
     * @param  string $codeset 
     * @return string          
     */
    public static function bindTextDomainCodeset($domain, $codeset)
    {
        if (! array_key_exists($domain, static::$textDomains)) {
            static::$textDomains[$domain] = [];
        }

        return static::$textDomains[$domain]['codeset'] = $codeset;
    }

    /**
     * Set the default domain.
     * 
     * @param  string|null $domain
     * @return string         
     */
    public static function textDomain($domain = null)
    {
        if (is_string($domain) && $domain !== '') {
            return static::$defaultDomain = $domain;
        }

        return static::$defaultDomain;
    }

    /**
     * Lookup a message in the current domain.
     * 
     * @param  string $msgid
     * @return string        
     */
    public static function getText($msgid)
    {
        $l10n = static::getReader();

        return static::encode($l10n->translate($msgid));
    }

    /**
     * Plural version of gettext.
     * 
     * @param  string $msgid1 
     * @param  string $msgid2 
     * @param  int    $n      
     * @return string
     */
    public static function nGetText($msgid1, $msgid2, $n)
    {
        $l10n = static::getReader();

        return static::encode($l10n->ngettext($msgid1, $msgid2, $n));
    }

    /**
     * Override the current domain.
     * 
     * @param  string $domain
     * @param  string $msgid 
     * @return string        
     */
    public static function dGetText($domain, $msgid)
    {
        $l10n = static::getReader($domain);

        return static::encode($l10n->translate($msgid), $domain);
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
    public static function dNGetText($domain, $msgid1, $msgid2, $n)
    {
        $l10n = static::getReader($domain);

        return static::encode($l10n->ngettext($msgid1, $msgid2, $n), $domain);
    }

    /**
     * Override the domain for a single lookup.
     * 
     * @param  string $domain   
     * @param  string $msgid    
     * @param  int    $category 
     * @return string
     */
    public static function dCGetText($domain, $msgid, $category)
    {
        $l10n = static::getReader($domain, $category);

        return static::encode($l10n->translate($msgid), $domain);
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
    public static function dCNGetText($domain, $msgid1, $msgid2, $n, $category)
    {
        $l10n = static::getReader($domain, $category);

        return static::encode($l10n->ngettext($msgid1, $msgid2, $n), $domain);
    }

    /**
     * Context version of gettext.
     * 
     * @param  string $context
     * @param  string $msgid
     * @return string
     */
    public static function pGetText($context, $msgid)
    {
        $l10n = static::getReader();

        return static::encode($l10n->pgettext($context, $msgid));
    }

    /**
     * Override the current domain in a context gettext call.
     * 
     * @param  string $domain
     * @param  string $context
     * @param  string $msgid 
     * @return string        
     */
    public static function dPGetText($domain, $context, $msgid)
    {
        $l10n = static::getReader($domain);

        return static::encode($l10n->pgettext($context, $msgid), $domain);
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
    public static function dCPGetText($domain, $context, $msgid, $category)
    {
        $l10n = static::getReader($domain, $category);

        return static::encode($l10n->pgettext($context, $msgid), $domain);
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
    public static function nPGetText($context, $msgid1, $msgid2, $n)
    {
        $l10n = static::getReader();

        return static::encode($l10n->npgettext($context, $msgid1, $msgid2, $n));
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
    public static function dNPGetText($domain, $context, $msgid1, $msgid2, $n)
    {
        $l10n = static::getReader($domain);

        return static::encode($l10n->npgettext($context, $msgid1, $msgid2, $n), $domain);
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
    public static function dCNPGetText($domain, $context, $msgid1, $msgid2, $n, $category)
    {
        $l10n = static::getReader($domain, $category);

        return static::encode($l10n->npgettext($context, $msgid1, $msgid2, $n), $domain);
    }

    /**
     * Determine the correct category.
     * 
     * @param  int $category
     * @return string
     */
    protected static function checkCategory($category)
    {
        if (in_array($category, array_keys(static::$lcCategories))) {
            return static::$lcCategories[$category];
        }

        return 'LC_ALL';
    }
}
