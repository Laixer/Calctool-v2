<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Faker\Factory as Faker;

class OauthTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEstablishOauthAuthorizationCode()
    {
    	$faker = Faker::create();
        $user = factory(Calctool\Models\User::class)->create();

        $appid = sha1(mt_rand());
        $appsecret = sha1(mt_rand());
        $appname = $faker->company;

        DB::table('oauth_clients')->insert([
            'id' => $appid,
            'secret' => $appsecret,
            'name' => $appname,
            'active' => true,
            'grant_authorization_code' => true,
            'grant_implicit' => false,
            'grant_password' => false,
            'grant_client_credential' => false,
            'note' => 'Unitttest application',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('oauth_client_endpoints')->insert([
            'client_id' => $appid,
            'redirect_uri' => 'http://localhost/',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->actingAs($user)
            ->visit('/oauth2/authorize?client_id=' . $appid . '&redirect_uri=http://localhost/&response_type=code')
            ->see('Applicatie ' . $appname)
            ->press('approve')
            ->assertTrue(request()->has('code'));

        $response = $this->call('POST', '/oauth2/access_token', [
                'client_id' => $appid,
                'client_secret' => $appsecret,
                'code' => request()->get('code'),
                'grant_type' => 'authorization_code',
                'redirect_uri' => 'http://localhost/',
            ]);

        $json_response = json_decode($response->getContent());

        $this->assertTrue($json_response->token_type == 'Bearer');
        $this->assertTrue(is_int($json_response->expires_in));
        $this->assertTrue(strlen($json_response->access_token) == 40);
        $this->assertTrue(strlen($json_response->refresh_token) == 40);

        /* Pass access token as url parameter */
        $this->get('/oauth2/rest/user?access_token=' . $json_response->access_token)
             ->seeJson([
                 'id' => $user->id
             ]);

        /* Pass access token as url parameter */
        $this->json('GET', '/oauth2/rest/user', [], ['Authorization' => 'Bearer ' . $json_response->access_token])
             ->seeJson([
                 'id' => $user->id
             ]);

        /* Refresh token */
        $response = $this->call('POST', '/oauth2/access_token', [
                'client_id' => $appid,
                'client_secret' => $appsecret,
                'refresh_token' => $json_response->refresh_token,
                'grant_type' => 'refresh_token',
                'redirect_uri' => 'http://localhost/',
            ]);

        $json_response = json_decode($response->getContent());

        $this->assertTrue($json_response->token_type == 'Bearer');
        $this->assertTrue(is_int($json_response->expires_in));
        $this->assertTrue(strlen($json_response->access_token) == 40);
        $this->assertTrue(strlen($json_response->refresh_token) == 40);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEstablishOauthClientCredential()
    {
        $faker = Faker::create();
        $user = factory(Calctool\Models\User::class)->create();

        $appid = sha1(mt_rand());
        $appsecret = sha1(mt_rand());
        $appname = $faker->company;

        DB::table('oauth_clients')->insert([
            'id' => $appid,
            'secret' => $appsecret,
            'name' => $appname,
            'active' => true,
            'grant_authorization_code' => false,
            'grant_implicit' => false,
            'grant_password' => false,
            'grant_client_credential' => true,
            'note' => 'Unitttest application',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('oauth_client_endpoints')->insert([
            'client_id' => $appid,
            'redirect_uri' => 'http://localhost/',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->call('POST', '/oauth2/access_token', [
                'client_id' => $appid,
                'client_secret' => $appsecret,
                'grant_type' => 'client_credentials',
                'redirect_uri' => 'http://localhost/',
            ]);

        $json_response = json_decode($response->getContent());

        $this->assertTrue($json_response->token_type == 'Bearer');
        $this->assertTrue(is_int($json_response->expires_in));
        $this->assertTrue(strlen($json_response->access_token) == 40);

        $this->get('/oauth2/rest/internal/user_all?access_token=' . $json_response->access_token)
             ->seeJson([
                 'id' => $user->id
             ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEstablishOauthClientCredentialForbiddenGrant()
    {
        $faker = Faker::create();
        $user = factory(Calctool\Models\User::class)->create();

        $appid = sha1(mt_rand());
        $appsecret = sha1(mt_rand());
        $appname = $faker->company;

        DB::table('oauth_clients')->insert([
            'id' => $appid,
            'secret' => $appsecret,
            'name' => $appname,
            'active' => true,
            'grant_authorization_code' => false,
            'grant_implicit' => false,
            'grant_password' => false,
            'grant_client_credential' => false,
            'note' => 'Unitttest application',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('oauth_client_endpoints')->insert([
            'client_id' => $appid,
            'redirect_uri' => 'http://localhost/',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->call('POST', '/oauth2/access_token', [
                'client_id' => $appid,
                'client_secret' => $appsecret,
                'grant_type' => 'client_credentials',
                'redirect_uri' => 'http://localhost/',
            ]);

        $json_response = json_decode($response->getContent());

        $this->assertTrue($json_response->error == 'invalid_request');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEstablishOauthAuthorizationCodeForbiddenRestCall()
    {
        $faker = Faker::create();
        $user = factory(Calctool\Models\User::class)->create();

        $appid = sha1(mt_rand());
        $appsecret = sha1(mt_rand());
        $appname = $faker->company;

        DB::table('oauth_clients')->insert([
            'id' => $appid,
            'secret' => $appsecret,
            'name' => $appname,
            'active' => true,
            'grant_authorization_code' => true,
            'grant_implicit' => false,
            'grant_password' => false,
            'grant_client_credential' => false,
            'note' => 'Unitttest application',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('oauth_client_endpoints')->insert([
            'client_id' => $appid,
            'redirect_uri' => 'http://localhost/',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->actingAs($user)
            ->visit('/oauth2/authorize?client_id=' . $appid . '&redirect_uri=http://localhost/&response_type=code')
            ->see('Applicatie ' . $appname)
            ->press('approve')
            ->assertTrue(request()->has('code'));

        $response = $this->call('POST', '/oauth2/access_token', [
                'client_id' => $appid,
                'client_secret' => $appsecret,
                'code' => request()->get('code'),
                'grant_type' => 'authorization_code',
                'redirect_uri' => 'http://localhost/',
            ]);

        $json_response = json_decode($response->getContent());

        $this->assertTrue($json_response->token_type == 'Bearer');
        $this->assertTrue(is_int($json_response->expires_in));
        $this->assertTrue(strlen($json_response->access_token) == 40);
        $this->assertTrue(strlen($json_response->refresh_token) == 40);

        $this->get('/oauth2/rest/internal/user_all?access_token=' . $json_response->access_token)
             ->seeJson([
                 'error' => 'access_denied'
             ]);
    }
}
