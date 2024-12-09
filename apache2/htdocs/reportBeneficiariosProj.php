<?php
	


	//incluindo os ficheiros de system DB
	// Conexao PDO
	
	include_once('system/config.php');
	require_once('system/BD.class.php');

	$db = BD::conn();

	//Begin
	// Primeiro pegamos os planos na base dedos

	$listPlanes = "";
	$infoProjectos = "";
	$infoBeneficiarios = "";

	$get_projectos = $db->prepare('SELECT id as id_prog, name AS nome_projecto FROM projects AS projectos WHERE parent_id != 12 and status = 1 order by name');
	$get_projectos->execute(array());

	while ($projecto = $get_projectos->fetchObject()) {
		$listProjectos .= "<option value='".$projecto->id_prog."'><a href='#plano'>".$projecto->nome_projecto."</a></option>";
	}

	if(isset($_GET['id_proj'])){


	$get_projectos = $db->prepare('SELECT id as id_prog, name AS nome_projecto FROM projects AS projectos WHERE id = ? and status = 1 limit 1');
	$get_projectos->execute(array($_GET['id_proj']));

	$projecto = $get_projectos->fetchObject();
	$nomeProjecto = "".$projecto->nome_projecto."";

	$get_beneficiarios = $db->prepare("
			SELECT
				projecto.name as nome_projecto, 
			    fEtaria_bnf.value as fEtaria_bnf,
			    num_bnf.value num_bnf
			FROM
				issues as i
			    left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
			    left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
			    LEFT JOIN projects as projecto on (projecto.id = i.project_id)
			WHERE
			    i.status_id = 1
			    and tracker_id = 11
			    and projecto.parent_id is not null
			    and projecto.id = ?
			    and fEtaria_bnf.value != ' '
			    and num_bnf.value != ' '
			    ");
	$get_beneficiarios->execute(array($_GET['id_proj']));

			

	while ($beneficiarios = $get_beneficiarios->fetchObject()) {
		$infoBeneficiarios .= "<tr><td>".$beneficiarios->fEtaria_bnf."</td><td>".$beneficiarios->num_bnf."</td></tr>";
		$infoProjectos .= "<tr><td>".$beneficiarios->fEtaria_bnf."</td><td>".$beneficiarios->num_bnf."</td></tr>";
		
		// Soma todos bnf do sexo Femenino	
			$get_bnfMulheres = $db->prepare("
			SELECT 
				sum(num_bnf.value) as bnf_mulheres
			FROM
				issues as i
			    left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
			    left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
			    LEFT JOIN projects as projecto on (projecto.id = i.project_id)
			WHERE
			    i.status_id = 1
			    and tracker_id = 11
			    and project_id = ?
			    and fEtaria_bnf.value != ' '
			    and num_bnf.value != ' '
			    and fEtaria_bnf.value like '%mulher%'
			");

			$get_bnfMulheres->execute(array($_GET['id_proj']));

			$bnfMulheres = $get_bnfMulheres->fetchObject();


			// Soma todos bnf do sexo Femenino
			$get_bnfHomens = $db->prepare("
			SELECT 
				sum(num_bnf.value) as bnf_homens
			FROM
				issues as i
			    left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
			    left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
			    LEFT JOIN projects as projecto on (projecto.id = i.project_id)
			WHERE
			    i.status_id = 1
			    and tracker_id = 11
			    and project_id = ?
			    and fEtaria_bnf.value != ' '
			    and num_bnf.value != ' '
			    and fEtaria_bnf.value like '%hom%'
			");

			$get_bnfHomens->execute(array($_GET['id_proj']));

			$bnfHomens = $get_bnfHomens->fetchObject();

	}

	}

?>





<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema de Monitoria de Projectos</title>
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
				<a class="navbar-brand" href="#"><span>Sistema de Monitoria de Projectos</span></a>
				
			</div>
		</div><!-- /.container-fluid -->
	</nav>

	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<div class="profile-sidebar">
			<div class="profile-usertitle">
				<div class="profile-usertitle-name">Usuario</div>
				<div class="profile-usertitle-status"><span class="label-success"></span>Usuario1</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>

		<ul class="nav menu">
			<li><a style="font-size:11px;" href="reportOrcamentoPD.php"><em class="fa fa-bar-chart">&nbsp;</em>Orçamento PDE</a></li>
			<li><a style="font-size:11px;" href="reportOrcamentoProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Or&ccedil;amento Projectos</a></li>
			<li><a style="font-size:11px;" href="reportBeneficiariosPD.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários PDE</a></li>
			<li class="active"><a style="font-size:11px;" href="reportBeneficiariosProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários Projectos</a></li>
			<li><a style="font-size:11px;" href="reportPProv.php"><em class="fa fa-file">&nbsp;</em>Actividades por Provincia</a></li>
			<li><a style="font-size:11px;" href="reportPD.php?p=12"><em class="fa fa-file">&nbsp;</em>Actividades PDE</a></li>
			<li><a style="font-size:11px;" href="report.php?p="><em class="fa fa-file">&nbsp;</em> Actividades Projecto</a></li>
			<li><a style="font-size:11px;" href="reportOrcamentoPD.php?p=12"><em class="fa fa-file">&nbsp;</em>Orçamento PDE</a></li>
			<li><a style="font-size:11px;" href="reportOrcamento.php?p="><em class="fa fa-file">&nbsp;</em>Orçamento Projectos</a></li>
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
				<h4 class="page-header">Beneficiários Projectos</h4>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<div class="panel-body">
					<div class="col-md-6">
						<form action="#">
							<div class="form-group">
								<label>Selecione o Projecto</label>
								<select name='id_proj' id="select-plan" class="form-control">
									<option>--- Selecione o Projecto</option>
									<?php echo $listProjectos;?>
								</select>
								<p></p>
								<button type="submit" class="btn btn-success">Gerar Graficos</button>
							</div>
						</form>
						<div>
							<table class="table table-bordered">
								<p style="color:#383838; font-size: 16px"><b>Projecto: </b><?php echo $nomeProjecto?></p>
								<thead>
									<tr>
							        <th>Faixa Etária</th>
							        <th>Beneficiários</th>
							      	</tr>
								</thead>
								<tbody>
									<?php echo $infoBeneficiarios;?>
									<tr><th>Total dos Beneficiários</th>
										<td>H -> <b><?php echo number_format($bnfHomens->bnf_homens);?></b> || M -> <b><?php echo number_format($bnfMulheres->bnf_mulheres);?></b></td>
									</tr>
								</tbody>
							</table>
						</div>	
					</div>
				</div>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							Gráfico circular: Género.
							
							<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
						<div class="panel-body">
							<div class="canvas-wrapper">
								<canvas id="doughnut-chart"></canvas>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							Gráfico circular: Faixa Etária.
							
							<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
						<div class="panel-body">
							<div class="canvas-wrapper">
								<canvas id="doughnut-chart_2"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div><!--/.row-->
		</div>

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

		var bnfGenero_Config = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [
						<?php echo number_format($bnfHomens->bnf_homens);?>,
						<?php echo number_format($bnfMulheres->bnf_mulheres);?>
					],
					backgroundColor: [
						window.chartColors.green,
						window.chartColors.blue,
					],
					label: 'Dataset 1'
				}],
				labels: [
					'Total de Mulheres',
					'Total de Homens',
				]
			},
			options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: 'Beneficiários por Género'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
		};


		var bnfFaixaEtaria_Config = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [ 
						50,75
					],
					backgroundColor: [
						window.chartColors.red,
						window.chartColors.orange,
					],
					label: 'Dataset 1'
				}],
				labels: [
					'Total de Mulheres',
					'Total de Homens',
				]
			},
			options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: 'Beneficiários por Faixa Etária'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
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
							var dataString = dataset.data[index].toString();

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
			var ctx = document.getElementById('doughnut-chart').getContext('2d');
			var ctx2 = document.getElementById('doughnut-chart_2').getContext('2d');
			window.myDoughnut = new Chart(ctx, bnfGenero_Config);
			window.myDoughnut = new Chart(ctx2, bnfFaixaEtaria_Config);
		};


			
	</script>
	
</body>
</html>