@if (session('message'))
    {{ session('message') }}
@endif

{{ $post->title }}
{{ $post->content }} 