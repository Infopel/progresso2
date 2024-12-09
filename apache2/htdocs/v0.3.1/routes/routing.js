// configure our routes

var app = angular.module('appSMP', ['ngRoute', 'ngResource']);


    app.config(function($routeProvider) {
        $routeProvider

            .when('/programar-tarefas', {
                templateUrl: 'views/crons.html',
                controller: 'cronsController'
            })

            // route for the home page
            .when('/', {
                templateUrl: 'views/orcamento_pde.html',
                controller: 'orc_pdeController'
            })
            .when('/relatorio/orcamento/projecto/:id_projecto', {
                templateUrl: 'views/orcamento_proj.html',
                controller  : 'orc_projController'
            })

            .when('/relatorio/beneficiarios/pde/:id_plano',{
                templateUrl: 'views/beneficiarios_pde.html',
                controller  : 'benef_pdeController'
            })

            .when('/relatorio/beneficiarios/projecto/:id_projecto',{
                templateUrl: 'views/beneficiarios_proj.html',
                controller  : 'benef_projController'
            })
            .when('/actividade_pde/:i', {
                templateUrl: 'views/act_pde.html',
                controller: 'act_pdeController'
            })
            .when('/actividade_proj', {
                templateUrl: 'views/act_proj.html',
                controller: ''
            })
            .when('/actividade_prov', {
                templateUrl: 'views/act_prov.php',
                controller: 'act_provController'
            })
        // route do conteudo de artigos da app
        .when('/relatorio/prov/:nProvincia', {
            templateUrl: 'views/home.html',
            controller: 'homeController'
        })
        // Actividade PDE
        .when('/relatorio/actividade/pde/:id_plano', {
            templateUrl: 'views/act_pde.html',
            controller: 'act_pdeController'
        })
        // Actividade Projecto
        .when('/relatorio/actividade/projecto/:id_projecto', {
            templateUrl: 'views/act_proj.html',
            controller: 'act_projController'
        })
        // Actividade Provincia
        .when('/relatorio/actividade/provincia/:nProvincia', {
            templateUrl: 'views/act_prov.html',
            controller: 'act_provController'
        })

        // Report Orcamento
            .when('/relatorio/report/orcamento/projecto/:id_projecto', {
            templateUrl: 'views/report_orcamento_projecto.html',
            controller: 'report_orcamentoController'
        })

        // Orcamento Plano Estrategico PDE
            .when('/relatorio/orcamento/plano/pde/:id_plano', {
            templateUrl: 'views/orcamento_pde.html',
            controller: 'orc_pdeController'
        })
        // Report Orcamento Plano Estrategico
        .when('/relatorio/report/orcamento/pde/:id_plano', {
            templateUrl: 'views/reportOrcamentoPDE.html',
            controller: 'report_orcamentoPDEController'
        })
    });



