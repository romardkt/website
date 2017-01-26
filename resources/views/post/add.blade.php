@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Add News Post</h2>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form', 'files' => true]) !!}
            @include('post.partials.post', ['submitText' => 'Create News Post'])
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.6.2/ckeditor.js"></script>
<script>
$('.datepicker').pickadate({
    format: 'mm/dd/yyyy',
    editable: false,
    selectYears: true,
    selectMonths: true,
    onSet: function(e) {
        generateSlug();
    }
});

$('.clockpicker').clockpicker({
    donetext: 'Done',
    twelvehour: true,
    align: 'right',
});

function generateSlug() {
    var slug = $('#title').val().toLowerCase().trim().replace(/[\s]+/g, '-').replace(/[\'\"\[\]\(\)]+/g, '');
    var date = '{{ Carbon\Carbon::now()->format('Y-m-d')}}';
    if($('#posted_at_date').val()) {
        var parts = $('#posted_at_date').val().split('/');
        var date = parts[2] + '-' + parts[0] + '-' + parts[1];
    }
    slug =  date + '-' + slug;

    $('#slug').val(slug);
    $('#title-slug').html(slug);
}

$('#title').on('keyup', function(e) {
    generateSlug();
});

generateSlug();

</script>
@endsection
