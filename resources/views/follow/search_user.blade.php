<div class="row" style="margin-top: 10px">
    <div class="input-group">
        <label for="search_user">搜索想要关注的用户</label>
        <input type="text" class="form-control" id="search_user" placeholder="搜索">
        <ul class="list-group" id="list_users">
        </ul>
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
                        $("#list_users").append("<li class='list-group-item' id=" + result.users[i].user.id + "></li>");
                        $("#" + result.users[i].user.id).append(result.users[i].user.nickname);
                        $("#" + result.users[i].user.id).append("<a class='btn btn-success' data-id=" + result.users[i].user.id + ">关注</a>");
                    }

                }
            }
        });
    });
</script>

