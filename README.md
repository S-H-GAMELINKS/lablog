# Laravelでの投稿機能作成チュートリアル
## Laravelでアプリのひな型作成

`Laravel`では以下のコマンドでアプリを作成できます。

```shell
laravel new blog
```

## Postモデルの作成

`Laravel`でのモデル作成は以下のコマンドで作成できます。

```shell
php artisan make:model -m Post
```

次に、以下のコードをマイグレーションファイルに追加します

```php
            $table->string('title');
            $table->text('content');
```

## Postコントローラーの作成

`Laravel`でのコントローラー作成は以下のコマンドで作成できます。

```shell
php artisan make:controller PostController --resource --model=Post
```

## 作成したPostのルーティング設定

`routes/web.php`を以下のように編集します。

```php:routes/web.php
Route::resource('posts', 'PostController');
```

## 投稿の一覧ページの作成

`app/Http/Controllers/PostController.php`の`index`アクションを以下のようにします

```php:app/Http/Controllers/PostController.php
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }
```

次に、`resources/views/posts/index.blade.php`を作成し、以下のようにします

```php:resources/views/posts/index.blade.php
<h1>Posts</h1>

@foreach($posts as $post)
    <a href="/posts/{{ $post->id }}">{{ $post->title }}</a>
    <a href="/posts/{{ $post->id }}/edit">Edit</a>
    <form action="/posts/{{ $post->id }}" method="POST" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit">Delete</button>
    </form>
@endforeach

<a href="/posts/create">New Post</a> 
```

## 新規作成ページ

`app/Http/Controllers/PostController.php`の`create`アクションを以下のように編集します


```php:app/Http/Controllers/PostController.php
    public function create()
    {
        return view('posts.create');
    }
```

次に、`resources/views/posts/create.blade.php`を以下のように作成します

```php:resources/views/posts/create.blade.php
<form method="POST" action="/posts">
    {{ csrf_field() }}
    <input type="text" name="title">
    <input type="text" name="content">
    <input type="submit">
</form>
```

その後、`app/Http/Controllers/PostController.php`の`store`アクションを以下のようにします。

```php:app/Http/Controllers/PostController.php
    public function store(Request $request)
    {
        $post = new Post();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        return redirect()->route('posts.show', ['id' => $post->id])->with('message', 'Post was successfully created.');
    }
```

## 個別ページの作成

`app/Http/Controllers/PostController.php`の`show`アクションを以下のようにします

```php:app/Http/Controllers/PostController.php
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }
```

その後、`resources/views/posts/show.blade.php`を以下のように作成します

```php:resources/views/posts/show.blade.php
@if (session('message'))
    {{ session('message') }}
@endif

{{ $post->title }}
{{ $post->content }} 

<a href="/posts/{{ $post->id }}/edit">Edit</a>
```

## 投稿の編集ページ

`app/Http/Controllers/PostController.php`の`edit`アクションを以下のように編集します

```php:app/Http/Controllers/PostController.php
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }
```

`resources/views/posts/edit.blade.php`を以下のように作成します

```php:resources/views/posts/edit.blade.php
<form method="POST" action="/posts/{{ $post->id }}">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">
    <input type="text" name="title" value="{{ $post->title }}">
    <input type="text" name="content" value="{{ $post->content }}">
    <input type="submit">
</form> 
```

最後に`app/Http/Controllers/PostController.php`の`update`アクションを以下のように修正します

```php:app/Http/Controllers/PostController.php
    public function update(Request $request, Post $post)
    {
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        return redirect()->route('posts.show', ['id' => $post->id])->with('message', 'Post was successfully updated.');
    }
```

## 削除機能の実装

`app/Http/Controllers/PostController.php`の`destroy`アクションを以下のようにします

```php:app/Http/Controllers/PostController.php
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index');
    }
```

## 投稿の削除機能

