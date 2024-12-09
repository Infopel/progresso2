var app = angular.module('appSMP', ['ngResource']);

app.controller('timeEntrysController', ['$scope', '$http', '$sce', function ($scope, $http, $routeParams, $sce){
    $scope.actionSeleced = null;
    $scope.actionID = null;
    $scope.triggerSelected = null;
    $scope.triggerID = null;
    $scope.relationID = null;
    $scope.dataInicio = null;
    $scope.isNewEntrie = true;

    let user = parent.document.getElementsByClassName('user');
    let actividade_id = parent.document.getElementById('time_entry_issue_id');
    let user_id = user[0].href.replace('http://18.219.77.220/users/', '');

    actividade_id = actividade_id.value;

        console.log("Actiidade: "+ actividade_id);
        console.log('User: '+user.value);
        console.log('User_id: '+user_id);

    /**
         * Programa
         *
         */
    $scope.selectedPrograma = null;
    $scope.selectedProgramaID = null;
    $scope.selectPrograma = function (projecto) {
        $scope.selectedPrograma = projecto.programa;
        $scope.selectedProgramaID = projecto.id_programa;
        /**
         * Query Vars
         */
        var url = 'v0.3.1/system/api/utilitesControler.php/?getActs=0&id=' + projecto.id_programa;
        $http.get(url)
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
    $scope.objMaterial = [];

    $scope.selectedBenf = null;
    $scope.selectedBenfIndex = null;

    $scope.selectedMaterial = null;
    $scope.selectedMaterialIndex = null;

    $scope.selectBenf = function (varBenf, index) {
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
    $scope.addBeneficiarios = function (selectedBenf, benValue) {
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
    $scope.delBenf = function (varValue, index) {
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


    if (actividade_id !== "") {
        $scope.isNewEntrie = false;

        /**
         * Query by activite_id
         */
        var url = 'v0.3.1/system/api/utilitesControler.php/?getAct=0&id_actividade=' + actividade_id;
        $http.get(url)
            .success(function (data) {
                // $scope.responseAct = data;
                $scope.selectedActividade = data.actividade.subject;
                $scope.selectedActividadeID = data.actividade.id;
                $scope.selectedProgramaID = data.actividade.parent;
            }).error(function (error) {
                console.log(error);
                $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
            })

    } else {
        //TODO
    }

    $scope.gravarReal = function (comment, vGasto, time) {

        let startDate = document.getElementById('startDate');
        let endDate = document.getElementById('endDate');

        $scope.startDate = startDate.value;
        $scope.endDate = endDate.value;

        if (this.selectedActividadeID == null) {
            alert("Erro!\nPreencha todos os campos!");
            return;
        }
        if (this.selectedActividadeID == null || this.selectedProgramaID == null) {
            alert("Selecione O programa e Actividade\n Preencha todos os campos!");
            return;
        }

        if (this.startDate == null || this.startDate == "") {
            alert("Erro!\nPreencha a Data de Inicio!");
            return;
        }

        if (this.endDate == null || this.endDate == "") {
            alert("Erro!\nPreencha a Data de Fim!");
            return;
        }

        if (this.objBeneficiarios.length == 0 && this.objMaterial.length == 0) {
            alert("Erro!\nIndique os beneficiarios ou material!");
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
            startDate: this.startDate,
            endDate: this.endDate,
            time: time,
            comment: comment,
            vGasto: vGasto,
            user_id: user_id,
            actividade_id: actividade_id
        });


        var config = {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        }

        $http.post('v0.3.1/system/api/utilitesControler.php/?store=0', data, config)
            .success(function (data, status, headers, config) {

                // console.log(data);

                $scope.storeResponse = data;
                $scope.time = null;
                $scope.comment = null;
                $scope.vGasto = null;
                $scope.objBeneficiarios = [];
                $scope.objMaterial = [];
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

                alert("Ocorreu um erro!");
            });
    }


    /**
     * Query Vars
     */
    var url = 'v0.3.1/system/api/utilitesControler.php/?getVars=0';
    $http.get(url)
        .success(function (data) {
            $scope.responseVars = data;
        }).error(function (error) {
            console.log(error);
            $scope.response = "The server did not get the data from database! Contact you webmaster. #edilsonhmberto@gmail.com";
        })
}])


