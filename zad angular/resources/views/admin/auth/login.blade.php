@extends('admin.layout.auth')
@section('body')
<div class="login-box">
  <div class="login-logo">
    <a href="{{ route('front.home') }}"><b>ZAD  Global SChool</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
    {{-- {{ Auth::user()->name }} --}}
    {!! Form::open(['route'=>'admin.login.post']) !!}
      <div class="form-group has-feedback">
      	{!! Form::email('email', '', ['class'=>'form-control', 'placeholder'=>'mail']) !!}
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <p class="text-danger">{{ $errors->first('email') }}</p>
      </div>
      
      <div class="form-group has-feedback">
      {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Password']) !!}
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <p class="text-danger">{{ $errors->first('password') }}</p>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember" value="1"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    {!! Form::close() !!}

   
   
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@endsection