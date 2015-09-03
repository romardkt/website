@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page"> All News Posts</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-right">
        <div class="btn-group">
            @if($isAuthorized['reporter'])
            <a class="btn btn-default" href="{{ route('posts_add') }}">Add News</a>
            @endif
        </div>
    </div>
</div>
<hr/>
<div class="row post-page">
    <div class="col-xs-12 post">
        <div class="list-group">
            @foreach($posts as $post)
            <a class="list-group-item" href="{{ route('post_view', [$post->slug]) }}">
                @if($post->is_featured)
                <div class="pull-left">
                    <img class="img-thumbnail" src="{{ asset($post->image) }}" style="width: 168px; margin-right: 15px;"/>
                </div>
                @endif
                <h4 class="list-group-item-header">
                    {{{ $post->title }}}
                    <div class="badge {{{ $post->category }}}">{{{ ucwords($post->category) }}}</div>
                    @if($post->is_featured)
                    <span class="badge">Featured</span>
                    @endif
                    <p class="text-muted">
                        Posted on <strong>{{{ convertDate($post->post_at, 'M j Y h:i A') }}}</strong> by <strong>{{{ $post->postedBy->fullname() }}}</strong>
                        @if($post->remove_at !== null)
                        removed on <strong>{{{ convertDate($post->remove_at, 'M j Y h:i A') }}}</strong>
                        @endif
                    </p>
                </h4>
                <p>{{{ str_limit(strip_tags($post->content), 250, '...') }}}</p>
            </a>
            @endforeach
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-right">
        {{ $posts->render() }}
    </div>
</div>

@endsection

@section('page-scripts')
@endsection
