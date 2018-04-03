<?php
namespace NonceShield\Tests\Integration;

/**
 * HttpHeader class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class HttpHeader
{
    public static function getSessId($name, $headerLine)
    {
        $cookies = explode(';', $headerLine);
        foreach ($cookies as $cookie) {
            $item = explode('=', $cookie);
            if ($item[0] === $name) {
                return $item[1];
            }
        }
        
        return null;
    }
}
