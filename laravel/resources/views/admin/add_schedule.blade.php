@extends('layouts.admin')

@section('content')

@if ($scheduleName == 'base')
    @include('admin.common.page_title',['title'=>'Basic schedule registration'])
@endif
@if ($scheduleName == 'custom')
    @include('admin.common.page_title',['title'=>'Custom schedule registration'])
@endif

@if ($scheduleName == 'custom')
    <p class="text-right"><small>Register a custom schedule with ID:{{$user->id}}.</small></p>
@endif

@if ($scheduleName == 'custom')
    @include('admin.common.user_prof_top')

    @include('admin.common.prof_back')

    @include('admin.common.user_prof_panel',['user'=>$user])
@endif



<form class="form-horizontal list_panel" method="POST" action="{{ route('admin.addScheduleConf') }}">
    {{ csrf_field() }}
    <input type="hidden" name="schedule_name" value="{{$scheduleName}}">
    @if ($scheduleName == 'custom')
        <input type="hidden" name="user_id" value="{{$user->id}}">
        <input type="hidden" name="rank[]" value="{{$user->rank_id}}">
        <input type="hidden" name="user_name" value="{{$user->name}}">
    @endif


    <div class="form-group mt50">
        <label class="col-sm-4 control-label">Schedule</label>
        <div class="col-sm-7">
            <input id="pickadate_show_datetime" class="form-control fieldset__input js__datepicker" type="text" 
                data-value="{{ \Carbon\Carbon::parse(old('date'))->format('Y-m-d') }}"
                required>
            <input id="show_datetime" type="hidden" name="show_datetime" 
                value="{{ \Carbon\Carbon::parse(old('date'))->format('Y-m-d') }}"
                required>
            <div class="{{ $errors->has('show_datetime') ? 'has-error' : '' }}">
                @if ($errors->has('show_datetime'))
                    <span class="help-block">
                        <strong>{{ $errors->first('show_datetime') }}</strong>
                    </span>
                @endif
            </div> 
        </div>
    </div>
        
    @if ($scheduleName == 'base')
        <div class="form-group mt50">
            <label class="col-sm-4 control-label">Target category</label>
            <div class="col-sm-7">
                <div class="{{ $errors->has('rank') ? 'has-error' : '' }}">
                    <select name="rank[]" id="rank" class="form-control" multiple>
                        @for ($i = 0; $i < count($rank); $i++)
                            <option value="{{ $i+1 }}" @if(old('rank') == $i+1  ) selected @endif>{{ $rank[$i]->name }}</option>
                        @endfor
                    </select> 
                    @if ($errors->has('rank'))
                        <span class="help-block">
                            <strong>{{ $errors->first('rank') }}</strong>
                        </span>
                    @endif
                </div>  
            </div>
        </div>
    @endif

    <div class="form-group">
        <label class="col-sm-4 control-label">Amount to increase / decrease</label>
        <div class="col-sm-7">
            <div class="{{ $errors->has('add_fund') ? 'has-error' : '' }}">
                <input class="form-control" type="number" name="add_fund" value="{{ old('add_fund') }}" required>
                @if ($errors->has('add_fund'))
                    <span class="help-block">
                        <strong>{{ $errors->first('add_fund') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group mt50">
        <label class="col-sm-4 control-label"></label>
        <div class="col-sm-7">
            <button type="submit" class="btn btn-primary btn-block">To confirmation screen</button>
        </div>
    </div>
</form>
@endsection