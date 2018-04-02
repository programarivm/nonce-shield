<?php
namespace NonceShield;

use NonceShield\Exception\EmptyCsrfTokenException;
use NonceShield\Exception\UnstartedSessionException;

/**
 * NonceSession class.
 *
 * Handles the nonce token in the PHP session.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class NonceSession
{
    const NAME = '_nonce_shield_token';

    /**
     * Constructor.
     */
    public function __construct()
    {
        if (empty(session_id())) {
            throw new UnstartedSessionException();
        }
    }

    /**
     * Creates and stores a new nonce token into the session.
     *
     * @return NonceSession
     */
    public function startToken() {
        if (empty(session_id())) {
            throw new UnstartedSessionException();
        }

        $_SESSION[self::NAME] = sha1(uniqid(mt_rand()));

        return $this;
    }

    /**
     * Gets the current nonce token from the session.
     *
     * @return string
     */
    public function getToken() {
        if (empty(session_id())) {
            throw new UnstartedSessionException();
        }

        if (empty($_SESSION[self::NAME])) {
            throw new EmptyCsrfTokenException();
        }

        return $_SESSION[self::NAME];
    }

    /**
     * Validates the incoming nonce token against the session.
     *
     * @return boolean
     */
    public function validateToken($token) {
        if (empty(session_id())) {
            throw new UnstartedSessionException();
        }

        if (empty($_SESSION[self::NAME])) {
            throw new EmptyCsrfTokenException();
        }

        return $token === $_SESSION[self::NAME];
    }
}
