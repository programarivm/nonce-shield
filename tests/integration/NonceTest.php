<?php
namespace NonceShield\Tests\Integration;

use NonceShield\Nonce;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class NonceTest extends TestCase
{
    const BASE_URI = 'http://localhost:8001/';

    const TIME_DELAY = 1;

    /**
     * A Guzzle wrapper that deals with the HTTP communication.
     *
     * @var array
     */
    private $http;

    /**
     * The response from the HTTP endpoint.
     *
     * @var array
     */
    private $response;

    private function scrapToken($html)
    {
      $dom = new \DOMDocument;
      $dom->loadHTML($html);
      $xp = new \DOMXpath($dom);
      $nodes = $xp->query('//input[@name="_nonce_shield_token"]');
      $node = $nodes->item(0);

      return $node->getAttribute('value');
    }

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
        $token = $this->scrapToken($this->response->getBody()->getContents());

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
        $token = $this->scrapToken($this->response->getBody()->getContents());

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
        // get the nonce token
        $this->response = $this->http->request('GET', 'auto-processing-form.php');
        $token = $this->scrapToken($this->response->getBody()->getContents());

        // post a foo token
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
    public function ajax_get_token_200()
    {
        $this->response = $this->http->request('GET', 'get-token.php');

        $json = json_decode(
            $this->response->getBody()->getContents(),
            true
        );

        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertTrue(is_string($json['_nonce_shield_token']));
        $this->assertEquals(60, strlen($json['_nonce_shield_token']));
    }

    /**
     * @test
     */
    public function ajax_post_token_200()
    {
        $this->response = $this->http->request('GET', 'get-token.php');

        $json = json_decode(
            $this->response->getBody()->getContents(),
            true
        );

        $this->response = $this->http->request(
            'POST',
            'validate-token.php', [
                'headers' => [
                    'X-CSRF-Token' => $json['_nonce_shield_token']
                ]
            ]
        );

        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * @test
     */
    public function ajax_post_token_403()
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
}
