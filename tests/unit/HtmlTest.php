<?php
namespace NonceShield\Tests\Unit;

use NonceShield\Exception\UnstartedSessionException;
use NonceShield\Html;
use PHPUnit\Framework\TestCase;

class HtmlTest extends TestCase
{
    /**
     * @test
     */
    public function instantiate()
    {
        session_start();
        $html = new Html;
        session_destroy();

        $this->assertInstanceOf(Html::class, $html);
    }

    /**
     * @test
     */
    public function instantiate_without_session_started()
    {
        $this->expectException(UnstartedSessionException::class);

        $html = new Html;
    }

    /**
     * @test
     */
    public function input()
    {
        session_start();
        $html = new Html;
        $attrs = [
            'name' => '_nonce_shield_token',
            'id' => '_nonce_shield_token',
            'value' => 'foo'
        ];
        $htmlInput = $html->input($attrs);
        session_destroy();

        $this->assertEquals($htmlInput,
            '<input type="hidden" name="' . $attrs['name'] . '" id="' . $attrs['id'] . '" value="' . $attrs['value'] . '" />'
        );
    }
}
