<?php namespace Clusteramaryllis\Gettext;

use InvalidArgumentException;

class Gettext
{
    /**
     * bindtextdomain
     *
     * @param  string $domain
     * @param  string $directory
     * @return string
     */
    public function bindTextDomain($domain, $directory)
    {
        return bindtextdomain($domain, $directory);
    }

    /**
     * bind_text_domain_codeset
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
     * Set locale.
     *
     * @param  int   $category
     * @return mixed
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

        if (is_array($preLocales[0])) {
            $primaryLocale = $preLocales[0][0];
            $locales       = $preLocales[0];
        } else {
            $primaryLocale = $preLocales[0];
            $locales       = $preLocales;
        }

        @putenv("{$strCategory}={$primaryLocale}");
        @putenv("LANG={$primaryLocale}");
        @putenv("LANGUAGE={$primaryLocale}");

        foreach ($locales as $locale) {
            T_setlocale($category, $locale);
        }

        return $primaryLocale;
    }

    /**
     * Text domain.
     *
     * @param  string $domain
     * @return string
     */
    public function textDomain($domain = null)
    {
        return textdomain($domain);
    }

    /**
     * gettext wrapper.
     *
     * @param  string $message
     * @return string
     */
    public function getText($message)
    {
        return gettext($message);
    }

    /**
     * ngettext wrapper.
     *
     * @param  string $domain
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @return string
     */
    public function nGetText($msgid1, $msgid2, $n = 1)
    {
        return ngettext($msgid1, $msgid2, $n);
    }

    /**
     * dgettext wrapper.
     *
     * @param  string $domain
     * @param  string $message
     * @return string
     */
    public function dGetText($domain, $message)
    {
        return dgettext($domain, $message);
    }

    /**
     * dngettext wrapper.
     *
     * @param  string $domain
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @return string
     */
    public function dNGetText($domain, $msgid1, $msgid2, $n = 1)
    {
        return dngettext($domain, $msgid1, $msgid2, $n);
    }

    /**
     * dcgettext wrapper.
     *
     * @param  string $domain
     * @param  string $message
     * @param  int    $category
     * @return string
     */
    public function dCGetText($domain, $message, $category = LC_ALL)
    {
        return dcgettext($domain, $message, $category);
    }

    /**
     * dcngettext wrapper.
     *
     * @param  string $domain
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @param  int    $category
     * @return string
     */
    public function dCNGetText($domain, $msgid1, $msgid2, $n = 1, $category = LC_ALL)
    {
        return dcngettext($domain, $msgid1, $msgid2, $n, $category);
    }

    /**
     * pgettext wrapper.
     *
     * @param  string $context
     * @param  string $message
     * @return string
     */
    public function pGetText($context, $message)
    {
        return pgettext($context, $message);
    }

    /**
     * dpgettext wrapper.
     *
     * @param  string $domain
     * @param  string $context
     * @param  string $message
     * @return string
     */
    public function dPGetText($domain, $context, $message)
    {
        return dpgettext($domain, $context, $message);
    }

    /**
     * dcpgettext wrapper.
     *
     * @param  string $domain
     * @param  string $context
     * @param  string $message
     * @param  int    $category
     * @return string
     */
    public function dCPGetText($domain, $context, $message, $category = LC_ALL)
    {
        return dcpgettext($domain, $context, $message, $category);
    }

    /**
     * npgettext wrapper.
     *
     * @param  string $context
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @return string
     */
    public function nPGetText($context, $msgid1, $msgid2, $n = 1)
    {
        return npgettext($context, $msgid1, $msgid2, $n);
    }

    /**
     * dnpgettext wrapper.
     *
     * @param  string $domain
     * @param  string $context
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @return string
     */
    public function dNPGetText($domain, $context, $msgid1, $msgid2, $n = 1)
    {
        return dnpgettext($domain, $context, $msgid1, $msgid2, $n);
    }

    /**
     * dcnpgettext wrapper.
     *
     * @param  string $domain
     * @param  string $context
     * @param  string $msgid1
     * @param  string $msgid2
     * @param  int    $n
     * @param  int    $category
     * @return string
     */
    public function dCNPGetText($domain, $context, $msgid1, $msgid2, $n = 1, $category = LC_ALL)
    {
        return dcnpgettext($domain, $context, $msgid1, $msgid2, $n, $category);
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
