<?php
namespace NonceShield;

/**
 * Uri class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Uri
{
    /**
     * Gets the value of the given variable in the uri.
     *
     * @param string $uri
     * @param string $name
     * @return string
     */
    public static function getVar($uri, $name)
    {
        $urlQuery = parse_url($uri, PHP_URL_QUERY);
        $vars = explode('&', $urlQuery);
        foreach ($vars as $var) {
            $item = explode('=', $var);
            if ($item[0] === $name) {
                return $item[1];
            }
        }

        return null;
    }

    /**
     * Gets the uri without the given variable in the query string.
     *
     * @param string $uri
     * @param string $name
     * @return string
     */
    public static function withoutVar($uri, $name)
    {
        $queryStringWithoutToken = [];
        $urlQuery = parse_url($uri, PHP_URL_QUERY);
        $vars = explode('&', $urlQuery);
        foreach ($vars as $var) {
            $item = explode('=', $var);
            if ($item[0] !== $name) {
                $queryStringWithoutToken[$item[0]] = $item[1];
            }
        }
        empty($queryStringWithoutToken) ? $baseUri = strtok($uri, '?') : $baseUri = strtok($uri, '?') . '?';

        return $baseUri . http_build_query($queryStringWithoutToken);
    }
}
