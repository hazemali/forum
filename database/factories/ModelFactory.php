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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(laravel\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(laravel\Thread::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'user_id' => function () {
            return factory('laravel\User')->create()->id;
        },
        'channel_id' => function () {
            return factory('laravel\Channel')->create()->id;
        },
        'visits' => 0
    ];

});


$factory->define(laravel\Reply::class, function (Faker\Generator $faker) {

    return [
        'body' => $faker->paragraph,
        'user_id' => function () {
            return factory('laravel\User')->create()->id;
        },
        'thread_id' => function () {
            return factory('laravel\Thread')->create()->id;
        }
    ];

});


$factory->define(laravel\Channel::class, function (Faker\Generator $faker) {
    $name = $faker->word;
    return [
        'name' => $name,
        'slug' => $name
    ];
});


$factory->define(Illuminate\Notifications\DatabaseNotification::class, function (Faker\Generator $faker) {

    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => 'laravel\Notifications\ThreadWasUpdated',
        'notifiable_id' => function () {

            return auth()->id() ?: factory('laravel\Reply')->create()->id;

        },
        'notifiable_type' => 'laravel\User',
        'data' => ['foo' => 'bar']
    ];

});