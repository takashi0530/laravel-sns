@extends('app')

@section('title', '記事更新')

@include('nav')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card mt-3">
                    <div class="card-body pt-0">
                        @include('error_card_list')
                        <div class="card-text">
                        <form method="POST" action="{{ route('articles.update', ['article' => $article]) }}"><!-- route()の第二引数には連想配列形式でルーティングのパラメーターを渡すことができる   URI: articles/{article}  {}の中に$articleが入る -->
                            @method('PATCH')  <!-- formタグではPOSTしか用意されていないが、PUTかPATCHメソッドでリクエストする必要があるためmethodでPATCHメソッドを指定している -->
                            @include('articles.form')
                            <button type="submit" class="btn blue-gradient btn-block">更新する</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection