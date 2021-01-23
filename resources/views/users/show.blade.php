@extends('app')

@section('title', $user->name)

@section('content')
    @include('nav')
    <div class="container">
    @include('users.user')

        {{-- ユーザーが投稿した記事一覧 --}}
       @include('users.tabs', ['hasArticles' => true, 'hasLikes' => false])

        @foreach($articles as $article)
            @include('articles.card')
        @endforeach

    </div>
@endsection


