<?php

namespace App\Http\Controllers\Front;

use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Reviews\Review;
use Illuminate\Database\Eloquent\ModelNotFoundException;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use ProductTransformable;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * ProductController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepo = $productRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        $list = $this->productRepo->searchProduct(request()->input('q'));

        $products = $list->where('status', 1)->map(function (Product $item) {
            return $this->transformProduct($item);
        });

        return view('front.products.product-search', [
            'products' => $this->productRepo->paginateArrayResults($products->all(), 10)
        ]);
    }

    /**
     * Get the product
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $slug)
    {
        $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        $product = $this->transformProduct($product);
        $images = $product->images()->get();
        $category = $product->categories()->first();
        $productAttributes = $product->attributes;

        $productID = $product->id;

        //productIDから商品のレビューを取得
        $reviews = new Review();
        $reviews = $reviews->where('productID', $productID)->latest()->take(10)->get();

        return view('front.products.product', compact(
            'product',
            'images',
            'productAttributes',
            'category',
            'reviews'
        ));
    }

    //入力された評価コメントをDBに追加
    public function add_review(string $slug, Request $request){
        try {
            $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        } catch (ModelNotFoundException $e) {
            echo 'Product Not Found';
            return;
        }
        $productID = $product->id;//productIDの取得
        $id = Auth::id();//userIDの取得

        $add_data = new Review();//クエリを作成
        $add_data->rank = $request->input('rank');
        $add_data->productID = $productID;
        $add_data->userID = $id;
        $add_data->comment = $request->input('comment');
        $add_data->save();// データベースに挿入

        return redirect()->back()->with('success', '評価とコメントを登録しました');
    }
}
