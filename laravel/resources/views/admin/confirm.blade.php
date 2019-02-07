@extends('layouts.admin') 
@section('content')

@include('admin.common.page_title',['title'=>$pageTitle])

@include('admin.common.user_prof_top')
@include('admin.common.prof_back')
@include('admin.common.user_prof_panel',['user'=>$user])

@if ($confirm == 'addFund')
    <form class="panel panel-danger text-center" method="POST" action="{{ route('admin.addFundCheck',$user)}}">
        {{ csrf_field() }} 
        {{ method_field('patch') }} 
        <div class="panel-body">
            <p><small>Will adding old capital in the past schedule will rewrite past charts?</small></p>
            <hr>
            <p>{{\Carbon\Carbon::parse($request->charge_datetime)->format('m/d/Y')}}</p>
            <p>\{{number_format($request->add_fund)}}</p>
            <p><small>Add here and rewrite the past chart.</small></p>
        </div>
        <input name="charge_datetime" type="hidden" value="{{$request->charge_datetime}}">
        <input type="hidden" class="form-control list_input" name="add_fund" value="{{$request->add_fund}}">
        <input type="hidden" name="confirm" value="{{intval(true)}}">
        <button class="btn btn-default mb20">ryewrite</button>
    </form>
@endif


@endsection