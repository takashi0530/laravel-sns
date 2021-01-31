@extends('app')

@section('title', 'ログイン')

@section('content')
    <div class="container">
        <div class="row">
            <div class="mx-auto col col-12 col-sm-11 col-md-9 col-lg-7 col-xl-6">   <!-- mx-auto：ブロック中央寄せ  col-sm-11：スマホサイズのときカラム幅を11にする   col-md-9：タブレットサイズ以上のときカラム幅を9にする   col-lg-7:PCサイズの場合カラム幅を7にする -->
                <h1 class="text-center"><a href="/" class="text-dark">memo</a></h1>
                <div class="card mt-3">     <!-- 要素の基本サイズ：16px   mt-5:要素の基本サイズ16px × 3倍 ＝ 48px -->
                    <div class="card-body text-center">
                        <h2 class="h3 card-title text-center mt-2 fa-google">ログイン</h2>

                        <a href="{{ route('login.{provider}', ['provider' => 'google']) }}" class="btn btn-block btn-danger">

                        <i class="fab fa-google mr-1"></i>Googleでログイン</a>

                        @include('error_card_list')

                        <div class="card-text">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="md-form">
                                    <label for="email">メールアドレス</label>
                                    <input class="form-control" type="text" id="email" name="email" required value="{{ old('email') }}">
                                </div>

                                <div class="md-form">
                                    <label for="password">パスワード</label>
                                    <input class="form-control" type="password" id="password" name="password" required>
                                </div>

                                <input type="hidden" name="remember" id="remember" value="on"> <!-- laravelの remember_token機能。次回から自動でログインする ものと同じ機能-->  <!-- ユーザーがログインした際、メールとパスワードとrememberのパラメーターがonの状態でPOST送信される --> <!-- 初回ログイン時にremember_web_・・・というCookieが保存され２回目からのログインを不要にしている -->

                                <div class="text-left">
                                    <a href="{{ route('password.request') }}" class="card-text">パスワードを忘れた方</a>
                                </div>

                                <button class="btn btn-block blue-gradient mt-2 mb-2" type="submit">ログイン</button>   <!-- btn:ボタンのデザインになる   btn-block：横幅いっぱいのブロックレベルボタンになる -->  <!--  他のグラデーション詳細：https://mdbootstrap.com/docs/jquery/css/gradients/ -->
                            </form>

                            <div class="mt-0">
                                <a href="{{ route('register') }}" class="card-text">ユーザー登録はこちら</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection