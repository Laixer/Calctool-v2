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
             ->type($faker->name, 'name')
             ->type($faker->email, 'email')
             ->type($faker->text, 'subject')
             ->type($faker->text, 'message')
             ->press('Verstuur')
             ->see('Bericht en kopie verstuurd');
    }
}
