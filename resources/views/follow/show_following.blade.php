@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            @include('follow.search_user')
            @if(count($followings)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>关注的用户名</th>
                            <th>血压</th>
                            <th>血糖</th>
                            <th>取消关注</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($followings as $following)
                            <tr>
                                <td>{{$following->user->profile->nickname}}</td>
                                <td>
                                    <a href="" type="button" data-id="{{$following->user_id}}" data-toggle="modal"
                                       data-target="#pressure_dialog" class="btn btn-success openModal">查看血压</a>
                                </td>
                                <td>
                                    <a href="" type="button" data-id="{{$following->user_id}}" data-toggle="modal"
                                       data-target="#sugar_dialog" class="btn btn-success openModal">查看血糖</a>
                                </td>
                                <td>{{$following->created_at}}</td>
                                <td>
                                    <a href="" type="button" data-id="{{$following->id}}" data-toggle="modal"
                                       data-target="#unfollow_dialog" class="btn btn-danger openModal">不再关注</a>
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
    <!--detail Modal -->
    <div class="modal fade" id="detail_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="name"></h4>
                </div>
                <div class="modal-body">
                    <div id="main" style="width: 600px;height:400px;"></div>
                </div>
            </div>
        </div>
    </div>

@endsection
