@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            <div class="row">
                血糖统计
            </div>
            @include('statistics.search')
            @if(count($list)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>用户名</th>
                            <th>年龄</th>
                            <th>血糖最高值</th>
                            <th>血糖最低值</th>
                            <th>血糖平均值</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $item)
                            <tr>
                                <td>{{$item['nickname']}}</td>
                                <td>{{$item['age']}}</td>
                                <td>{{$item['max_sugar']}}</td>
                                <td>{{$item['min_sugar']}}</td>
                                <td>{{$item['avg_sugar']}}</td>
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
    <script>
    </script>
@endsection