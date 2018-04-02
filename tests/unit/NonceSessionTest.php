<?php
namespace NonceShield\Tests\Unit;

use NonceShield\NonceSession;
use NonceShield\Exception\EmptyCsrfTokenException;
use NonceShield\Exception\UnstartedSessionException;
use PHPUnit\Framework\TestCase;

class NonceSessionTest extends TestCase
{
    /**
     * @test
     */
    public function instantiate()
    {
        session_start();
        $csrfSession = new NonceSession;
        session_destroy();

        $this->assertInstanceOf(NonceSession::class, $csrfSession);
    }

    /**
     * @test
     */
    public function instantiate_without_session_started()
    {
        $this->expectException(UnstartedSessionException::class);

        $csrfSession = new NonceSession;
    }

    /**
     * @test
     */
    public function get_token()
    {
        session_start();
        $token = (new NonceSession)->startToken()->getToken();
        session_destroy();

        $this->assertTrue(is_string($token));
        $this->assertEquals(40, strlen($token));
    }

    /**
     * @test
     */
    public function get_token_no_chaining_methods()
    {
        session_start();
        $csrfSession = (new NonceSession)->startToken();
        $token = $csrfSession->getToken();
        session_destroy();

        $this->assertTrue(is_string($token));
        $this->assertEquals(40, strlen($token));
    }

    /**
     * @test
     */
    public function get_token_without_session_started()
    {
        $this->expectException(UnstartedSessionException::class);

        $token = (new NonceSession)->startToken()->getToken();
    }

    /**
     * @test
     */
    public function get_token_without_csrf_token_in_session()
    {
        $caught = false;

        session_start();

        try {
            $token = (new NonceSession)->getToken();
        } catch (EmptyCsrfTokenException $e) {
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
    public function is_valid()
    {
        session_start();
        $csrfSession = (new NonceSession)->startToken();
        $token = $csrfSession->getToken();
        $isValid = $csrfSession->validateToken($token);
        session_destroy();

        $this->assertTrue($isValid);
    }

    /**
     * @test
     */
    public function is_invalid()
    {
        session_start();
        $csrfSession = (new NonceSession)->startToken();
        $token = 'foo';
        $isValid = $csrfSession->validateToken($token);
        session_destroy();

        $this->assertFalse($isValid);
    }

    /**
     * @test
     */
    public function validate_without_csrf_token_in_session()
    {
        $caught = false;

        session_start();

        try {
            $isValid = (new NonceSession)->validateToken('foo');
        } catch (EmptyCsrfTokenException $e) {
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
