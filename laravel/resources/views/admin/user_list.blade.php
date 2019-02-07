@extends('layouts.admin') 
@section('content')
@include('admin.common.page_title',['title'=>$listsTitle])
<p class="text-right"><small>Tap or click to show details</small></p>
@isset($allUserIdArray)
    @if (count($lists) > 0 )
        @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
            <form action="{{ route('admin.userBulkEdit') }}" method="post" class="text-right">
                {{ csrf_field() }}
                <input type="hidden" name="id_array"
                    value="@foreach ($allUserIdArray as $value){{$value}}@if (!$loop->last),@endif @endforeach">
                <button class="btn btn-default">Bulk edit of target audience</button>
            </form>
        @endif
    @endif    
@endisset

@if ($type == 'all')
    <div class="row">
        <div class="col-xs-12">
            <div class="text-right">
                <a href="{{ route('admin.userList',['ascOrDesc'=>'asc']) }}">             
                    <button class="btn @if($ascOrDesc == 'asc') btn-info @else btn-default @endif btn-default">Ascending</button>
                </a>
                <a href="{{ route('admin.userList',['ascOrDesc'=>'desc']) }}">                                                             
                    <button class="btn @if($ascOrDesc == 'desc') btn-info @else btn-default @endif">Descending</button>
                </a>
            </div>
        </div>
    </div>    
@endif

@isset($listsCouAll)
    <p>Displaying {{$listsCou}} of {{$listsCouAll}} matches</p>
@endisset

@forelse ($lists as $list)
    <a href="{{ route('admin.userProf',$list) }}">
        @include('admin.common.user_prof_panel',['user'=>$list])
    </a>
@empty
    <p>No corresponding users</p>
@endforelse
@if (empty($appendData)) 
    {{$lists->links()}} 
@else 
    {{$lists->appends($appendData)->links()}} 
@endif
@endsection