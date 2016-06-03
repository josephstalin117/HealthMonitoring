@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            @if(count($lines)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>警戒值</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lines as $line)
                            <tr>
                                <td>{{$line->name}}</td>
                                <td>{{$line->line}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="row">
                    暂无警戒值
                </div>
            @endif
        </div>
    </div>
@endsection