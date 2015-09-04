<div class="modal fade" id="addLocation" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add a Location</h4>
            </div>
            <div class="modal-body">
                <div id="location-error"></div>
                {!! Form::open(['class' => 'form form-vertical', 'id' => 'add-location-form']) !!}
                <div class="form-group">
                    {!! Form::label('Location') !!}
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Street') !!}
                    {!! Form::text('street', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('City') !!}
                    {!! Form::text('city', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('State') !!}
                    {!! Form::text('state', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Zip Code') !!}
                    {!! Form::text('zip', null, ['class' => 'form-control']) !!}
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button id="location-add-submit" type="button" class="btn btn-primary">Create Location</button>
            </div>
        </div>
    </div>
</div>
