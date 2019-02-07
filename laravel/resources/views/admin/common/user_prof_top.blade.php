<div class="row">
    <div class="text-right">
        <div class="col-xs-12">
            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 )
                <a href="{{route('admin.userEdit',$user)}}" 
                    @if(\Route::current() -> getName()=='admin.userEdit') style="display:none" @endif>             
                        <button class="btn btn-default prof_top_btn">Edit</button>
                </a>                
            @endif

            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                <a href="{{ route('admin.addFund',$user) }}" 
                    @if(\Route::current() -> getName()=='admin.addFund') style="display:none" @endif>     
                    <button class="btn btn-default prof_top_btn">Capital</button>
                </a>                
            @endif

            <a href="{{ route('admin.showChargeLog',$user) }}" 
                @if(\Route::current() -> getName()=='admin.showChargeLog') style="display:none" @endif>     
                <button class="btn btn-default prof_top_btn">Capital log</button>
            </a>

            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                <a href="{{ route('admin.addCustomSchedule',$user) }}" 
                    @if(\Route::current() -> getName()=='admin.addCustomSchedule') style="display:none" @endif>       
                    <button class="btn btn-default prof_top_btn">Add CS</button>
                </a>                
            @endif
            <a href="{{ route('admin.showLog',$user) }}" 
                    @if(\Route::current() -> getName()=='admin.showLog') style="display:none" @endif>
                <button class="btn btn-default prof_top_btn">Past chart</button>
            </a>
            <a href="{{ route('admin.showFutureLog',$user) }}" 
                    @if(\Route::current() -> getName()=='admin.showFutureLog') style="display:none" @endif>     
                <button class="btn btn-default prof_top_btn">Future chart</button>
            </a>

            @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                <button class="btn btn-danger prof_top_btn del_btn" data-id="{{ $user->id }}">Delete</button>
                <form method="post" action="{{ route('admin.userDelete', $user) }}" id="form_{{ $user->id }}">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                </form>
            @endif
        </div>
    </div>
</div>