<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Faker\Factory as Faker;

class SupportTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSendMessage()
    {
    	$faker = Faker::create();

        $this->visit('/support')
             ->type($faker->email, 'email')
             ->type($faker->text, 'subject')
             ->press('Verstuur')
             ->see('Naam is een verplicht veld');
    }
}
