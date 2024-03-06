<?php
namespace App\Shop\Reviews;

use App\Shop\Customers\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Review extends Model
{
    protected $hidden = [];
    public function list(){
        $reviews = self::all();
        return ['reviews' => $reviews];
    }

    //入力された評価コメントをDBに追加
    public function add($product_id, $customer_id, $rank, $comment){
        // 新しいレビューをデータベースに追加
        self::create([
            'product_id' => $product_id,
            'customer_id' => $customer_id,
            'rank' => $rank,
            'comment' => $comment,
        ]);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}