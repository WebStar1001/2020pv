<?php

namespace Tests\Browser;

use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class LoginPageTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testEmailAndPasswordFieldsPresented()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage())
                ->type('email', 'admin@amin.com')
                ->type('password', 'password')
                ->press('Login')
                ->assertPathIs('/admin/dashboard');
        });
    }
}
