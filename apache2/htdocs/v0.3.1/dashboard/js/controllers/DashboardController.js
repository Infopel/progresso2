/*
* Autor: Edislon Muncaze
*/
/**
 * let user = document.getElementsByClassName('user');
 * user[0].outerText;
 * user[0].href.replace('http://5.189.162.27:84/redmine/users/', '')
 *
 */
(function (angular) {
    'use strict';
    var app = angular.module('appSMP', ['ngSanitize']);


    app.controller('dashboardController', ['$scope', '$http', '$sce', function ($scope, $http, $routeParams, $sce) {
        $scope.logedUser = null;
        $scope.loading = true;
        $scope.loadingHotTasks = true;
        $scope.provincia = 'Maputo-Cidade';
        $scope.projects_provincia = [];
        let user = parent.document.getElementsByClassName('user');
        let user_id = user[0].href.replace('http://18.219.77.220/users/', '');
        //let user_id = 25;
        this.logedUser = user_id;

        /**
         * GET by provincia
         */
        $scope.getByProvincia = function (provincia) {
            $scope.loading = true;
            $scope.provincia = provincia;
            var url = 'core/handler.php?u='+this.logedUser+'&q=prov&parent=12&provincia='+provincia;
            $http.get(url)
                .then(function (response) {
                    $scope.project_Provincia = response.data;
                    $scope.loading = false;
                }, function (error) {
                    console.log(error)
                });
        }


        /**
         * GET dashboard overview
         */
        var url = 'core/handler.php?u='+this.logedUser;
        $http.get(url)
            .then(function(response){
                $scope.response = response.data;
                $scope.overview = response.data.overview;
                $scope.project_Provincia = response.data.proProvincia;
                $scope.loading = false;
                $scope.loadingHotTasks = false;
                $scope.projects_provincia = response.data._projectsProv;
                $scope.member_contrib = response.data.member_contrib;

                c3_member_contrib(response.data.member_contrib);
                c3_task_overview(response.data.overview._c3_overview, response.data.overview._c3_overview_init);
            }, function(error){
                console.log(error)
            });



        function c3_task_overview(data, init_data){
            var chart = c3.generate({
                bindto: '#task_Overview',
                data: {
                    // columns: [
                    //     ['Abertas', 30],
                    //     ['Em progresso', 64],
                    //     ['Fechdas', 98],
                    // ],
                    json: init_data,
                    type: 'donut',
                    order: null,
                    colors: {
                        "Em progresso": '#00CB96',
                        "Fechdas": '#2D96FF',
                        "Abertas": '#FF2D62',
                    },
                    labels: false
                },
                donut: {
                    width: 50,
                    // padAngle: .1
                }
            });
            setTimeout(function () {
                chart.unload({
                    ids: 'Minhas Tarefas'
                });
            }, 900);

            setTimeout(function () {
                chart.unload({
                    ids: 'Atribuidas a min'
                });
            }, 2000);

            setTimeout(function () {
                chart.unload({
                    ids: 'Por Terminar'
                });
            }, 2400);

            setTimeout(function () {
                chart.load({
                    json: data
                });
            }, 1600);

            // console.log(data['Abertas'])
        }

        function c3_member_contrib(data){

            data.forEach((member, key) => {
                console.log(key+1)

                var chart = c3.generate({
                    bindto: '#c3_member_'+ key + 1,
                    size: {
                        height: 60,
                        width: 60
                    },
                    data: {
                        columns: [
                            ['Total', member.tasks.total],
                            ['Nao concluidas', member.tasks.in_progress],
                            ['Concluidas', member.tasks.done],
                        ],
                        type: 'bar',
                        order: null,
                        colors: {
                            "Total": '#00CB96',
                            "Nao concluidas": '#2D96FF',
                            "Concluidas": '#FF2D62',
                        },
                        labels: false
                    },
                    bar: {
                        width: 8,
                        // padAngle: .1
                    },
                    axis: {
                        x: {
                            show: false
                        },
                        y: {
                            show: false
                        }
                    },
                    grid: {
                        y: {
                            show: false
                        }
                    },
                    legend: {
                        show: false
                    }
                });
            });
        }

    }])
})(window.angular);
