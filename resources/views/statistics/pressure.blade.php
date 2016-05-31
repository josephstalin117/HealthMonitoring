@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            @include('statistics.search')
            @if(count($list)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>用户名</th>
                            <th>年龄</th>
                            <th>血压最高值</th>
                            <th>血压最低值</th>
                            <th>高压平均值</th>
                            <th>低压平均值</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $item)
                            <tr>
                                <td>{{$item['nickname']}}</td>
                                <td>年龄待添加</td>
                                <td>{{$item['max_high']}}</td>
                                <td>{{$item['min_low']}}</td>
                                <td>{{$item['avg_high']}}</td>
                                <td>{{$item['avg_low']}}</td>
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