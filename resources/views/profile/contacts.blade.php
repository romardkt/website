@section('profile_content')
<legend>My Emergency Contacts</legend>
<div class="row">
    <div class="col-xs-12">
        <div class="list-group">
            <a href="{{ route('profile_contact_add') }}" class="list-group-item active">Add a contact...</a>
            @forelse($contacts as $contact)
            <a href="{{ route('profile_contact_edit', [$contact->id]) }}" class="list-group-item">
                <h4 class="list-group-item-heading">{{{ $contact->name }}}</h4>
                <p class="list-group-item-text">{{{ $contact->phone }}}</p>
            </a>
            @empty
            <h4 class="text-center">No contacts added yet.</h4>
            @endforelse
        </div>
    </div>
</div>
@endsection

@include('profile.header')
