<?php
namespace NonceShield\Exception;

use NonceShield\Exception;

/**
 * Thrown when the nonce key is unsecure.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
final class UnsecureNonceKeyException extends \UnexpectedValueException implements Exception
{
}
