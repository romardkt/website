        @if($errors->any())
        <div class="alert alert-danger">
            <h4>Validation errors:</h4>
            @foreach($errors->all() as $error)
            <p>
                {{ $error }}
            </p>
            @endforeach
        </div>
        @endif
