<h1>Posts</h1>

@foreach($posts as $post)
    <a href="/posts/{{ $post->id }}">{{ $post->title }}</a>
@endforeach

<a href="/posts/create">New Post</a> 