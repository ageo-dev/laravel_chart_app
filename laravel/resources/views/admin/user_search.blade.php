@extends('layouts.admin') 
@section('content')

@include('admin.common.page_title',['title'=>'User search'])

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="panel panel-default">
            <div class="panel-body">
                <form id="user_search_form" class="form-horizontal" method="GET" action="{{ route('admin.userSearch',['ascOrDesc'=>'desc']) }}">
                    {{ csrf_field() }}

                    {{-- <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-sm-4 control-label">User name</label>

                        <div class="col-sm-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">                                
                            <small>* Multiple searches can be performed at the comma "," delimiter</small>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr> --}}

                    <div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
                        <label for="id" class="col-sm-4 control-label">User ID</label>

                        <div class="col-sm-6">
                            <input id="id" type="text" class="form-control" name="id" value="{{ old('id') }}">                                
                            <small>* Multiple searches can be performed at the comma "," delimiter</small>
                            @if ($errors->has('id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-sm-4 control-label">mail address</label>

                        <div class="col-sm-6">
                            <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}">
                            <small>* Multiple searches can be performed at the comma "," delimiter</small>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span> 
                            @endif
                        </div>
                    </div>
                    
                    <hr>

                    <div class="form-group{{ $errors->has('first_fund_s') ? ' has-error' : '' }}{{ $errors->has('first_fund_m') ? ' has-error' : '' }}">
                        <label for="first_fund_s" class="col-sm-4 control-label">Scope of capital (principal)</label>

                        <div class="col-sm-6">
                            <input id="first_fund_s" type="text" class="form-control" name="first_fund_s" value="{{ old('first_fund_s') }}"
                                autofocus> 
                            <p>From</p>
                            <input id="first_fund_m" type="text" class="form-control" name="first_fund_m" value="{{ old('first_fund_m') }}"
                                autofocus> 
                            @if ($errors->has('first_fund_s'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('first_fund_s') }}</strong>
                                </span>
                            @endif
                            @if ($errors->has('first_fund_m'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('first_fund_m') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <hr>

                    <div class="form-group{{ $errors->has('now_fund_s') ? ' has-error' : '' }}{{ $errors->has('now_fund_m') ? ' has-error' : '' }}">
                        <label for="now_fund_s" class="col-sm-4 control-label">Current capital range</label>

                        <div class="col-sm-6">
                            <input id="now_fund_s" type="text" class="form-control" name="now_fund_s" value="{{ old('now_fund_s') }}"
                                autofocus> 
                            <p>From</p>
                            <input id="now_fund_m" type="text" class="form-control" name="now_fund_m" value="{{ old('now_fund_m') }}"
                                autofocus> 
                            @if ($errors->has('now_fund_s'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('now_fund_s') }}</strong>
                                </span>
                            @endif
                            @if ($errors->has('now_fund_m'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('now_fund_m') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <hr>


                    <div class="form-group{{ $errors->has('investment_count_s') ? ' has-error' : '' }}{{ $errors->has('investment_count_m') ? ' has-error' : '' }}">
                        <label for="investment_count_s" class="col-sm-4 control-label">Number of additional capital</label>

                        <div class="col-sm-6">
                            <input id="investment_count_s" type="text" class="form-control" name="investment_count_s" value="{{ old('investment_count_s') }}"
                                autofocus> 
                            <p>From</p>
                            <input id="investment_count_m" type="text" class="form-control" name="investment_count_m" value="{{ old('investment_count_m') }}"
                                autofocus> 
                            @if ($errors->has('investment_count_s'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('investment_count_s') }}</strong>
                                </span>
                            @endif
                            @if ($errors->has('investment_count_m'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('investment_count_m') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <hr>

                    <div class="form-group{{ $errors->has('rank_id') ? ' has-error' : '' }}">
                        <label for="rank_id" class="col-sm-4 control-label">User category</label>

                        <div class="col-sm-6">
                            <select name="rank_id[]" id="rank_id" class="form-control" autofocus multiple>
                                @for ($i = 0; $i < count($rank); $i++)
                                    <option value="{{ $i+1 }}" @if(old('rank_id') == $i+1  ) selected @endif>{{ $rank[$i]->name }}</option>
                                @endfor
                            </select> 
                            <small>※ You can specify more than one</small>
                            @if ($errors->has('rank_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('rank_id') }}</strong>
                                </span> 
                            @endif
                        </div>
                    </div>

                    <hr>
                    <div class="form-group{{ $errors->has('create_date_s') ? ' has-error' : '' }}{{ $errors->has('create_date_m') ? ' has-error' : '' }}">
                        <label for="create_date_s" class="col-sm-4 control-label">Registration date range</label>

                        <div class="col-sm-6">
                            <input id="create_date_s" type="text" class="form-control fieldset__input js__datepicker" name="create_date_s" data-value="{{ \Carbon\Carbon::parse(old('create_date_s','2018-01-01 00:00:00'))->format('Y-m-d') }}"
                                autofocus>
                            <p>From</p>
                            <input id="create_date_m" type="text" class="form-control fieldset__input js__datepicker" name="create_date_m" data-value="{{ \Carbon\Carbon::parse(old('create_date_m'))->format('Y-m-d') }}"
                                autofocus>
                            @if ($errors->has('create_date_s'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('create_date_s') }}</strong>
                                </span>
                            @endif
                            @if ($errors->has('create_date_m'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('create_date_m') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-group{{ $errors->has('start_date_s') ? ' has-error' : '' }}{{ $errors->has('start_date_m') ? ' has-error' : '' }}">
                        <label for="start_date_s" class="col-sm-4 control-label">Operation start date range</label>

                        <div class="col-sm-6">
                            <input id="start_date_s" type="text" class="form-control fieldset__input js__datepicker" name="start_date_s" data-value="{{ \Carbon\Carbon::parse(old('start_date_s','2018-01-01 00:00:00'))->format('Y-m-d') }}"
                                autofocus>
                            <p>From</p>
                            <input id="start_date_m" type="text" class="form-control fieldset__input js__datepicker" name="start_date_m" data-value="{{ \Carbon\Carbon::parse(old('start_date_m'))->format('Y-m-d') }}"
                                autofocus>
                            @if ($errors->has('start_date_s'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('start_date_s') }}</strong>
                                </span>
                            @endif
                            @if ($errors->has('start_date_m'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('start_date_m') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-group{{ $errors->has('custom_per_s') ? ' has-error' : '' }}{{ $errors->has('custom_per_m') ? ' has-error' : '' }}">
                        <label for="custom_per_s" class="col-sm-4 control-label">Custom automatic increase rate (%) range</label>

                        <div class="col-sm-6">
                            <input id="custom_per_s" type="text" class="form-control" name="custom_per_s" value="{{ old('custom_per_s') }}"
                                autofocus>
                            <p>From</p>
                            <input id="custom_per_m" type="text" class="form-control" name="custom_per_m" value="{{ old('custom_per_m') }}"
                                autofocus>
                            @if ($errors->has('custom_per_s'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('custom_per_s') }}</strong>
                                </span>
                            @endif
                            @if ($errors->has('custom_per_m'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('custom_per_m') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-group{{ $errors->has('custom_per_flag') ? ' has-error' : '' }}">
                        <label for="custom_per_flag" class="col-sm-4 control-label">Using Custom Auto Increment Rate</label>

                        <div class="col-sm-6">
                            <select name="custom_per_flag" id="custom_per_flag" class="form-control" autofocus>
                                <option value="all" @if(old('custom_per_flag') == 'all' ) selected @endif>Both userd and unused</option>
                                <option value="{{ intval(false) }}" 
                                    @if(old('custom_per_flag')==intval(false) 
                                        && old('custom_per_flag') != null 
                                        && old('custom_per_flag') != 'all') selected @endif>Use default auto rate of increase</option>
                                <option value="{{ intval(true) }}" @if(old('custom_per_flag')==intval(true)) selected @endif>Use custom automatic growth rate</option>
                            </select> 
                            @if ($errors->has('custom_per_flag'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('custom_per_flag') }}</strong>
                                </span> 
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="form-group{{ $errors->has('memo') ? ' has-error' : '' }}">
                        <label for="memo" class="col-sm-4 control-label">Note</label>

                        <div class="col-sm-6">
                            <textarea name="memo" id="role" class="form-control" cols="30" rows="3">{{ old('memo') }}</textarea>
                            <small>* Multiple searches can be performed at the comma "," delimiter</small>
                            @if ($errors->has('memo'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('memo') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('asc_or_desc') ? ' has-error' : '' }}">
                        <label for="asc_or_desc" class="col-sm-4 control-label">Display order (registration date)</label>

                        <div class="col-sm-6">
                            <select name="asc_or_desc" id="asc_or_desc" class="form-control" autofocus>
                                <option value="desc" @if(old('asc_or_desc') == 'desc' ) selected @endif>Registration order(descending)</option>
                                <option value="asc" @if(old('asc_or_desc') == 'asc' ) selected @endif>Registration order(ascending)</option>
                                <option value="start_date_desc" @if(old('asc_or_desc') == 'start_date_desc' ) selected @endif>Operation start date(descending)</option>
                                <option value="start_date_asc" @if(old('asc_or_desc') == 'start_date_asc' ) selected @endif>Operation start date(ascending)</option>
                                <option value="investment_count_desc" @if(old('asc_or_desc') == 'investment_count_desc' ) selected @endif>Capital adition order(descending)</option>
                                <option value="investment_count_asc" @if(old('asc_or_desc') == 'investment_count_asc' ) selected @endif>Capital adition order(ascending)</option>
                                <option value="custom_per_desc" @if(old('asc_or_desc') == 'custom_per_desc' ) selected @endif>Custom automatic increase rate(descending)</option>
                                <option value="custom_per_asc" @if(old('asc_or_desc') == 'custom_per_asc' ) selected @endif>Custom automatic increase rate(ascending)</option>
                            </select> 
                            @if ($errors->has('asc_or_desc'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('asc_or_desc') }}</strong>
                                </span> 
                            @endif
                        </div>
                    </div>
                    <hr>

                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-4">
                            <button id="user_search_btn" type="button" class="btn btn-primary btn-block">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection