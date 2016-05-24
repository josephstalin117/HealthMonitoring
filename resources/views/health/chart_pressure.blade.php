<div id="main" style="width: 600px;height:400px;"></div>

<script>
    $(document).on("click", ".openModal", function () {
        var user_id = "{{$user_id}}";
        loadChart(user_id);
    });
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
            url: "{{url('api/pressure')}}" + "/" + user_id,
            success: function (result) {

                var date = [];
                var high = [];
                var low = [];
                for (var i = 0; i < result.pressures.length; i++) {
                    var pressureDate = new Date(result.pressures[i].time.date);
                    date.push(pressureDate.Format("yyyy-MM-dd hh:mm:ss"));
                    high.push(result.pressures[i].high);
                    low.push(result.pressures[i].low);
                }

                initChart();
                pressureChart.setOption({
                    xAxis: {
                        data: date
                    },
                    series: [
                        {
                            name: '高压',
                            type: 'line',
                            data: high
                        },
                        {
                            name: '低压',
                            type: 'line',
                            data: low
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
                name: '血压',
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
