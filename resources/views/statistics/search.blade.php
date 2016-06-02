<div class="row" style="margin-top: 10px">
    <form action="" method="get">
        搜索
        <input type="text" name="keyword" value="{{$keyword}}">
        <select name="range" id="range">
            <option value="all">全部</option>
            <option value="30">30岁以下</option>
            <option value="40">30~40岁</option>
            <option value="50">40~50岁</option>
            <option value="60">50~60岁</option>
            <option value="60+">大于60岁</option>
        </select>
        <button type="submit" id="search" class="btn btn-success">确定</button>
    </form>
</div>
<script>
    $(function () {
        $("#range").val("{{$range}}");
   });
</script>