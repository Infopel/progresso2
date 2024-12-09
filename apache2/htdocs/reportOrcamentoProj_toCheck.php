<?php
	
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);

	//incluindo os ficheiros de system DB
	// Conexao PDO
	
	include_once('system/config.php');
	require_once('system/BD.class.php');

	$db = BD::conn();

	//Begin
	// Primeiro pegamos os planos na base dedos

	$listProgramas = "";
	$orc_programa = "";


	$get_projectos = $db->prepare('SELECT id as id_prog, name AS nome_projecto FROM projects AS projectos WHERE parent_id != 12 and status = 1 order by name');
	$get_projectos->execute(array());

	while ($projecto = $get_projectos->fetchObject()) {
		$listProgramas .= "<option value='".$projecto->id_prog."'><a href='#plano'>".$projecto->nome_projecto."</a></option>";
	}

	if(isset($_GET['id_prog'])){



		$get_projectos = $db->prepare('
			SELECT
				projectos.id,
				projectos.name as nome_projecto,
			    orc.value as orc_previsto
			FROM
			    projects AS projectos
			        LEFT JOIN
			    custom_values AS orc ON (orc.custom_field_id = 23
			        AND orc.customized_id = projectos.id)
			        LEFT JOIN
			    custom_values AS vgasto ON (vgasto.custom_field_id = 108
			        AND vgasto.customized_id = projectos.id)
			WHERE
				projectos.id = ?
			    AND status = 1
			    and parent_id != 12
			');
		$get_projectos->execute(array($_GET['id_prog']));
		$projecto = $get_projectos->fetchObject();

		$get_OrcamentoProj = $db->prepare("
			SELECT 
			    sum(orc_prog.value) as orAct_progama,
			    sum(v_gasto.value) as v_gasto_progama
			FROM
			    issues as i
			    left join custom_values as orc_prog on  (orc_prog.customized_id = i.id and orc_prog.custom_field_id = 29)
			    left join custom_values as v_gasto on  (v_gasto.customized_id = i.id and v_gasto.custom_field_id = 108)
			WHERE
			    project_id = ? AND tracker_id = 11
			        AND status_id = 1");
		$get_OrcamentoProj->execute(array($_GET['id_prog']));

		$info_Orcamento = $get_OrcamentoProj->fetchObject();
		$orc_programa = "<td>".$projecto->nome_projecto." Meticais</td><td><b>".number_format($projecto->orc_previsto)."</b> Meticais</td><td><b>".number_format($info_Orcamento->orAct_progama)."</b> Meticais </td><td><b>".number_format($info_Orcamento->v_gasto_progama)."</b> Meticais</td>";
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
			<li><a href="index.php"><em class="fa fa-bar-chart">&nbsp;</em>Dashboard</a></li>
			<li><a href="reportOrcamentoPD.php"><em class="fa fa-bar-chart">&nbsp;</em>Or&ccedil;amento PD</a></li>
			<li class="active"><a href="reportOrcamentoProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Or&ccedil;amento Projecto</a></li>
			<li><a href="reportBeneficiariosPD.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários de PD</a></li>
			<li><a href="reportBeneficiariosProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários de Projectos</a></li>
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
					<div class="col-md-7">
						<form action="#">
							<div class="form-group">
								<label>Selecione o Projecto</label>
								<select name='id_prog' id="select-plan" class="form-control" style="max-width: 450px">
									<option></option>>
									<?php echo $listProgramas;?>
								</select>
								<p></p>
								<button type="submit" class="btn btn-success">Gerar Graficos</button>
							</div>
						</form>
						<div class="form-group">
							<table class="table table-bordered">
								<thead>
									<tr>
							        <th>Projecto</th>
							        <th>Orcamento Programa</th>
							        <th>(+) Orcamento Actividades Projecto</th>
							        <th>Valor Gasto</th>
							      	</tr>
								</thead>
								<tbody>
									<?php echo $orc_programa;?>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<div class="col-md-7">
					<div class="panel panel-default">
						<div class="panel-heading">
							Grafico de Barras - Relatório de Or&ccedil;amento
							</div>
						<div class="panel-body">
							<div class="canvas-wrapper">
								<canvas id="bar-chart"></canvas>
							</div>
						</div>
					</div>
				</div>
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
			labels: ["A"],
			datasets: [{
				label: 'Valor Gasto',
				backgroundColor: window.chartColors.green,
				borderColor: window.chartColors.green,
				borderWidth: 1,
				data: [ <?php echo $projecto->orc_previsto;?> ]
			}, {
				label: 'Orcamento Previsto',
				backgroundColor: window.chartColors.blue,
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [ <?php echo $info_Orcamento->orAct_progama;?>]
			}, {
				label: 'Oramento do Plano Estrategico',
				backgroundColor: window.chartColors.red,
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data: [<?php echo $info_Orcamento->v_gasto_progama;?>]
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