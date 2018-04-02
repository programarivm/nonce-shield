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
    private $csrfSession;

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
        $this->csrfSession = new NonceSession;
        $this->html = new Html($this->csrfSession);
    }

    /**
     * Creates and stores a new nonce token into the session.
     */
    public function startToken()
    {
        $this->csrfSession->startToken();
    }

    /**
     * Gets the current nonce token from the session.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->csrfSession->getToken();
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
            case $_SERVER['REQUEST_METHOD'] !== 'POST':
                HttpResponse::methodNotAllowed();
                break;

            case isset($_SERVER['HTTP_X_CSRF_TOKEN']):
                if (!$this->csrfSession->validateToken($_SERVER['HTTP_X_CSRF_TOKEN'])) {
                    HttpResponse::forbidden();
                }
                break;

            default:
                if (!$this->csrfSession->validateToken($_POST[$this->csrfSession::NAME])) {
                    HttpResponse::forbidden();
                }
                break;
        }
    }
}
