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
                            <th>发送私信</th>
                            <th>取消关注</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($followings as $following)
                            <tr>
                                <td>{{$following->follow_user->profile->nickname}}</td>
                                <td>
                                    <a href="" type="button" data-toggle="modal"
                                       data-target="#{{$following->id}}_pressure"
                                       class="btn btn-success openPressureModal">查看血压</a>
                                </td>
                                <td>
                                    <a href="" type="button" data-toggle="modal"
                                       data-target="#{{$following->id}}_sugar" class="btn btn-success openSugarModal">查看血糖</a>
                                </td>
                                <td>
                                    <a href="" type="button" data-toggle="modal"
                                       data-target="#{{$following->id}}_message" class="btn btn-primary">发送私信</a>
                                </td>
                                <td>
                                    <a href="" type="button" class="btn btn-danger"
                                       onclick="unfollow({{$following->id}})">不再关注</a>
                                </td>
                            </tr>

                            <!--message Modal -->
                            <div class="modal fade" id="{{$following->id}}_message" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                        aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="name"></h4>
                                        </div>
                                        <div class="modal-body">
                                            @include('message.send',['to_user_id'=>$following->follow_user->id])
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--pressure Modal -->
                            <div class="modal fade" id="{{$following->id}}_pressure" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                        aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="name"></h4>
                                        </div>
                                        <div class="modal-body">
                                            @include('health.chart_pressure',['follow_user_id'=>$following->follow_user->id])
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--sugar Modal -->
                            <div class="modal fade" id="{{$following->id}}_sugar" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                        aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="name"></h4>
                                        </div>
                                        <div class="modal-body">
                                            @include('health.chart_sugar',['follow_user_id'=>$following->follow_user->id])
                                        </div>
                                    </div>
                                </div>
                            </div>

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

    <script>
        function unfollow(unfollow_id) {
            if (confirm("是否取消关注")) {
                $.ajax({
                    url: "{{url('/api/unfollow')}}" + "/" + unfollow_id,
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
