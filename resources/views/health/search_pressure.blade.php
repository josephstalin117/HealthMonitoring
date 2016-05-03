@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            <div class="row" style="margin-top: 10px">
                请输入用户的姓名吧!!
                <input type="text" id="search_content">
                <button id="submit" class="btn btn-success">确定</button>
            </div>
            <div class="row" id="search_content">
                @if(count($profiles)>0)
                    <div class="row" style="margin-top: 10px;">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>姓名</th>
                                <th>查看血压</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($profiles as $profile)
                                <tr>
                                    <td>{{$profile->nickname}}</td>
                                    <td>
                                        <a href="" type="button" data-id="{{$profile->user->id}}"
                                           data-toggle="modal"
                                           data-target="#detail_dialog" class="btn btn-danger openModal"><i
                                                    class="fa fa-btn fa-trash"></i>查看此用户血压</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="row">
                        暂无数据
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!--detail Modal -->
    <divo class="modal fade" id="detail_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
        <script>
            $(document).on("click", ".openModal", function () {
                var user_id = $(this).data('id');
                loadChart(user_id);
            });
        </script>

        <script type="text/javascript">
            //ajax数据加载

            //时间修改函数
            Date.prototype.Format = function (fmt) { //author: meizz
                var o = {
                    "M+": this.getMonth() + 1,                 //月份
                    "d+": this.getDate(),                    //日
                    "h+": this.getHours(),                   //小时
                    "m+": this.getMinutes(),                 //分
                    "s+": this.getSeconds(),                 //秒
                    "q+": Math.floor((this.getMonth() + 3) / 3), //季度
                    "S": this.getMilliseconds()             //毫秒
                };
                if (/(y+)/.test(fmt))
                    fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
                for (var k in o)
                    if (new RegExp("(" + k + ")").test(fmt))
                        fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
                return fmt;
            }

            function loadChart(user_id) {
                $.ajax({
                    url: "{{url('api/pressure')}}" + "/" + user_id,
                    success: function (result) {
                        console.log(result);

                        var date = [];
                        var high = [];
                        for (var i = 0; i < result.pressures.length; i++) {
                            console.log(result.pressures[i]);
                            date.push(result.pressures[i].time.date);
                            high.push(result.pressures[i].high);
                        }

                        initChart();
                        pressureChart.setOption({
                            xAxis: {
                                data: date
                            },
                            series: [
                                {
                                    name: '血压',
                                    type: 'line',
                                    data: high
                                }
                            ]
                        });
                    }
                });
            }
            // 基于准备好的dom，初始化echarts实例
            var pressureChart = echarts.init(document.getElementById('main'));

            // 指定图表的配置项和数据
            option = {
                title: {
                    text: '血压趋势分析'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['血压']
                },
                toolbox: {},
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        boundaryGap: false,
                        data: ['03-01', '03-02', '03-03', '03-04', '03-05', '03-06', '03-07']
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '血糖',
                        type: 'line',
                        data: [120, 132, 101, 134, 90, 230, 210]
                    }
                ]
            };

            // 使用刚指定的配置项和数据显示图表。
            pressureChart.setOption(option);
            // 使用刚指定的配置项和数据显示图表。
            function initChart() {
                pressureChart.setOption(option);
            }
        </script>
@endsection