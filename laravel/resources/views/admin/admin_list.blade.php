@extends('layouts.admin') 
@section('content')

@include('admin.common.page_title',['title'=>'Administrator list'])         

<div class="row">
    <div class="col-xs-12">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Administrator ID</th>
                    <th>name</th>
                    <th>Authority</th>
                    <th>Registered Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lists as $list)
                    <tr>
                        <td>
                            {{$list->id}}
                        </td>
                        <td>
                            {{$list->name}}
                        </td>
                        <td>
                            {{$list->role->name}}
                        </td>
                        <td>
                            {{\Carbon\Carbon::parse($list->created_at)->format('m/d/Y  H:i')}}
                        </td>
                        <td>
                            @if ($list->id != 1)
                                <button class="btn btn-danger btn-xs del_btn" data-id="{{ $list->id }}">Delete</button>
                                <form method="post" action="{{ route('admin.adminDelete', $list) }}" id="form_{{ $list->id }}">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <input type="hidden" name="page_num" value="{{$pageNum}}">
                                </form>                                        
                            @endif
                        </td>
                    </tr>
                @empty
                    <p>No administrator</p>
                @endforelse
        
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
            {{$lists->links()}}
    </div>
</div>
@endsection