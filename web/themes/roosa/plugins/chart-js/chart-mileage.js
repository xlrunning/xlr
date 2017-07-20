/**
 * Created by chrischen on 2017/3/3.
 */
//柱状图，车辆里程
var mileage = document.getElementById("chart-car-mileage-bar").getContext("2d");
var mileage_chart = new Chart(mileage, {
    type: 'bar',
    data: {
        labels: ["0-3万公里", "3-5万公里", "5-8万公里", "8-10万公里", "10万公里以上"],
        datasets: [{
            label: '辆',
            data: [528, 258, 188, 88,28],
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

window.randomScalingFactor = function(min,max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

//品牌分布，折线图
var mileage_line = document.getElementById("chart-car-brand-line").getContext("2d");
var mileage_chart_line = new Chart(mileage_line, {
    type: 'line',
    data: {
        labels: ["奥迪", "宝马", "大众", "雪弗兰", "别克", "日产", "丰田","起亚","比亚迪","福特"],
        datasets: [{
            label: '辆',
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor:'rgba(54, 162, 235, 0.2)',
            data: [ randomScalingFactor(288,588),
                    randomScalingFactor(288,588),
                    randomScalingFactor(388,588),
                    randomScalingFactor(388,588),
                    randomScalingFactor(288,588),
                    randomScalingFactor(288,588),
                    randomScalingFactor(388,588),
                    randomScalingFactor(388,588),
                    randomScalingFactor(388,588),
                    randomScalingFactor(388,588)],
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)"
        }]
    },
    options: {
        hover: {
            mode: 'nearest',
            intersect: true
        },
    }
});