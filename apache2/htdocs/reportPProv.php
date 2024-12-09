<?php
	//incluindo os ficheiros de system DB
	// Conexao PDO
	
	include_once('system/config.php');
	require_once('system/BD.class.php');

	$db = BD::conn();

	//Begin
	// Primeiro pegamos os planos na base dedos

	$get_planoEst = $db->prepare('SELECT id AS id_proj, name AS nome_plano FROM projects AS main_plan WHERE parent_id is null and status = 1');
	$get_planoEst->execute();

	$listPlanes = "";
	while ($planos = $get_planoEst->fetchObject()) {
		$listPlanes .= "<option value='".$planos->id_proj."'><a href='#plano'>".$planos->nome_plano."</a></option>";
	}

	$listInfoProv = "";
	$provincia = "";
	$listaProj = "";

	if(isset($_GET['provincia'])){

		$get_projectos = $db->prepare('SELECT 
		    i.id AS id_actividade,
		    i.subject AS actividade,
		    projecto.name AS nome_projecto,
		    cv_p.value AS provincia
		FROM
		    custom_values AS cv_p
		        LEFT JOIN
		    issues AS i ON (i.id = customized_id)
		        LEFT JOIN
		    projects AS projecto ON (projecto.id = project_id)
		WHERE
		    value = ?
		        AND customized_type = "Issue"
		        and status = 1
		ORDER BY nome_projecto;');

		$get_projectos->execute(array($_GET['provincia']));

		$provincia = ($_GET['provincia']);

		while ($info = $get_projectos->fetchObject()) {

			$Indicadores = $db->prepare("
				SELECT 
				    i.id AS id_indicador,
				    i.subject AS indicador,
				    projecto.name AS nome_projecto,
				    meta.value as meta,
				    realizado.value as realizado
				FROM
				    issues as i
				        LEFT JOIN
				    projects AS projecto ON (projecto.id = project_id)
				        LEFT JOIN
				    custom_values AS meta ON (meta.custom_field_id = 100
				        AND meta.customized_id = i.id)
				        LEFT JOIN
				    custom_values AS realizado ON (realizado.custom_field_id = 105
				        AND realizado.customized_id = i.id)
				WHERE
				    i.parent_id = ? and status = 1");
			
			$Indicadores->execute(array($info->id_actividade));
			$getIndicador = $Indicadores->fetchObject();

			$listInfoProv .= "<tr><td>".$info->actividade."</td><td>".$info->nome_projecto."</td><td>".$info->provincia."</td><td>".$getIndicador->indicador."</td><td>".$getIndicador->meta."</td><td>".$getIndicador->realizado."</td></tr>";
			$listaProj .= "# ".$info->nome_projecto." ";

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
				<a class="navbar-brand" href="#"><span>Sistema de Monitoria de Projectos</span> </a>
				
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
			<li><a style="font-size:11px;" href="reportBeneficiariosProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários Projectos</a></li>
			<li class="active"><a style="font-size:11px;" href="reportPProv.php"><em class="fa fa-file">&nbsp;</em>Actividades por Provincia</a></li>
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
				<h4 class="page-header">Actividades por Província</h4>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12" style="padding-left: 16px; padding-right: 16px">
					<div class=".panel .panel-default">
						<form action="#">
							<div class="form-group" style="max-width: 320px">
								<label>Selecione a Província</label>
								<select name='provincia' id="select-plan" class="form-control">
									<option value="Maputo-Cidade">Maputo-Cidade</option>
									<option value="Maputo-Província">Maputo-Província</option>
									<option value="Gaza">Gaza</option>
									<option value="Inhambane">Inhambane</option>
									<option value="Sofala">Sofala</option>
									<option value="Manica">Manica</option>
									<option value="Zambézia">Zambézia</option>
									<option value="Tete">Tete</option>
									<option value="Nampula">Nampula</option>
									<option value="Niassa">Niassa</option>
									<option value="Cabo Delgado">Cabo Delgado</option>
								</select>
								<p></p>
								<button type="submit" class="btn btn-success">Selecionar</button>
							</div>
						</form>
							<div class="panel-heading" style="margin-left: -16px;">Província: <?php echo $provincia;?></div>
							<div class=".panel-body">
								<table class="table table-bordered">
									<thead>
										<tr>
										<th>Nome da actividade</th>
								        <th style="min-width: 230px;">Projecto</th>
								        <th>Província</th>
								        <th>Indicador</th>
								        <th style="min-width: 70px;">Meta</th>
								        <th style="min-width: 70px;">Realizado</th>
								      	</tr>
									</thead>
									<tbody>
										<?php echo $listInfoProv;?>
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


			function getProv(){
					
					if(this.name = a){
						alert("a");
					}

				}
			
	</script>

</body>
</html>