<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Shop\Reviews\Review;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

$factory->define(Review::class, function(Faker\Generator $faker){
    $review = $faker->unique()->sentence;

    return [
        'productID' => $this->faker->number_Between(1,5),
        'userID' => $this->faker->number_Between(1,5),
        'rank' => $this->faker->number_Between(1,5),
        'comment' => $this->faker->paragraph
    ];
});