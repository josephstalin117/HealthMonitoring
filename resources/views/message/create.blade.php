@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')

            <div class="form-group">
                <div class="input-group">
                    <label for="search_user">搜索发送者用户</label>
                    <input type="text" class="form-control" id="search_user" placeholder="搜索">
                    <ul class="list-group" id="list_users">
                    </ul>
                </div>
                <label for="user_name">用户</label>
                <input class="form-control" type="text" id="to_user_id" maxlength="11" disabled>
                <label for="content">内容</label>
                <textarea class="form-control" rows="3" id="content"></textarea>
            </div>
            <div class="row">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <a class="btn btn-primary" onclick="send()">发送</a>
            </div>
        </div>
    </div>

    <script>

        $("#search_user").change(function () {
            $.ajax({
                url: "{{url('/api/usermanage/search')}}" + "/" + $("#search_user").val(),
                dataType: "json",
                method: "get",
                success: function (result) {
                    if ("success" == result.status) {
                        $("#list_users").html('');
                        for (var i = 0; i < result.users.length; i++) {
                            $("#list_users").append("<li class='list-group-item' id=" + result.users[i].id + "></li>");
                            $("#" + result.users[i].id).append(result.users[i].nickname);
                            $("#" + result.users[i].id).append("<a class='btn btn-success follow' onclick='select_user(" + result.users[i].id + ")'>选择</a>");
                        }

                    }
                }
            });
        });

        function select_user(to_user_id) {
            $("#to_user_id").val(to_user_id);
        }

        function send() {

            var to_user_id = $("#to_user_id").val();
            var content = $("#content").val();
            $.ajax({
                url: "{{url('/api/message/send/to_user_id')}}" + "/" + to_user_id + "/content/" + content,
                dataType: "json",
                method: "get",
                success: function (data) {
                    if ("success" == data.status) {
                        $("#content").val('');
                        alert("信息已发送");
                    } else {
                        alert("关注失败");

                    }
                }
            });
        }

    </script>
@endsection