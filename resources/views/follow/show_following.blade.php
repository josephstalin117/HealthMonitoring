@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            @include('follow.search_user')
            @if(count($followings)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>关注的用户名</th>
                            <th>血压</th>
                            <th>血糖</th>
                            <th>发送私信</th>
                            <th>取消关注</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($followings as $following)
                            <tr>
                                <td>{{$following->follow_user->profile->nickname}}</td>
                                <td>
                                    <a href="" type="button" data-id="{{$following->user_id}}" data-toggle="modal"
                                       data-target="#pressure_dialog" class="btn btn-success openModal">查看血压</a>
                                </td>
                                <td>
                                    <a href="" type="button" data-id="{{$following->user_id}}" data-toggle="modal"
                                       data-target="#sugar_dialog" class="btn btn-success openModal">查看血糖</a>
                                </td>
                                <td>
                                    <a href="" type="button" data-id="{{$following->user_id}}" class="btn btn-primary">发送私信</a>
                                </td>
                                <td>
                                    <a href="" type="button" class="btn btn-danger"
                                       onclick="unfollow({{$following->follow_user->id}})">不再关注</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="row">
                    暂无关注用户
                </div>
            @endif
        </div>
    </div>

    <script>
        function unfollow(unfollow_id) {
            if (confirm("是否取消关注")) {
                $.ajax({
                    url: "{{url('/api/unfollow')}}" + "/" + unfollow_id,
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
