@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            @include('common.search')
            <div class="row" style="margin-top: 10px">
                <a href="{{url('article/create')}}" class="btn btn-success">创建新文章</a>
            </div>
            @if(count($articles)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>文章名</th>
                            <th>文章内容</th>
                            <th>文章时间</th>
                            <th>修改</th>
                            <th>删除</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td>{{$article->title}}</td>
                                <td>{{$article->content}}</td>
                                <td>{{$article->created_at}}</td>
                                <td>
                                    <a href="{{url('article/update')}}/{{$article->id}}"
                                       type="button"
                                       class="btn btn-primary">修改</a>
                                </td>
                                <td>
                                    <a href="" type="button" onclick="delete_article({{$article->id}})"
                                       class="btn btn-danger"><i class="fa fa-btn fa-trash"></i>删除</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        function delete_article(id) {
            if (confirm("是否删除此文章")) {
                $.ajax({
                    url: "{{url('/api/article/delete')}}" + "/" + id,
                    dataType: "json",
                    method: "get",
                    success: function (data) {
                        if ("success" == data.status) {
                            location.reload();
                        }
                    }
                });
            }
        }
    </script>
@endsection
