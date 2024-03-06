<?php

namespace App\Shop\Reviews\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Orders\Order;
use App\Shop\Products\Product;
use Illuminate\Support\Collection;
use App\Shop\Reviews\Review;

interface ReviewRepositoryInterface extends BaseRepositoryInterface
{
    public function createReview(array $data) : Review;

    // public function updateOrder(array $params) : bool;

    // public function findReviewById(int $id) : Review;

    public function listReviews(string $review = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    // public function findProducts(Order $order) : Collection;

    // public function associateProduct(Product $product, int $quantity = 1, array $data = []);

    // public function searchOrder(string $text) : Collection;

    // public function listOrderedProducts() : Collection;

    // public function buildOrderDetails(Collection $items);

    // public function getAddresses() : Collection;

    // public function getCouriers() : Collection;
}
