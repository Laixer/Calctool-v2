<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testHomeLinks()
    {
        $this->visit('/')
             ->see('Login')
             ->see('Registreren')
             ->see('Vergeten')
             ->visit('/register')
             ->see('Direct inloggen?')
             ->visit('/support')
             ->see('bericht')
             ->see('vraag');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test404()
    {
        $this->call('GET', '/randompage');
        $this->assertResponseStatus(404);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAdminRedirect()
    {
        $this->call('GET', '/admin');
        $this->assertRedirectedTo('/login');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testPasswordRest()
    {
        $this->visit('/login')
             ->type('testuser@calculatietool.com', 'email')
             ->press('Verzenden')
             ->see('Instructies verzonden');
    }
}
