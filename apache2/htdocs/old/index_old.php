<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);

	//incluindo os ficheiros de system DB
	// Conexao PDO
	
	include_once('system/config.php');
	require_once('system/BD.class.php');

	$db = BD::conn();

	//Begin
	// Primeiro pegamos os planos na base dedos

	$listPlans = "";
	$dataListPro = "";
	$dataListOrc = "";
	$dataListvGasto = "";
	$dataListvObjesp = "";
	$listProgramas = "";
	$dataListChart ="";
	$provincia = "";

	$orc_objEspecifico = "";

	$get_planoEst = $db->prepare('SELECT id AS id_proj, name AS nome_plano FROM projects AS main_plan WHERE parent_id is null');
	$get_planoEst->execute();

	while ($planos = $get_planoEst->fetchObject()) {
		$listPlans .= "<option value='".$planos->id_proj."'><a href='#plano'>".$planos->nome_plano."</a></option>";
	}

	if(isset($_GET['id_plan'])){

		$id_plan = $_GET['id_plan'];
		$provincia = $_GET['prov'];

		$get_projectos = $db->prepare('SELECT id, name AS nome_programa FROM projects AS projectos WHERE parent_id = ?');
		$get_projectos->execute(array($_GET['id_plan']));

			$get_orcObjectivos = $db->prepare("
			SELECT 
			    sum(orc_esp.value) as total_objEspecifivos
			FROM
			    bitnami_redmine.issues as i
			    left join custom_values as orc_esp on (orc_esp.customized_id = i.id and custom_field_id = 29)
			WHERE
			    tracker_id = 16
			    and i.project_id = ?
			ORDER BY i.id;"
			);

			$get_orcObjectivos->execute(array($_GET['id_plan']));
			$orcObjectivos = $get_orcObjectivos->fetchObject();
			$orc_objEspecifico ="<td><b>". number_format($orcObjectivos->total_objEspecifivos)."</b> Meticais</td>";

		while ($programa = $get_projectos->fetchObject()) {

			$dataListChart .= '"'.$programa->nome_programa.'",';

			$get_orcObjectivos = $db->prepare("
			SELECT 
			    sum(orc_esp.value) as total_objEspecifivos
			FROM
			    bitnami_redmine.issues as i
			    left join custom_values as orc_esp on (orc_esp.customized_id = i.id and custom_field_id = 29)
			WHERE
			    tracker_id = 16
			    and i.project_id = ?
			ORDER BY i.id;"
			);

			$get_orcObjectivos->execute(array($_GET['id_plan']));
			$orcObjectivos = $get_orcObjectivos->fetchObject();
			$dataListvObjesp .='"'.$orcObjectivos->total_objEspecifivos.'",';
			
			$get_orcamentos = $db->prepare(" SELECT sum(orc.value) as total_orcamento, sum(vgasto.value) as valor_gasto FROM projects AS projectos LEFT JOIN custom_values AS orc ON (orc.custom_field_id = 23 AND orc.customized_id = projectos.id) LEFT JOIN custom_values AS vgasto ON (vgasto.custom_field_id = 108 AND vgasto.customized_id = projectos.id) where parent_id = ?");
			$get_orcamentos->execute(array($programa->id));

			$orcamento = $get_orcamentos->fetchObject();

			$dataListOrc .= '"'.$orcamento->total_orcamento.'",';
			$dataListvGasto .= '"'.$orcamento->valor_gasto.'",';

			$listProgramas .= "<tr><td>".$programa->nome_programa."</td><td><b>". number_format($orcamento->total_orcamento)."</b> Meticais</td><td><b>".number_format($orcamento->valor_gasto)."</b> Meticais</td></tr>";

			$get_programas = $db->prepare("SELECT distinct projecto.name AS nome_projecto, p_parent.name as programa
			FROM
			    custom_values AS cv_p
			        LEFT JOIN
			    issues AS i ON (i.id = customized_id)
			        LEFT JOIN
			    projects AS projecto ON (projecto.id = project_id)
			    left join
			    projects AS p_parent ON (p_parent.id = projecto.parent_id)
			WHERE
			    value = ?
			    and
			    projecto.name is not null
			    and p_parent.name = ? ");

			$get_programas->execute(array($provincia, $programa->nome_programa));

			$dir = "";

			while ($prog_desc = $get_programas->fetchObject()) {
				$dir .= '---'.$prog_desc->nome_projecto.'</br>';
			}

			// Query num_projects, num_programas, activitys
			$get_nums = $db->prepare("SELECT 
			    count(distinct projecto.name) as num_projectos,
			    count(distinct p_parent.name) as num_programas
			FROM
			    custom_values AS cv_p
			        LEFT JOIN
			    issues AS i ON (i.id = customized_id)
			        LEFT JOIN
			    projects AS projecto ON (projecto.id = project_id)
			    left join
			    projects AS p_parent ON (p_parent.id = projecto.parent_id)
			WHERE
			    value = ?
			    and
			    projecto.name is not null
			    and p_parent.name = ?");
			$get_nums->execute(array($provincia, $programa->nome_programa));

			$return_nums = $get_nums->fetchObject();

			$dataListPro .= '<tr><td>'.$programa->nome_programa.'</td><td>'.$dir.'</td><td>'.$return_nums->num_projectos.'</td><td>'.$return_nums->num_programas.'</td></tr>';
		}

	}

	

	

	



?>





<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Associacao Progresso</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>


<body>
	<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span></button>
				<a class="navbar-brand" href="#"><span>Associacao</span> Progresso</a>
				
			</div>
		</div><!-- /.container-fluid -->
	</nav>

	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<div class="profile-sidebar">
			<div class="profile-usertitle">
				<div class="profile-usertitle-name">Usuario</div>
				<div class="profile-usertitle-status"><span class="label-success"></span>Associacao Progresso</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>
		<div style="margin: 10px" class="profile-usertitle-status">Relatórios</div>

		<ul class="nav menu">
			<li class="active"><a href="index.php"><em class="fa fa-bar-chart">&nbsp;</em>Or&ccedil;amento</a></li>
			<li><a href="reportOrcamentoPD.php"><em class="fa fa-bar-chart">&nbsp;</em>Or&ccedil;amento PD</a></li>
			<li><a href="reportBeneficiarios.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários de Projectos</a></li>
			<li><a href="reportIndicadorMeta.php"><em class="fa fa-bar-chart">&nbsp;</em>Indicadores / Metas</a></li>
			<li><a href="reportIndicadorPD.php"><em class="fa fa-bar-chart">&nbsp;</em>Indicadores / Metas PD</a></li>
			<li><a href="reportPProv.php"><em class="fa fa-bar-chart">&nbsp;</em>Projectos por Provincia</a></li>
			<li><a href="reportContProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Contribuição de Projectos</a></li>
			
		</ul>
	</div><!--/.sidebar-->


	<!-- Main Board-->

	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Relatórios</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h2 class="page-header">Relatorio de Or&ccedil;amento</h2>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<div class="panel-body">
					<div class="col-md-6">
						<p>Tabela de Orcamento</p>
						<form action="#">
							<div class="form-group">
								<label>Selecione o Plano Estrategico</label>
								<select name='id_plan' id="select-plan" class="form-control">
									<?php echo $listPlans;?>
								</select>
								<p></p>
								<button type="submit" class="btn btn-success">Gerar Graficos</button>
							</div>
						</form>
						<div class="form-group">
							<table class="table table-bordered">
								<thead>
									<tr>
							        <th>Programa</th>
							        <th>Orcamento Previsto</th>
							        <th>Valor Gasto</th>
							      	</tr>
								</thead>
								<tbody>
									<tr>
										<td>Oramento do Plano Estrategico</td>
										<?php echo $orc_objEspecifico;?>
										<td>---------</td>
									</tr>
									<?php echo $listProgramas;?>
								</tbody>
							</table>
						</div>

					</div>
					<div class="col-md-6"></div>
				</div>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Grafico de Barras - Relatório de Or&ccedil;amento
						</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="main-chart" id="bar-chart" height="200" width="600"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div><!--/.row-->
		<style type="text/css">
			.prov-li{
			    width: 100%;
			    padding: 8px;
			    list-style: none;
			    margin: 0px;
			    .background-color: #a0ce4e !important;	
			}
			.prov-li a{
				text-decoration:none;
				color: #383838;
				padding: 8px;
				font-weight: bold;
			}
		</style>
		<div class="row">
			<div class="col-lg-12">
				<h2 class="page-header">Relatorio de Or&ccedil;amento</h2>
			</div>

			<div class="col-md-3" style="max-width:370px; min-width: 370px;">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Provincias</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<ul style="margin-left: 0; padding: 0; padding-bottom: 0; margin-bottom: 0;">
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Maputo-Cidade">Maputo-Cidade</a>
									</li>
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Maputo-Província">Maputo-Província</a>
									</li>
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Gaza">Gaza</a>
									</li>
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Inhambane">Inhambane</a>
									</li>
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Sofala">Sofala</a>
									</li>
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Manica">Manica</a>
									</li>
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Zambézia">Zambézia</a>
									</li>
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Tete">Tete</a>
									</li>
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Nampula">Nampula</a>
									</li>
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Niassa">Niassa</a>
									</li>
									<li class="prov-li">
										<a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Cabo Delgado">Cabo Delgado</a>
									</li>
								</ul>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				
				<table class=" table table-bordered">
					<thead>
						<tr>
							<td style="border:none">Provincia: <b><?php echo $provincia;?></b></td>
							<td style="border:none"></td>
							<td style="border:none"></td>
							<td style="border:none"></td>
						</tr>
						<tr>
							<th>Descricao dos Projectos</th>
							<th>(Desc) dos Programas</th>
							<th><b>(N)</b> Projectos</th>
							<th><b>(N)</b> Programas</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php echo $dataListPro;?>
						</tr>
					</tbody>
				</table>
			</div>

		</div><!--/.row-->

	</div>

	<script src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>


	<!--/.
			<script src="js/chart.min.js"></script>
			<script src="js/chart-data.js"></script>
		row-->

	<script src="http://www.chartjs.org/dist/2.7.2/Chart.bundle.js"></script>
	<script src="http://www.chartjs.org/samples/latest/utils.js"></script>

	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/custom.js"></script>
	<script>
		var randomScalingFactor = function() {
			return Math.round(Math.random() * 100);
		};

		var color = Chart.helpers.color;
		var barChartData = {
			labels: [<?php echo $dataListChart;?>],
			datasets: [{
				label: 'Valor Gasto',
				backgroundColor: window.chartColors.green,
				borderColor: window.chartColors.green,
				borderWidth: 1,
				data: [ <?php echo $dataListvGasto;?> ]
			}, {
				label: 'Orcamento Previsto',
				backgroundColor: window.chartColors.blue,
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [ <?php echo $dataListOrc?>]
			}, {
				label: 'Oramento do Plano Estrategico',
				backgroundColor: window.chartColors.red,
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data: [<?php echo $dataListvObjesp;?>
				]
			}]

		};


		// Define a plugin to provide data labels
		Chart.plugins.register({
			afterDatasetsDraw: function(chart) {
				var ctx = chart.ctx;

				chart.data.datasets.forEach(function(dataset, i) {
					var meta = chart.getDatasetMeta(i);
					if (!meta.hidden) {
						meta.data.forEach(function(element, index) {
							// Draw the text in black, with the specified font
							ctx.fillStyle = 'rgb(0, 0, 0)';

							var fontSize = 16;
							var fontStyle = 'normal';
							var fontFamily = 'Helvetica Neue';
							ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

							// Just naively convert to string for now
							var dataString = dataset.data[index].toString() + " Mts";

							// Make sure alignment settings are correct
							ctx.textAlign = 'center';
							ctx.textBaseline = 'middle';

							var padding = 5;
							var position = element.tooltipPosition();
							ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
						});
					}
				});
			}
		});


		window.onload = function() {
			var ctx = document.getElementById('bar-chart').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: 'Grafico de Barras - Relatório de Orçamento'
					}
				}
			});

		};

			
	</script>
	
</body>
</html>