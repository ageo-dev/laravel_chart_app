@extends('layouts.admin_login')

@section('content')
<div id="login_body" class="container">
    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3">
            <div class="text-center">
                <div class="logo_title">
                    <p class="top_content_title">For administrators only</p>
                </div>                
                <form class="form" role="form" method="POST" action="{{ route('admin.login') }}">
                    {{ csrf_field() }}
                    <div class="{{ $errors->has('name') ? ' has-error' : '' }}">
                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                        <input class="top_content_input" type="text" name="name" size="30" maxlength="20"
                            placeholder="ID"
                            name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input class="top_content_input" type="password" name="password" size="30" maxlength="20" 
                            placeholder="Password"
                            name="password" required>
                    </div>
                    <div style="display:none">
                        <!-- <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> -->
                        <input id="remember" type="checkbox" name="remember" checked>
                        <label for="remember" class="color_white">remember</label>
                    </div>
                    <div>
                        <button class="btn btn-info top_content_button" type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
