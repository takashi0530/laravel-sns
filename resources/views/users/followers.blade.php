@extends('app')

@section('title', $user->name . 'のフォロワー')

{{-- コンテンツここから --}}
@section('content')

    {{-- ナビの読み込み --}}
    @include('nav')

    <div class="container">

        {{-- ユーザー情報 --}}
        @include('users.user')

        {{-- 記事/いいね のタブ --}}
        @include('users.tabs', ['hasArticles' => false, 'hasLikes' => false])

        {{-- ユーザーがフォローしているユーザー一覧 --}}
        @foreach ($followers as $person)
            @include('users.person')
        @endforeach

    </div>

@endsection