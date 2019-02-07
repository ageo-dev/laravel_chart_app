@extends('layouts.admin')
@section('content')

@include('admin.common.page_title',['title'=>'Additional capital log'])

@include('admin.common.user_prof_top')

@include('admin.common.prof_back')

@include('admin.common.user_prof_panel',['user'=>$user])

<div class="row mt50">
    
    <div class="col-sm-12 mt50">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Reflected date and time</th>
                    <th>Additional amount</th>
                    <th>Additional (registration) date</th>
                </tr>
            </thead>
            <tbody>
                
                @forelse ($chargeLogs as $log)

                    <tr>
                        <td><small>{{\Carbon\Carbon::parse($log->charge_datetime)->format('Y年m月d日')}}</small></td>
                        <td>{{number_format($log->fund)}}</td>
                        <td><small>{{\Carbon\Carbon::parse($log->created_at)->format('Y年m月d日')}}</small></td>
                    </tr>
                    
                @empty
                    <p>No relevant data</p>
                @endforelse
            </tbody>
        </table> 
        {{$chargeLogs->links()}}
    </div>
</div>

@endsection