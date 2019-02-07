@extends('layouts.admin')
@section('content')

@include('admin.common.page_title',['title'=>'Future Chart'])

@include('admin.common.user_prof_top')

@include('admin.common.prof_back')

@include('admin.common.user_prof_panel',['user'=>$user])


<div class="row mt50">
    <div class="row">
        <div id="chart_bar_content" class="col-xs-12" style="padding:0px">
            <canvas id="chart_bar" height="450" width="600"></canvas>
        </div>        
    </div>

    <div class="col-xs-12 col-sm-4 mb20">
        <h5 class="text-center mt50">Additional capital log</h5>
        
        @php
            $chargeArray = [];
        @endphp

        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Additional Amount</th>
                </tr>
            </thead>
            <tbody>   

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

            </tbody>
        </table> 
    </div>     
   

    <div class="col-sm-12 mt50 col-md-8">
        <p>Future chart({{$logGetCount}}days)</p>

        <p>Show another month</p>
        <form id="chart_form" action="{{route('admin.getFutureLog',$user)}}" method="post">
            {{ csrf_field() }} 
            <div class="{{ $errors->has('log_get_count') ? 'has-error' : '' }}">
                @if ($errors->has('log_get_count'))
                    <span class="help-block">
                        <strong>{{ $errors->first('log_get_count') }}</strong>
                    </span>
                @endif
            </div>
            <div class="col-xs-4">
                <input name="log_get_count" type="number" min="10" max="1000" class="form-control" 
                    value="{{old('log_get_count',$logGetCount)}}"
                    required>
            </div>
            <div class="col-xs-4">
                <button type="submit" class="btn btn-default">display</button>
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
                        <td>
                            @if ($loop->first) 
                                -
                            @else
                                {{number_format($difference)}}    
                            @endif
                        </td>
                        <td>
                            @if (array_key_exists(\Carbon\Carbon::parse($log->show_datetime)->format('m/d/Y'),$chargeArray))
                                {{number_format($chargeArray[\Carbon\Carbon::parse($log->show_datetime)->format('m/d/Y')])}}
                            @else
                                0 
                            @endif
                        </td>
                        <td>
                            {{number_format($log->base_fund)}}
                        </td>
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
                    <p>No relevant data</p>
                @endforelse
            </tbody>
        </table> 

        <div class="mt50">
                <p>About type</p>
                <p><small>【A】Automatic increase&nbsp;&nbsp;【CA】 Custom automatic increase&nbsp;&nbsp;【BS】 Basic Schedule&nbsp;&nbsp;【CS】 Custom schedule</small></p>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

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

@endsection