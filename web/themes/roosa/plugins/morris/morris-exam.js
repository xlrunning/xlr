var tax_data = [
   {"period": "2011 Q3", "licensed": 3407, "sorned": 660},
   {"period": "2011 Q2", "licensed": 3351, "sorned": 629},
   {"period": "2011 Q1", "licensed": 3269, "sorned": 618},
   {"period": "2010 Q4", "licensed": 3246, "sorned": 661},
   {"period": "2009 Q4", "licensed": 3171, "sorned": 676},
   {"period": "2008 Q4", "licensed": 3155, "sorned": 681},
   {"period": "2007 Q4", "licensed": 3226, "sorned": 620},
   {"period": "2006 Q4", "licensed": 3245, "sorned": null},
   {"period": "2005 Q4", "licensed": 3289, "sorned": null}
];

function MorrisAreaChart() {
	if($('#morris-hero-area').length) {
		  window.MorrisAreaChart = Morris.Area({
			element: 'morris-hero-area',
                padding: 10,
                behaveLikeLine: true,
                gridEnabled: false,
                gridLineColor: '#e1e8ed',
                axes: true,
                fillOpacity: .7,
                // grid: false,
                data: [
                	{period: '2015 Q1',二手车交易: 10,车商入住: 10,融资租赁: 10},
                    {period: '2015 Q2',二手车交易: 1778,车商入住: 7294,融资租赁: 12441},
                    {period: '2015 Q3',二手车交易: 4912,车商入住: 12969,融资租赁: 3501},
                    {period: '2016 Q4',二手车交易: 3767,车商入住: 3597,融资租赁: 5701},
                    {period: '2016 Q1',二手车交易: 6420,车商入住: 1822,融资租赁: 2303},
                    {period: '2016 Q2',二手车交易: 5670,车商入住: 4293,融资租赁: 1881},
                    {period: '2016 Q3',二手车交易: 4820,车商入住: 3795,融资租赁: 1588},
                    {period: '2017 Q4',二手车交易: 25073,车商入住: 5967,融资租赁: 5175},
                    {period: '2017 Q1',二手车交易: 17687,车商入住: 25460,融资租赁: 18028},
                    {period: '2017 Q2',二手车交易: 20140,车商入住: 5123,融资租赁: 10}
                    ],
                lineColors: ['#b2ddb4', '#81c784', '#50b154'],
                xkey: 'period',
                ykeys: ['二手车交易', '车商入住', '融资租赁'],
                labels: ['二手车交易', '车商入住', '融资租赁'],
                pointSize: 0,
                lineWidth: 0,
                hideHover: 'auto'
		});
	}
}

function MorrisLineChart() {
	if($('#morris-hero-line').length) {
		window.MorrisLineChart = Morris.Line({
			element: 'morris-hero-line',
			data: tax_data,
			xkey: 'period',
			ykeys: ['licensed', 'sorned'],
			labels: ['Licensed', 'Off the road'],
			resize: true,
			lineColors: ['#4a89dc','#81c784'],
		});
	}
}

function MorrisBarChart() {
	if($('#morris-hero-bar').length) {
		window.MorrisBarChart = Morris.Bar({
			element: 'morris-hero-bar',
			data: [
			{device: 'iPhone', geekbench: 136},
			{device: 'iPhone 3G', geekbench: 137},
			{device: 'iPhone 3GS', geekbench: 275},
			{device: 'iPhone 4', geekbench: 380},
			{device: 'iPhone 4S', geekbench: 655},
			{device: 'iPhone 5', geekbench: 1571}
			],
			xkey: 'device',
			ykeys: ['geekbench'],
			labels: ['Geekbench'],
			xLabelAngle: 35,
			hideHover: 'auto',
			resize: true,
			barColors: ['#4a89dc'],
		});
	}
}

function MorrisDonutChart01() {
	if($('#morris-hero-donut-01').length) {
		window.MorrisDonutChart01 = Morris.Donut({
			element: 'morris-hero-donut-01',
			data: [
			{label: 'Jam', value: 25 },
			{label: 'Frosted', value: 40 },
			{label: 'Custard', value: 25 },
			{label: 'Sugar', value: 10 }
			],
			resize: true,
			colors: ['#4a89dc','#81c784', '#4fc3f7', '#f6bb42'],
			formatter: function (y) { return y + "%" }
		});
	}
}

function MorrisDonutChart02() {
	if($('#morris-hero-donut-02').length) {
		window.MorrisDonutChart02 = Morris.Donut({
			element: 'morris-hero-donut-02',
			data: [
			{value: 70, label: 'foo', formatted: 'at least 70%' },
			{value: 15, label: 'bar', formatted: 'approx. 15%' },
			{value: 10, label: 'baz', formatted: 'approx. 10%' },
			{value: 5, label: 'A really really long label', formatted: 'at most 5%' }
			],
			resize: true,
			colors: ['#4a89dc'],
			formatter: function (x, data) { return data.formatted; }
		});
	}
}



$(document).ready(function() {
  MorrisAreaChart();
  MorrisLineChart();
  MorrisBarChart();
  MorrisDonutChart01();
  MorrisDonutChart02();

  $(window).resize(function() {
  	if($('#morris-hero-area').length) {
	    window.MorrisAreaChart.redraw();
	}
  	if($('#morris-hero-line').length) {
   	 window.MorrisLineChart.redraw();
	}
  	if($('#morris-hero-bar').length) {
    	window.MorrisBarChart.redraw();
	}
  	if($('#morris-hero-donut-01').length) {
    	window.MorrisDonutChart01.redraw();
	}
  	if($('#morris-hero-donut-02').length) {
    	window.MorrisDonutChart02.redraw();
	}
  });

});
