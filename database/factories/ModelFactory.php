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

$factory->define(CalculatieTool\Models\User::class, function (Faker\Generator $faker) {
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
        'pref_profit_calc_contr_mat' => $faker->numberBetween(0, 100),
        'pref_profit_calc_contr_equip' => $faker->numberBetween(0, 100),
        'pref_profit_calc_subcontr_mat' => $faker->numberBetween(0, 100),
        'pref_profit_calc_subcontr_equip' => $faker->numberBetween(0, 100),
        'pref_profit_more_contr_mat' => $faker->numberBetween(0, 100),
        'pref_profit_more_contr_equip' => $faker->numberBetween(0, 100),
        'pref_profit_more_subcontr_mat' => $faker->numberBetween(0, 100),
        'pref_profit_more_subcontr_equip' => $faker->numberBetween(0, 100),
        'user_type' => 3,        
        'user_group' => 100,        
    ];
});

$factory->defineAs(CalculatieTool\Models\User::class, 'admin', function ($faker) use ($factory) {
    $user = $factory->raw(CalculatieTool\Models\User::class);

    return array_merge($user, ['user_type' => 2]);
});

$factory->define(CalculatieTool\Models\Relation::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function() {
            return factory(CalculatieTool\Models\User::class)->create()->id;
        },
        'kind_id' => 1,
        'debtor_code' => $faker->randomNumber,
        'company_name' => $faker->company,
        'type_id' => $faker->numberBetween(1,45),
        'kvk' => $faker->numberBetween(10000000,99999999),
        'btw' => $faker->regexify('NL[0-9]{9}B[0-9]{2}'),
        'phone' => $faker->numberBetween(0,999999999999),
        'email' => $faker->email,
        'website' => $faker->url,
        'website' => $faker->url,
        'address_street' => $faker->streetName,
        'address_number' => $faker->buildingNumber,
        'address_postal' => $faker->regexify('[0-9]{4}[A-Z]{2}'),
        'address_city' => $faker->city,
        'province_id' => $faker->numberBetween(1, 12),
        'country_id' => $faker->numberBetween(1, 52),
        'iban' => $faker->regexify('NL[0-9]{2}[A-Z]{4}[0-9]{10}'),
        'iban_name' => $faker->name,
    ];
});

$factory->define(CalculatieTool\Models\Contact::class, function (Faker\Generator $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'mobile' => $faker->numberBetween(0,999999999999),
        'phone' => $faker->numberBetween(0,999999999999),
        'email' => $faker->email,
        'relation_id' => function() {
            return factory(CalculatieTool\Models\Relation::class)->create()->id;
        },
        'function_id' => $faker->numberBetween(1,31),
        'gender' => $faker->randomElement(['M','V']),
    ];
});

$factory->define(CalculatieTool\Models\Project::class, function (Faker\Generator $faker) {
    return [
        'project_name' => $faker->word,
        'address_street' => $faker->streetName,
        'address_number' => $faker->buildingNumber,
        'address_postal' => $faker->regexify('[0-9]{4}[A-Z]{2}'),
        'address_city' => $faker->city,
        'note' => $faker->text,
        'hour_rate' => $faker->randomFloat(3, 20, 80),
        'hour_rate_more' => $faker->randomFloat(3, 20, 80),
        'user_id' => function() {
            return factory(CalculatieTool\Models\User::class)->create()->id;
        },
        'province_id' => $faker->numberBetween(1, 12),
        'country_id' => $faker->numberBetween(1, 52),
        'type_id' => 2,
        'client_id' => function() {
            return factory(CalculatieTool\Models\User::class)->create()->id;
        },
        'profit_calc_contr_mat' => $faker->numberBetween(0, 100),
        'profit_calc_contr_equip' => $faker->numberBetween(0, 100),
        'profit_calc_subcontr_mat' => $faker->numberBetween(0, 100),
        'profit_calc_subcontr_equip' => $faker->numberBetween(0, 100),
        'profit_more_contr_mat' => $faker->numberBetween(0, 100),
        'profit_more_contr_equip' => $faker->numberBetween(0, 100),
        'profit_more_subcontr_mat' => $faker->numberBetween(0, 100),
        'profit_more_subcontr_equip' => $faker->numberBetween(0, 100),
        'work_execution' => date('Y-m-d'),
        'work_completion' => date('Y-m-d'),
    ];
});
