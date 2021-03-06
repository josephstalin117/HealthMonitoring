<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>健康监测系统</title>

    <!-- Fonts -->

    <!-- Styles -->
    <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/font-awesome.min.css')}}" rel="stylesheet">
    <script src="{{URL::asset('js/jquery.min.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('js/echarts.min.js')}}"></script>

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
<nav class="navbar navbar-collapse navbar-static-top navbar-inverse">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                健康管理
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav nav-pills">
                @if(Auth::check())

                    @if(0==Auth::user()->role)
                        <li><a href="{{ url('/usermanage') }}">用户管理</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                用户健康信息管理 <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/pressure/search') }}">用户血压管理</a></li>
                                <li><a href="{{ url('/sugar/search') }}">用户血糖管理</a></li>
                                <li><a href="{{ url('/line/set') }}">警戒线管理</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                大数据统计 <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/statistics/pressure') }}">血压统计</a></li>
                                <li><a href="{{ url('/statistics/sugar') }}">血糖统计</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ url('/articlemanage') }}">管理养生文章</a></li>
                    @elseif(Config::get('constants.ROLE_USER')==Auth::user()->role)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                我的健康信息 <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/pressures') }}">血压管理</a></li>
                                <li><a href="{{ url('/sugars') }}">血糖管理</a></li>
                                <li><a href="{{ url('/lines') }}">警戒线</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                关注用户 <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/following') }}">我关注的用户</a></li>
                                <li><a href="{{ url('/followers') }}">关注我的用户</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                消息<span class="caret"></span>
                                <p id="unread"></p>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/message/sends') }}">我发出的消息</a></li>
                                <li><a href="{{ url('/message/receives') }}">我收到的消息</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ url('/articles') }}">查看养生文章</a></li>
                    @endif
                @endif
            </ul>


            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">登陆</a></li>
                    <li><a href="{{ url('/register') }}">注册</a></li>
                @else
                    <li><a href="{{ url('/profile') }}">修改个人资料</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            {{ Auth::user()->profile->nickname }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}">登出</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@yield('content')

        <!-- JavaScripts -->

<script>
    //获取前端请求的页面
    $(function () {
        $.ajax({
            url: "{{url('/api/message/check')}}",
            dataType: "json",
            method: "get",
            success: function (data) {
                if ("success" == data.status) {
                    $("#unread").text(data.unread);
                } else {
                }
            }
        });
    });
</script>

{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
