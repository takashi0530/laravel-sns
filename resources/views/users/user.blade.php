<div class="card mt-3">
    <div class="card-body">
        <div class="d-flex flex-row">
            <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
                <i class="fas fa-user-circle fa-3x"></i>
            </a>
            <!--  -->
            <!-- authorizedプロパティを定義：ログイン中かどうかの結果 -->
            @if(Auth::id() !== $user->id)
                <follow-button
                    class="ml-auto"
                    v-bind:initial-is-followed-by='@json($user->isFollowedBy(Auth::user()))'  {{-- ＠json()で配列をjson形式に変換できる。@jsonで値でなく文字列でvueコンポーネントに渡せる --}}
                    v-bind:authorized='@json(Auth::check())'                                  {{-- authファサードのcheckメソッド：ログイン中かどうかを返す --}}
                    endpoint="{{ route('users.follow', ['name' => $user->name]) }}"           {{-- endpoint="http://localhost/users/takashi/follow" --}}
                    >
                </follow-button>
            @endif
        </div>
        <h2 class="h5 card-title m-0">
            <a href="{{ route('users.show', ['name' => $user->name]) }}" class="text-dark">
                {{ $user->name }}
            </a>
        </h2>
    </div>
    <div class="card-body">
        <div class="card-text">
            <a href="{{ route('users.followings', ['name' => $user->name]) }}" class="text-muted">
                {{ $user->count_followings }} フォロー
            </a>
            <a href="{{ route('users.followers', ['name' => $user->name]) }}" class="text-muted">
                {{ $user->count_followers}} フォロワー
            </a>
        </div>
    </div>
</div>

