@extends('layouts.admin') 
@section('content')

@include('admin.common.page_title',['title'=>'Detailed information'])

@include('admin.common.user_prof_top')

@include('admin.common.user_prof_panel',['user'=>$user])

<div class="form-horizontal list_panel">
    <div class="form-group mt50">
        <label class="col-sm-4 control-label">category</label>
        <div class="col-sm-7">
                <input type="text" class="form-control" value="{{$user->rank->name}}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">mail address</label>
        <div class="col-sm-7">
                <input type="text" class="form-control" value="{{$user->email}}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Date of registration</label>
        <div class="col-sm-7">
                <input type="text" class="form-control" value="{{\Carbon\Carbon::parse($user->created_at)->format('Y-m-d H:i')}}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Operation start date</label>
        <div class="col-sm-7">
                <input type="text" class="form-control" value="{{\Carbon\Carbon::parse($user->start_date)->format('Y-m-d')}}" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Custom automatic increase rate</label>
        <div class="col-sm-7">
                <input type="text" class="form-control" value="{{$user->custom_per}}%" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Using Custom Auto Increment Rate</label>
        <div class="col-sm-7">
                <input type="text" class="form-control" 
                value="@if(intval($user->custom_per_flag)) use @else do not use @endif" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Initial investment</label>
        <div class="col-sm-7">
                <input type="text" class="form-control" value="¥{{ number_format( $user->first_fund ) }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Number of additional contributions</label>
        <div class="col-sm-7">
                <input type="text" class="form-control" value="{{$user->investment_count}}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Total investment</label>
        <div class="col-sm-7">
                <input type="text" class="form-control" value="¥{{ number_format( $user->total_fund ) }}" readonly>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Notes</label>
        <div class="col-sm-7">
                <textarea name="memo" id="role" class="form-control" cols="30" rows="3" readonly>{{ $user->memo }}</textarea>
        </div>
    </div>
</div>
@endsection