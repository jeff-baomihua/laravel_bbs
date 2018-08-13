<?php

use Faker\Generator as Faker;
//定制数据工厂
$factory->define(App\Models\Reply::class, function (Faker $faker) {

    //随机取一个月以内的时间
    $time = $faker->dateTimeThisMonth();

    return [
        // 'name' => $faker->name,
        'content' => $faker->sentence(),
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
