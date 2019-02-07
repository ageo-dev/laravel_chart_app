@extends('layouts.admin') 

@section('content')

@include('admin.common.page_title',['title'=>'Add user'])

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    {{-- <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">name</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"
                                required autofocus>

                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div> --}}
                    <input id="name" type="hidden" class="form-control" name="name" value="anonymous">

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">mail address</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                                required>

                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">password</label>

                        <div class="col-md-6">
                            <input id="password" type="text" class="form-control" name="password" required>
                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('first_fund') ? ' has-error' : '' }}">
                        <label for="first_fund" class="col-md-4 control-label">Capital (principal)</label>

                        <div class="col-md-6">
                            <input id="first_fund" type="text" class="form-control" name="first_fund" value="{{ old('first_fund') }}"
                                required autofocus>

                            @if ($errors->has('first_fund'))
                            <span class="help-block">
                                <strong>{{ $errors->first('first_fund') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
                        <label for="start_date" class="col-md-4 control-label">Operation start date</label>

                        <div class="col-md-6">
                            <input id="start_date" type="date" class="form-control" name="start_date" value="{{ \Carbon\Carbon::parse(old('start_date'))->format('Y-m-d') }}"
                                required autofocus>

                            @if ($errors->has('start_date'))
                            <span class="help-block">
                                <strong>{{ $errors->first('start_date') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                Add
                            </button>
                        </div>
                    </div>
                    <hr class="mt20">
                    <div class="row">
                        <div class="col-xs-8 col-xs-offset-2">
                            <p class="mt20"><small>To prevent information leakage, passwords are encrypted in the database and stored.</small></p>
                            <p class="mt20"><small>Encrypted passwords can not be restored.</small></p>
                            <p class="mt20"><small>If the user or administrator forgets the password, please log in to the administration screen with account with administrative authority and reset the password.</small></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection