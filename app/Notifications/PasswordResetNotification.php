<?php

namespace App\Notifications;

use App\Mail\BareMail;  //bar
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public $token;
    public $mail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token, BareMail $mail)  //文字列の引数である$token  と     BareMailクラスのインスタンスである$mailを先程のプロパティに代入  コンストラクタにて注入することをコンストラクタインジェクションという
    {
        $this->token = $token; //$tokenプロパティを定義
        $this->mail = $mail;   //$mailプロパティを定義
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {   //toMailメソッド ： メールの具体的な設定をしている
        return $this->mail
            ->from(config('mail.from.address'),config('mail.from.name'))    //from()メソッド：第一引数は送信元メアド  第二引数はメールの送信者名  config()関数：laravel/config/mail.php の値を取得している
            ->to($notifiable->email)                                        //to()メソッド： 送信先メールアドレスを渡す $notifiableにはパスワード再設定送信先となるUserモデルが代入されている。$notifiable->emailでメアドを取得している
            ->subject('[memo]パスワード再設定')                                //subject()メソッド： メールの件名を渡す
            ->text('emails.password_reset')                                  //text()メソッド  ： テキスト形式のメールを送るときに使うメソッド。引数にはメールテンプレートを指定する。 /Users/takashi/laravel-sns/laravel/resources/views/emails/password_reset.blade.php にテンプレートがある
            ->with([                                                        //with()メソッド   :テンプレートにわたす変数を連想配列で渡す
                'url' => route('password.reset',[                           //キーのURLには、route関数をセットしている。|        | GET|HEAD  | password/reset/{token} | password.reset   | App\Http\Controllers\Auth\ResetPasswordController@showResetForm        | web        |
                    'token' => $this->token,
                    'email' => $notifiable->email,
                ]),
                'count' => config(
                    'auth.passwords.' .
                    config('auth.defaults.passwords').
                    '.expire'
                ),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
