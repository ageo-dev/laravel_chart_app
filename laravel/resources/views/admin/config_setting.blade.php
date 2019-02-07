@extends('layouts.admin') 
@section('content')

@include('admin.common.page_title',['title'=>'Configuration'])

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <ul class="nav nav-tabs">
            <li @if (!$errors->has('rank_name*') && !session('dupValiFlag')) class="active" @endif>
                <a href="#config" data-toggle="tab">Basic setting</a>
            </li>
            <li 
                @if ($errors->has('rank_name*') || session('dupValiFlag')) class="active" @endif><a href="#role" data-toggle="tab">Category name setting</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane @if (!$errors->has('rank_name*') && !session('dupValiFlag')) active @endif " id="config">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('admin.configSetting') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('update_hour') ? ' has-error' : '' }}">
                                <label for="update_hour" class="col-sm-4 control-label">Chart update time</label>

                                <div class="col-sm-6">
                                    <input id="update_hour" type="number" min="0" max="23" class="form-control" name="update_hour" value="{{ floor(old('update_hour',$appConfig->update_hour)) }}" required autofocus>                                
                                    <small>例） Example) Please enter 10 if updating to 10 o'clock, 13 if updating at 13 o'clock.</small>
                                    @if ($errors->has('update_hour'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('update_hour') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            <div class="form-group{{ $errors->has('update_minute') ? ' has-error' : '' }}">
                                <label for="update_minute" class="col-sm-4 control-label">Chart update minute</label>

                                <div class="col-sm-6">
                                    <input id="update_minute" type="number" min="0" max="59" class="form-control" name="update_minute" value="{{ floor(old('update_minute',$appConfig->update_minute)) }}" required>                                
                                    <small>Example) Enter 0 if updating to 0 minutes, 30 when updating to 30 minutes.</small>
                                    @if ($errors->has('update_minute'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('update_minute') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <hr>

                            <div class="form-group{{ $errors->has('default_per') ? ' has-error' : '' }}">
                                <label for="default_per" class="col-sm-4 control-label">Automatic increase rate (%)</label>

                                <div class="col-sm-6">
                                    <input id="default_per" type="number" class="form-control" name="default_per" value="{{ old('default_per',$appConfig->default_per) }}" required>                                
                                    <small>If you enter in minus, subtract the percentage from the capital every day.</small>
                                    @if ($errors->has('default_per'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('default_per') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <hr>

                            <div class="form-group{{ $errors->has('list_paginate') ? ' has-error' : '' }}">
                                <label for="list_paginate" class="col-sm-4 control-label">Number of data reads</label>

                                <div class="col-sm-6">
                                    <input id="list_paginate" type="number" min="0" max="300" class="form-control" name="list_paginate" value="{{ old('list_paginate',$appConfig->list_paginate) }}" required>                                
                                    <small>You can change the number of reads when reading data in the administration screen.</small>
                                    @if ($errors->has('list_paginate'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('list_paginate') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-4">
                                    @if ( session('dupValiFlag'))
                                        <p class="red">It is not possible to register the same category name.</p>
                                    @endif
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Change
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane @if ($errors->has('rank_name*') || session('dupValiFlag')) active @endif" id="role">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('admin.roleSetting') }}">
                            {{ csrf_field() }}

                            @foreach ($ranks as $rank)
                                <div class="form-group{{ $errors->has('rank_name'.$rank->id) ? ' has-error' : '' }}">
                                    <label for="rank_name{{$rank->id}}" class="col-sm-4 control-label">
                                        @if ($rank->id == 1)
                                            Initial category
                                        @else
                                            Category {{$rank->id - 1}}
                                        @endif
                                    </label>

                                    <div class="col-sm-6">
                                        <input id="rank_name{{$rank->id}}" type="text" class="form-control" name="rank_name{{$rank->id}}" value="{{ old('rank_name'.$rank->id,$rank->name) }}" required>                                
                                        @if ($errors->has('rank_name'.$rank->id))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('rank_name'.$rank->id) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <hr>
                            @endforeach

                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-4">
                                    @if ( session('dupValiFlag'))
                                        <p class="red">It is not possible to register the same category name.</p>
                                    @endif
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Change
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection