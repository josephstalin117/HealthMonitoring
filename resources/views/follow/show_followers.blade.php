@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            @include('follow.search_user')
            @if(count($followers)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>关注我的用户名</th>
                            <th>发送私信</th>
                            <th>取消关注</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($followers as $follower)
                            <tr>
                                <td>{{$follower->user->profile->nickname}}</td>
                                <td>
                                    <a href="" type="button" class="btn btn-primary">发送私信</a>
                                </td>
                                <td>
                                    <a href="" type="button" data-id="{{$follower->id}}" data-toggle="modal"
                                       data-target="#unfollow_dialog" class="btn btn-danger openModal">不再让他关注</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="row">
                    暂无关注用户
                </div>
            @endif
        </div>
    </div>


@endsection
