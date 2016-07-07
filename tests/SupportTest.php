<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SupportTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSendMessage()
    {
        $this->visit('/support')
             ->type('testuser@calculatietool.com', 'email')
             ->type('Test message', 'subject')
             ->press('Verstuur')
             ->see('Naam is een verplicht veld');
    }
}
