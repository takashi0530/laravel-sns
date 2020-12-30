下記のURLからパスワードの再設定を行って下さい。

{{ $url }}

このURLの有効期限は{{ $count }}分です。

このメールに心当たりがない場合は、第三者がメールアドレスの入力を誤った可能性があります。

その場合は、このメールを破棄していただいて結構です。

memo({{ url(config('app.url')) }})
<!--
    url()関数       ：引数として渡されたパスを完全なURLに変換する
    config()関数    ：laravel/config/app.php のurlの値を取得する 'url' => env('APP_URL', 'http://localhost'),

--> 