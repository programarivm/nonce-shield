<?php
namespace NonceShield;

use NonceShield\Html;
use NonceShield\HttpResponse;

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
     * Creates a new nonce token.
     */
    public function getToken()
    {
      $options = [
        'cost' => 15,
        'salt' => session_id())
      ];

      return password_hash($slug, PASSWORD_BCRYPT, $options);
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

        return $this->html->input($token);
    }

    /**
     * Validates the incoming nonce token against the session's token.
     */
    public function validateToken()
    {
        switch (true) {

            case isset($_SERVER['HTTP_X_CSRF_TOKEN']):
              if (!$this->nonceSession->validateToken($_SERVER['HTTP_X_CSRF_TOKEN'])) {
                  $this->nonceSession->startToken();
                  HttpResponse::forbidden();
              }
              break;

            case $_SERVER['REQUEST_METHOD'] === 'GET':
                if (!$this->nonceSession->validateToken($_GET[$this->nonceSession::NAME])) {
                    $this->nonceSession->startToken();
                    HttpResponse::forbidden();
                }
                break;

            case $_SERVER['REQUEST_METHOD'] === 'POST':
                if (!$this->nonceSession->validateToken($_POST[$this->nonceSession::NAME])) {
                    $this->nonceSession->startToken();
                    HttpResponse::forbidden();
                }
                break;

            default:
                HttpResponse::forbidden();
                break;
        }
    }
}
