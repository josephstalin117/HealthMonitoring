<div id="sugar_main" style="width: 600px;height:400px;"></div>

<script>

    $(document).on("click", ".openSugarModal", function () {
        loadSugarChart({{$follow_user_id}});
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

    function loadSugarChart(user_id) {
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

                initSugarChart();
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
    var sugarChart = echarts.init(document.getElementById('sugar_main'));

    // 指定图表的配置项和数据
    sugar_option = {
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
    sugarChart.setOption(sugar_option);
    // 使用刚指定的配置项和数据显示图表。
    function initSugarChart() {
        sugarChart.setOption(sugar_option);
    }
</script>