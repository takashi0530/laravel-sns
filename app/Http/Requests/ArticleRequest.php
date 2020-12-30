<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //デフォルトはfalse
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() //rules()メソッド： バリデーションのルールを定義する
    {
        return [
            'title' => 'required|max:50',
            'body' => 'required|max:500',
            //json形式であること、半角スペースと/を弾くバリデーション       /^(?!.*\s).+$/u  ：    半角スペースが無いことをチェックする                               /^(?!.*\/).*$/u   ：   /スラッシュがないことをチェックする
            'tags' => 'json|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u',
        ];
    }

    public function attributes() {  //attributes()メソッド：バリデーションエラーメッセージに表示される項目名をカスタマイズできる
        return[
            'title' => 'タイトル',
            'body' => '本文',
            'tags' => 'タグ'
        ];
    }

    //passedValidation()メソッドバリデーションが成功したときに自動的に呼び出されるメソッド
    public function passedValidation() {
        //json_decode関数  ：まず、json_decode($this->tags)で、JSON形式の文字列であるタグ情報をPHPのjson_decode関数を使って連想配列に変換。
        //collect関数でコレクションに変換
        //slice関数で第一引数から第二引数までのコレクションメソッドを使う
        //map関数
        $this->tags = collect(json_decode($this->tags))
            ->slice(0, 5)
            ->map(function ($requestTag) {
                return $requestTag->text;
            });
    }


}
