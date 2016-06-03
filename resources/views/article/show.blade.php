@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            <div class="form-group">
                <label for="">文章名</label>
                <p>{{$article->title}}</p>
            </div>
            <div class="form-group">
                <label for="content">内容</label>
                <p>{{$article->content}}</p>
            </div>
            <a href="{{url('/articles')}}" type="button" class="btn btn-default">返回</a>
            </form>
        </div>
    </div>
@endsection
