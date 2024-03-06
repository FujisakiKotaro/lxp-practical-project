<?php

namespace App\Shop\Reviews\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Brands\Brand;
use App\Shop\ProductAttributes\ProductAttribute;
use App\Shop\Products\Exceptions\ProductNotFoundException;
use App\Shop\Products\Product;
use App\Shop\Reviews\Repositories\Interfaces\ReviewRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Reviews\Exceptions\ReviewCreateErrorException;
use App\Shop\Reviews\Review;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    // use ProductTransformable, UploadableTrait;

    /**
     * ProductRepository constructor.
     * @param Review $review
     */
    public function __construct(Review $review)
    {
        parent::__construct($review);
        $this->model = $review;
    }

    /**
     * List all the reviews
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listReviews(string $review = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        return DB::table('reviews')
                ->join('customers', 'customer_id', '=', 'customers.id')
                ->select('reviews.*', 'customers.name as name')
                ->orderBy($review, $sort)
                ->get();
    }

    /**
     * Create the review
     *
     * @param array $data
     *
     * @return Review
     */
    public function createReview(array $data) : Review
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new ReviewCreateErrorException($e);
        }
    }

    /**
     * Delete the review
     *
     * @param Product $review
     *
     * @return bool
     * @throws \Exception
     * @deprecated
     */
    public function deleteReview(Review $review) : bool
    {
        $review->images()->delete();
        return $review->delete();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function removeReview() : bool
    {
        return $this->model->where('id', $this->model->id)->delete();
    }

    /**
     * @return bool
     */
    public function deleteCover(): bool
    {
        return $this->model->update(['cover' => null]);
    }


    /**
     * @param string $text
     * @return mixed
     */
    public function searchReview(string $text) : Collection
    {
        if (!empty($text)) {
            return $this->model->searchReview($text);
        } else {
            return $this->listReviews();
        }
    }
}
