@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            @include('manage.search')
            <div class="row" style="margin-top: 10px">
                <a href="{{url('/pressure/create')}}" type="button" class="btn btn-success">
                    输入今天的血压吧!!
                </a>
            </div>
            @if(count($pressures)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>高压</th>
                            <th>低压</th>
                            <th>日期</th>
                            <th>删除</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pressures as $pressure)
                            <tr>
                                <td>{{$pressure->high}}</td>
                                <td>{{$pressure->low}}</td>
                                <td>{{$pressure->created_at}}</td>
                                <td>
                                    <a href="" type="button" data-id="{{$pressure->id}}"
                                       data-toggle="modal"
                                       data-target="#delete_dialog" class="btn btn-danger openModal"><i
                                                class="fa fa-btn fa-trash"></i>删除</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="row">
                    暂无数据
                </div>
            @endif
        </div>
    </div>
@endsection