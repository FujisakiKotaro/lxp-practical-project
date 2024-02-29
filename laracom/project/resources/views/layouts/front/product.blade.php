<div class="row">
    <div class="col-md-6">
        @if (!empty($product->cover))
            <ul id="thumbnails" class="col-md-4 list-unstyled">
                <li>
                    <a href="javascript: void(0)">
                        <img class="img-responsive img-thumbnail" src="{{ $product->cover }}" alt="{{ $product->name }}" />
                    </a>
                </li>
                @if (isset($images) && !$images->isEmpty())
                    @foreach ($images as $image)
                        <li>
                            <a href="javascript: void(0)">
                                <img class="img-responsive img-thumbnail" src="{{ asset("storage/$image->src") }}"
                                    alt="{{ $product->name }}" />
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <figure class="text-center product-cover-wrap col-md-8">
                <img id="main-image" class="product-cover img-responsive" src="{{ $product->cover }}?w=400"
                    data-zoom="{{ $product->cover }}?w=1200">
            </figure>
        @else
            <figure>
                <img src="{{ asset('images/NoData.png') }}" alt="{{ $product->name }}"
                    class="img-bordered img-responsive">
            </figure>
        @endif
    </div>
    <div class="col-md-6">
        <div class="product-description">
            <h1>{{ $product->name }}</h1>
            <div class="product-total-price">
                <span class="price">{{number_format($product->price * config('cart.usd_to_jpy_rate'))}} <small>{{config('cart.currency_symbol')}} </small> </span>
                <span class="shipping"> +送料 {{config('cart.shipping_cost')}} {{config('cart.currency_symbol')}} </span>
            </div>
            <p>SKU : {{$product->sku}} </p>
            <div class="description">{!! $product->description !!}</div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.errors-and-messages')
                    <form action="{{ route('cart.store') }}" class="form-inline" method="post">
                        {{ csrf_field() }}
                        @if (isset($productAttributes) && !$productAttributes->isEmpty())
                            <div class="form-group">
                                <label for="productAttribute">Choose Combination</label> <br />
                                <select name="productAttribute" id="productAttribute" class="form-control select2">
                                    @foreach ($productAttributes as $productAttribute)
                                        <option value="{{ $productAttribute->id }}">
                                            @foreach ($productAttribute->attributesValues as $value)
                                                {{ $value->attribute->name }} : {{ ucwords($value->value) }}
                                            @endforeach
                                            @if (!is_null($productAttribute->sale_price))
                                                ({{ config('cart.currency_symbol') }}
                                                {{ $productAttribute->sale_price }})
                                            @elseif(!is_null($productAttribute->price))
                                                ( {{ config('cart.currency_symbol') }} {{ $productAttribute->price }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>
                        @endif
                        <div>数量</div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="quantity" id="quantity"
                                placeholder="数量を入力してください" value="{{ old('quantity') }}" />
                            <input type="hidden" name="product" value="{{ $product->id }}" />
                        </div>
                        <button type="submit" class="btn btn-warning"><i class="fa fa-cart-plus"></i> かごに追加
                        </button>
                    </form>
                    <div class="review_corner">
                        <!-- 評価・コメントの一覧を表示 -->
                        @if(isset($reviews))
                        <div class="review_list" style="display:flex;">
                            <div>
                            @foreach($reviews as $key => $review)
                                <div style="display:flex; margin-top: 10px;">
                                    <!-- 星の記述 -->
                                    <div>{{ $stars[$key] }}</div>
                                    <div class="product_explanation">{{$review->comment}}</div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        @else
                        <div>reviewsを取得できていません</div>
                        @endif
                        <!-- 評価・コメントの入力(ログインしているときのみ) -->
                        @auth
                        <div style="margin-top: 10px;">
                            <form id="review_form" action="{{ route('front.post.product', ['product' => $product->slug]) }}" method="post">
                            @csrf
                            <div><span class="evaluation">評価</span><span class="comment">コメント</span></div>
                            <div>
                                <span >
                                    <select name="rank"class="review_rank">
                                        <option value="5">5</option>
                                        <option value="4">4</option>
                                        <option value="3">3</option>
                                        <option value="2">2</option>
                                        <option value="1">1</option>
                                    </select>
                                </span>
                                <span><input type="text" maxlength="100" class="review_comment" name="comment" /></span>
                                <span><input id="review_submit" type="submit" value="登録" /></span>
                            </div>
                            </form>
                            @if(session('success'))
                                <script>
                                    alert("{{ session('success') }}");
                                </script>
                            @endif
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var productPane = document.querySelector('.product-cover');
            var paneContainer = document.querySelector('.product-cover-wrap');

            new Drift(productPane, {
                paneContainer: paneContainer,
                inlinePane: false
            });
        });
        //フォームが無効な場合にdisableとする処理
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('review_form');
            var submitBtn = document.getElementById('review_submit');

            form.addEventListener('input', function () {
                // フォームが有効な場合
                if (form.checkValidity()) {
                    submitBtn.removeAttribute('disabled');
                } else {
                    // フォームが無効な場合
                    console.log('Button is disabled');
                    submitBtn.setAttribute('disabled', 'disabled');
                }
            });
        });
    </script>
@endsection