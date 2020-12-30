<?php

namespace App\Policies;

use App\Article;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;


/*
*  ポリシーの各メソッドと、コントローラーのアクションメソッドとの対応関係
*
*  ポリシーのメソッド	   コントローラーのアクションメソッド
*  viewAny	            index
*  view    	            show
*  create  	            create, store
*  update	            edit, update
*  destroy	            destroy
*
* ポリシーの各メソッドは、現時点では何も処理が定義されていませんが、ここに、対応するアクションメソッドの実行を許可して良い「条件」を追加し、条件を満たせばtrueを、満たさなければfalseを返すようにします
*
*/

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any articles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)     //index()アクション：記事一覧画面閲覧      型の前に ? をつけることでその引数がnullであることも許容される。    Userモデルの型宣言をnullableにすると、メソッド内の判定条件において「ユーザーがログイン済みであること」が求められなくなる
    {
        return true;
    }

    /**
     * Determine whether the user can view the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function view(?User $user, Article $article)  //show()アクション：詳細画面閲覧
    {
        return true;
    }

    /**
     * Determine whether the user can create articles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)  //create()アクション：新規作成
    {
        return true;
    }

    /**
     * Determine whether the user can update the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function update(User $user, Article $article)
    {
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can delete the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function delete(User $user, Article $article)
    {
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can restore the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function restore(User $user, Article $article)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the article.
     *
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return mixed
     */
    public function forceDelete(User $user, Article $article)
    {
        //
    }
}
