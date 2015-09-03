                <div class="form-group">
                    {!! Form::label('Year for the form') !!}
                    {!! Form::select('year', array_combine(range(date('Y') + 1, date('Y') - 1), range(date('Y') + 1, date('Y') - 1)), date('Y'), ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Name of the form') !!}
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    <span class="help-block">Name the form</span>
                </div>

                <div class="form-group">
                    {!! Form::label('Document') !!}
                    {!! Form::file('document', null, ['class' => 'form-control']) !!}
                </div>

                <hr/>

                <div class="form-group">
                    <div class="col-xs-12 col-sm-offset-1 text-center">
                        <button type="submit" class="btn btn-primary">{{ $submitText }}</button>
                        <a class="btn btn-default" href="{{ route('manage_forms') }}">Cancel</a>
                    </div>
                </div>
