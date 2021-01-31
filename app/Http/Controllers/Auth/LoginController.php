<?php

namespace App\Http\Controllers\Auth;
use App\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;   //logoutアクションメソッドは、AuthenticatesUsersトレイトのなかにある
use Illuminate\Http\Request;

//Googleログイン（もしくはTwitter等他のプロバイダーのログイン時）に必要なuse
use Laravel\Socialite\Facades\Socialite;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;     //redirectToプロパティ：'/' トップページ（記事一覧画面）

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //Googleログイン（ソーシャルログイン）のためのコントローラーメソッド。driverメソッドに外部のサービス名を渡す
    public function redirectToProvider(string $provider) {
        return Socialite::driver($provider)->redirect();
    }

    //Googleログインしたあとのリダイレクト先
    public function handleProviderCallback(Request $request, string $provider) {



        
        //Googleから取得したユーザー情報
        $providerUser = Socialite::driver($provider)->stateless()->user();

        //Googleのメールアドレスをもとにユーザーモデルを取得し、そのアドレスがUserモデルに存在するか調べて条件に一致する最初の1件のユーザーモデルを取得する（存在しない場合はnullを返す）
        $user = User::where('email', $providerUser->getEmail())->first();

        //$userがnullでなければ、つまりGoogleから取得したメールアドレスと同じメールアドレスを持つユーザーモデルが存在すれば、そのユーザーでログイン処理
        if ($user) {
            $this->guard()->login($user, true);
            //ログイン後の画面(記事一覧画面に遷移)する
            return $this->sendLoginResponse($request);
        };

        //$userがnullの場合の処理
        //ユーザー名の登録画面を表示させるアクションメソッドを実行させるルーティング（リダイレクト）
        return redirect()->route('register.{provider}', [
            'provider' => $provider,
            'email' => $providerUser->getEmail(),
            //Googleから発行されたトークンが返る($providerUser->token)
            'token' => $providerUser->token,
        ]);

    }
}
