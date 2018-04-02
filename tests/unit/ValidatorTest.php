<?php
namespace NonceShield\Tests\Unit;

use NonceShield\Exception\UnstartedSessionException;
use NonceShield\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function instantiate()
    {
        session_start();
        $validator = new Validator;
        session_destroy();

        $this->assertInstanceOf(Validator::class, $validator);
    }

    /**
     * @test
     */
    public function instantiate_without_session_started()
    {
        $this->expectException(UnstartedSessionException::class);

        $validator = new Validator;
    }

    /**
     * @test
     */
    public function token()
    {
        session_start();
        $validator = new Validator;
        $isValid = $validator->token('foo', 'bar');
        session_destroy();

        $this->assertTrue($isValid);
    }
}
