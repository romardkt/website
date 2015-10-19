<div class="row">
    <div class="col-xs-12 col-sm-3 text-center">&nbsp;</div>
    <div class="col-xs-12 col-sm-6 text-center">
        @if(count($session->registrant->contacts()->get()) < 2)
        <div class="alert alert-warning">You must enter at least 2 contacts</div>
        @endif
    </div>
    <div class="col-xs-12 col-sm-3 text-center">
        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#add-contact"><i class="fa fa-fw fa-lg fa-plus"></i> Add Contact</button>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}

        <legend>Enter Emergency Contacts</legend>

        @foreach($session->registrant->contacts()->get() as $i => $contact)
            <h4 class="col-sm-offset-2">Contact #{{ $i + 1}} &nbsp; <button class="btn btn-danger remove-contact-btn" data-contact="{{ $contact->id }}" type="button"><i class="fa fa-fw fa-lg fa-trash-o"></i></button></h4>

        <div class="form-group">
            {!! Form::label('Name') !!}
            {!! Form::text('name[]', $contact->name, ['class' => 'form-control', 'disabled']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Phone') !!}
            {!! Form::text('phone[]', $contact->phone, ['class' => 'form-control', 'disabled']) !!}
        </div>

        <hr>
        @endforeach
        <div class="form-group">
            <div class="col-xs-12 text-center">
                <a class="btn btn-default" href="{{ route('league_register', [$league->slug, 'info']) }}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> Back</a>
                @if(count($session->registrant->contacts()->get()) >= 2)
                <button type="submit" class="btn btn-primary">Next <i class="fa fa-fw fa-lg fa-arrow-right"></i></button>
                @else
                <button type="submit" class="btn btn-primary" disabled>Next <i class="fa fa-fw fa-lg fa-arrow-right"></i></button>
                @endif
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div class="modal fade" id="add-contact" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add a Contact</h4>
            </div>
            <div class="modal-body">
                <div id="contact-error" class="alert alert-danger"></div>
                {!! Form::open(['class' => 'form form-vertical', 'role' => 'form', 'id' => 'contact-form']) !!}

                {!! Form::hidden('user_id', $session->registrant->id) !!}

                <div class="form-group">
                    {!! Form::label('Name') !!}
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    <span class="help-block">Enter contacts full name</span>
                </div>

                <div class="form-group">
                    {!! Form::label('Phone') !!}
                    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                    <span class="help-block">Enter contacts phone number ###-###-####</span>
                </div>

                {!! Form::close() !!}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="add-contact-btn">Add Contact</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@section('page-scripts')
<script>
    $('#contact-error').hide();

    $('#add-contact-btn').on('click touchstart', function (e) {
        e.preventDefault();
        var data = $('#contact-form').serialize();

        $.ajax({
            url: '{{ route('profile_add_contact') }}',
            type: 'post',
            data: data,
            success: function (resp) {
                if (resp.status == 'success') {
                    window.location.reload();
                } else {
                    $('#contact-error').html(resp.msg);
                    $('#contact-error').show();
                }
            }
        });

    });

    $('.remove-contact-btn').on('click touchstart', function (e) {
        e.preventDefault();
        var contactId = $(this).data('contact');
        console.log('here');
        if (confirm('Are you sure?')) {
            $.ajax({
                url: '{{ route('profile_remove_contact') }}',
                type: 'post',
                data: { '_token': '{{ csrf_token() }}', 'contact_id': contactId },
                success: function (resp) {
                    if (resp.status == 'success') {
                        window.location.reload();
                    } else {
                        $('#contact-error').html(resp.msg);
                        $('#contact-error').show();
                    }
                }
            });
        }
    });
</script>
@endsection
