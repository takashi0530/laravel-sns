<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;   //RegistersUsersトレイト  ＜トレイトの場所：laravel/vendor/laravel/framework/src/Illuminate/Foundation/Auth/RegistersUsers.php＞  
//  トレイトのパス：Users/takashi/laravel-sns/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Auth/RegistersUsers.php

use Illuminate\Http\Request;



use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    //RegistersUsersトレイトの中にshowRegistrationFormアクションメソッドとregisterアクションメソッドが定義されている
    // /Users/takashi/laravel-sns/laravel/vendor/laravel/framework/src/Illuminate/Foundation/Auth/RegistersUsers.php のRegistersUsersトレイトを継承している
    //RegistersUsersトレイトを使用するためにはクラス内で use トレイト名 と記述する必要がある
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // RouteServiceProviderクラスのHOMEという定数が代入されている
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    //渡された値をバリデートするメソッド
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'alpha_num', 'min:3', 'max:16', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    //Googleのような"Provider(サービスの提供者)"のユーザーを登録する画面を表示するアクションメソッド。また、このアクションメソッドはユーザー登録に関わる処理であるので、RegisterControllerに実装することにします。
    public function showProviderUserRegistrationForm(Request $request, string $provider) {

        $token = $request->token;

        $providerUser = Socialite::driver($provider)->userFromToken($token);

        return view('auth.social_register', [
            'provider' => $provider,
            'email' => $providerUser->getEmail(),
            'token' => $token,
        ]);

    }

    // ユーザー名の登録画面で「ユーザー登録」ボタンを押した後の処理
    public function registerProviderUser(Request $request, string $provider) {

        //$requestのvalidateメソッド：
        $request->validate([
            'name' => ['required', 'string', 'alpha_num', 'min:3', 'max:16', 'unique:users'],
            //Googleが発行したトークン：必須 かつ 文字列
            'token' => ['required', 'string'],
        ]);

        //$request->token ： Googleから発行済みのトークンの値を取得できる
        $token = $request->token;

        //Laravel\Socialite\Two\Userクラスのインスタンスを取得
        //useFormTokenメソッド：Googleから発行済みトークンを使ってGoogleのAPIに再度ユーザー情報の問い合わせを行う
        //$providerUse : 問い合わせによって取得したユーザー情報
        $providerUser = Socialite::driver($provider)->userFromToken($token);

        //ユーザーモデルのクリエイトメソッドでユーザーモデルのインスタンスを作成している
        //createメソッド ： usersテーブルへのレコードの保存も行う
        $user = User::create([
            //ユーザー名登録画面に入力されたユーザー名
            'name' => $request->name,
            //トークンを使ってGoogleのAPIから取得したユーザー情報のメールアドレス
            'email' => $providerUser->getEmail(),
            //パスワードは登録不要としているためnull
            'password' => null,
        ]);

        //
        $this->guard()->login($user, true);

        //ユーザー登録のあとに、記事一覧画面にリダイレクト
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());

    }
}
