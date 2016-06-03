@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row jumbotron">
            @include('common.errors')
            <div class="row" style="margin-top: 10px">
                <a href="{{url('/sugar/create')}}" type="button" class="btn btn-success">
                    输入今天的血糖吧!!
                </a>
                <a href="" type="button" data-toggle="modal"
                   data-target="#detail_dialog" class="btn btn-danger openModal">查看本人血糖曲线</a>
            </div>
            @if(count($sugars)>0)
                <div class="row" style="margin-top: 10px;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>血糖</th>
                            <th>日期</th>
                            <th>删除</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sugars as $sugar)
                            <tr>
                                <td>{{$sugar->sugar}}</td>
                                <td>{{$sugar->created_at}}</td>
                                <td>
                                    <a href="" onclick="delete_sugar({{$sugar->id}})" type="button"
                                       class="btn btn-danger"><i class="fa fa-btn fa-trash"></i>删除</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $sugars->links() !!}
                </div>
            @else
                <div class="row">
                    暂无数据
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

    <script>
        $(document).on("click", ".openModal", function () {
            var user_id = "{{$user_id}}";
            loadChart(user_id);
        });

        function delete_sugar(id) {
            if (confirm("是否删除?")) {
                $.get("{{url('api/sugar/delete')}}" + "/" + id, function (data) {
                    if ('success' == data.status) {
                        location.reload();
                    }
                });
            }
        }
    </script>

    <script>
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
            if (/(y+)/.test(fmt)) {
                fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
            }
            for (var k in o) {
                if (new RegExp("(" + k + ")").test(fmt)) {
                    fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
                }
            }
            return fmt;
        }

        function loadChart(user_id) {
            $.ajax({
                url: "{{url('api/sugar')}}" + "/" + user_id,
                success: function (result) {

                    var date = [];
                    var sugar = [];
                    for (var i = 0; i < result.sugars.length; i++) {
                        var sugarDate = new Date(result.sugars[i].time.date);
                        date.push(sugarDate.Format("yyyy-MM-dd hh:mm:ss"));
                        sugar.push(result.sugars[i].sugar);
                    }

                    initChart();
                    sugarChart.setOption({
                        xAxis: {
                            data: date
                        },
                        series: [
                            {
                                name: '血糖',
                                type: 'line',
                                data: sugar
                            }

                        ]
                    });
                }
            });
        }
        // 基于准备好的dom，初始化echarts实例
        var sugarChart = echarts.init(document.getElementById('main'));

        // 指定图表的配置项和数据
        option = {
            title: {
                text: '血糖趋势分析'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['血糖']
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
        sugarChart.setOption(option);
        // 使用刚指定的配置项和数据显示图表。
        function initChart() {
            sugarChart.setOption(option);
        }
    </script>
@endsection