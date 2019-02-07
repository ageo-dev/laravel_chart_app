@extends('layouts.admin') 
@section('content')
@include('admin.common.page_title',['title'=>$listsTitle])
<p class="text-right"><small>Please enter only the items to be edited</small></p>
@php
    if(old('id_array')){
        $idArray = explode(',',old('id_array'));
    }
@endphp
<p class="text-right"><small>Edit {{count($idArray)}} ​​cases</small></p>
<div class="list_panel">
    <form id="prof_edit" class="form-horizontal" method="POST" action="{{route('admin.userBulkEditCheck')}}">
        {{ csrf_field() }}
        <div class="form-group mt50">
            <label class="col-sm-4 control-label">Category</label>
            <div class="col-sm-7">
                <div class="{{ $errors->has('rank_id') ? 'has-error' : '' }}">
                    <select name="rank_id" id="rank_id" class="form-control">
                        <option value="0">Do not edit</option>
                        @for ($i = 0; $i < count($rank); $i++)
                            <option value="{{ $i+1 }}" @if(old('rank_id') == $i+1  ) selected @endif>{{ $rank[$i]->name }}</option>
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
            <label class="col-sm-4 control-label">Operation start date</label>
            <div class="col-sm-7">
                <div class="{{ $errors->has('start_date') ? 'has-error' : '' }}">
                    <input id="start_date" type="date" class="form-control" name="start_date" value="{{ old('start_date') }}">
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
                    <input id="custom_per" type="text" class="form-control" name="custom_per" value="{{ old('custom_per') }}"
                    placeholder="Do not edit" required>
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
                        <option value="non">Do not edit</option>
                        <option value="yes" @if(old('custom_per_flag') == 'yes') selected @endif>use</option>
                        <option value="no" @if(old('custom_per_flag') == 'no') selected @endif>do not use</option>
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
                    <input id="first_fund" type="text" class="form-control" name="first_fund" value="{{ old('first_fund') }}"
                    placeholder="Do not edit" required>
                    @if ($errors->has('first_fund'))
                        <span class="help-block">
                            <strong>{{ $errors->first('first_fund') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group mt50">
            <label class="col-sm-4 control-label">CS schedule</label>
            <div class="col-sm-7">
                <input type="date" name="show_datetime"  class="form-control"
                    value="{{ old('show_datetime') }}" required>
                <div class="{{ $errors->has('show_datetime') ? 'has-error' : '' }}">
                    @if ($errors->has('show_datetime'))
                        <span class="help-block">
                            <strong>{{ $errors->first('show_datetime') }}</strong>
                        </span>
                    @endif
                </div> 
            </div>
        </div>
            
        <div class="form-group">
            <label class="col-sm-4 control-label">Amount to increase / decrease</label>
            <div class="col-sm-7">
                <div class="{{ $errors->has('add_fund') ? 'has-error' : '' }}">
                    <input class="form-control" type="number" name="add_fund" value="{{ old('add_fund') }}"
                        placeholder="Do not edit" required>
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
                <input type="hidden" name="id_array" value="{{old('id_array',$idArrayPlane)}}">
                <button type="button" id="prof_edit_btn" class="btn btn-primary btn-block">To confirmation screen</button>
            </div>
        </div>
    </form>
    <form id="bulk_del_form" class="form-horizontal" method="POST" action="{{route('admin.userBulkEditCheck')}}">
        {{ csrf_field() }} {{ method_field('delete') }}
        <div class="form-group mt50">
            <label class="col-sm-4 control-label"></label>
            <div class="col-sm-7">
                <input type="hidden" name="id_array" value="{{old('id_array',$idArrayPlane)}}">
                <button type="button" id="prof_bulk_del_btn" class="btn btn-danger btn-block">Delete all</button>
            </div>
        </div>
    </form>
</div>


<div class="panel panel-danger text-center">
    <div class="panel-heading">
            Target user ID list
    </div>
    <div class="panel-body">
        <p><small>Edit this user's information, CS</small></p>
        <hr>
        @foreach ($idArray as $value)
            <p>{{$value}}</p>
        @endforeach
    </div>
</div>

@endsection