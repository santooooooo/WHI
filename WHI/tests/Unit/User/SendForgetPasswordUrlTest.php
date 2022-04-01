<?php

namespace Tests\Unit\User;

use Tests\TestCase;
use App\Mail\SendForgetPasswordUrl;

class SendForgetPasswordUrlTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @test
     * @return void
     */
    public function sendEmail()
    {
	    $mailable = new SendForgetPasswordUrl();
	    $mailable->assertSeeInHtml('Laravel');
    }
}
