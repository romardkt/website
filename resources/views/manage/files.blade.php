@extends('app')

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h2 class="page">CUPA Management</h2>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-2 text-center">
        @include('manage.menu')
    </div>
    <div class="col-xs-12 col-sm-8">
        <legend>CUPA Files</legend>
        <div class="row text-right">
            <div class="col-xs-12">
                <a class="add-btn btn btn-default" href="{{ route('manage_files_add') }}"><i class="fa fa-lg fa-fw fa-plus"></i> Add File</a>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-xs-12" id="files">
                <div class="input-group">
                    <input type="text" placeholder="Filter Files" class="search form-control"/>
                    <span class="input-group-addon clear-btn" onclick="$('#files .search').val(''); fileList.search();"><i class="fa fa-fw fa-lg fa-remove"></i></span>
                </div>
                <hr/>
                <div class="list-group">
                    @foreach($files as $file)
                    <div class="list-group-item">
                        <div class="pull-right">
                            <a class="btn btn-default" href="{{ $file->location }}"><i class="fa fa-lg fa-fw fa-download"></i><span class="hidden-xs hidden-sm"> Download</span></a>
                            <a class="btn btn-danger" href="{{ route('manage_files_remove', [$file->id]) }}" onclick="return confirm('Are you sure you want to remove this file?');"><i class="fa fa-lg fa-fw fa-trash-o"></i><span class="hidden-xs hidden-sm"> Delete</span></a>
                        </div>
                        <h4 class="list-group-item-heading file-name">{{ $file->name }}</h4>
                        <p class="list-group-item-text">
                            <span class="text-muted">{{ displayFilesize($file->size) }}</span>
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-scripts')
<script>
var options = {
  valueNames: [ 'file-name' ],
  listClass: 'list-group',
  //page: 400
};
var fileList = new List('files', options);
</script>


@endsection
