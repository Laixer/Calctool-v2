<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testDefaultAccounts()
    {
        $this->seeInDatabase('user_account', ['username' => 'admin', 'active' => true]);
        $this->seeInDatabase('user_account', ['username' => 'guest', 'active' => false ]);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAdminLogin()
    {
        $this->visit('/')
             ->type('admin', 'username')
             ->type('ABC@123', 'secret')
             ->press('Login')
             ->seePageIs('/admin');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGuestLogin()
    {
        $this->visit('/')
             ->type('guest', 'username')
             ->type('ABC@123', 'secret')
             ->press('Login')
             ->see('verkeerd');
    }
}
