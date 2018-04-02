<?php
namespace NonceShield;

use NonceShield\NonceSession;
use NonceShield\Html;
use NonceShield\HttpResponse;

/**
 * Nonce class.
 *
 * Acts as a wrapper of NonceShield\Session and NonceShield\Html.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Nonce
{
    /**
     * The nonce session.
     *
     * @var NonceSession
     */
    private $nonceSession;

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
        $this->nonceSession = new NonceSession;
        $this->html = new Html($this->nonceSession);
    }

    /**
     * Creates and stores a new nonce token into the session.
     */
    public function startToken()
    {
        $this->nonceSession->startToken();
    }

    /**
     * Gets the current nonce token from the session.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->nonceSession->getToken();
    }

    /**
     * Returns an HTML input tag with the value of the current nonce token embedded.
     *
     * @return string
     */
    public function htmlInput()
    {
        return $this->html->input();
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
                // ...
                break;

            case $_SERVER['REQUEST_METHOD'] === 'POST':
                if (!$this->nonceSession->validateToken($_POST[$this->nonceSession::NAME])) {
                    $this->nonceSession->startToken();
                    HttpResponse::forbidden();
                }
                break;

            default:
                // ...
                break;
        }
    }
}
