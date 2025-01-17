<?php

namespace App\Http\Controllers\Front;

use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Reviews\Review;

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
        $product_id = $product->id;

        //product_idから商品のレビューを取得
        $reviews = new Review();
        $reviews = $reviews->where('product_id', $product_id)->latest()->take(10)->get();

        // 星の記述
        $stars = '';
        foreach($reviews as $idx => $review){
            $reviews[$idx]['stars'] = $this->generateStarRatingsHtml($review->rank);
        }

        return view('front.products.product', compact(
            'product',
            'images',
            'productAttributes',
            'category',
            'reviews'
        ));
    }

    // 星評価のHTMLを生成するヘルパー関数
    private function generateStarRatingsHtml($rank){
        return str_repeat('★', $rank) . str_repeat('☆', 5 - $rank);
    }
}
