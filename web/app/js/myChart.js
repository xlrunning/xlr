/**
 * Created by chrischen on 2017/4/15.
 */
    // 基于准备好的dom，初始化echarts实例
var myChart = echarts.init(document.getElementById('main'));

var option = {
    tooltip : {
        trigger: 'item',
    },
    legend: {
        selectedMode: false,
        x : 'center',
        data:['已用额度','剩余额度']
    },
    calculable : true,
    series : [
        {
            name: '授信额度',
            type: 'pie',
            radius : '50%',
            center: ['50%', '50%'],
            itemStyle: {
                normal: {
                    label: {
                        show: true,
                        textStyle: {
                            fontFamily: 'MicrosoftYaHei',
                            fontSize: '12',
                            fontWeight: 'bold'
                        },
                        formatter: '{b}:\n {c}元\n{d}%'
                    },
                    labelLine: {
                        show: true
                    }
                }
            },
            data: [
                {value:200000, name:'已用额度'},
                {value:300000, name:'剩余额度'}
            ],
        },
    ],
    color:['#66CCFF','#36A2EB']
};

// 使用刚指定的配置项和数据显示图表。
myChart.setOption(option);