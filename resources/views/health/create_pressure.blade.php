@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            @include('manage.search')
            <form action="{{url('/pressure/store')}}" method="post">
                {!! csrf_field() !!}
                <input type="hidden" name="id" id="id">
                <div class="form-group">
                    <label for="high">高压</label>
                    <input class="form-control" type="number" id="high" name="high"
                           maxlength="11" placeholder="请输入您的高压值">
                </div>
                <div class="form-group">
                    <label for="low">低压</label>
                    <input class="form-control" type="number" name="low" maxlength="5"
                           placeholder="请输入您的低压值">
                </div>
                <div class="row">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
@endsection
