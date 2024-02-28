<?php
namespace App\Shop\Reviews;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Review extends Model
{
    protected $hidden = [];
    public function list(){
        $reviews = self::all();
        return view('reviews.list', ['reviews' => $reviews]);
    }

    //入力された評価コメントをDBに追加
    public function add($product_id, $user_id, $rank, $comment){
        // 新しいレビューをデータベースに追加
        self::create([
            'product_id' => $product_id,
            'user_id' => $user_id,
            'rank' => $rank,
            'comment' => $comment,
        ]);
    }
}