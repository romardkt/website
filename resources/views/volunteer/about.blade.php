@extends('app')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        {{ $page->content }}

        <p class="text-justify">If you have any questions, please email {{ secureEmail('volunteer@cincyultimate.org') }}</p>
    </div>
</div>
@endsection
