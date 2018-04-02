<?php
namespace NonceShield;

use NonceShield\NonceSession;
use NonceShield\Exception\EmptyCsrfTokenException;
use NonceShield\Exception\UnstartedSessionException;

/**
 * Html class.
 *
 * Renders html tags with the csrf token embedded.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Html
{
    /**
     * The nonce session.
     *
     * @var NonceSession
     */
    private $csrfSession;

    /**
     * Constructor.
     */
    public function __construct($csrfSession = null)
    {
        if (empty(session_id())) {
            throw new UnstartedSessionException();
        }

        $this->csrfSession = $csrfSession;
    }

    /**
     * Returns an HTML input tag with the value of the current nonce token embedded.
     */
    public function input()
    {
        if (empty(session_id())) {
            throw new UnstartedSessionException();
        }

        if (empty($this->csrfSession->getToken())) {
            throw new EmptyCsrfTokenException();
        }

        return '<input type="hidden" name="' . $this->csrfSession::NAME . '" id="' . $this->csrfSession::NAME . '" value="' . $_SESSION[$this->csrfSession::NAME] . '" />';
    }
}
