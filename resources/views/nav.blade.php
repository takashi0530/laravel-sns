<nav class="navbar navbar-expand navbar-dark blue-gradient">
    <a href="/" class="navbar-brand">
        <i class="far fa-sticky-note mr-1"></i><!-- fontawesome -->
        memo
    </a>
    <ul class="navbar-nav ml-auto">

        @guest  <!-- ログインしていないユーザーに表示 -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">ユーザー登録</a>
            </li>
        @endguest

        @guest  <!-- ログインしていないユーザーに表示 -->
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link">ログイン</a>
            </li>
        @endguest

        @auth  <!-- すでにログインしているユーザーに表示 -->
            <li class="nav-item">
            <a href="{{ route('articles.create') }}" class="nav-link"><i class="fas fa-pen mr-1">投稿する</i></a>
            </li>
        @endauth

        @auth  <!-- すでにログインしているユーザーに表示 -->
            <!-- ドロップダウン -->
            <li class="nav-item dropdown">
                <a href="" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                    <button class="dropdown-item" type="button" 
                        onclick="location.href='{{ route("users.show", ["name" => Auth::user()->name]) }}' ">
                        マイページ
                    </button>
                    <div class="dropdown-divider"></div>
                    <button form="logout-button" class="dropdown-item" type="submit">ログアウト</button>
                </div>
            </li>
            <form id="logout-button" method="POST" action="{{ route('logout') }}">
                @csrf
            </form>
            <!-- ドロップダウン -->
        @endauth

    </ul>
</nav>