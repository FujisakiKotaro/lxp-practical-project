<?php

namespace App\Shop\Reviews\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use Illuminate\Support\Collection;
use App\Shop\Reviews\Review;

interface ReviewRepositoryInterface extends BaseRepositoryInterface
{
    public function createReview(array $data) : Review;

    public function listReviews(string $review = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;
}
