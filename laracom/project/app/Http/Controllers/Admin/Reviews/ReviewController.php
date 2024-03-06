<?php

namespace App\Http\Controllers\Admin\Reviews;

use App\Http\Controllers\Controller;
use App\Shop\Reviews\Repositories\Interfaces\ReviewRepositoryInterface;
use App\Shop\Reviews\Review;

class ReviewController extends Controller
{
    /**
     * Get the review
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    /**
     * @var ReviewRepositoryInterface
     */
    private $reviewRepo;

    /**
     * ReviewController constructor.
     *
     * @param ReviewRepositoryInterface $reviewRepository
     */
    public function __construct(
        ReviewRepositoryInterface $reviewRepository
    ) {
        $this->reviewRepo = $reviewRepository;
        $this->middleware(['permission:create-product, guard:employee'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-product, guard:employee'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-product, guard:employee'], ['only' => ['destroy']]);
        $this->middleware(['permission:view-product, guard:employee'], ['only' => ['index', 'show']]);
    }

    public function index()
    {
        $list = $this->reviewRepo->listReviews('id');
        $reviews = $list->all();

        return view('admin.reviews.list', [
            'reviews' => $this->reviewRepo->paginateArrayResults($reviews, 25)
        ]);
    }
}
