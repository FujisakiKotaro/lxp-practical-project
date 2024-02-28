<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Reviews\Review;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller{

    // use ProductTransformable;
    private $productRepo;

    public function __construct(ProductRepositoryInterface $productRepository){
        $this->productRepo = $productRepository;
    }

    //入力された評価コメントをDBに追加
    public function store(Request $request){
        try {
            $slug = $request->input('slug');
            $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        } catch (ModelNotFoundException $e) {
            echo 'Product Not Found';
            return;
        }
        $product_id = $product->id;//product_idの取得
        $id = Auth::id();//userIDの取得

        $add_data = new Review();//クエリを作成
        $add_data->rank = $request->input('rank');
        $add_data->product_id = $product_id;
        $add_data->user_id = $id;
        $add_data->comment = $request->input('comment');
        $add_data->save();// データベースに挿入

        return redirect()->back()->with('success', '評価とコメントを登録しました');
    }
}