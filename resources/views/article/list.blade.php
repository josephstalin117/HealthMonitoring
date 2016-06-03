@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            @include('common.search')
            @if(count($articles)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>文章名</th>
                            <th>文章时间</th>
                            <th>阅读</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td>{{$article->title}}</td>
                                <td>{{$article->created_at}}</td>
                                <td>
                                    <a href="{{url('article')}}/{{$article->id}}"
                                       type="button"
                                       class="btn btn-primary">阅读</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
