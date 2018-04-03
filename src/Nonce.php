<?php
namespace NonceShield;

use NonceShield\Exception\UnsecureNonceKeyException;
use NonceShield\Exception\UnstartedSessionException;
use NonceShield\Html;
use NonceShield\HttpResponse;
use NonceShield\Uri;

/**
 * Nonce class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Nonce
{
    const NAME = '_nonce_shield_token';

    /**
     * HTML renderer.
     *
     * @var Html
     */
    private $html;

    /**
     * Constructor.
     */
    public function __construct()
    {
        if (empty(session_id())) {
            throw new UnstartedSessionException();
        }
        if (empty($_ENV['NONCE_KEY'])) {
            throw new UnsecureNonceKeyException('The nonce key cannot be empty.');
        }
        if (strlen($_ENV['NONCE_KEY']) < 32) {
            throw new UnsecureNonceKeyException('The nonce key must be 32 chars long at least.');
        }
        if (!preg_match("#[0-9]+#", $_ENV['NONCE_KEY'])) {
            throw new UnsecureNonceKeyException('The nonce key must contain at least one number.');
        }
        if (!preg_match("#[a-z]+#", $_ENV['NONCE_KEY'])) {
            throw new UnsecureNonceKeyException('The nonce key must contain at least one lowercase letter.');
        }
        if (!preg_match("#[A-Z]+#", $_ENV['NONCE_KEY'])) {
            throw new UnsecureNonceKeyException('The nonce key must contain at least one uppercase letter.');
        }
        if (!preg_match("#[^\da-zA-Z]+#", $_ENV['NONCE_KEY'])) {
            throw new UnsecureNonceKeyException('The nonce key must contain at least one special character.');
        }

        $this->html = new Html;
    }

    /**
     * Calculates the nonce token.
     */
    public function getToken($url)
    {
      $options = [
        'cost' => 11,
        'salt' => session_id() . getenv('NONCE_KEY')
      ];

      return password_hash($url, PASSWORD_BCRYPT, $options);
    }

    /**
     * Returns an HTML input tag with the value of the current nonce token embedded.
     *
     * @return string
     */
    public function htmlInput($url)
    {
        $token = $this->getToken($url);

        $attrs = [
            'name' => self::NAME,
            'id' => self::NAME,
            'value' => $token
        ];

        return $this->html->input($attrs);
    }

    /**
     * Validates the incoming nonce token.
     */
    public function validateToken()
    {
        switch (true) {
            case isset($_SERVER['HTTP_X_CSRF_TOKEN']):
                $token = $this->getToken($_SERVER['REQUEST_URI']);
                if ($token !== $_SERVER['HTTP_X_CSRF_TOKEN']) {
                    HttpResponse::forbidden();
                }
              break;

            case $_SERVER['REQUEST_METHOD'] === 'POST':
                $token = $this->getToken($_SERVER['REQUEST_URI']);
                if ($token !== $_POST[self::NAME]) {
                    HttpResponse::forbidden();
                }
                break;

            case $_SERVER['REQUEST_METHOD'] === 'GET':
                $requestUri = urldecode($_SERVER['REQUEST_URI']);
                $token = $this->getToken(Uri::withoutVar($requestUri, self::NAME));
                if ($token !== Uri::getVar($requestUri, self::NAME)) {
                    HttpResponse::forbidden();
                }
                break;

            default:
                HttpResponse::forbidden();
                break;
        }
    }
}
