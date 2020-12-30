@if ($errors->any())    <!-- MessageBagクラスのany()メソッド：エラーメッセージの有無を返す --> <!-- エラーメッセージが１件以上ある場合はtrue -->
    <div class="card-text text-left alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error) <!-- $erros変数 laravelのblade内で使用できる変数。  illuminate\Support\MessageBagクラスのインスタンス。配列でバリデーションエラーメッセージを持ってる --> <!-- MessageBagクラスのall()メソッドで全メッセージを配列で取得しforeachで回して表示 --> <!-- /Users/takashi/laravel-sns/laravel/vendor/laravel/framework/src/Illuminate/Support/MessageBag.php -->
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif