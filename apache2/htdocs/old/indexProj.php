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

	$listPlanes = "";
	$dataListPro = "";
	$dataListOrc = "";
	$dataListvGasto = "";
	$listProgramas = "";
	$dataListChart ="";
	$provincia = "";

	$get_planoEst = $db->prepare('SELECT id AS id_proj, name AS nome_plano FROM projects AS main_plan WHERE parent_id IS NOT NULL AND parent_id <> 12 AND status=1');
	$get_planoEst->execute();

	while ($planos = $get_planoEst->fetchObject()) {
		$listPlanes .= "<option value='".$planos->id_proj."'><a href='#plano'>".$planos->nome_plano."</a></option>";
	}

	if(isset($_GET['id_plan'])){

		$id_plan = $_GET['id_plan'];
		$provincia = $_GET['prov'];

		$get_projectos = $db->prepare('SELECT id, name AS nome_programa FROM projects AS projectos WHERE parent_id = ?');
		$get_projectos->execute(array($_GET['id_plan']));

		while ($programa = $get_projectos->fetchObject()) {

			$dataListChart .= '"'.$programa->nome_programa.'",';

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
			<li><a href="index.php"><em class="fa fa-bar-chart">&nbsp;</em>Or&ccedil;amento PD</a></li>
			<li class="active"><a href="indexProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Or&ccedil;amento Projecto</a></li>
			<li><a href="reportBeneficiarios.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários PD</a></li>
<li><a href="reportBeneficiariosProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários Projecto</a></li>
			<!--<li><a href="reportIndicadorMeta.php"><em class="fa fa-bar-chart">&nbsp;</em>Indicadores / Metas</a></li>
			<li><a href="reportIndicadorPD.php"><em class="fa fa-bar-chart">&nbsp;</em>Indicadores / Metas PD</a></li>-->
			<li><a href="reportPProv.php"><em class="fa fa-bar-chart">&nbsp;</em>Projectos por Provincia</a></li>
			<li><a href="reportContProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Contribuição de Projectos</a></li>
			<li><a href="dashboard.php"><em class="fa fa-bar-chart">&nbsp;</em>Dashboard</a></li>
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
									<?php echo $listPlanes;?>
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
							        <th>Orcamento Previsto</th>
							        <th>Valor Gasto</th>
							      	</tr>
								</thead>
								<tbody>
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
						<ul class="pull-right panel-settings panel-button-tab-right" style="width: 80px; padding: 0px; background: none; border: none;">
							<li class="dropdown" style="width: 80px; padding: 0px; background: none; border: none;"><a class="pull-right dropdown-toggle" data-toggle="dropdown" style="width: 90px; padding: 0px" href="#"> <button type="button" class="btn btn-md btn-default">Provicias</button>
						
							</a>
								<ul class="dropdown-menu dropdown-menu-right">
									<li>
										<ul class="dropdown-settings">
											<li><a href="#">
												Provincia 1
											</a></li>
											<li class="divider"></li>
											<li><a href="#">
												Provincia 2
											</a></li>
											<li class="divider"></li>
											<li><a href="#">
												Provincia 3
											</a></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
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
	</div>

	<script src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/custom.js"></script>
	<script>
		window.onload = function () {

		var barChartData = {
		labels : [<?php echo $dataListChart;?>],
		datasets : [
			{
				fillColor : "rgba(79, 129, 189)",
				//strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
				data : [9500076.55, 9500076.55, 9500076.55, 9500076.55] // Valor Gasto
			},
			{
				fillColor : "rgba(192, 80, 77)",
				//strokeColor : "rgba(48, 164, 255, 0.8)",
				highlightFill : "rgba(48, 164, 255, 0.75)",
				highlightStroke : "rgba(48, 164, 255, 1)",
				color:"#30a5ff",
				data : [<?php echo $dataListOrc;?>] // Orcamento previsto
			},
			{
				fillColor : "rgba(145, 180, 74)",
				//strokeColor : "rgba(48, 164, 255, 0.8)",
				highlightFill : "rgba(48, 164, 255, 0.75)",
				highlightStroke : "rgba(48, 164, 255, 1)",
				data : [19500076.55, 19500076.55, 19500076.55, 19500076.55] // Somatorio do orcamento
			}
		]

	}

/*

(79, 129, 189)
(192, 80, 77)
(145, 180, 74)
*/
		
			var chart2 = document.getElementById("bar-chart").getContext("2d");
			window.myBar = new Chart(chart2).Bar(
				barChartData, {
				responsive: true,
				scaleLineColor: "rgba(0,0,0,.2)",
				scaleGridLineColor: "rgba(0,0,0,.05)",
				scaleFontColor: "#c5c7cc"
				});

				var chart3 = document.getElementById("doughnut-chart").getContext("2d");
				window.myDoughnut = new Chart(chart3).Doughnut(doughnutData, {
				responsive: true,
				segmentShowStroke: false
				});

				var chart3_1 = document.getElementById("doughnut-chart_2").getContext("2d");
				window.myDoughnut = new Chart(chart3).Doughnut(doughnutData, {
				responsive: true,
				segmentShowStroke: false
				});

				var chart4 = document.getElementById("doughnut-chart_2").getContext("2d");
				window.myPie = new Chart(chart4).Pie(pieData, {
				responsive: true,
				segmentShowStroke: false,
				dysplay: true
				});

			};

			
	</script>
	
</body>
</html>