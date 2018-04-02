<?php
namespace NonceShield\Tests\Unit;

use NonceShield\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    /**
     * @test
     */
    public function get_var_example_01()
    {
        $uri = 'http://localhost:8001/foo.php?action=someaction&_nonce_shield_token=bar';
        $token = Uri::getVar($uri, '_nonce_shield_token');

        $this->assertEquals($token, 'bar');
    }

    /**
     * @test
     */
    public function get_var_example_02()
    {
        $uri = 'http://localhost:8001/foo.php?_nonce_shield_token=foo&action=someaction';
        $token = Uri::getVar($uri, '_nonce_shield_token');

        $this->assertEquals($token, 'foo');
    }

    /**
     * @test
     */
    public function without_var_example_01()
    {
        $requestUri = 'http://localhost:8001/foo.php?action=someaction&_nonce_shield_token=example_01';
        $uriWithoutToken = Uri::withoutVar($requestUri, '_nonce_shield_token');

        $this->assertEquals($uriWithoutToken, 'http://localhost:8001/foo.php?action=someaction');
    }

    /**
     * @test
     */
    public function without_var_example_02()
    {
        $requestUri = 'http://localhost:8001/foo.php?_nonce_shield_token=example_01&action=someaction';
        $uriWithoutToken = Uri::withoutVar($requestUri, '_nonce_shield_token');

        $this->assertEquals($uriWithoutToken, 'http://localhost:8001/foo.php?action=someaction');
    }
}
