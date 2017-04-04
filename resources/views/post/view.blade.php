@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-6 text-left">
        <a class="btn btn-default" href="{{ route('posts') }}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> All Posts</a>
    </div>
    <div class="col-xs-6 text-right">
        @can('is-reporter')
        <a class="btn btn-default" href="{{ route('post_edit', [$post->slug]) }}"><i class="fa fa-fw fa-lg fa-edit"></i> Edit Post</a>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $post->title }}</h2>
        <p class="text-muted">
            Posted at <strong>{{ convertDate($post->post_at, 'M j Y h:i A') }}</strong> by <strong>{{ $post->postedBy->fullname() }} </strong>
        </p>
        {!! generateSocailShareButtons('center', route('post_view', [$post->slug])) !!}
        <hr/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 text-justify">
        @if($post->image !== null)
        <div class="text-center">
            <img class="img-thumbnail" src="{{ asset($post->image) }}"/>
        </div>
        <br/>
        @endif
        {!! $post->content !!}
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
