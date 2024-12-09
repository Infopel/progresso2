<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//incluindo os ficheiros de system DB
// Conexao PDO

include_once('system/config.php');
require_once('system/BD.class.php');

$db = BD::conn();

?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema de Monitoria de Projectos</title>

	<!-- Bootstrap core CSS -->
	<!-- <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
	<!-- Custom styles for this template -->
	<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />

	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	<!-- MVC -->
	<script src="v0.3.1/js/angular.min.js"></script>
	<script src="v0.3.1/js/angular-resource/angular-resource.js"></script>
	<script src="scripts/main.js"></script>

	<style>
		.feather {
			width: 16px;
			height: 16px;
			vertical-align: text-bottom;
		}
	</style>
</head>

<script>
	// Get ID do Usuario do Redmine e a Actividade/ Issue
	// let user_id = parent.getElementsByClassName('user');
	// console.log(user_id[0]);
</script>

<body ng-app="appSMP">

	<div class="col-10" ng-controller="timeEntrysController">
		<div class="row mb-4">
			<div class="col-md-12">
				<div class="row">
				</div>
				<div class="row col-12">
					<div class="w-100" id="bnf-materiais">
						<div class="row">
							<div class="col-md-12 p-2 mr-4" style="min-width: 450px !important;">
								<div class="card p-3 box-shadow border-0">
									<div class="graf-title d-none">
										<p style="cursor: default; color:rgb(117, 117, 117) ; user-select: none;-webkit-font-smoothing: antialiased; font-family: Roboto;font-size: 16px;">
											<!-- <h2 style="color:#555">Registar realizados</h2> -->
										</P>
										<p style=" margin-top: -18px; color:rgb(189, 189, 189);; cursor: default; user-select: none;-webkit-font-smoothing: antialiased;font-family: Roboto;font-size: 14px">
											....
										</p>
									</div>

									<div>
										<div class="dropdown mr-2 dropright" ng-if='isNewEntrie'>
											<button class="btn btn-sm btn-light border-bottom dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Projecto <span style="color:#bb0000"> *</span>:
											</button>
											<b> {{selectedPrograma}}</b>
											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="font: 400 14.3333px Arial;">
												<div class="dropdown-item input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text" id="basic-addon1"><span data-feather="search"></span>
													</div>
													<input style="font: 400 14.3333px Arial;" type="text" class="form-control" ng-model="searchText" placeholder="pesquisar" aria-describedby="basic-addon1">
												</div>
												<div class="dropdown-divider"></div>

												<div ng-repeat="(key, value) in responseVars.programas">
													<h6 class="dropdown-header">{{key}}</h6>
													<a href="" ng-repeat="projecto in value | filter:searchText" class="dropdown-item" style="cursor:pointer" ng-click="selectPrograma(projecto)">&nbsp;&nbsp;» {{projecto.programa}}</a>
												</div>
											</div>
										</div>

										<div class="dropdown mr-2 mt-3 dropright">
											<button class="btn btn-sm btn-light border-bottom dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Actividade <span style="color:#bb0000"> *</span>:
											</button>
											<b> {{selectedActividade}}</b>
											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="font: 400 14.3333px Arial;">
												<div class="dropdown-item input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text" id="basic-addon1"><span data-feather="search"></span>
													</div>
													<input style="font: 400 14.3333px Arial;" type="text" class="form-control" ng-model="searchAct" placeholder="pesquisar" aria-label="Username" aria-describedby="basic-addon1">
												</div>
												<div class="dropdown-divider"></div>

												<div ng-repeat="(key, value) in responseAct.actividades">
													<h6 class="dropdown-header">{{key}}</h6>
													<a href="" ng-repeat="actividade in value | filter:searchAct" class="dropdown-item" style="cursor:pointer" ng-click="selectActividade(actividade)">&nbsp;&nbsp;» {{actividade.subject}}</a>
												</div>
											</div>
										</div>

										<div class="form-group row m-0 mt-3">
											<div class="row m-0 mb-2">
												<label class="col-form-label text-left btn-sm btn-light border-success border-bottom">Data de Inicio <span style="color:#bb0000"> *</span>:</label>
												<div class="col-md-6">
													<input type="text" ng-model="startDate" id="startDate" class="form-control form-control-sm" placeholder="mm/dd/yyyy">
												</div>
											</div>

											<div class="row m-0 mb-2">
												<label class="col-form-label text-left btn-sm btn-light border-success border-bottom">Data de Fim <span style="color:#bb0000"> *</span>:</label>{{dueDate}}
												<div class="col-md-6">
													<input type="text" ng-model="endDate" id="endDate" class="form-control form-control-sm" placeholder="mm/dd/yyyy">
												</div>
											</div>

											<div class="row m-0 mb-2">
												<label class="col-form-label text-left btn-sm btn-light border-danger border-bottom">Horas:</label>
												<div class="col-md-6 col-lg-8">
													<input type="text" ng-model="time" id="time" class="form-control form-control-sm" placeholder="1.00">
												</div>
											</div>
										</div>

										<div class="row m-0">
											<div class="mt-3" id="list_bnf">
												<h6>Indicar Beneficiarios <span style="color:#bb0000"> *</span></h6>
												<form name="regForm" ng-submit="addBeneficiarios(selectedBenf, benValue)" id="regForm" novalidate>
													<div class="dropdown mr-2 row m-0">
														<button class="btn btn-sm btn-light border-bottom dropdown-toggle mr-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Beneficiarios: <b>{{selectedBenf}}</b>
														</button>

														<div class="mr-2">
															<input style="max-width:80px" ng-model='benValue' type="text" id='benValue' class="form-control form-control-sm" placeholder="val" required>
														</div>

														<div class="mr-2">
															<button type="submit" class="btn btn-sm btn-primary" ng-disabled="regForm.$invalid || selectedBenf == null"><span data-feather="plus"></span> Adicionar</button>
														</div>

														<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="font: 400 14.3333px Arial;">
															<div class="p-2" disabled>
																Selecione a variavel
															</div>
															<div class="dropdown-divider"></div>
															<a href="" class="dropdown-item" ng-repeat='benf_vars in responseVars.benf_vars' style="cursor:pointer" ng-click="selectBenf(benf_vars.var, $index)">{{benf_vars.var}}</a>
														</div>
													</div>
												</form>

												<div class="p-2 border-right mt-2">
													<div class="row m-0" ng-repeat='beneficiarios in objBeneficiarios'>
														{{beneficiarios.var}} »&nbsp;<b>{{beneficiarios.realizado}}</b>
														<a href="" class="ml-3 text-danger" ng-click="delBenf(beneficiarios.var, $index)"><span style="margin-top:4px" data-feather="minus-square"></span></a>
													</div>
												</div>

												<div class="form-group row m-0 mt-3 d-none">
													<div class="row m-2" ng-repeat='benf_vars in responseVars.benf_vars'>
														<label style="min-width:220px; max-width:220px" class="mr-3 col-form-label text-left btn-sm btn-light border-bottom">{{benf_vars.var}}</label>
														<div>
															<input style="max-width:100px" type="text" class="form-control form-control-sm" placeholder="val">
														</div>
													</div>
												</div>
											</div>

											<div class="mt-3" id="list_material">
												<h6>Indicar Material</h6>
												<form name="regMatForm" ng-submit="addMateriais(selectedMaterial, matValue)" id="regMatForm" novalidate>
													<div class="dropdown mr-2 row m-0">
														<button class="btn btn-sm btn-light border-bottom dropdown-toggle mr-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Materia: <b>{{selectedMaterial}}</b>
														</button>

														<div class="mr-2">
															<input style="max-width:100px" ng-model='matValue' type="text" id='matValue' class="form-control form-control-sm" placeholder="val" required>
														</div>

														<div class="mr-2">
															<button type="submit" class="btn btn-sm btn-primary" ng-disabled="regMatForm.$invalid || selectedMaterial == null"><span data-feather="plus"></span>Adicionar</button>
														</div>

														<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="font: 400 14.3333px Arial;">
															<div class="p-2" disabled>
																Selecione a variavel:
															</div>
															<div class="dropdown-divider"></div>
															<a href="" class="dropdown-item" ng-repeat='material_vars in responseVars.material_vars' style="cursor:pointer" ng-click="selectMaterial(material_vars.var, $index)">{{material_vars.var}}</a>
														</div>
													</div>
												</form>

												<div class="p-2 mt-2">
													<div class="row m-0" ng-repeat='material in objMaterial'>
														{{material.var}} »&nbsp;<b>{{material.realizado}}</b>
														<a href="" class="ml-3 text-danger" ng-click="delMaterial(material.var, $index)"><span style="margin-top:4px" data-feather="minus-square"></span></a>
													</div>
												</div>

											</div>

										</div>

										<div class="m-0 mt-4">
											<div class="mt-2 mb-2">
												<div class="row m-0 mb-2">
													<label class="col-form-label text-left btn-sm">Comentário</label>
													<div class="col-md-8">
														<input type="text" ng-model="comment" id="comment" class="form-control form-control-sm" placeholder="Comentário">
													</div>
												</div>
											</div>

											<div class="mt-2 mb-2">
												<div class="row m-0 mb-2">
													<label class="col-form-label text-left btn-sm">Valor Gasto:</label>
													<div class="col-md-8">
														<input type="text" ng-model="vGasto" id="vGasto" class="form-control form-control-sm" placeholder="0.00">
													</div>
												</div>
											</div>

											<div class="mt-3" id="list_material">
												<div class="mr-2">
													<button type="submit" class="btn btn-sm btn-success" ng-click="gravarReal(comment, vGasto, time)"><span data-feather="save"></span>
														Gravar dados</button>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>

	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

	<!-- Icons -->
	<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
	<script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
	<script>
		feather.replace();
	</script>

	<script>
		feather.replace();
		$(document).ready(function() {

			$('#startDate').datepicker({
				uiLibrary: 'bootstrap4'
			});

			$('#endDate').datepicker({
				uiLibrary: 'bootstrap4'
			});

			$("#time").on("keypress keyup blur", function(event) {
				$(this).val($(this).val().replace(/[^0-9\.]/g, ''));
				if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
					event.preventDefault();
				}
			});

			$("#matValue").on("keypress keyup blur", function(event) {
				$(this).val($(this).val().replace(/[^0-9\.]/g, ''));
				if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
					event.preventDefault();
				}
			});

			$("#benValue").on("keypress keyup blur", function(event) {
				$(this).val($(this).val().replace(/[^0-9\.]/g, ''));
				if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
					event.preventDefault();
				}
			});

		});
	</script>
	<style>
		.gj-icon {
			margin-top: -4px;
		}
	</style>
</body>

</html>
