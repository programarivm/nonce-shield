<?php
namespace NonceShield\Tests\Unit;

use NonceShield\Exception\UnsecureNonceKeyException;
use NonceShield\Nonce;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../vendor/autoload.php';

class NonceTest extends TestCase
{
    /**
     * @test
     */
    public function secure_nonce_key_example_01()
    {
        $_ENV['NONCE_KEY'] = '11111111111111111111111111111-aA';
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(false);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     */
    public function secure_nonce_key_example_02()
    {
        $_ENV['NONCE_KEY'] = '46eMIjzTMlL4H53rCIUl7Lf0IRPfjQ2去';
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(false);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     */
    public function secure_nonce_key_example_03()
    {
        $_ENV['NONCE_KEY'] = '46eMIjzTMlL4H53rCIUl7Lf0IRPfjQ最去';
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(false);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     */
    public function unsecure_nonce_key_not_set()
    {
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(true);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(false);
        }
    }

    /**
     * @test
     */
    public function unsecure_nonce_key_empty()
    {
        $_ENV['NONCE_KEY'] = '';
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(true);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(false);
        }
    }

    /**
     * @test
     */
    public function unsecure_nonce_key_less_than_32_chars()
    {
        $_ENV['NONCE_KEY'] = '5ZLXPORAl39jM';
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(true);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(false);
        }
    }

    /**
     * @test
     */
    public function unsecure_nonce_key_not_containing_numbers()
    {
        $_ENV['NONCE_KEY'] = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(true);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(false);
        }
    }

    /**
     * @test
     */
    public function unsecure_nonce_key_not_containing_letters()
    {
        $_ENV['NONCE_KEY'] = '11111111111111111111111111111111';
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(true);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(false);
        }
    }

    /**
     * @test
     */
    public function unsecure_nonce_key_not_containing_uppercase_letter()
    {
        $_ENV['NONCE_KEY'] = '1111111111111111111111111111111a';
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(true);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(false);
        }
    }

    /**
     * @test
     */
    public function unsecure_nonce_key_not_containing_lowercase_letter()
    {
        $_ENV['NONCE_KEY'] = '1111111111111111111111111111111A';
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(true);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(false);
        }
    }

    /**
     * @test
     */
    public function unsecure_nonce_key_containing_special_chars_only()
    {
        $_ENV['NONCE_KEY'] = '__________----------__________--';
        $caught = false;
        session_start();
        try {
            $nonce = new Nonce();
        } catch (UnsecureNonceKeyException $e) {
            $caught = true;
            $this->assertTrue(true);
        } finally {
            session_destroy();
        }
        if (!$caught) {
            $this->assertTrue(false);
        }
    }
}
