@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            <div class="row" style="margin-top: 10px">
                <a href="{{url('/message/create')}}" type="button" class="btn btn-success">
                    发送私信
                </a>
            </div>
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
                                    <a href="" type="button" class="btn btn-danger"
                                       onclick="delete_message({{$message->id}})"><i
                                                class="fa fa-btn fa-trash"></i>删除</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $messages->links() !!}
                </div>
            @else
                <div class="row">
                    暂无信息
                </div>
            @endif
        </div>
    </div>
    <script>
        function delete_message(message_id) {
            if (confirm("是否删除此信息")) {
                $.ajax({
                    url: "{{url('/api/message/delete')}}" + "/" + message_id,
                    dataType: "json",
                    method: "get",
                    success: function (data) {
                        if ("success" == data.status) {
                            location.reload();
                        }
                    }
                });
            }
        }
    </script>
@endsection