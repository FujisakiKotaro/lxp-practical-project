<?php

namespace App\Http\Controllers\Admin\Reviews;

use App\Http\Controllers\Controller;
use App\Shop\Reviews\Review;

class ReviewController extends Controller
{
    /**
     * Get the review
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index(){
        $review_list = Review::all();
        return view('admin.reviews.list', ['reviews' => $review_list]);
    }

}
