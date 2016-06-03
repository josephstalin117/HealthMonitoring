<div class="form-group">
    <label for="content">私信内容</label>
    <input type="text" id="content" class="form-control">
    <button type="text" id="send" class="btn btn-primary" onclick="send()">发送</button>
</div>

<script>
    function send() {
        var content = $("#content").val();
        $.ajax({
            url: "{{url('/api/message/send/to_user_id')}}" + "/" + {{$to_user_id}} +"/content/" + content,
            dataType: "json",
            method: "get",
            success: function (data) {
                if ("success" == data.status) {
                    $("#content").val('');
                    alert("信息已发送");
                    location.reload();
                } else {
                    alert("发送失败");
                    location.reload();
                }
            }
        });
    }
</script>