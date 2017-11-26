<?php

use Faker\Generator as Faker;

$factory->define(Illuminate\Notifications\DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => "b763d750-8d4e-4f56-8463-7aaf0528ffb0",
        'type' =>"App\Notifications\ThreadWasUpdated",
        'notifiable_id' => function(){
            return factory(App\User::class)->create()->id;
        },
        'notifiable_type' => "App\User",
        'data' => ["message robi "],
        'read_at' => null,
        'created_at' => "2017-11-26 10:16:31",
        'updated_at' => "2017-11-26 10:16:31",
    ];
});
