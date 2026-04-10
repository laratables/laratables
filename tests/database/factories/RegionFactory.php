<?php

use Faker\Generator as Faker;
use Freshbitsweb\Laratables\Tests\Stubs\Models\Region;

$factory->define(Region::class, function (Faker $faker) {
    return [
        'name' => $faker->word.' Region',
    ];
});
