<?php
namespace NonceShield;

use NonceShield\Exception\UnstartedSessionException;

/**
 * Validator class.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Validator
{
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
     * Validates the nonce token against the incoming url.
     *
     * @return boolean
     */
    public function token($token, $url) {
        if (empty(session_id())) {
            throw new UnstartedSessionException();
        }

        // ...

        return true;
    }
}
