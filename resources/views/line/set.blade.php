@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            <div class="form-group">
                <label for="pressure_high">高压值</label>
                <input class="form-control" type="number" id="pressure_high" maxlength="11">
                <label for="pressure_low">低压值</label>
                <input class="form-control" type="number" id="pressure_low" maxlength="11">
                <label for="sugar">血糖</label>
                <input class="form-control" type="number" id="sugar" maxlength="11">
            </div>
            <div class="row">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <a class="btn btn-primary" onclick="save()">保存</a>
            </div>
        </div>
    </div>

    <script>

        $(function () {
            $.get("{{url('api/line/get')}}", function (data) {
                if ("success" == data.status) {
                    $("#pressure_high").val(data.pressure_high);
                    $("#pressure_low").val(data.pressure_low);
                    $("#sugar").val(data.sugar);
                }
            });

        });

        function save() {
            $.get("{{url('api/line/pressure/high')}}" + "/" + $("#pressure_high").val());
            $.get("{{url('api/line/pressure/low')}}" + "/" + $("#pressure_low").val());
            $.get("{{url('api/line/sugar')}}" + "/" + $("#sugar").val());
            alert("保存成功");
        }

    </script>
@endsection