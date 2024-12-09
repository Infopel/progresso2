app.controller('homeController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {
    // HomePage Controoler - Dashboard
    $scope.nProvincia = $routeParams.nProvincia;

    $url = 'system/api/_a65nasd84.php/?report=dashboard&prov=' + $routeParams.nProvincia;
    $http.get($url)
        .success(function (data) {
            $scope.response = $sce.trustAsHtml('' + data + '');
        }).error(function (error) {
            console.log(error);
            $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
        })
}])
    // Orcamento PDE Controller
    .controller('orc_pdeController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {

        $scope.exportExcel = function () {

            var downloadLink;
            // downloadLink
            downloadLink = document.createElement("a");
            // Nome do ficheiro
            filename = 'Orcamento_PDE' + Date.now() + '.xls';
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table border=1>{table}</table></body></html>'
                , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
            var table = "tableData";
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = { worksheet: "Orcamento_PDE" || 'Worksheet', table: table.innerHTML }
            downloadLink.href = uri + base64(format(template, ctx))
            downloadLink.download = filename;
            downloadLink.click();
        };

        //Get plano
        $url_proj = 'system/api/_a65nasd84.php/?report=plano&p=' + $routeParams.id_plano;
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_plano = data;
            })
            .error(function (error) {
                // console.log(error);
                $scope.response_projecto = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get planos
        $url_proj = 'system/api/_a65nasd84.php/?report=planos';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_planos = $sce.trustAsHtml('' + data + '');
            })
            .error(function (erro) {
                // $log.error(erro);
                $scope.response_projectos = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        $url = 'system/api/_a65nasd84.php/?report=OrcamentoPDE&p=' + $routeParams.id_plano;
        $http.get($url)
            .success(function (data) {
                $scope.response = $sce.trustAsHtml('' + data + '');
                $scope.response_data = data;
                $scope.responseOrcamento = data;
                $scope.script = $sce.trustAsHtml('<script>load(); </script>');

            }).error(function (error) {
                // console.log(error);
                $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            })
    }])
    // Orcamento Projecto Controller
    .controller('orc_projController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {

        $scope.exportExcel = function () {

            var downloadLink;
            // downloadLink
            downloadLink = document.createElement("a");
            // Nome do ficheiro
            filename = 'Orcamento_Projecto' + Date.now() + '.xls';
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table border=1>{table}</table></body></html>'
                , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
            var table = "tableData";
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = { worksheet: "Orcamento_PROJ" || 'Worksheet', table: table.innerHTML }
            downloadLink.href = uri + base64(format(template, ctx))
            downloadLink.download = filename;
            downloadLink.click();
        };

        //Get projecto
        $url_proj = 'system/api/_a65nasd84.php/?report=projecto&p=' + $routeParams.id_projecto;
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_projecto = data;
            })
            .error(function (erro) {
                console.log(error);
                $scope.response_projecto = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get projectos
        $url_proj = 'system/api/_a65nasd84.php/?report=projectos';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_projectos = $sce.trustAsHtml('' + data + '');
            })
            .error(function (erro) {
                console.log(error);
                $scope.response_projectos = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        $url = 'system/api/_a65nasd84.php/?report=OrcamentoPROJ&p=' + $routeParams.id_projecto;
        $http.get($url)
            .success(function (data) {
                $scope.response = data;
                $scope.script = $sce.trustAsHtml('<script>load(); </script>')
            }).error(function (error) {
                console.log(error);
                $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            })
    }])

    // Beneficiarios PDE Controller
    .controller('benef_pdeController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {
        $scope.loading = true;

        $scope.exportExcel = function () {

            var downloadLink;
            // downloadLink
            downloadLink = document.createElement("a");
            // Nome do ficheiro
            filename = 'Beneficiários_PDE' + Date.now() + '.xls';
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table border=1>{table}</table><br><br><table border=1>{table_2}</table><br><br><table border=1>{table_3}</table></body></html>'
                , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
            var table = "tableData";
            var table_2 = "tableData_2";
            var table_3 = "tableData_3";
            if (!table.nodeType) table = document.getElementById(table)
            if (!table_2.nodeType) table_2 = document.getElementById(table_2)
            if (!table_3.nodeType) table_3 = document.getElementById(table_3)
            var ctx = { worksheet: "Beneficiários_PDE" || 'Worksheet', table: table.innerHTML, table_2: table_2.innerHTML, table_3: table_3.innerHTML }
            downloadLink.href = uri + base64(format(template, ctx))
            downloadLink.download = filename;
            downloadLink.click();
        };


        //Get Planos
        $url_proj = 'system/api/_a65nasd84.php/?report=plano&p=' + $routeParams.id_plano;
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_plano = data;
            })
            .error(function (erro) {
                console.log(error);
                $scope.response_projecto = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get Planos
        $url_proj = 'system/api/_a65nasd84.php/?report=planos';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_planos = $sce.trustAsHtml('' + data + '');
            })
            .error(function (erro) {
                console.log(error);
                $scope.response_projectos = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });


        $url = 'system/api/_a65nasd84.php/?report=beneficiariosPDE&p=' + $routeParams.id_plano;
        $http.get($url)
            .success(function (data) {
                $scope.response = data;
                $scope.load = $sce.trustAsHtml('<script>load();</script>');
                $scope.loading = false;
            }).error(function (error) {
                console.log(error);
                $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            })
    }])
    // Beneficiarios Projecto Controller
    .controller('benef_projController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {
        $scope.exportExcel = function () {

            var downloadLink;
            // downloadLink
            downloadLink = document.createElement("a");
            // Nome do ficheiro
            filename = 'Beneficiários_PROJECTO' + Date.now() + '.xls';
            var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table border=1>{table}</table><br><br></body></html>'
                , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
            var table = "tableData";
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = { worksheet: "Beneficiários_PROJECTO" || 'Worksheet', table: table.innerHTML }
            downloadLink.href = uri + base64(format(template, ctx))
            downloadLink.download = filename;
            downloadLink.click();
        };

        //Get projecto
        $url_proj = 'system/api/_a65nasd84.php/?report=projecto&p=' + $routeParams.id_projecto;
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_projecto = data;
            })
            .error(function (erro) {
                console.log(error);
                $scope.response_projecto = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get projectos
        $url_proj = 'system/api/_a65nasd84.php/?report=projectos';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_projectos = $sce.trustAsHtml('' + data + '');
            })
            .error(function (erro) {
                console.log(error);
                $scope.response_projectos = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });
        $url = 'system/api/_a65nasd84.php/?report=beneficiariosPROJ&p=' + $routeParams.id_projecto;
        $http.get($url)
            .success(function (data) {
                $scope.response = data;
                $scope.load = $sce.trustAsHtml("<script>load();</script>");
            }).error(function (error) {
                console.log(error);
                $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            })
    }])

    // Actividade PDE Controller
    .controller('act_pdeController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {

        // Functions
        $scope.selectYear = null;
        $scope.selectPeriodo = null;
        $scope.loading = true;

        // Filtro por Data de Inicio ou fim
        $scope.getByDate = function (startDate = null, endDate = null) {
            if (startDate != null && endDate == null) {
                $scope.loading = true;
                $url = 'system/api/query.php/?periodo=true&startDate=' + startDate + '&p=' + $routeParams.id_plano;
                $http.get($url)
                    .success(function (data) {
                        $scope.response_OjectivoGeral = data.OjectivoGeral;
                        $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else if (startDate == null && endDate != null) {
                $scope.loading = true;
                $url = 'system/api/query.php/?periodo=true&endDate=' + endDate + '&p=' + $routeParams.id_plano;
                $http.get($url)
                    .success(function (data) {
                        $scope.response_OjectivoGeral = data.OjectivoGeral;
                        $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else if (endDate != null && startDate != null) {
                $scope.loading = true;
                $url = 'system/api/query.php/?periodo=true&startDate=' + startDate + '&endDate=' + endDate + '&p=' + $routeParams.id_plano;
                $http.get($url)
                    .success(function (data) {
                        $scope.response_OjectivoGeral = data.OjectivoGeral;
                        $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else {
                alert("Selecione uma data de inicio e/ou fim da para validar a pesquisa!");
            }
        }

        // Filtro por ano
        $scope.getByYear = function (year) {
            $scope.loading = true;
            if (year == 'todos') {
                $scope.selectYear = 'Todos';
                $url = 'system/api/query.php/?report=actividadePDE&p=' + $routeParams.id_plano;
            } else {
                $scope.selectYear = year;
                $url = 'system/api/query.php/?ano=' + year + '&p=' + $routeParams.id_plano;
            }
            $http.get($url)
                .success(function (data) {
                    $scope.response_OjectivoGeral = data.OjectivoGeral;
                    $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                    $scope.loading = false;
                })
                .error(function (data) {
                    console.log(data);
                })
        }

        // Filtro por periodo
        $scope.getByPeriodo = function (periodo) {
            switch (periodo) {
                case '1T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "1 Trimestre";
                    $url = 'system/api/query.php/?periodo=true&ano=' + this.selectYear + '&Px=1&Py=3&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '2T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "2 Trimestre";
                    $url = 'system/api/query.php/?periodo=true&ano=' + this.selectYear + '&Px=4&Py=6&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '3T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "3 Trimestre";
                    $url = 'system/api/query.php/?periodo=true&ano=' + this.selectYear + '&Px=7&Py=9&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '4T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "4 Trimestre";
                    $url = 'system/api/query.php/?periodo=true&ano=' + this.selectYear + '&Px=10&Py=12&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '1S':
                    $scope.loading = true;
                    $scope.selectPeriodo = "1 Semestre";
                    $url = 'system/api/query.php/?periodo=true&ano=' + this.selectYear + '&Px=1&Py=6&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;
                case '2S':
                    $scope.loading = true;
                    $scope.selectPeriodo = "2 Semestre";
                    $url = 'system/api/query.php/?periodo=true&ano=' + this.selectYear + '&Px=7&Py=12&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                default:

                    break;
            }
        }
        //Get Filtro Anos
        $url_proj = 'system/api/_a65nasd84.php/?report=filter_ano';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_anoFilter = data;
            })
            .error(function (erro) {
                console.log(erro);
                $scope.response_plano = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get plano
        $url_proj = 'system/api/_a65nasd84.php/?report=plano&p=' + $routeParams.id_plano;
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_plano = data;
                $scope.loadtable = $sce.trustAsHtml("<script>$('.datatable-responsive').DataTable({'language':{'url':'//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json'}});</script>'");
            })
            .error(function (error) {
                console.log(error);
                $scope.response_plano = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get planos
        $url_proj = 'system/api/_a65nasd84.php/?report=planos';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_planos = $sce.trustAsHtml('' + data + '');
            })
            .error(function (erro) {
                console.log(error);
                $scope.response_planos = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        // Get Data
        $url = 'system/api/query.php/?report=actividadePDE&p=' + $routeParams.id_plano;
        $http.get($url)
            .success(function (data) {
                $scope.response_OjectivoGeral = data.OjectivoGeral;
                $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                $scope.loadtable = $sce.trustAsHtml("<script>$('.datatable-responsive').DataTable({'language':{'url':'//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json'}});</script>'");

                $scope.loading = false;
            }).error(function (error) {
                console.log(error);
                $scope.response = $sce.trustAsHtml("The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com");
            })
    }])

    // Actividade Projecto Controller
    .controller('act_projController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {

        // Functions
        $scope.selectYear = null;
        $scope.selectPeriodo = null;
        $scope.loading = true;

        // Filtro por Data de Inicio ou fim
        $scope.getByDate = function (startDate = null, endDate = null) {
            if (startDate != null && endDate == null) {
                $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&startDate=' + startDate + '&p=' + $routeParams.id_projecto;
                $http.get($url)
                    .success(function (data) {
                        $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                        $scope.resultadoFinal = data.resultadoFinal;
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else if (startDate == null && endDate != null) { // FIltro pela data de Fim
                $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&endDate=' + endDate + '&p=' + $routeParams.id_projecto;
                $http.get($url)
                    .success(function (data) {
                        $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                        $scope.resultadoFinal = data.resultadoFinal;
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else if (endDate != null && startDate != null) { // FIltro pela data de inicio e de fim
                $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&startDate='+startDate+'&endDate='+endDate+'&p=' + $routeParams.id_projecto;
                $http.get($url)
                    .success(function (data) {
                        $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                        $scope.resultadoFinal = data.resultadoFinal;
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else {
                alert("Selecione uma data de inicio e/ou fim da para validar a pesquisa!");
            }
        }

        // Filtro por ano
        $scope.getByYear = function (year) {
            $scope.loading = true;
            if (year == 'todos') {
                $scope.selectYear = 'Todos';
                $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&p=' + $routeParams.id_projecto;
            } else {
                $scope.selectYear = year;
                $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&ano=' + year + '&p=' + $routeParams.id_projecto;
            }
            $http.get($url)
                .success(function (data) {
                    $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                    $scope.resultadoFinal = data.resultadoFinal;
                    $scope.loading = false;
                })
                .error(function (data) {
                    console.log(data);
                })
        }

        // Filtro por periodo
        $scope.getByPeriodo = function (periodo) {
            switch (periodo) {
                case '1T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "1 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&periodo=true&ano=' + this.selectYear + '&Px=1&Py=3&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '2T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "2 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&periodo=true&ano=' + this.selectYear + '&Px=4&Py=6&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '3T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "3 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&periodo=true&ano=' + this.selectYear + '&Px=7&Py=9&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '4T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "4 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&periodo=true&ano=' + this.selectYear + '&Px=10&Py=12&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '1S':
                    $scope.loading = true;
                    $scope.selectPeriodo = "1 Semestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&periodo=true&ano=' + this.selectYear + '&Px=1&Py=6&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;
                case '2S':
                    $scope.loading = true;
                    $scope.selectPeriodo = "2 Semestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&periodo=true&ano=' + this.selectYear + '&Px=7&Py=12&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                default:

                    break;
            }
        }

        //Get Filtro Anos
        $url_proj = 'system/api/_a65nasd84.php/?report=filter_ano';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_anoFilter = data;
            })
            .error(function (error) {
                console.log(error);
                $scope.response_plano = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get projecto
        $url_proj = 'system/api/_a65nasd84.php/?report=projecto&p=' + $routeParams.id_projecto;
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_projecto = data;
                $scope.loadtable = $sce.trustAsHtml("<script>$('.datatable-responsive').DataTable({'language':{'url':'//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json'}});</script>'");
            })
            .error(function (erro) {
                console.log(error);
                $scope.response_projecto = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get projectos
        $url_proj = 'system/api/_a65nasd84.php/?report=projectos';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_projectos = $sce.trustAsHtml('' + data + '');
            })
            .error(function (erro) {
                console.log(error);
                $scope.response_projectos = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //_a65nasd84

        $url = 'system/api/_a65nasd84.php/?report=actividadePROJ&p=' + $routeParams.id_projecto;
        $http.get($url)
            .success(function (data) {
                $scope.responseTable = $sce.trustAsHtml('' + data.table + '');
                $scope.resultadoFinal = data.resultadoFinal;
                $scope.loading = false;
            }).error(function (error) {
                console.log(error);
                $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            })
    }])

    // Actividade Por Provincia Controller
    .controller('act_provController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {

        // Functions
        $scope.selectYear = null;
        $scope.selectPeriodo = null;
        $scope.loading = true;

        // Filtro por Data de Inicio ou fim
        $scope.getByDate = function (startDate = null, endDate = null) {
            if (startDate != null && endDate == null) {
                alert("Data de Inicio: " + startDate + "\nData de Fim: " + endDate + "\n the dev team are working on this feature. it will be avalibe soon. @DevTeam");
            } else if (startDate == null && endDate != null) {
                alert("Data de Inicio: " + startDate + "\nData de Fim: " + endDate + "\n the dev team are working on this feature. it will be avalibe soon. @DevTeam");
            } else if (endDate != null && startDate != null) {
                alert("Data de Inicio: " + startDate + "\nData de Fim: " + endDate + "\n the dev team are working on this feature. it will be avalibe soon. @DevTeam");
            } else {
                alert("Selecione uma data de inicio e/ou fim da para validar a pesquisa!");
            }
        }

        // Filtro por ano
        $scope.getByYear = function (year) {
            $scope.loading = true;
            if (year == 'todos') {
                $scope.selectYear = 'Todos';
                $url = 'system/api/_a65nasd84.php/?report=actividadePROV&p=' + $routeParams.nProvincia;
            } else {
                $scope.selectYear = year;
                $url = 'system/api/_a65nasd84.php/?report=actividadePROV&ano=' + year + '&p=' + $routeParams.nProvincia;
            }
            $http.get($url)
                .success(function (data) {
                    $scope.response = $sce.trustAsHtml('' + data + '');
                    $scope.loading = false;
                })
                .error(function (data) {
                    console.log(data);
                })
        }

        // Filtro por periodo
        $scope.getByPeriodo = function (periodo) {
            switch (periodo) {
                case '1T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "1 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROV&periodo=true&ano=' + this.selectYear + '&Px=1&Py=3&p=' + $routeParams.nProvincia;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response = $sce.trustAsHtml('' + data + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '2T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "2 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROV&periodo=true&ano=' + this.selectYear + '&Px=4&Py=6&p=' + $routeParams.nProvincia;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response = $sce.trustAsHtml('' + data + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '3T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "3 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROV&periodo=true&ano=' + this.selectYear + '&Px=7&Py=9&p=' + $routeParams.nProvincia;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response = $sce.trustAsHtml('' + data + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '4T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "4 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROV&periodo=true&ano=' + this.selectYear + '&Px=10&Py=12&p=' + $routeParams.nProvincia;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response = $sce.trustAsHtml('' + data + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '1S':
                    $scope.loading = true;
                    $scope.selectPeriodo = "1 Semestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROV&periodo=true&ano=' + this.selectYear + '&Px=1&Py=6&p=' + $routeParams.nProvincia;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response = $sce.trustAsHtml('' + data + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;
                case '2S':
                    $scope.loading = true;
                    $scope.selectPeriodo = "2 Semestre";
                    $url = 'system/api/_a65nasd84.php/?report=actividadePROV&periodo=true&ano=' + this.selectYear + '&Px=7&Py=12&p=' + $routeParams.nProvincia;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response = $sce.trustAsHtml('' + data + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                default:

                    break;
            }
        }

        //Get Filtro Anos
        $url_proj = 'system/api/_a65nasd84.php/?report=filter_ano';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_anoFilter = data;
            })
            .error(function (error) {
                console.log(error);
                $scope.response_plano = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        $scope.nProvincia = $routeParams.nProvincia;
        $url = 'system/api/_a65nasd84.php/?report=actividadePROV&p=' + $routeParams.nProvincia;
        $http.get($url)
            .success(function (data) {
                $scope.response = $sce.trustAsHtml('' + data + '');
                $scope.loadtable = $sce.trustAsHtml("");
                $scope.loading = false;
            }).error(function (error) {
                console.log(error);
                $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            })
    }])


    //Report Orcamento de Projecto
    .controller('report_orcamentoController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {
        // Functions
        $scope.selectYear = null;
        $scope.selectPeriodo = null;
        $scope.loading = true;

        // Filtro por Data de Inicio ou fim
        $scope.getByDate = function (startDate = null, endDate = null){

            if (startDate != null && endDate == null) {
                $scope.loading = true;
                $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&startDate=' + startDate + '&p=' + $routeParams.id_projecto;
                $http.get($url)
                    .success(function (data) {
                        $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                        $scope.resultadoFinal = data.resultadoFinal;
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else if (startDate == null && endDate != null) { // FIltro pela data de Fim
                $scope.loading = true;
                $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&endDate=' + endDate + '&p=' + $routeParams.id_projecto;
                $http.get($url)
                    .success(function (data) {
                        $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                        $scope.resultadoFinal = data.resultadoFinal;
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else if (endDate != null && startDate != null) { // FIltro pela data de inicio e de fim
                $scope.loading = true;
                $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&startDate=' + startDate + '&endDate=' + endDate + '&p=' + $routeParams.id_projecto;
                $http.get($url)
                    .success(function (data) {
                        $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                        $scope.resultadoFinal = data.resultadoFinal;
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else {
                alert("Selecione uma data de inicio e/ou fim da para validar a pesquisa!");
            }
        }

        // Filtro por ano
        $scope.getByYear = function (year) {
            $scope.loading = true;
            if (year == 'todos') {
                $scope.selectYear = 'Todos';
                $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&p=' + $routeParams.id_projecto;
            } else {
                $scope.selectYear = year;
                $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&ano=' + year + '&p=' + $routeParams.id_projecto;
            }
            $http.get($url)
                .success(function (data) {
                    $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                    $scope.resultadoFinal = data.resultadoFinal;
                    $scope.loading = false;
                })
                .error(function (data) {
                    console.log(data);
                })
        }

        // Filtro por periodo
        $scope.getByPeriodo = function (periodo) {
            switch (periodo) {
                case '1T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "1 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&periodo=true&ano=' + this.selectYear + '&Px=1&Py=3&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            // console.log(data);
                        })
                    break;

                case '2T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "2 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&periodo=true&ano=' + this.selectYear + '&Px=4&Py=6&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '3T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "3 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&periodo=true&ano=' + this.selectYear + '&Px=7&Py=9&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '4T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "4 Trimestre";
                    $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&periodo=true&ano=' + this.selectYear + '&Px=10&Py=12&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '1S':
                    $scope.loading = true;
                    $scope.selectPeriodo = "1 Semestre";
                    $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&periodo=true&ano=' + this.selectYear + '&Px=1&Py=6&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;
                case '2S':
                    $scope.loading = true;
                    $scope.selectPeriodo = "2 Semestre";
                    $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&periodo=true&ano=' + this.selectYear + '&Px=7&Py=12&p=' + $routeParams.id_projecto;
                    $http.get($url)
                        .success(function (data) {
                            $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.resultadoFinal = data.resultadoFinal;
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                default:

                    break;
            }
        }

        //Get Filtro Anos
        $url_proj = 'system/api/_a65nasd84.php/?report=filter_ano';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_anoFilter = data;
            })
            .error(function (error) {
                // console.log(error);
                $scope.response_plano = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get projecto
        $url_proj = 'system/api/_a65nasd84.php/?report=projecto&p=' + $routeParams.id_projecto;
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_projecto = data;
                $scope.loadtable = $sce.trustAsHtml("<script>$('.datatable-responsive').DataTable({'language':{'url':'//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json'}});</script>'");
            })
            .error(function (erro) {
                // console.log(error);
                $scope.response_projecto = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get projectos
        $url_proj = 'system/api/_a65nasd84.php/?report=projectos';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_projectos = $sce.trustAsHtml('' + data + '');
            })
            .error(function (erro) {
                // console.log(error);
                $scope.response_projectos = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        $url = 'system/api/_a65nasd84.php/?report=reportOrcamento&p=' + $routeParams.id_projecto;
        $http.get($url)
            .success(function (data) {
                $scope.responseTable = $sce.trustAsHtml('' + data.tableContent + '');
                $scope.resultadoFinal = data.resultadoFinal;
                $scope.loading = false;
            }).error(function (error) {
                //console.log(error);
                $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            })
    }])

    //Report Orcamento de Plano Estrategico PDE
    .controller('report_orcamentoPDEController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {

        // Functions
        $scope.selectYear = null;
        $scope.selectPeriodo = null;
        $scope.loading = true;

        // Filtro por Data de Inicio ou fim
        $scope.getByDate = function (startDate = null, endDate = null) {
            if (startDate != null && endDate == null) {
                $scope.loading = true;
                $url = 'system/api/_repOrcPde.php/?periodo=true&startDate=' + startDate + '&p=' + $routeParams.id_plano;
                $http.get($url)
                    .success(function (data) {
                        $scope.response_OjectivoGeral = data.OjectivoGeral;
                        $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else if (startDate == null && endDate != null) {
                $scope.loading = true;
                $url = 'system/api/_repOrcPde.php/?periodo=true&endDate=' + endDate + '&p=' + $routeParams.id_plano;
                $http.get($url)
                    .success(function (data) {
                        $scope.response_OjectivoGeral = data.OjectivoGeral;
                        $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else if (endDate != null && startDate != null) {
                $scope.loading = true;
                $url = 'system/api/_repOrcPde.php/?periodo=true&startDate=' + startDate + '&endDate=' + endDate + '&p=' + $routeParams.id_plano;
                $http.get($url)
                    .success(function (data) {
                        $scope.response_OjectivoGeral = data.OjectivoGeral;
                        $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                        $scope.loading = false;
                    })
                    .error(function (data) {
                        console.log(data);
                    })
            } else {
                alert("Selecione uma data de inicio e/ou fim da para validar a pesquisa!");
            }
        }

        // Filtro por ano
        $scope.getByYear = function (year) {
            $scope.loading = true;
            if (year == 'todos') {
                $scope.selectYear = 'Todos';
                $url = 'system/api/_repOrcPde.php/?report=actividadePDE&p=' + $routeParams.id_plano;
            } else {
                $scope.selectYear = year;
                $url = 'system/api/_repOrcPde.php/?ano=' + year + '&p=' + $routeParams.id_plano;
            }
            $http.get($url)
                .success(function (data) {
                    $scope.response_OjectivoGeral = data.OjectivoGeral;
                    $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                    $scope.loading = false;
                })
                .error(function (data) {
                    console.log(data);
                })
        }

        // Filtro por periodo
        $scope.getByPeriodo = function (periodo) {
            switch (periodo) {
                case '1T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "1 Trimestre";
                    $url = 'system/api/_repOrcPde.php/?periodo=true&ano=' + this.selectYear + '&Px=1&Py=3&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '2T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "2 Trimestre";
                    $url = 'system/api/_repOrcPde.php/?periodo=true&ano=' + this.selectYear + '&Px=4&Py=6&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '3T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "3 Trimestre";
                    $url = 'system/api/_repOrcPde.php/?periodo=true&ano=' + this.selectYear + '&Px=7&Py=9&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '4T':
                    $scope.loading = true;
                    $scope.selectPeriodo = "4 Trimestre";
                    $url = 'system/api/_repOrcPde.php/?periodo=true&ano=' + this.selectYear + '&Px=10&Py=12&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                case '1S':
                    $scope.loading = true;
                    $scope.selectPeriodo = "1 Semestre";
                    $url = 'system/api/_repOrcPde.php/?periodo=true&ano=' + this.selectYear + '&Px=1&Py=6&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;
                case '2S':
                    $scope.loading = true;
                    $scope.selectPeriodo = "2 Semestre";
                    $url = 'system/api/_repOrcPde.php/?periodo=true&ano=' + this.selectYear + '&Px=7&Py=12&p=' + $routeParams.id_plano;
                    $http.get($url)
                        .success(function (data) {
                            $scope.response_OjectivoGeral = data.OjectivoGeral;
                            $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                            $scope.loading = false;
                        })
                        .error(function (data) {
                            console.log(data);
                        })
                    break;

                default:

                    break;
            }
        }


        //Get Filtro Anos
        $url_proj = 'system/api/_a65nasd84.php/?report=filter_ano';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_anoFilter = data;
            })
            .error(function (error) {
                console.log(error);
                $scope.response_plano = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });


        //Get Plano
        $url_proj = 'system/api/_a65nasd84.php/?report=plano&p=' + $routeParams.id_plano;
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_projecto = data;
                $scope.loadtable = $sce.trustAsHtml("<script>$('.datatable-responsive').DataTable({'language':{'url':'//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese.json'}});</script>'");

            })
            .error(function (error) {
                console.log(error);
                $scope.response_projecto = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        //Get Planos
        $url_proj = 'system/api/_a65nasd84.php/?report=planos';
        $http.get($url_proj)
            .success(function (data) {
                $scope.response_projectos = $sce.trustAsHtml('' + data + '');
            })
            .error(function (error) {
                console.log(error);
                $scope.response_projectos = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            });

        $url = 'system/api/_repOrcPde.php/?report=reportOrcamentoPDE&p=' + $routeParams.id_plano;
        $http.get($url)
            .success(function (data) {
                $scope.response_OjectivoGeral = data.OjectivoGeral;
                $scope.response = $sce.trustAsHtml('' + data.tableContent + '');
                $scope.loading = false;
            }).error(function (error) {
                console.log(error);
                $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            })
    }])

    .controller('cronsController', ['$scope', '$http', '$routeParams', '$sce', function ($scope, $http, $routeParams, $sce) {
        // HomePage Controoler - Dashboard
        $scope.actionSeleced = null;
        $scope.actionID = null;
        $scope.triggerSelected = null;
        $scope.triggerID = null;
        $scope.relationID = null;

        /**
         * User Selected Action for the cron Job Task
         */
        $scope.action = function(action)
        {
            switch (action) {
                case "Notify":
                    $scope.actionSeleced = "Notificar por email";
                    break;

                case "NewAct":
                    $scope.actionSeleced = "Criar Actividade";
                    break;

                case "Upcoming":

                    break;

                default:
                    break;
            }
        }

        /**
         * User selected trigger to callback the action
         * or Action Trigger
         */
        $scope.trigger = function (trigger)
        {
            switch (trigger) {
                case "done":
                    $scope.triggerSelected = "Concluido";
                    $scope.triggerID = 103;
                    break;

                case "B5days":
                    $scope.triggerSelected = "5 dias antes do termino";
                    $scope.triggerID = 105;
                    break;

                case "newPRO":
                    $scope.triggerSelected = "Nova Projecto criado";
                    $scope.triggerID = 107;
                    break;

                case "newACT":
                    $scope.triggerSelected = "Novo Actividade criada";
                    $scope.triggerID = 109;
                    break;

                default:
                    break;
            }
        }

        /**
         * Relation - trigged to
         */

        $scope.relatedTo = function(relatedTo)
        {
            switch (relatedTo) {
                case "PE":
                    $scope.selectRelation = "Plano Estrategico";
                    break;

                case "PRO":
                    $scope.selectRelation = "Projectos";
                    break;

                case "ACT":
                    $scope.selectRelation = "Activiadaes";
                    break;

                default:
                    break;
            }
        }

        /**
         * Programa
         *
         */
        $scope.selectedPrograma = null;
        $scope.selectedProgramaID = null;
        $scope.selectPrograma = function (projecto)
        {
            $scope.selectedPrograma = projecto.programa;
            $scope.selectedProgramaID = projecto.id_programa;
            /**
             * Query Vars
             */
            $url = 'system/api/utilitesControler.php/?getAct=0&id=' + projecto.id_programa;
            $http.get($url)
                .success(function (data) {
                    $scope.responseAct = data;
                }).error(function (error) {
                    console.log(error);
                    $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
                })
        }

        /**
         * Actividade
         *
         */
        $scope.selectedActividade = null;
        $scope.selectedActividadeID = null;
        $scope.selectActividade = function (actividade) {
            $scope.selectedActividade = actividade.subject;
            $scope.selectedActividadeID = actividade.id;
        }

        $scope.objBeneficiarios = [];
        $scope.objMaterial= [];

        $scope.selectedBenf = null;
        $scope.selectedBenfIndex = null;

        $scope.selectedMaterial = null;
        $scope.selectedMaterialIndex = null;

        $scope.selectBenf = function(varBenf, index)
        {
            $scope.selectedBenf = varBenf;
            $scope.selectedBenfIndex = index;
        }

        $scope.selectMaterial = function (varMaterial, index) {
            $scope.selectedMaterial = varMaterial;
            $scope.selectedMaterialIndex = index;
        }

        /**
         * Adicionar Variaveis e Valores para os beneficiarios
         */
        $scope.addBeneficiarios = function (selectedBenf, benValue){
            $scope.newObjBenf = {
                'var': selectedBenf,
                'realizado': benValue
            };
            this.objBeneficiarios.push($scope.newObjBenf)
            this.responseVars.benf_vars.splice(this.selectedBenfIndex, 1);
            $scope.selectedBenf = null;
            this.benValue = null;
        }

        /**
         * Adicionar Variaveis e Valores para os Materiais
         */
        $scope.addMateriais = function (selectedMaterial, matValue) {
            $scope.newObjMaterail = {
                'var': selectedMaterial,
                'realizado': matValue
            };
            this.objMaterial.push($scope.newObjMaterail)
            this.responseVars.material_vars.splice(this.selectedMaterialIndex, 1);
            $scope.selectedMaterial = null;
            this.matValue = null;
        }

        /**
         * Remove Beneficiario selecionado (del Function)
         */
        $scope.delBenf = function(varValue, index){
            console.log(varValue);
            // Remover um elemento da lista dos selecionados
            this.objBeneficiarios.splice(index, 1);
            // Adicionar o elemento removido na lista das variaveis dos beneficiarios
            $scope.newObjBenf = {
                'var': varValue
            };
            this.responseVars.benf_vars.push($scope.newObjBenf)
        }

        /**
         * Remove Material selecionado (del Function)
         */
        $scope.delMaterial = function (varValue, index) {
            console.log(varValue);
            // Remover um elemento da lista dos selecionados
            this.objMaterial.splice(index, 1);
            // Adicionar o elemento removido na lista das variaveis dos beneficiarios
            $scope.newObjMaterial = {
                'var': varValue
            };
            this.responseVars.material_vars.push($scope.newObjMaterial)
        }


        $scope.gravarReal = function (comment, vGasto, dueDate, time)
        {
            console.log(
                "Benf: " + this.objBeneficiarios[0] + "\n"+
                "Material: "+ this.objMaterial
            );

            if (this.selectedActividadeID == null || this.selectedProgramaID == null){
                alert("Selecione O programa e Actividade\n Preencha todos os campos!");
                return;
            }

            /**
             * -------------------------------------
             * Save data
             */

            var data = $.param({
                actividade: this.selectedActividadeID,
                programa: this.selectedProgramaID,
                benf: JSON.stringify(this.objBeneficiarios),
                material: JSON.stringify(this.objMaterial),
                dueDate: dueDate,
                time: time,
                comment: comment,
                vGasto: vGasto
            });

            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            }

            $http.post('system/api/utilitesControler.php/?store=0', data, config)
                .success(function (data, status, headers, config) {
                    $scope.storeResponse = data;
                    this.dueDate = null;
                    this.time = null;
                    this.comment = null;
                    this.vGasto = null;
                    this.objBeneficiarios = [];
                    this.objMaterial = [];

                    $scope.dueDate = null;
                    $scope.time = null;
                    $scope.comment = null;
                    $scope.vGasto = null;
                    alert(data.message);
                })
                .error(function (data, status, header, config) {
                    $scope.ResponseDetails = "Data: " + data +
                        "<hr />status: " + status +
                        "<hr />headers: " + header +
                        "<hr />config: " + config;
                });
        }


        /**
         * Query Vars
         */
        $url = 'system/api/utilitesControler.php/?getVars=0';
        $http.get($url)
            .success(function (data) {
                $scope.responseVars = data;
            }).error(function (error) {
                console.log(error);
                $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            })
    }])

