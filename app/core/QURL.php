<?php

class QURL extends \Illuminate\Support\Facades\URL
{

    /**
     * Get the root domain of the currently accessed hostname
     *
     * @param  string      $domain The string to be match against the domain
     *                             parsing regex
     * @return string|bool
     */
    public static function getRootDomain($domain = null)
    {
        $domain = $domain ?: $_SERVER['SERVER_NAME'];
        if (preg_match(
            '/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i',
            $domain,
            $regs)) {
            return $regs['domain'];
        }

        return false;
    }

    /**
     * Get the subdomain of a given domain
     *
     * @access public
     * @param  string $domain
     * @return string
     */
    public static function getSubdomain($domain = null)
    {
        $domain = $domain ?: $_SERVER['SERVER_NAME'];
        return rtrim(
            str_replace(static::getRootDomain($domain), '', $domain),
            '.'
        );
    }

}