@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <div class="pull-right">
            <a class="btn btn-default" href="{{ route('scholarship_hoy') }}">Back</a>
        </div>
        <h2 class="page">{{ $page->display }} Submissions</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Document</th>
                    <th class="text-center">Accepted?</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submissions as $submission)
                <tr>
                    <td>{{ $submission->name }}</td>
                    <td>{{ $submission->email }}</td>
                    <td><a href="{{ asset($submission->document) }}">Document Link</a></td>
                    <td class="text-center">{!! ($submission->accepted == 1) ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>' !!}</td>
                    <td>
                        <a class="btn btn-default" href="{{ route('scholarship_hoy_manage_edit', [$submission->id]) }}">Edit</a>
                        <a class="btn btn-danger" onclick="return confirm('Are you sure?');" href="{{ route('scholarship_hoy_manage_delete', [$submission->id]) }}">Remove</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection
