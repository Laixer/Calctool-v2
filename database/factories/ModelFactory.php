<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Calctool\Models\User::class, function (Faker\Generator $faker) {
    return [
        'username' => str_replace(' ', '', strtolower($faker->unique()->name)),
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->email,
        'secret' => Hash::make($faker->password),
        'api' => md5(mt_rand()),
        'token' => sha1(Hash::make('ABC@123')),
        'ip' => $faker->ipv4,
        'gender' => $faker->randomElement(['M','V']),
        'active' => 'Y',
        'confirmed_mail' => date('Y-m-d'),
        'registration_date' => date('Y-m-d'),
        'expiration_date' => date('Y-m-d', strtotime("+1 month", time())),
        'referral_key' => md5(mt_rand()),
        'website' => $faker->url,
        'notepad' => $faker->realText,
        'confirmed_mail' => $faker->dateTimeThisYear($max = 'now'),
        // 'mobile' => $faker->phoneNumber,
        // 'phone' => $faker->phoneNumber,
        'pref_hourrate_calc' => $faker->randomFloat(3, 20, 80),
        'pref_hourrate_more' => $faker->randomFloat(3, 20, 80),
        'user_type' => 3,        
        'user_group' => 100,        
    ];
});

