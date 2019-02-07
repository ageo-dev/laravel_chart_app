@extends('layouts.admin')

@section('content')

@if ($data->schedule_name == 'base')
    @include('admin.common.page_title',['title'=>'Basic schedule registration confirmation'])
@endif
@if ($data->schedule_name == 'custom')
    @include('admin.common.page_title',['title'=>'Custom schedule registration confirmation'])
@endif


@if ($data->schedule_name == 'custom')
    <p class="text-right"><small>Register a custom schedule with ID:{{$data->user_id}}</small></p>
@endif

@if (count($duplications) > 0)

    <div class="panel panel-danger text-center">
        <div class="panel-heading">
            Confirm overwrite
        </div>
        <div class="panel-body">
            <p><small>I will overwrite this schedule, is it OK?</small></p>
            <hr>
            @foreach ($duplications as $duplication)
                @if($data->schedule_name == 'base')
                    <p>{{ $duplication->rank->name }}</p>                    
                @endif
                <p>{{ \Carbon\Carbon::parse($duplication->show_datetime)->format('Y年m月d日') }}</p>
                <p>\{{ $duplication->add_fund }}</p>
                <hr>
            @endforeach
        </div>
    </div>
    
@endif

<form class="form-horizontal list_panel" method="POST" action="{{ route('admin.addScheduleExecute') }}">
    {{ csrf_field() }}
    @if ($data->schedule_name == 'custom')
        <input type="hidden" name="user_id" value="{{$data->user_id}}">
    @endif
    <input type="hidden" name="schedule_name" value="{{$data->schedule_name}}">

    @foreach ($data->rank as $item)
        <input type="hidden" name="rank[]" value="{{$item}}">
    @endforeach
    <input type="hidden" name="show_datetime" value="{{$data->show_datetime}}">
    <input type="hidden" name="add_fund" value="{{$data->add_fund}}">

    <div class="row mt50 mb20">
        <div class="col-xs-8 col-xs-offset-2 text-center">
            <h4>Schedule</h4>
            <h3>{{\Carbon\Carbon::parse($data->show_datetime)->format('m/d/Y')}}</h3>
            @if ($data->schedule_name == 'base')
                <h4>Target category</h4>
                @foreach  ($rankNames as $key => $value)
                    <p>{{$value}}</p>
                @endforeach                        
                <hr>
            @endif    
            <h4>Amount to increase / decrease</h4>
            <h3>\{{$data->add_fund }}</h3>
            <p>We will add this to the total asset value.</p>

            @if ($message != null)
                <div class="alert alert-danger" role="alert">
                    <p>{{$message}}</p>
                </div>
            @endif
            
            <button type="submit" class="btn btn-primary btn-block mt50">Registration</button>

            @if ($data->schedule_name == 'base')
                <a href="{{route('admin.addBaseSchedule')}}">
            @endif
            @if ($data->schedule_name == 'custom')
                <a href="{{route('admin.addCustomSchedule',$data->user_id)}}">
            @endif
                <button type="button" class="btn btn-default btn-block mt20">Cancel</button>
            </a>
        </div>
    </div>
</form>
@endsection