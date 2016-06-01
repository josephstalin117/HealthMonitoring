@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{URL::asset('css/bootstrap-datetimepicker.min.css')}}">
    <script src="{{URL::asset('js/bootstrap-datetimepicker.min.js')}}"></script>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel-body">
                    @include('common.errors')
                    <form action="{{url('profile/update')}}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="nickname">真实姓名</label>
                            <input type="text" class="form-control" id="nickname" name="nickname"
                                   placeholder="输入您的姓名" maxlength="5"
                                   value="{{$profile->nickname}}">
                        </div>
                        <div class="form-group">
                            <label for="telephone">手机</label>
                            <input type="text" class="form-control" id="telephone" name="telephone"
                                   placeholder="请输入您的手机号" required="true" pattern="^[0-9]{11}$" maxlength="11"
                                   value="{{$profile->telephone}}">
                        </div>
                        <div class="form-group">
                            <label for="address">地址</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   placeholder="请输入您的地址" value="{{$profile->address}}">
                        </div>
                        <div class="form-group">
                            <label for="birth_show">出生年月</label>
                            <div class="input-group date form_date col-md-5" data-date="{{$profile->birth}}"
                                 data-date-format="yyyy-mm-dd"
                                 data-link-field="" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="birth" name="birth"
                                       value="{{$profile->birth}}" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span
                                            class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="photo">头像</label>
                            <img src="{{asset($profile->avatar)}}" alt="" class="media-object"
                                 style="width: 120px; height: 120px;">
                            <input type="file" id="photo" name="photo">
                            <p class="help-block">上传头像请小于2m</p>
                        </div>
                        <button type="submit" class="btn btn-default">提交</button>
                    </form>
                </div>
            </div>
        </div>


        <script>
            $('.form_date').datetimepicker({
                language: 'fr',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
            });
        </script>
@endsection
