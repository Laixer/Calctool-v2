<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MyCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testEditMyAccount() // TODO
    {
        $user = factory(CalculatieTool\Models\User::class)->create();

        $this->actingAs($user)
             ->visit('/mycompany')
             ->see('bedrijf');
             /*->see($user->firstname)
             ->see('Mijn Bedrijf')
             ->see('Prijslijsten')
             ->see('Urenregistratie')
             ->see('Inkoopfacturen')
             ->see('Relaties');*/
    }
}
