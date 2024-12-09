<?php
$servername = "127.0.0.1:3306";
$username = "root";
$password = "Password";
$db="bitnami_redmine";
$proj_id = $_GET['p'];
$conn = new mysqli($servername, $username, $password,$db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully<br/>";

	$sql = "SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS objectivoGeral,
    objEspecifico.subject AS objectivoEspecifico, 
	t.name AS name, ind.subject AS indicador, 
	cv.value AS metaNumerica, mD.value AS metaDescritiva,
	obs.value AS observacoes, fv.value AS fonteVerificacao, rea.value AS realizado, var.value AS variavel
	from issues AS i
	INNER JOIN trackers AS t ON (i.tracker_id = t.id)
	LEFT JOIN issues AS ind ON (ind.parent_id = i.id AND ind.tracker_id=12) 
	LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
	LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
	LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
	LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=ind.id)
	LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=ind.id)
	LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
	LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
	LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=ind.id)
	LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=ind.id)
	WHERE i.tracker_id = 11 AND i.project_id='".$proj_id."' Order by t.position ASC,i.root_id ASC, i.parent_id ASC";

	$sql2 = "SELECT * from projects WHERE id='".$proj_id."'";
	
    $results=mysqli_query($conn, $sql);
	
	$results2=mysqli_query($conn, $sql2);
	
	//while($row=mysqli_fetch_assoc($results)){
		
		//var_dump($row);
	//}
	$num=count(($results));
	//echo $num;
  ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!DOCTYPE HTML PUBLIC "//-w3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html5/strict/.dtd">

<html>
<title>Relatórios Associacao Progresso</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema de Monitoria de Projectos</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?

family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
<style type="text/css">
@media print {
    @page {size: A4 landscape; }
}
</style>
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
			<li><a style="font-size:11px;" href="reportBeneficiariosProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários Projectos</a></li>
			<li><a style="font-size:11px;" href="reportPProv.php"><em class="fa fa-file">&nbsp;</em>Actividades por Provincia</a></li>
			<li class="active"><a style="font-size:11px;" href="reportPD.php?p=12"><em class="fa fa-file">&nbsp;</em>Actividades PDE</a></li>
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
				 <h4>Actividades PDE (<?php $row=mysqli_fetch_assoc($results2); echo ($row['name'])?>)</h4>
			</div>
		</div><!--/.row-->

		<div class="row">
  <input type="button" onclick="window.print()" value="Imprimir" style="background-color:green;color:white;float:right;padding-right:10px;"/>
  <br/><br/>
<table border="1" width="100%">
	<thead>
	<tr>
	        <th style="font-size:10px;">Obj.Geral</th>
			<th style="font-size:10px;">Obj.Especifico</th>
			<th style="font-size:10px;">Estrategia</th>
			<th style="font-size:10px;">Actividade</th>
			<th style="font-size:10px;">Indicador</th>
			<th style="font-size:10px;">Variavel</th>
			<th style="font-size:10px;">Meta(numerica)</th>
			<th style="font-size:10px;">Meta(descritiva)</th>
			<th style="font-size:10px;">Fonte de verifica&ccedil;&atilde;o</th>
			<th style="font-size:10px;">Realizado</th>
			<th style="font-size:10px;">Percentual</th>
			<th style="font-size:10px;">Observa&ccedil;&otilde;es</th>
		</tr>
	</thead>
	<tbody>
<?php
while($row=mysqli_fetch_assoc($results)){
?>
<tr>
  <td style="font-size:8px;"><?= ($row['objectivoGeral'])?></td>
  <td style="font-size:8px;"><?= ($row['objectivoEspecifico'])?></td>
  <td style="font-size:8px;"><?= ($row['estrategia'])?></td>
  <td style="font-size:8px;"><?= ($row['actividade'])?></td>
  <td style="font-size:8px;"><?= ($row['indicador'])?></td>
  <td style="font-size:8px;"><?= ($row['variavel'])?></td>
  <td style="font-size:8px;"><?= ($row['metaNumerica'])?></td>
  <td style="font-size:8px;"><?= ($row['metaDescritiva'])?></td>
   <td style="font-size:8px;"><?= ($row['fonteVerificacao'])?></td>
  <td style="font-size:8px;"><?= ($row['realizado'])?></td>
  <td style="font-size:8px;"><?= (floatval(($row['metaNumerica'])) / floatval(($row['realizado'])))*100?></td>
  <td style="font-size:8px;"><?= ($row['observacoes'])?></td>
</tr>
<?php

}
?>
</tbody>
</table></div></div>
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
	</script>
</body>
</html>
