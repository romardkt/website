        @include('partials.errors')

        <div class="form-group">
            {!! Form::label('Page Title') !!}
            {!! Form::text('display', $page->display, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Content') !!}
            {!! Form::textarea('content', $page->content, ['class' => 'ckeditor']) !!}
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update {{ $page->display }}</button>
                <a class="btn btn-default" href="{{ route($page->route) }}">Cancel</a>
            </div>
        </div>
