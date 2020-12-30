import './bootstrap'
import Vue from 'vue'
import ArticleLike from './components/ArticleLike'

//タグ入力のvueコンポーネントを記事入力フォームに組み込む
import ArticleTagsInput from './components/ArticleTagsInput'

//フォローボタンのvueコンポーネントをユーザーページのbladeに組み込む
import FollowButton from './components/FollowButton'

const app = new Vue({
    el: '#app',
    components: {
        //いいねボタンのコンポーネントの読み込み
        ArticleLike,
        //タグ入力コンポーネントの読み込み
        ArticleTagsInput,
        //フォローボタンのコンポーネントを読み込み
        FollowButton,
    }
})