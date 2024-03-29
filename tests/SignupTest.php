<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Faker\Factory as Faker;

class SignupTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewAccount()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $user = factory(CalculatieTool\Models\User::class)->make();

        $this->visit('/register')
             ->type($user->firstname, 'contact_firstname')
             ->type($user->lastname, 'contact_name')
             ->type($faker->company, 'company_name')
             ->type($user->username, 'username')
             ->type($user->email, 'email')
             ->type($password, 'secret')
             ->type($password, 'secret_confirmation')
             ->check('tos')
             ->press('Aanmelden')
             ->see('bevestingsmail verstuurd');

        $this->seeInDatabase('user_account', ['username' => $user->username]);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewAccountUsernameExist()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $this->visit('/register')
             ->type($faker->firstName, 'contact_firstname')
             ->type($faker->lastName, 'contact_name')
             ->type($faker->company, 'company_name')
             ->type('admin', 'username')
             ->type($faker->email, 'email')
             ->type($password, 'secret')
             ->type($password, 'secret_confirmation')
             ->check('tos')
             ->press('Aanmelden')
             ->see('gebruikersnaam is al in gebruik');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewAccountEmailExist()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $this->visit('/register')
             ->type($faker->firstName, 'contact_firstname')
             ->type($faker->lastName, 'contact_name')
             ->type($faker->company, 'company_name')
             ->type('testuser', 'username')
             ->type('info@calculatietool.com', 'email')
             ->type($password, 'secret')
             ->type($password, 'secret_confirmation')
             ->check('tos')
             ->press('Aanmelden')
             ->see('email is al bezet');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewAccountPasswordToShort()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $this->visit('/register')
             ->type($faker->firstName, 'contact_firstname')
             ->type($faker->lastName, 'contact_name')
             ->type($faker->company, 'company_name')
             ->type('testsecuser', 'username')
             ->type($faker->email, 'email')
             ->type('q', 'secret')
             ->type('q', 'secret_confirmation')
             ->check('tos')
             ->press('Aanmelden')
             ->see('wachtwoord moet minimaal');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewAccountPasswordNotMatch()
    {
        $faker = Faker::create();

        $this->visit('/register')
             ->type($faker->firstName, 'contact_firstname')
             ->type($faker->lastName, 'contact_name')
             ->type($faker->company, 'company_name')
             ->type('testsecuser', 'username')
             ->type($faker->email, 'email')
             ->type($faker->password, 'secret')
             ->type($faker->password, 'secret_confirmation')
             ->check('tos')
             ->press('Aanmelden')
             ->see('Wachtwoorden komen niet overeen');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewAccountWithReferralKey()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $referred_user = factory(CalculatieTool\Models\User::class)->create([
            'expiration_date' => date('Y-m-d'),
        ]);

        $user = factory(CalculatieTool\Models\User::class)->make();

        $this->visit('/register?client_referer=' . $referred_user->referral_key)
             ->type($user->firstname, 'contact_firstname')
             ->type($user->lastname, 'contact_name')
             ->type($faker->company, 'company_name')
             ->type($user->username, 'username')
             ->type($user->email, 'email')
             ->type($password, 'secret')
             ->type($password, 'secret_confirmation')
             ->check('tos')
             ->press('Aanmelden')
             ->see('bevestingsmail verstuurd');

        $this->seeInDatabase('user_account', ['username' => $user->username, 'expiration_date' => date('Y-m-d', strtotime("+3 month", time()))]);
        $this->seeInDatabase('user_account', ['username' => $referred_user->username, 'expiration_date' => date('Y-m-d', strtotime("+3 month", time()))]);
    }
}
