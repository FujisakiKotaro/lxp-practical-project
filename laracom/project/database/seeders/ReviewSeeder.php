<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Shop\Reviews\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Review::class, 10)->create();
    }
}
