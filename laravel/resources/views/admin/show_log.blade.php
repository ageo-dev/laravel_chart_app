@extends('layouts.admin')
@section('content')

@include('admin.common.page_title',['title'=>'Past Chart'])

@include('admin.common.user_prof_top')

@include('admin.common.prof_back')

@include('admin.common.user_prof_panel',['user'=>$user])

<div class="row mt50">
    
    @if ($chart == 'bar')
        <div class="row">
            <div id="chart_bar_content" class="col-xs-12" style="padding:0px">
                <canvas id="chart_bar" height="450" width="600"></canvas>
            </div>        
        </div>
        <div class="row mt20">
            <div class="col-xs-6 text-right" style="padding:0px">
                <button class="btn btn-default chart_change_btn_l">
                    <img src="{{asset('img/pie-chart.svg')}}" >
                </button>
            </div>
            <div class="col-xs-6 text-left" style="padding:0px">
                <button class="btn btn-default chart_change_btn_r chat_btn_active">
                    <img src="{{asset('img/bar-chart.svg')}}" style="width:25px;">
                </button>
            </div>
        </div>
    @endif

   
    <div class="col-sm-12 col-md-4 mb20">
        @if ($chart == 'circle')
            <div class="row">
                <div id="chart_circle_content" class="col-xs-12" style="padding:0px;">
                    <canvas id="chart_circle" height="450" width="600"></canvas>
                </div>
            </div>
            <div class="row mt20">
                <div class="col-xs-6 text-right" style="padding:0px">
                    <button class="btn btn-default chart_change_btn_l chat_btn_active">
                        <img src="{{asset('img/pie-chart.svg')}}" >
                    </button>
                </div>
                <div class="col-xs-6 text-left" style="padding:0px">
                    <button class="btn btn-default chart_change_btn_r">
                        <img src="{{asset('img/bar-chart.svg')}}" style="width:25px;">
                    </button>
                </div>
            </div>
        @endif
        
        
        @php
            $chargeArray = [];
        @endphp

        
        @if (count($chargeLogs) > 0)
            <h5 class="text-center mt50">Additional capital log</h5>
            <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Additional Amount</th>
                </tr>
            </thead>
            <tbody>   
                
        @endif

        @forelse ($chargeLogs as $chargeLog)

            <tr>
                <td><small>{{\Carbon\Carbon::parse($chargeLog->charge_datetime)->format('m/d/Y')}}</small></td>
                <td>{{number_format($chargeLog->fund)}}</td>
            </tr>

            @php
                if(array_key_exists(\Carbon\Carbon::parse($chargeLog->charge_datetime)->format('m/d/Y'),$chargeArray)){
                    $chargeArray[\Carbon\Carbon::parse($chargeLog->charge_datetime)->format('m/d/Y')] += $chargeLog->fund;
                }else{
                    $chargeArray[\Carbon\Carbon::parse($chargeLog->charge_datetime)->format('m/d/Y')] = $chargeLog->fund;
                }
            @endphp                         
                
        @empty
            <p class="text-center mt12">There is no additional capital for this month</p>
        @endforelse
        
        @if (count($chargeLogs) > 0)
                </tbody>
            </table>   
        @endif
    </div>      

    <div class="col-sm-12 mt50 col-md-8">
        <p>Show another month</p>
        <form id="chart_form" action="{{route('admin.logSearch')}}" method="post">
            {{ csrf_field() }} 
            <div class="{{ $errors->has('log_year') ? 'has-error' : '' }}">
                @if ($errors->has('log_year'))
                    <span class="help-block">
                        <strong>{{ $errors->first('log_year') }}</strong>
                    </span>
                @endif
            </div>
            <div class="{{ $errors->has('log_month') ? 'has-error' : '' }}">
                @if ($errors->has('log_month'))
                    <span class="help-block">
                        <strong>{{ $errors->first('log_month') }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-xs-4">
                <input name="log_year" type="number" min="2018" class="form-control" 
                    value="{{old('log_year',$logYear)}}" 
                    required>
            </div>
            <div class="col-xs-4">
                <input name="log_month" type="number" min="1" max="12" class="form-control" 
                    value="{{old('log_month',$logMonth)}}"
                    required>
            </div>
            <div class="col-xs-4">
                <input type="hidden" name="user_id" value="{{$user->id}}">
                <input type="hidden" id="chart_type" name="chart" value="{{$chart}}">
                <button class="btn btn-default">display</button>
            </div>                                    
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Asset</th>
                    <th>Previous day difference</th>
                    <th>Add capital</th>
                    <th>Total capital</th>
                    <th>Total difference</th>
                    <th>type</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $oldFund = null;
                    $difference = null;
                @endphp

                @if ($beforeDayLog == null)
                    
                    @php
                        $oldFund = $startDayLog->fund;
                    @endphp
                
                @else

                    @php
                        $oldFund = $beforeDayLog->fund;
                    @endphp

                @endif
                
                @forelse ($logs as $log)
                
                    @php
                        if(isset($oldFund)){
                            $difference = $log->fund - $oldFund;
                        }
                        $oldFund = $log->fund;
                    @endphp

                    <tr>
                        <td><small>{{\Carbon\Carbon::parse($log->show_datetime)->format('m/d/Y')}}</small></td>
                        <td>{{number_format($log->fund)}}</td>
                        <td>{{number_format($difference)}}</td>
                        <td>
                            @if (array_key_exists(\Carbon\Carbon::parse($log->show_datetime)->format('m/d/Y'),$chargeArray))
                                {{number_format($chargeArray[\Carbon\Carbon::parse($log->show_datetime)->format('m/d/Y')])}}
                            @else
                                0 
                            @endif
                        </td>
                        <td>{{number_format($log->base_fund)}}</td>
                        <td>{{number_format($log->fund - $log->base_fund)}}</td>
                        <td>
                            <small>
                                @if ($log->type == 'cs')
                                    CS
                                @endif
                                @if ($log->type == 'bs')
                                    BS
                                @endif
                                @if ($log->type == 'auto')
                                    A
                                @endif
                                @if ($log->type == 'auto_custom')
                                    CA
                                @endif
                                @if ($log->type == 'start')
                                    Operation start
                            @endif
                            </small>
                        </td>
                    </tr>
                    
                @empty
                    <p class="padt50">No relevant data</p>
                @endforelse
            </tbody>
        </table> 
        <div class="mt50">
            @isset($updateDate)
                @if (\Carbon\Carbon::now() >= \Carbon\Carbon::Parse($updateDate))
                    <p class="mb20 red">Today is already open to the public</p>
                @else
                    <p class="mb20">Today is not yet open to the public</p>                
                @endif
            @endisset
            <p>About type</p>
            <p><small>【A】Automatic increase&nbsp;&nbsp;【CA】 Custom automatic increase&nbsp;&nbsp;【BS】 Basic Schedule&nbsp;&nbsp;【CS】 Custom schedule</small></p>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

@if ($chart == 'circle')

    <script>
        var doughnutData = [
            {
                value: @if($user->now_fund - $user->total_fund > 0 ) {{$user->now_fund - $user->total_fund}} @else 0 @endif,
                color:"#00b7ee",
                highlight: "#69cfee",
                label: "Profit "
            },
            {
                value: {{$user->total_fund}},
                color: "#e2e2e2",
                highlight: "#d1d1d1",
                label: "Capital "
            },
        ];
    
    
        window.onload = function(){
            var ctx = document.getElementById("chart_circle").getContext("2d");
            window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {
                responsive : true
            });
        }
    
    </script>

@endif

@if ($chart == 'bar')

    <script>
        var lineChartData = {
            labels : [
                @foreach ($logs as $log)
                    "{{\Carbon\Carbon::parse($log->show_datetime)->format('d日')}}",
                @endforeach
                ],
                datasets : [{
                    label: "",
                    fillColor : /*"#f2dae8"*/"rgba(103, 214, 214, 1)",
                    strokeColor : /*"#dd9cb4"*/"rgba(221,156,180,0.6)",
                    pointColor : /*"#dd9cb4"*/"rgba(221,156,180,0.6)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : /*"#dd9cb4"*/"rgba(221,156,180,0.6)",
                    data : [
                        @foreach ($logs as $log)
                            {{$log->fund}},
                        @endforeach
                    ]
                }
            ]
        };

        var ctx = document.getElementById("chart_bar").getContext("2d");
        window.myLine = new Chart(ctx).Line(lineChartData, {
            responsive: true
        });
    </script>
@endif

@endsection