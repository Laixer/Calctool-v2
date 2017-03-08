<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Faker\Factory as Faker;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testDefaultAccounts()
    {
        $this->seeInDatabase('user_account', ['username' => 'admin', 'active' => true]);
        $this->seeInDatabase('user_account', ['username' => 'guest', 'active' => false ]);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testRedirectToLogin()
    {
        $this->visit('/mycompany')
             ->see('Gebruikersnaam of e-mailadres');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAdminLogin()
    {
        $this->visit('/')
             ->type('admin', 'username')
             ->type('ABC@123', 'secret')
             ->press('Login')
             ->seePageIs('/admin');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGuestLogin()
    {
        $this->visit('/')
             ->type('guest', 'username')
             ->type('ABC@123', 'secret')
             ->press('Login')
             ->see('verkeerd');

        Redis::del('auth:guest:fail', 'auth:guest:block');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAuthTrottle()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $user = factory(Calctool\Models\User::class)->create();

        for ($i=0; $i<10; ++$i) {
            $this->visit('/')
                 ->type($user->username, 'username')
                 ->type($password, 'secret')
                 ->press('Login');
        }

        $this->see('Toegang geblokkeerd voor 15 minuten. Probeer later opnieuw.');

        Cache::flush();
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testDefaultLogin()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $user = factory(Calctool\Models\User::class)->create([
            'secret' => Hash::make($password)
        ]);

        $this->visit('/')
             ->type($user->username, 'username')
             ->type($password, 'secret')
             ->press('Login')
             ->seePageIs('/')
             ->see('Welkom')
             ->see($user->firstname)
             ->see('Mijn Bedrijf')
             ->see('Prijslijsten')
             ->see('Urenregistratie')
             ->see('Inkoopfacturen')
             ->see('Relaties');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testDefaultEmailLogin()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $user = factory(Calctool\Models\User::class)->create([
            'secret' => Hash::make($password)
        ]);

        $this->visit('/')
             ->type($user->email, 'username')
             ->type($password, 'secret')
             ->press('Login')
             ->seePageIs('/')
             ->see('Welkom')
             ->see($user->firstname)
             ->see('Mijn Bedrijf')
             ->see('Prijslijsten')
             ->see('Urenregistratie')
             ->see('Inkoopfacturen')
             ->see('Relaties');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testPasswordChange()
    {
        $faker = Faker::create();
        $password = $faker->password;
        $new_password = $faker->password;

        $user = factory(Calctool\Models\User::class)->create([
            'secret' => Hash::make($password)
        ]);

        $this->actingAs($user)
             ->visit('/myaccount')
             ->see('Wachtwoord wijzigen')
             ->type($password, 'curr_secret')
             ->type($new_password, 'secret')
             ->type($new_password, 'secret_confirmation')
             ->press('save-password')
             ->see('Instellingen opgeslagen');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testPasswordChangeNoMatch()
    {
        $faker = Faker::create();
        $password = $faker->password;
        $new_password = $faker->password;

        $user = factory(Calctool\Models\User::class)->create();

        $this->actingAs($user)
             ->visit('/myaccount')
             ->see('Wachtwoord wijzigen')
             ->type($password, 'curr_secret')
             ->type($new_password, 'secret')
             ->type($new_password, 'secret_confirmation')
             ->press('save-password')
             ->see('Huidige wachtwoord klopt niet');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testDeactivateAccount()
    {
        $user = factory(Calctool\Models\User::class)->create();

        $this->actingAs($user)
             ->visit('/myaccount')
             ->see('Abonnementsduur')
             ->visit('/myaccount/deactivate')
             ->seePageIs('/login');

        $this->seeInDatabase('user_account', ['username' => $user->username, 'active' => false]);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testRedirectAfterLogin()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $user = factory(Calctool\Models\User::class)->create([
            'secret' => Hash::make($password)
        ]);

        $this->visit('/myaccount')
             ->type($user->username, 'username')
             ->type($password, 'secret')
             ->press('Login')
             ->seePageIs('/myaccount');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testSubscriptionExpired()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $user = factory(Calctool\Models\User::class)->create([
            'expiration_date' => date('Y-m-d', strtotime("-1 days", time())),
            'secret' => Hash::make($password)
        ]);

        $this->visit('/')
             ->type($user->username, 'username')
             ->type($password, 'secret')
             ->press('Login')
             ->seePageIs('/myaccount')
             ->see('Account is gedeactiveerd, maak betaling.');
    }
}
