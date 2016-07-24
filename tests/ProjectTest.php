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
    public function testNewProject()
    {
        $user = factory(Calctool\Models\User::class)->create();

        $user_relation = factory(Calctool\Models\Relation::class)->create([
            'user_id' => $user->id,
        ]);

        $user_relation_contact = factory(Calctool\Models\Contact::class)->create([
            'relation_id' => $user_relation->id,
        ]);

        $user->self_id = $user_relation->id;
        $user->save();

        $relation = factory(Calctool\Models\Relation::class)->create([
            'user_id' => $user->id,
        ]);

        $relation_contact = factory(Calctool\Models\Contact::class)->create([
            'relation_id' => $relation->id,
        ]);

        $project = factory(Calctool\Models\Project::class)->make([
            'user_id' => $user->id,
            'client_id' => $user->id,
        ]);

        $this->actingAs($user)
             ->visit('/project/new')
             ->type($project->project_name, 'name')
             ->select($relation->id, 'contractor')
             ->type($project->address_number, 'address_number')
             ->type($project->address_postal, 'zipcode')
             ->type($project->address_street, 'street')
             ->type($project->address_city, 'city')
             ->select($project->province_id, 'province')
             ->select($project->country_id, 'country')
             ->press('save-project')
             // ->check('tax_reverse')
             ->see('Opgeslagen');
    }
}
