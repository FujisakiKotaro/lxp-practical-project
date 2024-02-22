<?php
namespace App\Shop\Reviews;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Review extends Model
{
    protected $hidden = [];

    //reviewの一覧を表示
    public function list(){
        // Review モデルのすべてのレコードを取得
        $reviews = self::all();

        //ビューを使用して表示する
        return view('reviews.list', ['reviews' => $reviews]);
    }

    //入力された評価コメントをDBに追加
    public function add($productID, $userID, $rank, $comment){
        // 新しいレビューをデータベースに追加
        self::create([
            'productID' => $productID,
            'userID' => $userID,
            'rank' => $rank,
            'comment' => $comment,
        ]);
    }
}