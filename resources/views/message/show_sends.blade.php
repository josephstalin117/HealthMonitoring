@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            @if(count($messages)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>接收人姓名</th>
                            <th>内容</th>
                            <th>时间</th>
                            <th>操作</th>
                            <th>删除</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            <tr>

                                <td>{{$message->to_user->profile->nickname}}</td>
                                <td>{{$message->content}}</td>
                                <td>{{$message->created_at}}</td>
                                <td><a href="" type="button" data-id="{{$message->id}}"
                                       class="btn btn-default">详情</a></td>
                                <td>
                                    <a href="" type="button" data-id="{{$message->id}}" class="btn btn-danger"><i
                                                class="fa fa-btn fa-trash"></i>删除</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="row">
                    暂无信息
                </div>
            @endif
        </div>
    </div>
    <script>
    </script>
@endsection