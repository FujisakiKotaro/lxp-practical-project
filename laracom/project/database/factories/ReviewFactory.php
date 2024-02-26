<?php

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Shop\Reviews\Review;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

$factory->define(Review::class, function(Faker\Generator $faker){
    $review = $faker->unique()->sentence;

    return [
        'productID' => $this->faker->numberBetween(1,5),
        'userID' => $this->faker->numberBetween(1,5),
        'rank' => $this->faker->numberBetween(1,5),
        'comment' => $this->faker->paragraph
    ];
});