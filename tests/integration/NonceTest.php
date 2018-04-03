<?php
namespace NonceShield\Tests\Integration;

use NonceShield\Nonce;
use NonceShield\Tests\Integration\HttpHeader;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

include 'HttpHeader.php';
include 'HtmlScraper.php';

class NonceTest extends TestCase
{
    const BASE_URI = 'http://localhost:8005/';

    const TIME_DELAY = 1;

    /**
     * A Guzzle wrapper that deals with the HTTP communication.
     *
     * @var array
     */
    private $http;

    /**
     * The response from the HTTP request.
     *
     * @var array
     */
    private $response;

    public function setUp()
    {
        sleep(self::TIME_DELAY);

        $this->http = new Client([
            'base_uri' => self::BASE_URI,
            'cookies' => true,
            'exceptions' => false
        ]);
    }

    public function tearDown() {
        $this->http = null;
    }

    /**
     * @test
     */
    public function auto_processing_form_GET_200()
    {
        $this->response = $this->http->request('GET', 'auto-processing-form.php');
        $token = HtmlScraper::token($this->response->getBody()->getContents());

        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTrue(is_string($token));
        $this->assertEquals(60, strlen($token));
    }

    /**
     * @test
     */
    public function auto_processing_form_POST_200()
    {
        $this->response = $this->http->request('GET', 'auto-processing-form.php');
        $token = HtmlScraper::token($this->response->getBody()->getContents());

        $this->response = $this->http->request(
            'POST',
            'auto-processing-form.php', [
                'form_params' =>  [
                    '_nonce_shield_token' => $token
                ]
            ]
        );

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function auto_processing_form_POST_403()
    {
        $this->response = $this->http->request(
            'POST',
            'auto-processing-form.php', [
                'form_params' =>  [
                    '_nonce_shield_token' => 'foo'
                ]
            ]
        );

        $this->assertEquals(403, $this->response->getStatusCode());
        $this->assertEquals(
            '{"message":"Forbidden."}',
            $this->response->getBody()->getContents()
        );
    }

    /**
     * @test
     */
    public function validate_token_GET_200()
    {
        $this->response = $this->http->request('GET', 'start-session.php');

        $sessId = HttpHeader::getSessId(
            'PHPSESSID',
            $this->response->getHeaderLine('Set-Cookie')
        );

        $options = [
          'cost' => 11,
          'salt' => $sessId
        ];

        $token = password_hash('/validate-token.php', PASSWORD_BCRYPT, $options);

        $this->response = $this->http->request(
            'GET',
            'validate-token.php',
            ['query' => ['_nonce_shield_token' => $token]]
        );

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function validate_token_POST_200()
    {
        $this->response = $this->http->request('GET', 'start-session.php');

        $sessId = HttpHeader::getSessId(
            'PHPSESSID',
            $this->response->getHeaderLine('Set-Cookie')
        );

        $options = [
          'cost' => 11,
          'salt' => $sessId
        ];

        $token = password_hash('/validate-token.php', PASSWORD_BCRYPT, $options);

        $this->response = $this->http->request(
            'POST',
            'validate-token.php', [
                'headers' => [
                    'X-CSRF-Token' => $token
                ]
            ]
        );

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function validate_token_POST_403()
    {
        $this->response = $this->http->request(
            'POST',
            'validate-token.php', [
                'headers' => [
                    'X-CSRF-Token' => 'foo'
                ]
            ]
        );

        $this->assertEquals(403, $this->response->getStatusCode());
        $this->assertEquals(
            '{"message":"Forbidden."}',
            $this->response->getBody()->getContents()
        );
    }

    /**
     * @test
     */
    public function validate_token_PUT_200()
    {
        $this->response = $this->http->request('GET', 'start-session.php');

        $sessId = HttpHeader::getSessId(
            'PHPSESSID',
            $this->response->getHeaderLine('Set-Cookie')
        );

        $options = [
          'cost' => 11,
          'salt' => $sessId
        ];

        $token = password_hash('/validate-token.php', PASSWORD_BCRYPT, $options);

        $this->response = $this->http->request(
            'PUT',
            'validate-token.php', [
                'headers' => [
                    'X-CSRF-Token' => $token
                ]
            ]
        );

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function validate_token_PUT_403()
    {
        $this->response = $this->http->request(
            'PUT',
            'validate-token.php', [
                'headers' => [
                    'X-CSRF-Token' => 'foo'
                ]
            ]
        );

        $this->assertEquals(403, $this->response->getStatusCode());
        $this->assertEquals(
            '{"message":"Forbidden."}',
            $this->response->getBody()->getContents()
        );
    }

    /**
     * @test
     */
    public function validate_token_DELETE_200()
    {
        $this->response = $this->http->request('GET', 'start-session.php');

        $sessId = HttpHeader::getSessId(
            'PHPSESSID',
            $this->response->getHeaderLine('Set-Cookie')
        );

        $options = [
          'cost' => 11,
          'salt' => $sessId
        ];

        $token = password_hash('/validate-token.php', PASSWORD_BCRYPT, $options);

        $this->response = $this->http->request(
            'DELETE',
            'validate-token.php', [
                'headers' => [
                    'X-CSRF-Token' => $token
                ]
            ]
        );

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function validate_token_DELETE_403()
    {
        $this->response = $this->http->request(
            'DELETE',
            'validate-token.php', [
                'headers' => [
                    'X-CSRF-Token' => 'foo'
                ]
            ]
        );

        $this->assertEquals(403, $this->response->getStatusCode());
        $this->assertEquals(
            '{"message":"Forbidden."}',
            $this->response->getBody()->getContents()
        );
    }
}
