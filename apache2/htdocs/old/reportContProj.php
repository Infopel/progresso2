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

	$get_planoEst = $db->prepare('SELECT id AS id_proj, name AS nome_plano FROM projects AS main_plan WHERE parent_id is null');
	$get_planoEst->execute();

	while ($planos = $get_planoEst->fetchObject()) {
		$listPlanes .= "<option value='".$planos->id_proj."'><a href='#plano'>".$planos->nome_plano."</a></option>";
	}

	if(isset($_GET['id_plan'])){

		$id_plan = $_GET['id_plan'];

		$get_projectos = $db->prepare('SELECT id, name AS nome_programa FROM projects AS projectos WHERE parent_id = ?');
		$get_projectos->execute(array($_GET['id_plan']));


		while ($programa = $get_projectos->fetchObject()) {

			$dataListPro .= '"'.$programa->nome_programa.'",';

			$get_orcamentos = $db->prepare(" SELECT sum(orc.value) as total_orcamento, sum(vgasto.value) as valor_gasto FROM projects AS projectos LEFT JOIN custom_values AS orc ON (orc.custom_field_id = 23 AND orc.customized_id = projectos.id) LEFT JOIN custom_values AS vgasto ON (vgasto.custom_field_id = 108 AND vgasto.customized_id = projectos.id) where parent_id = ?");
			$get_orcamentos->execute(array($programa->id));

			$orcamento = $get_orcamentos->fetchObject();

			$dataListOrc .= '"'.$orcamento->total_orcamento.'",';
			$dataListvGasto .= '"'.$orcamento->valor_gasto.'",';

			$listProgramas .= "<tr><td>".$programa->nome_programa."</td><td><b>". number_format($orcamento->total_orcamento)."</b> Meticais</td><td><b>".number_format($orcamento->valor_gasto)."</b> Meticais</td></tr>";

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
			<li ><a href="index.php"><em class="fa fa-bar-chart">&nbsp;</em>Or&ccedil;amento</a></li>
			<li><a href="reportOrcamentoPD.php"><em class="fa fa-bar-chart">&nbsp;</em>Or&ccedil;amento PD</a></li>
			<li><a href="reportBeneficiarios.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários de Projectos</a></li>
			<li><a href="reportIndicadorMeta.php"><em class="fa fa-bar-chart">&nbsp;</em>Indicadores / Metas</a></li>
			<li><a href="reportIndicadorPD.php"><em class="fa fa-bar-chart">&nbsp;</em>Indicadores / Metas PD</a></li>
			<li><a href="reportPProv.php"><em class="fa fa-bar-chart">&nbsp;</em>Projectos por Provincia</a></li>
			<li class="active"><a href="#"><em class="fa fa-bar-chart">&nbsp;</em>Contribuição de Projectos</a></li>
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
				<h1 class="page-header">Relatorio que capta contribuiçao dos projectos</h1>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<div class="panel-body">
						<form action="#">
							<div class="form-group" style="max-width: 400px">
								<label>Selecione o Plano Estrategico</label>
								<select name='id_plan' id="select-plan" class="form-control">
									<?php echo $listPlanes;?>
								</select>
								<p></p>
								<button type="submit" class="btn btn-success">Gerar Dados</button>
							</div>
						</form>
						<div class="form-group">
							<p>Tabela de Contribuição de Projectos</p>
							<table class="table table-bordered">
								<thead>
									<tr>
							        <th>Objectivo do PD</th>
							        <th>Nome da actividade do PD</th>
							        <th>Nome do Indicador</th>
							        <th>Meta</th>
							        <th>Realizado</th>
							        <th>% do alcance de meta</th>
							        <th>Projectos que contribuem</th>
							        <th>Valor de cada Projecto</th>
							      	</tr>
								</thead>
								<tbody>
									<tr>
							        <td>Objectivo do PD</td>
							        <td>Nome da actividade do PD</td>
							        <td>Nome do Indicador</td>
							        <td>Meta</td>
							        <td>Realizado</td>
							        <td>
							        	X%
							        </td>
							        <td>Projectos que contribuem</td>
							        <td>Valor de cada Projecto</td>
							      	</tr>
								</tbody>
							</table>
						</div>
				</div>
			</div>
		</div><!--/.row-->
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
		labels : [<?php echo $dataListPro;?>],
		datasets : [
			{
				fillColor : "rgba(220,220,220,0.5)",
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
				data : [<?php echo $dataListvGasto;?>]
			},
			{
				fillColor : "rgba(48, 164, 255, 0.2)",
				strokeColor : "rgba(48, 164, 255, 0.8)",
				highlightFill : "rgba(48, 164, 255, 0.75)",
				highlightStroke : "rgba(48, 164, 255, 1)",
				data : [<?php echo $dataListOrc;?>]
			}
		]

	}


		
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
				var chart4 = document.getElementById("pie-chart").getContext("2d");
				window.myPie = new Chart(chart4).Pie(pieData, {
				responsive: true,
				segmentShowStroke: false
				});

			};

			
	</script>
	
</body>
</html>