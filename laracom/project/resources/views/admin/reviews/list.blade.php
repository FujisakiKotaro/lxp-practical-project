@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($reviews)
            <div class="box">
                <div class="box-body">
                    <h2>Reviews</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="col-md-2">日時</td>
                                <td class="col-md-2">商品ID</td>
                                <td class="col-md-2">ユーザー(ID)</td>
                                <td class="col-md-2">評価</td>
                                <td class="col-md-4">コメント</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                <td><a title="Show review">{{ $review->created_at }}</a></td>
                                <td><a href="{{ route('admin.products.show', $review->product_id) }}">{{ $review->product_id }}</a></td>
                                <td><a href="{{ route('admin.customers.show', $review->user_id) }}">{{ $review->user_id }}</a></td>
                                <td>{{ $review->rank }}</td>
                                <td><p class="text-center">{{ $review->comment }}</p></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box -->
        @endif

    </section>
    <!-- /.content -->
@endsection