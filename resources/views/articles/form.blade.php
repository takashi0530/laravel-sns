@csrf
<div class="md-form">
    <label>タイトル</label>
    <input type="text" name="title" class="form-control" required value="{{ $article->title ?? old('title') }}">        <!-- Null合体演算子（??）・・・  式1 ?? 式2  式1がNullでないばあいは式1が結果となり  式1がnullなら式2が結果となる-->
</div>

<!-- タグ入力のvueコンポーネントを組み込む -->
<div class="form-group">
    <article-tags-input
    :initial-tags='@json($tagNames ?? [])'
    :autocomplete-items='@json($allTagNames ?? [])'

    >
    </article-tags-input>
</div>

<div class="form-group">
    <label></label>
    <textarea name="body" required class="form-control" rows="16" placeholder="本文">
        {{ $article->body ?? old('title') }}
    </textarea>
</div>









