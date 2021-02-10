<?php

use App\User;
use App\Loan;
use Faker\Generator as Faker;

$factory->define(Loan::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'creator_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
