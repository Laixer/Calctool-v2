<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Faker\Factory as Faker;

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
    public function testPasswordReset()
    {
        $faker = Faker::create();

        $this->visit('/login')
             ->type($faker->email, 'email')
             ->press('Verzenden')
             ->see('Instructies verzonden');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGenericPages()
    {
        $this->visit('/about')
             ->visit('/faq')
             ->visit('/terms-and-conditions')
             ->visit('/privacy-policy')
             ->visit('/support');
    }
}
