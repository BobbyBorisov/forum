<?php

use Faker\Generator as Faker;

$factory->define(App\Thread::class, function (Faker $faker) {
    $sentence = $faker->sentence();
    return [
        'user_id' => function(){
           return factory(App\User::class)->create()->id;
        },
        'channel_id' => function(){
            return factory(App\Channel::class)->create()->id;
        },
        'title' => $sentence,
        'slug' => str_slug($sentence),
        'body'  => $faker->paragraph,
        'created_at' => \Carbon\Carbon::now()
    ];
});
