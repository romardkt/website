                <div class="form-group">
                    <label class="col-sm-2 control-label" for="email">Email</label>
                    <div class="col-sm-10">
                        {!! Form::email('email', null, ['placeholder' => 'Email Address', 'id' => 'login-email', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="password">Password</label>
                    <div class="col-sm-10">
                        {!! Form::password('password', ['placeholder' => 'Password', 'onkeyup' => 'return submitLoginForm(event);', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">&nbsp;</label>
                    <div class="col-sm-10">
                        <button id="remember-btn" type="button" class="btn btn-success active"><i class="fa fa-fw fa-check"></i></button> Remember Me
                    </div>
                </div>
                {!! Form::hidden('remember', 1, ['id' => 'remember']) !!}
