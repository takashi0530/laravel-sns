<template>
    <div>
        <!-- @clickはv-on:clickの省略形 クリックするとclickFollowメソッドが実行する -->
        <button
            class="btn-sm shadow-none border border-primary p-2"
            :class="buttonColor"
            @click="clickFollow"
        >
            <i
                class="mr-1"
                :class="buttonIcon"
            ></i>
            {{ buttonText }}
        </button>
    </div>
</template>

<script>
//export default : importする際に指定がなければそのクラスや関数を呼ぶもの。importする際にdefault以外のクラスや関数を呼び出したいときは、{}でクラスやファイル名を指定して呼びだすことができる。
//export defaultは、コンポーネント化したい場合に使う。ほかの場所からも呼び出せるようになる。単一ファイル構成の場合はこれが使われる。new Vueはそのとき実行したい場合に使う。 書き方が違うだけなので、new Vueをexport defaultで書き換えることができる。
export default {
    props: {
        initialIsFollowedBy: {
            type: Boolean,
            default: false,
        },
        authorized: {
            type: Boolean,
            default: false,
        },
        endpoint: {
            type: String,
        },
    },
    data() {
        return {
            isFollowedBy: this.initialIsFollowedBy,
        }
    },
    computed: {
        buttonColor() {
            return this.isFollowedBy
                ? 'bg-primary text-white' //背景を青くテキストを白くする
                : 'bg-white'              //背景を白くする
        },
        buttonIcon() {
            return this.isFollowedBy
                ? 'fas fa-user-check'
                : 'fas fa-user-plus'
        },
        buttonText() {
            return this.isFollowedBy
                ? 'フォロー中'
                : 'フォロー'
        },
    },
    methods: {
        // フォローボタンがクリックされたらclickFollowメソッドが実行される
        clickFollow() {
            if (!this.authorized) {
                alert('フォロー機能はログイン中のみ使用できます')
                return
            }

            this.isFollowedBy
                ? this.unfollow() //既にフォローされていればunfollow()メソッドが発動
                : this.follow()   //フォローされいない
        },
        // asyncとはjsで非同期処理を簡潔に書く仕組み
        async follow() {
            // response にはaxiosによるHTTP通信の結果が代入されている
            //axios.put(this.endpoint)では users/{name}/followに対してHTTPのPUTメソッドでリクエストする
            //axiosはHTTP通信を行うためのjsライブラリのこと（laraveでは標準でこの機能が利用可能）
            const response = await axios.put(this.endpoint)

            this.isFollowedBy = true
        },
        async unfollow() {
            const response = await axios.delete(this.endpoint)

            this.isFollowedBy = false
        },
    }
}
</script>