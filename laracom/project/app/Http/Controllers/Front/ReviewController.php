<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
// use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Reviews\Review;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller{

    // use ProductTransformable;
    private $productRepo;

    public function __construct(ProductRepositoryInterface $productRepository){
        $this->productRepo = $productRepository;
    }
    public function show(string $slug){
        $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        // $product = $this->transformProduct($product);
        $productID = $product->id;

        //productIDから商品のレビューを取得
        $reviews = new Review();
        $reviews = $reviews->where('productID', $productID)->get();
        return view('front.products.product', compact('product', 'reviews'));
    }

    //入力された評価コメントをDBに追加
    public function add(string $slug, Request $request){
        echo "test1";

        // $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        // $productID = $product->id;//productIDの取得
        $productID = 1;//id1でテスト
        echo 'productID問題ない';
        $id = Auth::id();//userIDの取得

        $add_data = new Review();//クエリを作成
        $add_data->rank = $request['rank'];
        $add_data->productID = $productID;
        $add_data->userID = $id;
        $add_data->comment = $request['comment'];

        // データベースに挿入
        $add_data->save();
        echo '登録完了';


        // // 新しいレビューをデータベースに追加
        // Review::create([
        //     'productID' => $productID,
        //     'userID' => $id,
        //     'rank' => $rank,
        //     'comment' => $comment,
        // ]);
    }
}