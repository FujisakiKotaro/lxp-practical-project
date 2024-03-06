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
     * ProductController constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ReviewRepositoryInterface $productRepository
    ) {
        $this->reviewRepo = $productRepository;

        $this->middleware(['permission:create-product, guard:employee'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-product, guard:employee'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-product, guard:employee'], ['only' => ['destroy']]);
        $this->middleware(['permission:view-product, guard:employee'], ['only' => ['index', 'show']]);
    }

    // public function index(){
    //     // $review_list = Review::all();
    //     $review_list = Review::join('customers', 'customer_id', '=', 'customers.id')
    //                         ->select('reviews.*', 'customers.name as name')
    //                         ->get();
    //     return view('admin.reviews.list', ['reviews' => $review_list]);
    // }

    public function index()
    {
        $list = $this->reviewRepo->listReviews('id');

        // if (request()->has('q') && request()->input('q') != '') {
        //     $list = $this->reviewRepo->searchReview(request()->input('q'));
        // }

        $reviews = $list->map(function (Review $item) {
            return $this->transformProduct($item);
        })->all();

        return view('admin.products.list', [
            'products' => $this->reviewRepo->paginateArrayResults($reviews, 25)
        ]);
    }
}
