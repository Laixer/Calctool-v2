<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testNewProject() // TODO
    {
        $user = factory(Calctool\Models\User::class)->create();

        $this->actingAs($user)
             ->visit('/project/new')
             ->see('Projectgegevens');
             /*->see($user->firstname)
             ->see('Mijn Bedrijf')
             ->see('Prijslijsten')
             ->see('Urenregistratie')
             ->see('Inkoopfacturen')
             ->see('Relaties');*/
    }
}
