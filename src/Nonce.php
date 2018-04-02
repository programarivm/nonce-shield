<?php
namespace NonceShield;

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
        $this->html = new Html;
    }

    /**
     * Calculates the nonce token.
     */
    public function getToken($url)
    {
      $options = [
        'cost' => 11,
        'salt' => session_id()
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
                $token = $this->getToken($_SERVER['REQUEST_URI'], self::NAME);
                if ($token !== $_SERVER['HTTP_X_CSRF_TOKEN']) {
                    HttpResponse::forbidden();
                }
              break;

            case $_SERVER['REQUEST_METHOD'] === 'POST':
                $token = $this->getToken($_SERVER['REQUEST_URI'], self::NAME);
                if ($token !== $_POST[self::NAME]) {
                    HttpResponse::forbidden();
                }
                break;

            case $_SERVER['REQUEST_METHOD'] === 'GET':
                $token = $this->getToken(Uri::withoutVar($_SERVER['REQUEST_URI'], self::NAME));
                if ($token !== Uri::getVar($_SERVER['REQUEST_URI'], self::NAME)) {
                    HttpResponse::forbidden();
                }
                break;

            default:
                HttpResponse::forbidden();
                break;
        }
    }
}
