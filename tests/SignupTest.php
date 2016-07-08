<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
        $this->visit('/register')
             ->type('Voornaam', 'contact_firstname')
             ->type('Achternaam', 'contact_name')
             ->type('Testgebruiker', 'contact_name')
             ->type('Firma L&B', 'company_name')
             ->type('testuser', 'username')
             ->type('testuser@calculatietool.com', 'email')
             ->type('ABC@123', 'secret')
             ->type('ABC@123', 'secret_confirmation')
             ->check('tos')
             ->press('Aanmelden')
             ->see('bevestingsmail verstuurd');

        $this->seeInDatabase('user_account', ['username' => 'testuser']);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewAccountUsernameExist()
    {
        $this->visit('/register')
             ->type('Voornaam', 'contact_firstname')
             ->type('Achternaam', 'contact_name')
             ->type('Testgebruiker', 'contact_name')
             ->type('Firma L&B', 'company_name')
             ->type('admin', 'username')
             ->type('otheremail@calculatietool.com', 'email')
             ->type('ABC@123', 'secret')
             ->type('ABC@123', 'secret_confirmation')
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
        $this->visit('/register')
             ->type('Voornaam', 'contact_firstname')
             ->type('Achternaam', 'contact_name')
             ->type('Testgebruiker', 'contact_name')
             ->type('Firma L&B', 'company_name')
             ->type('testuser', 'username')
             ->type('info@calculatietool.com', 'email')
             ->type('ABC@123', 'secret')
             ->type('ABC@123', 'secret_confirmation')
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
        $this->visit('/register')
             ->type('Arie', 'contact_firstname')
             ->type('Kaas', 'contact_name')
             ->type('Kaas', 'contact_name')
             ->type('Firma L&B', 'company_name')
             ->type('testsecuser', 'username')
             ->type('testsecuser@calculatietool.com', 'email')
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
        $this->visit('/register')
             ->type('Arie', 'contact_firstname')
             ->type('Kaas', 'contact_name')
             ->type('Kaas', 'contact_name')
             ->type('Firma L&B', 'company_name')
             ->type('testsecuser', 'username')
             ->type('testsecuser@calculatietool.com', 'email')
             ->type('ABC@1235', 'secret')
             ->type('ABC@1233', 'secret_confirmation')
             ->check('tos')
             ->press('Aanmelden')
             ->see('Wachtwoorden komen niet overeen');
    }
}
