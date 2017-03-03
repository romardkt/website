        <legend>Information</legend>

        {!! Form::hidden('slug', null, ['id' => 'slug']) !!}

        <div class="form-group">
            {!! Form::label('Category') !!}
            {!! Form::select('category', $categories, null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'title']) !!}
            <span class="help-block">If you change this it will change the url: <span id="title-slug"></span></span>
        </div>

        <div class="form-group">
            {!! Form::label('Link') !!}
            {!! Form::text('link', null, ['class' => 'form-control']) !!}
            <span class="help-block">Link for news post (Optional)</span>
        </div>

        <div class="form-group">
            {!! Form::label('Content') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control ckeditor']) !!}
        </div>

        <legend>Feature</legend>

        <div class="form-group">
            <div class="checkbox">
                {!! Form::checkbox('is_featured', 1, false) !!} Should it be featured?
            </div>
            <span class="help-block">Display on homepage image slider</span>
        </div>

        @if(isset($post) && $post->image !== null)
        <div class="current-picture">
            <div class="text-muted">Current Image</div>
            <img src="{{ asset($post->image) }}" alt="Post image"/>
        </div>
        <div class="form-group">
            <div class="checkbox">
                {!! Form::checkbox('image_remove', 1, false) !!} Remove Image
            </div>
        </div>
        @endif

        <div class="form-group">
            {!! Form::label('Header Image') !!}
            {!! Form::file('image', null, ['class' => 'form-control']) !!}
            <span class="help-block">Required if featured post, resized to 800x400</span>
        </div>


        <legend>Dates</legend>

        <div class="form-group">
            {!! Form::label('Post At Date') !!}
            {!! Form::text('post_at_date', (isset($post)) ? convertDate($post->post_at, 'm/d/Y') : null, ['class' => 'form-control datepicker text-center', 'id' => 'posted_at_date']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Post At Time') !!}
            {!! Form::text('post_at_time', (isset($post)) ? convertDate($post->post_at, 'h:i A') : null, ['class' => 'form-control clockpicker text-center', 'id' => 'posted_at_time']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Remove At Date') !!}
            {!! Form::text('remove_at_date', (!empty($post->remove_at)) ? convertDate($post->remove_at, 'm/d/Y') : null, ['class' => 'form-control datepicker text-center']) !!}
            <span class="help-block">Date to hide post, leave blank for none</span>
        </div>

        <div class="form-group">
            {!! Form::label('Remove At Time') !!}
            {!! Form::text('remove_at_time', (!empty($post->remove_at)) ? convertDate($post->remove_at, 'h:i A') : null, ['class' => 'form-control clockpicker text-center']) !!}
            <span class="help-block">Time to hide post, leave blank for none</span>
        </div>


        <div class="form-group">
            <div class="checkbox">
                {!! Form::checkbox('is_visible', 1, false) !!} Should it be visible?
            </div>
            <span class="help-block">This will hide the post if not checked</span>
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $submitText }}</button>
                <a class="btn btn-default" href="{{ route('posts') }}">Cancel</a>
            </div>
        </div>
