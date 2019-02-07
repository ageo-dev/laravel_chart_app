@extends('layouts.admin') 
@section('content')
@include('admin.common.page_title',['title'=>$listsTitle])

<form id="bulk_update_form" class="form-horizontal list_panel" method="POST" action="{{route('admin.userBulkEditDone')}}">
    {{ csrf_field() }} {{ method_field('patch') }}
    <div class="form-group mt50">
        <label class="col-sm-4 control-label">Category</label>
        <div class="col-sm-7">
            @if ($rankFlag)
                <p class="conf_p red">{{$rankName}}</p>            
            @else
                <p class="conf_p">Do not edit</p>
            @endif
            <input type="hidden" name="rank_id" value="{{$request->rank_id}}">
            <input type="hidden" name="rank_flag" value="{{$rankFlag}}">

        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Operation start date</label>
        <div class="col-sm-7">
            @if ($startDateFlag)
            
                <p class="conf_p red">{{\Carbon\Carbon::parse($request->start_date)->format('Y年m月d日')}}</p>

            @else
                <p class="conf_p">Do not edit</p>
            @endif
            <input type="hidden" name="start_date" value="{{$request->start_date}}">
            <input type="hidden" name="start_date_flag" value="{{$startDateFlag}}">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Custom automatic increase rate</label>
        <div class="col-sm-7">
            @if ($customPerChangeFlag)
            
                <p class="conf_p red">{{$request->custom_per}}％</p>
            
            @else
                <p class="conf_p">Do not edit</p>
            @endif
            <input type="hidden" name="custom_per" value="{{ $request->custom_per }}"
                placeholder="Do not edit" required>
            <input type="hidden" name="custom_per_change_flag" value="{{$customPerChangeFlag}}">

        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Using Custom Auto Increment Rate</label>
        <div class="col-sm-7">
            @if ($customPerUseFlag)
                @if ($request->custom_per_flag == 'yes')
                    <p class="conf_p red">use</p>
                @else
                    <p class="conf_p red">do not use</p>
                @endif
            
            @else
                <p class="conf_p">Do not edit</p>
            @endif
            <input type="hidden" name="custom_per_flag" value="{{$request->custom_per_flag}}">
            <input type="hidden" name="custom_per_use_flag" value="{{$customPerUseFlag}}">

            
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Initial investment</label>
        <div class="col-sm-7">
            @if ($firstFundFlag)
            
                <p class="conf_p red">¥{{number_format($request->first_fund)}}</p>
            
            @else
                <p class="conf_p">Do not edit</p>
            @endif
            <input type="hidden" name="first_fund" value="{{$request->first_fund}}">
            <input type="hidden" name="first_fund_flag" value="{{$firstFundFlag}}">

        </div>
    </div>

    <div class="form-group mt50">
        <label class="col-sm-4 control-label">CS schedule</label>
        <div class="col-sm-7">
            @if ($csFlag)

                <p class="conf_p red">{{\Carbon\Carbon::parse($request->show_datetime)->format('Y年m月d日')}}</p>
            
            @else
                <p class="conf_p">Do not edit</p>
            @endif
            <input type="hidden" name="show_datetime" value="{{$request->show_datetime}}">
            <input type="hidden" name="cs_flag" value="{{$csFlag}}">

        </div>
    </div>
        
    <div class="form-group">
        <label class="col-sm-4 control-label">Amount to increase / decrease</label>
        <div class="col-sm-7">
            @if ($csFlag)

                <p class="conf_p red">¥{{number_format($request->add_fund)}}</p>
            
            @else
                <p class="conf_p">Do not edit</p>
            @endif
            <input type="hidden" name="add_fund" value="{{$request->add_fund}}">

        </div>
    </div>

    <div class="form-group mt50">
        <label class="col-sm-4 control-label"></label>
        <div class="col-sm-7">
                <input type="hidden" name="id_array" value="{{$request->id_array}}">
            <button type="button" id="bulk_send_btn" class="btn btn-primary btn-block">Edit</button>
        </div>
    </div>
</form>
@if (count($csUpdateLists) > 0)
<div class="panel panel-danger text-center">
    <div class="panel-heading">
        About overwriting CS
    </div>
    <div class="panel-body">
        <p><small>Overwrite the CS information of this user</small></p>
        <p><small>* What is displayed is CS information currently being registered</small></p>
        <hr>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Amount to increase / decrease</th>
                    <th>Reflected date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($csUpdateLists as $list)
                    <tr>
                        <td>
                            {{$list->user_id}}
                        </td>
        
                        <td>
                            {{$list->add_fund}}
                        </td>
                        <td>
                            {{\Carbon\Carbon::parse($list->show_datetime)->format('Y年 m月d日')}}
                        </td>
                    </tr>
                @endforeach
        
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection