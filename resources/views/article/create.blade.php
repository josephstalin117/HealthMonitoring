@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            <form action="{{url('/article/store')}}" method="post">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="">文章名</label>
                    <input class="form-control" id="title" name="title"
                           placeholder="请输入文章名">
                </div>
                <div class="form-group">
                    <label for="content">内容</label>
                                <textarea type="text" class="form-control" id="content" name="content"
                                          placeholder="请输入内容"></textarea>
                </div>
                <div class="modal-footer">
                    <a href="{{url('/articlemanage')}}" type="button" class="btn btn-default">关闭</a>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
@endsection
