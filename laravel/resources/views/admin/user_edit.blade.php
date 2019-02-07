@extends('layouts.admin') 
@section('content')

@include('admin.common.page_title',['title'=>'Edit user information'])

@include('admin.common.user_prof_top')

@include('admin.common.prof_back')
<div class="text-right mt20 mb20">
    <form id="fund_reset_form" action="{{route('admin.userFundReset',$user)}}" method="post">
            {{ csrf_field() }}
            <button id="fund_reset_btn" type="button" class="btn btn-danger">Overwrite past chart</button>
    </form>
</div>

@include('admin.common.user_prof_panel',['user'=>$user])

<form id="prof_edit" class="form-horizontal list_panel" method="POST" action="{{ route('admin.userEditExecute',$user) }}">
    {{ csrf_field() }} {{ method_field('patch') }}
    <input type="hidden" name="name" value="{{ old('name',$user->name) }}"> 
    <div class="form-group mt50">
        <label class="col-sm-4 control-label">Category</label>
        <div class="col-sm-7">
            <div class="{{ $errors->has('rank_id') ? 'has-error' : '' }}">
                <select name="rank_id" id="rank_id" class="form-control">
                    @for ($i = 0; $i < count($rank); $i++)
                        <option value="{{ $i+1 }}" @if(old('rank_id', $user->rank->id) == $i+1  ) selected @endif>{{ $rank[$i]->name }}</option>
                    @endfor
                </select> 
                @if ($errors->has('rank_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('rank_id') }}</strong>
                    </span>
                @endif
            </div>  
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">password</label>
        <div class="col-sm-7">
            <div class="{{ $errors->has('password') ? 'has-error' : '' }}">
                <input id="password" type="text" class="form-control" placeholder="Please enter the password to re-register" name="password">
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">mail address</label>
        <div class="col-sm-7">
            <div class="{{ $errors->has('email') ? 'has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email',$user->email) }}" required>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Operation start date</label>
        <div class="col-sm-7">
            <div class="{{ $errors->has('start_date') ? 'has-error' : '' }}">
                <input id="start_date" type="date" class="form-control" name="start_date" value="{{ \Carbon\Carbon::parse(old('start_date',$user->start_date))->format('Y-m-d') }}"
                    required>
                @if ($errors->has('start_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('start_date') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Custom automatic increase rate</label>
        <div class="col-sm-7">
            <div class="{{ $errors->has('custom_per') ? 'has-error' : '' }}">
                <input id="custom_per" type="text" class="form-control" name="custom_per" value="{{ old('custom_per',$user->custom_per) }}"
                    required>
                @if ($errors->has('custom_per'))
                    <span class="help-block">
                        <strong>{{ $errors->first('custom_per') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Using Custom Auto Increment Rate</label>
        <div class="col-sm-7">
            <div class="{{ $errors->has('custom_per_flag') ? 'has-error' : '' }}">
                <select name="custom_per_flag" id="custom_per_flag" class="form-control" required>
                    <option value="{{ intval(true) }}" @if(intval(old('custom_per_flag',$user->custom_per_flag))) selected @endif>use</option>
                    <option value="{{ intval(false) }}" @if(!intval(old('custom_per_flag',$user->custom_per_flag))) selected @endif>do not use</option>
                </select>
                @if ($errors->has('custom_per_flag'))
                    <span class="help-block">
                        <strong>{{ $errors->first('custom_per_flag') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Initial investment</label>
        <div class="col-sm-7">
            <div class="{{ $errors->has('first_fund') ? 'has-error' : '' }}">
                <input id="first_fund" type="text" class="form-control" name="first_fund" value="{{ floor(old('first_fund',$user->first_fund)) }}"
                    required>
                @if ($errors->has('first_fund'))
                    <span class="help-block">
                        <strong>{{ $errors->first('first_fund') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Notes</label>
        <div class="col-sm-7">
            <div class="{{ $errors->has('memo') ? 'has-error' : '' }}">
                <textarea name="memo" id="memo" class="form-control" cols="30" rows="3">{{ old('memo',$user->memo) }}</textarea>
                @if ($errors->has('memo'))
                    <span class="help-block">
                        <strong>{{ $errors->first('memo') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group mt50">
        <label class="col-sm-4 control-label"></label>
        <div class="col-sm-7">
            <button type="button" id="prof_edit_btn" class="btn btn-primary btn-block">Edit</button>
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

@endsection