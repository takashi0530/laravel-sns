{{-- app.blade.phpをベースとして使うことを宣言 --}}
@extends('app')

{{-- @yield('title')に対応する。 --}}
@section('title', '記事一覧')

{{-- viewの @yield('content')に対応する --}}
@section('content')

    {{-- ナビの読み込み(nav.blade.php)  @include('') で別のビューを読み込める --}}
    @include('nav')
    <div class="container">{{-- 最初のdivをcontainerとすれば１２分割したグリッドシステムが利用できる--}}
        @foreach($articles as $article)


            @include('articles.card')
        @endforeach
    </div>

    <div class="container">{{-- 最初のdivをcontainerとすれば１２分割したグリッドシステムが利用できる--}}
        <div class="card mt-3">{{-- margin-top:3   card:divのまわりに枠線がついて影ができる（記事のひとつひとつがcard）  --}}
            <div class="card-body d-flex flex-row">
                <i class="fas fa-user-circle fa-3x m-1"></i>  {{-- <i>はアイコン専用タグ fas fa-user-circle:人物アイコン --}}
                <div>
                    <div class="font-weight-bold">ユーザー名</div>
                    <div class="font-weight-lighter">2020/2/1 12:00</div>
                </div>
            </div>
            <div class="card-body pt-0 pb-2">
                <h3 class="h4 card-title">記事タイトル</h3>
                <div class="card-text">記事本文</div>
            </div>
        </div>
    </div>

@endsection

