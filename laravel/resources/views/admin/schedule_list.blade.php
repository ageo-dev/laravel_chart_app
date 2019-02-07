@extends('layouts.admin')
@section('content')

@if ($scheduleName == 'base')
    @include('admin.common.page_title',['title'=>'Basic schedule list'])         
@endif
@if ($scheduleName == 'custom')
    @include('admin.common.page_title',['title'=>'Custom schedule list'])                                                          
@endif

<div class="row">
    <div class="col-xs-12">
        <div class="text-right">
            @if ($scheduleName == 'base')
                <a href="{{ route('admin.baseScheduleList',['ascOrDesc'=>'asc']) }}">             
            @endif
            @if ($scheduleName == 'custom')
                <a href="{{ route('admin.customScheduleList',['ascOrDesc'=>'asc']) }}">                                                             
            @endif
         
                <button class="btn @if($ascOrDesc == 'asc') btn-info @else btn-default @endif btn-default">Ascending</button>
            </a>

            @if ($scheduleName == 'base')
                <a href="{{ route('admin.baseScheduleList',['ascOrDesc'=>'desc']) }}">             
            @endif
            @if ($scheduleName == 'custom')
                <a href="{{ route('admin.customScheduleList',['ascOrDesc'=>'desc']) }}">                                                             
            @endif
                <button class="btn @if($ascOrDesc == 'desc') btn-info @else btn-default @endif">Descending</button>
            </a>
        </div>
    </div>
</div>    

<div class="row">
    <div class="col-xs-12">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Scheduling ID</th>
                    @if ($scheduleName == 'base')
                        <th>Category</th>
                    @endif
                    @if ($scheduleName == 'custom')
                        <th>User ID</th>
                    @endif
                    <th>Amount to increase / decrease</th>
                    <th>Reflecting day</th>
                    <th>Registered Date</th>

                    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                        <th></th>
                        <th>Delete all</th>                       
                    @endif
 
                </tr>
            </thead>
            <tbody>
                @forelse ($lists as $list)
                    <tr>
                        <td>
                            {{$list->id}}
                        </td>
                        
                        @if ($scheduleName == 'base')
                            <td>
                                {{$list->rank->name}}        
                            </td>
                        @endif

                        @if ($scheduleName == 'custom')
                            <td>
                                {{$list->user_id}}
                            </td>
                        @endif        
                        <td>
                            {{$list->add_fund}}
                        </td>
                        <td>
                            {{\Carbon\Carbon::parse($list->show_datetime)->format('m/d/Y')}}
                        </td>
                        <td>
                            {{\Carbon\Carbon::parse($list->updated_at)->format('m/d/Y  h:m')}}
                        </td>

                        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                            <td>
                                <button class="btn btn-danger btn-xs del_btn" data-id="{{ $list->id }}">Delete</button>
                                <form method="post" action="{{ route('admin.scheduleDelete') }}" id="form_{{ $list->id }}">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <input type="hidden" name="schedule_id" value="{{$list->id}}">
                                    <input type="hidden" name="page_num" value="{{$pageNum}}">
                                    <input type="hidden" name="asc_or_desc" value="{{$ascOrDesc}}">
                                    @if ($scheduleName == 'base')
                                        <input type="hidden" name="schedule_name" value="base">                                   
                                    @endif
                                    @if ($scheduleName == 'custom')
                                        <input type="hidden" name="schedule_name" value="custom">                                                                     
                                    @endif
                                </form> 
                            </td>
                            <td>
                                <input type="checkbox" id="del_array{{$list->id}}" class="form-control del_array" data-id="{{ $list->id }}">
                            </td>                            
                        @endif

                    </tr>
                @empty
                    <p>No applicable schedule</p>
                @endforelse
        
            </tbody>
        
            <form action="{{ route('admin.scheduleArrayDelete') }}" id="form_del_array" method="post">
                {{ csrf_field() }}
                {{ method_field('delete') }}
                <input type="hidden" name="del_array" id="form_data_array" value="">
                <input type="hidden" name="page_num" value="{{$pageNum}}">
                <input type="hidden" name="asc_or_desc" value="{{$ascOrDesc}}">
                @if ($scheduleName == 'base')
                    <input type="hidden" name="schedule_name" value="base">                                   
                @endif
                @if ($scheduleName == 'custom')
                    <input type="hidden" name="schedule_name" value="custom">                                                                     
                @endif
            </form>
        </table>
    </div>
</div>
<div class="row">
    @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 )
        <div class="col-xs-12 text-right">
            @if (count($lists) > 0)            
                <button class="btn btn-default del_array_check_btn">Select all</button>
                <button class="btn btn-danger del_array_btn">Delete collectively</button>
            @endif
        </div>        
    @endif

    <div class="col-xs-12">
            {{$lists->links()}}
    </div>
</div>
@endsection