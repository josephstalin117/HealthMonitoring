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
                            <th>姓名</th>
                            <th>内容</th>
                            <th>时间</th>
                            <th>操作</th>
                            <th>删除</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            <tr>
                                <td>{{$message->user->profile->nickname}}</td>
                                <td>{{$message->content}}</td>
                                <td>{{$message->created_at}}</td>
                                @if(0==$message->type)
                                    <td><a href="" type="button" class="btn btn-primary" onclick="approve_follow({{$message->follow_id}})">同意关注请求</a></td>
                                @else
                                    <td><a href="" type="button" data-id="{{$message->id}}"
                                           class="btn btn-default">详情</a></td>
                                @endif
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
        function approve_follow(follow_id) {
            console.log(follow_id);
            $.ajax({
                url: "{{url('/api/follow/approve')}}" + "/" + follow_id,
                dataType: "json",
                method: "get",
                success: function (data) {
                    if ("success" == data.status) {
                        alert("同意对方关注请求");
                    }
                }
            });
        }
    </script>
@endsection