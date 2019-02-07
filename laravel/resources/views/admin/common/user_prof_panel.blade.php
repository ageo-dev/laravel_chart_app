<div class="row list_panel">
    <div class="col-xs-3">
        <img src="{{asset('img/account.png')}}" class="img-responsive center-block list_img mt12" alt="">
    </div>
    <div class="col-xs-7">
        <p class="name">ID : {{ $user->id }}</p>
        <p class="total">¥{{ number_format( $user->now_fund ) }}</p>
        <p class="plus">L @if ( $user->now_fund > $user->first_fund )
            <span> + 
            @endif

            @if ( $user->now_fund < $user->first_fund )
                <span class="red"> - 
            @endif
             ¥ {{ number_format( $user->now_fund - $user->first_fund ) }} 
             ({{round(($user->now_fund - $user->first_fund)/$user->first_fund * 100, 3)}}%)
             </span>
        </p>
        <p class="plus">Operating from&nbsp;&nbsp;&nbsp;{{\Carbon\Carbon::parse($user->start_date)->format(' m / d  / Y ')}}</p>
        <hr>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p><small>Capital at the start of operation</small></p>
                <p class="profit">¥{{number_format( $user->first_fund )}}</p>
                <p><small>Total investment amount</small></p>
                <p class="profit">¥{{number_format( $user->total_fund )}}</p>
            </div>
            <div class="col-xs-12 col-md-6">
                <p><small>Number of contributions</small></p>
                <p class="profit">{{$user->investment_count}}</p>
                <p><small>Profit</small></p>
                <p class="profit">¥ @if ( ( $user->now_fund - $user->total_fund ) >= 0 )
                    <span>
                    @else
                        <span class="red">
                    @endif
                    {{ number_format( $user->now_fund - $user->total_fund ) }}
                    </span>
                </p>
            </div>
        </div>

        @if(\Route::current()->getName() == 'admin.addFund')
        <p class="set">To subtract from the current amount of money, please give a negative.</p>
        <form class="form-horizontal" method="POST" action="{{ route('admin.addFundCheck',$user) }}">
            {{ csrf_field() }} 
            {{ method_field('patch') }} 
            @if ($errors->has('charge_datetime'))
                <div class="has-error">
                    <span class="help-block">
                        <strong>{{ $errors->first('charge_datetime') }}</strong>
                    </span>
                </div>
            @endif

            {{-- <div class="form-group">
                <div class="col-xs-12 form-inline text-right">
                    <input id='charge_date' type="text" 
                        class="form-control fieldset__input js__datepicker"
                        data-value="{{\Carbon\Carbon::today()}}"> --}}
                    {{-- <input id='charge_time' type="text" 
                        class="form-control fieldset__input js__datepicker"
                        data-value="{{\Carbon\Carbon::now()->format('H:i')}}"> --}}
                    {{-- <input id="charge_datetime" name="charge_datetime" type="hidden" value="{{\Carbon\Carbon::now()}}">
                </div>
            </div> --}}
            <input id='charge_date' type="text" 
                class="form-control fieldset__input js__datepicker list_input_date"
                data-value="{{old('charge_datetime',\Carbon\Carbon::today())}}">
            <input id="charge_datetime" name="charge_datetime" type="hidden" value="{{old('charge_datetime',\Carbon\Carbon::today())}}">
            @if ($errors->has('add_fund'))
                <div class="has-error">
                    <span class="help-block">
                        <strong>{{ $errors->first('add_fund') }}</strong>
                    </span>
                </div>
            @endif

            @if ($errors->has('confirm'))
            <div class="has-error">
                <span class="help-block">
                    <strong>{{ $errors->first('confirm') }}</strong>
                </span>
            </div>
        @endif
            <div class="input-group mt20">
                <input id="add_fund" type="text" class="form-control list_input" name="add_fund" value="{{ old('add_fund') }}" required>
                <input type="hidden" name="confirm" value="{{intval(false)}}">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-info list_button">Add</button>
                </span>
            </div>
        </form>
        @endif

    </div>
</div>