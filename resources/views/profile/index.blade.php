@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel-body">
                    @include('common.errors')
                    <form action="{{url('profile/update')}}" method="post">
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
                                   placeholder="请输入您的手机号" required="true"  pattern="^[0-9]{11}$" maxlength="11" value="{{$profile->telephone}}">
                        </div>
                        <div class="form-group">
                            <label for="address">地址</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   placeholder="请输入您的地址" value="{{$profile->address}}">
                        </div>
                        <div class="form-group">
                            <label for="avatar">头像</label>
                            <input type="file" id="avatar">
                            <p class="help-block">上传头像请小于2m</p>
                        </div>
                        <button type="submit" class="btn btn-default">提交</button>
                    </form>
                </div>
            </div>
        </div>
@endsection