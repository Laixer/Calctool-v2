<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Faker\Factory as Faker;

class RelationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testNewRelation()
    {
        $faker = Faker::create();

        $user = factory(CalculatieTool\Models\User::class)->create();

        $this->actingAs($user) //TODO replace with factory
             ->visit('/relation/new')
             ->see('Nieuwe relatie')
             ->select($faker->numberBetween(1,2), 'relationkind')
             ->type($faker->randomNumber, 'debtor')
             ->type($faker->company, 'company_name')
             ->select($faker->numberBetween(1,45), 'company_type')
             ->type($faker->url, 'website')
             ->type($faker->numberBetween(10000000,99999999), 'kvk')
             ->type($faker->regexify('NL[0-9]{9}B[0-9]{2}'), 'btw')
             ->type($faker->numberBetween(0,999999999999), 'telephone_comp')
             ->type($faker->email, 'email_comp')
             ->type($faker->buildingNumber, 'address_number')
             ->type($faker->regexify('[0-9]{4}[A-Z]{2}'), 'zipcode')
             ->type($faker->streetName, 'street')
             ->type($faker->city, 'city')
             ->select($faker->numberBetween(1,13), 'province')
             ->select($faker->numberBetween(1,53), 'country')
             ->type($faker->lastName, 'contact_name')
             ->type($faker->firstName, 'contact_firstname')
             ->type($faker->numberBetween(0,999999999999), 'mobile')
             ->type($faker->numberBetween(0,999999999999), 'telephone')
             ->type($faker->email, 'email')
             ->select($faker->numberBetween(1,31), 'contactfunction')
             ->select($faker->randomElement(['-1','M','V']), 'gender')
             ->press('Opslaan')
             ->see('Opgeslagen');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testNewRelationFieldsRequired()
    {
        $faker = Faker::create();

        $user = factory(CalculatieTool\Models\User::class)->create();

        $this->actingAs($user)
             ->visit('/relation/new')
             ->see('Nieuwe relatie')
             ->select($faker->numberBetween(1,2), 'relationkind')
             ->type($faker->randomNumber, 'debtor')
             ->select($faker->numberBetween(1,45), 'company_type')
             ->type($faker->url, 'website')
             ->type($faker->numberBetween(10000000,99999999), 'kvk')
             ->type($faker->regexify('NL[0-9]{9}B[0-9]{2}'), 'btw')
             ->type($faker->numberBetween(0,999999999999), 'telephone_comp')
             ->select($faker->numberBetween(1,13), 'province')
             ->select($faker->numberBetween(1,53), 'country')
             ->type($faker->firstName, 'contact_firstname')
             ->type($faker->numberBetween(0,999999999999), 'mobile')
             ->type($faker->numberBetween(0,999999999999), 'telephone')
             ->select($faker->numberBetween(1,31), 'contactfunction')
             ->select($faker->randomElement(['-1','M','V']), 'gender')
             ->press('Opslaan')
             ->see('Achternaam contactpersoon is een verplicht veld')
             ->see('Email is een verplicht veld')
             ->see('Straat is een verplicht veld')
             ->see('Huisnummer is een verplicht veld')
             ->see('Postcode is een verplicht veld')
             ->see('Plaats is een verplicht veld');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testNewRelationInvalidZipcode()
    {
        $faker = Faker::create();

        $user = factory(CalculatieTool\Models\User::class)->create();

        $this->actingAs($user)
             ->visit('/relation/new')
             ->see('Nieuwe relatie')
             ->select($faker->numberBetween(1,2), 'relationkind')
             ->type($faker->randomNumber, 'debtor')
             ->type($faker->company, 'company_name')
             ->select($faker->numberBetween(1,45), 'company_type')
             ->type($faker->url, 'website')
             ->type($faker->numberBetween(10000000,99999999), 'kvk')
             ->type($faker->regexify('NL[0-9]{9}B[0-9]{2}'), 'btw')
             ->type($faker->numberBetween(0,999999999999), 'telephone_comp')
             ->type($faker->email, 'email_comp')
             ->type($faker->buildingNumber, 'address_number')
             ->type($faker->randomNumber(3), 'zipcode')
             ->type($faker->streetName, 'street')
             ->type($faker->city, 'city')
             ->select($faker->numberBetween(1,13), 'province')
             ->select($faker->numberBetween(1,53), 'country')
             ->type($faker->lastName, 'contact_name')
             ->type($faker->firstName, 'contact_firstname')
             ->type($faker->numberBetween(0,999999999999), 'mobile')
             ->type($faker->numberBetween(0,999999999999), 'telephone')
             ->type($faker->email, 'email')
             ->select($faker->numberBetween(1,31), 'contactfunction')
             ->select($faker->randomElement(['-1','M','V']), 'gender')
             ->press('Opslaan')
             ->see('De zipcode moet zijn 6 characters');
    }
}
