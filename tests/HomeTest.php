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
             ->see('Inloggen')
             ->see('Klantenservice')
             ->visit('/register')
             ->see('bericht');
    }
}
