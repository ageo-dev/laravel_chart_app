@extends('layouts.admin') 
@section('content')

@include('admin.common.page_title',['title'=>'Add capital'])

@include('admin.common.user_prof_top')
@include('admin.common.prof_back')
@include('admin.common.user_prof_panel',['user'=>$user])

@endsection