    @if(Session::has('msg-error'))
    <div class="message alert alert-danger">
        {{ Session::get('msg-error') }}
    </div>
    @endif
    @if(Session::has('msg-success'))
    <div class="message alert alert-success">
        {{ Session::get('msg-success') }}
    </div>
    @endif
