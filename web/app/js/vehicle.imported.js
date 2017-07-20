// Vue.config.delimiters = ['${', '}'];
var vm = new Vue({
    el: '#form_of_imported',
    delimiters: ['${', '}'],
    data: {
        brand_selected: '',
        brand_options: [
        ],
        series_selected: '',
        series_options: [
        ],

        model_selected: '',
        model_options: [],
    },
    methods: {
        changeSeries : function (event) {
            console.log('series...');
            var self = this;
            $.ajax({
                url: api_url_series + '?brand=' + self.brand_selected,
                method: "post",
                success: function (res) {
                    options = [];
                    for (var i in res.data){
                        options.push({
                            text: res.data[i][0]['name'],
                            value: res.data[i][0]['series_id']
                        });
                    }
                    self.series_options = options;
                },
                error: function (res) {
                    alert('参数错误');
                }
            });
        },
        changeModels: function (event) {
            console.log('model...');
            var self = this;
            $.ajax({
                url: api_url_model + '?series=' + self.series_selected,
                method: "post",
                success: function (res) {
                    options = [];
                    for (var i in res.data){
                        options.push({
                            text: res.data[i][0]['name'],
                            value: res.data[i][0]['id']
                        });
                    }
                    self.model_options = options;
                },
                error: function (res) {
                    alert('参数错误');
                }
            });
        }
    }
});

var api_url_brand = '/api/imported/brands';
var api_url_series = '/api/imported/series';
var api_url_model = '/api/imported/models';
$(function () {
    $.ajax({
        url: api_url_brand,
        method: "post",
        success: function (res) {
            options = [];
            for (var i in res.data){
                console.log(res.data[i]);
                options.push({
                    text: res.data[i][0]['name'],
                    value: res.data[i][0]['brand_id']
                });
            }
            vm.brand_options = options;
        }
    });
});