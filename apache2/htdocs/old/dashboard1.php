<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL);

	//incluindo os ficheiros de system DB
	// Conexao PDO
	
	include_once('system/config.php');
	require_once('system/BD.class.php');

	$db = BD::conn();
	$listPlanes = "";
	$dataListPro = "";
	$dataListOrc = "";
	$dataListvGasto = "";
	$listProgramas = "";
	$dataListChart ="";
	$provincia = "";

	$get_planoEst = $db->prepare('SELECT id AS id_proj, name AS nome_plano FROM projects AS main_plan WHERE parent_id is null');
	$get_planoEst->execute();

	while ($planos = $get_planoEst->fetchObject()) {
		$listPlanes .= "<option value='".$planos->id_proj."'><a href='#plano'>".$planos->nome_plano."</a></option>";
	}

	if(isset($_GET['id_plan'])){

		$id_plan = 12;
		$provincia = $_GET['prov'];

		$get_projectos = $db->prepare('SELECT id, name AS nome_programa FROM projects AS projectos WHERE parent_id = ?');
		$get_projectos->execute(array($_GET['id_plan']));

		while ($programa = $get_projectos->fetchObject()) {

			$dataListChart .= '"'.$programa->nome_programa.'",';

			//$get_orcamentos = $db->prepare(" SELECT sum(orc.value) as total_orcamento, sum(vgasto.value) as valor_gasto FROM projects AS projectos LEFT JOIN custom_values AS orc ON (orc.custom_field_id = 23 AND orc.customized_id = projectos.id) LEFT JOIN custom_values AS vgasto ON (vgasto.custom_field_id = 108 AND vgasto.customized_id = projectos.id) where parent_id = ?");
			//$get_orcamentos->execute(array($programa->id));

			//$orcamento = $get_orcamentos->fetchObject();

			//$dataListOrc .= '"'.$orcamento->total_orcamento.'",';
			//$dataListvGasto .= '"'.$orcamento->valor_gasto.'",';

			//$listProgramas .= "<tr><td>".$programa->nome_programa."</td><td><b>". number_format($orcamento->total_orcamento)."</b> Meticais</td><td><b>".number_format($orcamento->valor_gasto)."</b> Meticais</td></tr>";

			
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
	<link href="https://fonts.googleapis.com/css?

family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
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
			<li><a href="indexProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Or&ccedil;amento Projecto</a></li>
			<li><a href="reportBeneficiarios.php"><em class="fa fa-bar-chart">&nbsp;</em>Beneficiários de Projectos</a></li>
			<!--<li><a href="reportIndicadorMeta.php"><em class="fa fa-bar-chart">&nbsp;</em>Indicadores / Metas</a></li>
			<li><a href="reportIndicadorPD.php"><em class="fa fa-bar-chart">&nbsp;</em>Indicadores / Metas PD</a></li>-->
			<li><a href="reportPProv.php"><em class="fa fa-bar-chart">&nbsp;</em>Projectos por Provincia</a></li>
			<li><a href="reportContProj.php"><em class="fa fa-bar-chart">&nbsp;</em>Contribuição de Projectos</a></li>
			<li class="active"><a href="dashboard.php"><em class="fa fa-bar-chart">&nbsp;</em>Dashboard</a></li>
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

			<div class="col-md-6" style="max-width:450px; margin-left: 20px; margin-right: 20px">
				<!--<form action="#">
					<div class="form-group">
						<label>Selecione o Plano Estrategico</label>
						<select name='id_plan' id="select-plan" class="form-control">
							<--?php echo $listPlanes;?>
						</select>
						<p></p>
						<button type="submit" class="btn btn-success">Selecionar Plano</button>
					</div>
				</form>-->
			</div>
		</div><!--/.row-->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel-body">
				<div class="col-md-3" style="max-width:370px; min-width: 370px; margin-left: 20px; margin-right: 20px">
					<div>
			                <div id="mozmap">
			                    <img id="map" src="imgs/map-moz-color-zone.png" usemap="#mocambique">
			                </div>
			                <map name="mocambique" id="mocambique">
			                        
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Maputo-Cidade" itemid="1">
			                                    <area shape="poly" style="fill: red" href="#" rev="-327,-132,37,83,48,409" id="Maputo-Cidade" alt="Maputo-Cidade" title="Maputo-Cidade" coords="80,440, 79,437, 78,434, 78,427, 72,419, 72,416, 65,415, 61,411, 55,411, 53,413, 53,414, 54,415, 55,429, 55,439, 55,449, 55,453, 52,455, 52,462, 56,465, 58,469, 56,473, 57,480, 59,490, 61,490, 63,492, 65,491, 76,491, 77,488, 78,478, 78,475, 78,472, 79,467, 78,468,76,472, 72,468, 69,465,67,466, 65,466, 66,465, 72,461, 73,457, 76,451, 82,446, 84,445, 83,445, 82,442, 80,440">
			                                    <p style="color: #fff; position: absolute; margin-top: -30px; margin-left: 70px">
			                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label label-default" data-original-title="Maputo-Cidade">Maputo-Cidade</span>
			                                    </p>
			                                </a>
			                            
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Maputo-Província" itemid="2">
			                                    <area shape="poly" style="fill: red" href="#" rev="-327,-132,37,83,48,409" id="Maputo-Provincia" alt="Maputo-Provincia" title="Maputo-Provincia" coords="80,440, 79,437, 78,434, 78,427, 72,419, 72,416, 65,415, 61,411, 55,411, 53,413, 53,414, 54,415, 55,429, 55,439, 55,449, 55,453, 52,455, 52,462, 56,465, 58,469, 56,473, 57,480, 59,490, 61,490, 63,492, 65,491, 76,491, 77,488, 78,478, 78,475, 78,472, 79,467, 78,468,76,472, 72,468, 69,465,67,466, 65,466, 66,465, 72,461, 73,457, 76,451, 82,446, 84,445, 83,445, 82,442, 80,440">
			                                    <p style="color: #fff; position: absolute; margin-top: -70px; margin-left: 60px">

			                                        
			                                        <span id="ListViewMapa_span1_1" data-placement="right" class="tooltips label label-default" data-original-title="Maputo-Província">Maputo-Província</span>
			                                    </p>
			                                </a>
			                            
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Gaza" itemid="3">
			                                    <area shape="poly" style="fill: red" href="#" rev="-228,-130,91,134,29,315" id="Gaza" alt="Gaza" title="Gaza" coords="111,427, 112,424, 116,417, 108,420, 104,415, 104,409, 101,407, 101,397, 101,393, 101,390, 99,385, 99,380, 85,360, 87,353, 87,346, 83,337, 85,330, 83,330, 78,332, 76,330, 74,330, 72,328, 64,324, 63,323, 63,324, 36,358, 41,380, 41,389, 46,394, 45,397, 48,403, 51,405,51,409, 53,413, 55,411,61,411, 65,415, 72,416, 72,419, 78,427, 78,434, 79,437, 80,440, 82,442, 83,445, 84,445, 96,439, 116,430, 116,429, 111,427">
			                                    <p style="color: #fff; position: absolute; margin-top: -140px; margin-left: 60px">

			                                        
			                                        <span id="ListViewMapa_span1_2" data-placement="right" class="tooltips label label-default" data-original-title="Gaza">Gaza</span>
			                                    </p>
			                                </a>
			                            
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Inhambane" itemid="4">
			                                    <area shape="poly" style="fill: red" href="#" rev="-152,-148,66,120,80,311" id="Inhambane" alt="Inhambane" title="Inhambane" coords="125,315, 123,317, 120,317, 116,320, 112,323, 108,323, 104,323, 100,327, 85,330, 85,330, 83,337, 87,346, 87,353, 85,360, 99,380, 99,385, 101,390, 101,393, 101,397, 101,407, 104,409, 104,415, 108,420, 116,417, 112,424, 111,427, 116,429, 116,430, 130,424, 133,420, 142,408,143,402, 143,398,142,397, 141,399, 140,400, 138,402, 139,399, 140,397, 140,396, 140,388, 142,384, 142,377, 144,373, 143,372, 143,367, 142,359, 143,353, 142,347, 141,348, 140,350, 139,352, 140,356, 139,358, 137,356, 138,348, 137,342, 137,335, 131,320, 130,317, 131,316, 131,314, 132,312, 132,312, 125,315">
			                                    <p style="color: #fff; position: absolute; margin-top: -150px; margin-left: 110px">

			                                        
			                                        <span id="ListViewMapa_span1_3" data-placement="right" class="tooltips label label-default" data-original-title="Inhambane">Inhambane</span>
			                                    </p>
			                                </a>
			                            
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Sofala" itemid="5">
			                                    <area shape="poly" style="fill: red" href="#" rev="-3,-118,61,164,58,174" id="Manica" alt="Manica" title="Manica" coords="72,328, 74,330, 76,330, 78,332, 83,330, 85,330, 85,330, 100,327, 104,323, 105,317, 104,314, 102,314, 102,312, 101,307, 96,304, 90,297, 91,291, 89,286, 94,281, 97,281, 100,278, 98,272, 100,272, 105,253, 100,248, 96,245, 96,243, 99,238, 99,233, 102,228, 105,231, 108,230,108,223, 107,221,106,212, 109,198, 114,193, 116,188, 115,188, 105,184, 103,182, 97,176, 93,177, 88,179, 86,185, 81,195, 79,197, 78,202, 76,204, 77,206, 76,210, 77,212, 77,218, 76,218, 76,222, 75,225, 76,231, 78,236, 78,237, 78,240, 76,239, 74,240, 76,246, 75,247, 72,247, 70,249, 70,252, 69,254, 71,255, 72,255, 74,257, 73,263, 72,266, 72,269,73,272, 73,274, 74,275, 77,274, 76,276, 79,279, 78,286, 76,286, 75,288, 74,288, 74,293, 69,301, 67,301, 64,303, 66,313, 61,318, 63,323, 64,324, 72,328">
			                                    <p style="color: #fff; position: absolute; margin-top: -260px; margin-left: 80px">

			                                        
			                                        <span id="ListViewMapa_span1_4" data-placement="right" class="tooltips label label-default" data-original-title="Manica">Manica</span>
			                                    </p>
			                                </a>
			                            
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Manica" itemid="6">
			                                    <area shape="poly" style="fill: red" href="#" rev="-72,-122,77,143,86,185" id="Sofala" alt="Sofala" title="Sofala" coords="159,239, 151,231, 149,226, 142,224, 137,216, 130,208, 125,194, 125,194, 116,188, 114,193, 109,198, 106,212, 107,221, 108,223, 108,230, 105,231, 102,228, 99,233, 99,238, 96,243, 96,245, 100,248, 105,253, 100,272, 98,272, 100,278, 97,281, 94,281, 89,286, 91,291, 90,297,96,304, 101,307, 102,312,102,313, 104,314, 105,316, 104,323, 108,323, 112,323, 116,320, 120,317, 123,317, 125,315, 132,312, 132,313, 132,312, 131,312, 130,310, 129,306, 127,305, 125,304, 122,300, 121,298, 121,294, 122,290, 123,289, 119,283, 125,279, 128,278, 139,267, 145,260, 150,254, 153,253, 156,251, 161,251, 161,251, 158,248, 158,239">
			                                    <p style="color: #fff; position: absolute; margin-top: -230px; margin-left: 115px">

			                                        
			                                        <span id="ListViewMapa_span1_5" data-placement="right" class="tooltips label label-default" data-original-title="Sofala">Sofala</span>
			                                    </p>
			                                </a>
			                            
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Tete" itemid="7">
			                                    <area shape="poly" style="fill: red" href="#" rev="0,0,145,120,-2,100" id="Tete" alt="Tete" title="Tete" coords="79,197, 81,195, 86,185, 88,179, 93,177, 97,176, 103,182, 105,184, 115,188, 116,188, 125,194, 125,194, 125,194, 130,208, 138,216, 137,206, 136,198, 136,197, 131,197, 131,194, 132,191, 132,188, 129,188, 128,186, 125,185, 125,182, 122,180, 120,178, 118,176, 117,173,115,173, 113,169, 114,166,113,163, 110,162, 109,159, 110,157, 112,155, 113,153, 114,152, 114,147, 116,145, 116,142, 116,136, 118,133, 117,128, 116,125, 116,121, 112,116, 109,117, 106,118, 104,119, 100,118, 99,120, 96,121, 95,119, 94,122, 93,121, 90,118, 87,115, 87,111, 86,110, 84,109, 84,107, 84,106, 81,105, 56,115, 45,119, 34,124, 19,128, 2,136,5,146, 7,151, 6,152, 6,154, 7,165, 26,165, 30,165, 34,170, 41,171, 46,175, 47,176, 58,178, 67,182, 68,184, 73,186, 76,186, 73,191, 73,192, 76,201, 76,203, 76,204, 78,202, 79,197">
			                                    <p style="color: #fff; position: absolute; margin-top: -360px; margin-left: 65px">

			                                        
			                                        <span id="ListViewMapa_span1_6" data-placement="right" class="tooltips label label-default" data-original-title="Tete">Tete</span>
			                                    </p>
			                                </a>
			                            
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Zambézia" itemid="8">
			                                    <area shape="poly" style="fill: red" href="#" rev="-465,0,111,120,132,133" id="Zambézia" alt="Zambézia" title="Zambézia" coords="235,180, 231,170, 231,169, 231,165, 226,156, 224,155, 217,148, 212,146, 205,141, 202,140, 200,142, 195,138, 193,138, 189,138, 186,135, 173,135, 169,141, 164,144, 159,148, 150,148, 150,148, 149,165, 143,168, 139,168, 136,170, 136,175, 135,178, 134,179, 133,180, 135,183,137,185, 137,186,137,188, 136,191, 137,193, 136,197, 136,197, 136,198, 137,206, 138,216, 142,224, 149,226, 151,231, 158,239, 158,248, 161,251, 163,249, 164,246, 166,244, 169,240, 172,236, 177,230, 179,226, 181,222, 187,216, 193,212, 200,208, 209,205, 216,202, 225,198, 235,195, 237,191, 234,186, 233,184, 235,180">
			                                    <p style="color: #fff; position: absolute; margin-top: -320px; margin-left: 165px">

			                                        
			                                        <span id="ListViewMapa_span1_7" data-placement="right" class="tooltips label label-default" data-original-title="Zambézia">Zambézia</span>
			                                    </p>
			                                </a>
			                            
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Nampula" itemid="9">
			                                    <area shape="poly" style="fill: red" href="#" rev="-344,-9,116,107,171,87" id="Nampula" alt="Nampula" title="Nampula" coords="266,90, 263,91, 259,95, 256,95, 249,98, 247,100, 241,103, 240,103, 239,104, 226,108, 219,108, 219,107, 218,109, 212,111, 209,112, 206,113, 205,111, 202,112, 198,112, 198,114, 197,116, 195,116, 194,118, 193,119, 186,122, 183,123, 177,131, 173,135, 186,135, 189,138,193,138, 195,138, 200,142,202,140, 206,141, 212,146, 217,148, 224,155, 226,156, 231,165, 231,169, 231,170, 235,180, 233,184, 234,186, 237,191, 237,191, 253,181, 255,179, 255,176, 257,174, 259,172, 265,163, 275,152, 279,146, 279,142, 276,142, 275,141, 276,140, 280,138, 280,136, 279,134, 282,131, 284,123, 284,119, 281,119, 278,119, 278,118, 280,116,280,111, 277,113, 276,111, 278,107, 276,95, 278,94, 274,92, 276,90, 274,90, 266,90">
			                                    <p style="color: #fff; position: absolute; margin-top: -385px; margin-left: 235px">

			                                        
			                                        <span id="ListViewMapa_span1_8" data-placement="right" class="tooltips label label-default" data-original-title="Nampula">Nampula</span>
			                                    </p>
			                                </a>
			                            
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Niassa" itemid="10">
			                                    <area shape="poly" style="fill: red" href="#" rev="-144,-2,116,131,108,20" id="Niassa" alt="Niassa" title="Niassa" coords="164,144, 169,141, 173,135, 177,130, 183,123, 186,122, 193,119, 194,118, 195,116, 197,116, 198,114, 198,112, 202,112, 205,111, 206,113, 209,112, 212,111, 218,109, 219,107, 214,99, 210,86, 213,82, 215,78, 213,79, 207,76, 208,70, 208,59, 214,51, 216,49, 221,42, 219,35,221,27, 216,23, 211,22,204,23, 203,30, 198,32, 195,33, 192,34, 190,33, 183,32, 181,33, 177,32, 172,36, 169,37, 169,36, 169,35, 164,35, 160,34, 160,31, 154,29, 151,27, 148,29, 144,32, 139,33, 118,31, 111,53, 114,63, 117,88, 125,88, 141,109, 143,111, 151,124, 152,130, 149,139, 150,148, 159,148, 164,144">
			                                    <p style="color: #fff; position: absolute; margin-top: -410px; margin-left: 170px">

			                                        
			                                        <span id="ListViewMapa_span1_9" data-placement="right" class="tooltips label label-default" data-original-title="Niassa">Niassa</span>
			                                    </p>
			                                </a>
			                            
			                                <a href="?id_plan=<?php echo $_GET['id_plan'];?>&prov=Cabo Delgado" itemid="11">
			                                    <area shape="poly" style="fill: red" href="#" rev="-266,-6,77,111,205,-1" id="Cabo-Delgado" alt="Cabo-Delgado" title="Cabo-Delgado" coords="277,75, 274,78, 273,73, 277,69, 280,65, 274,62, 276,58, 276,51, 277,47, 274,32, 276,27, 272,25, 275,22, 278,18, 277,16, 277,14, 280,11, 275,8, 279,5, 278,3, 278,1, 274,1, 272,3, 268,4, 262,10, 255,13, 252,14, 247,17, 246,18, 243,20, 240,20, 234,20, 230,23, 226,23,221,27, 221,27, 219,35,221,42, 216,49, 214,51, 208,59, 208,70, 207,76, 212,79, 214,78, 213,82, 210,86, 214,99, 219,107, 219,108, 226,108, 239,104, 240,103, 241,103, 247,100, 249,98, 256,95, 259,95, 263,91, 266,90, 274,90, 276,90, 277,88, 277,75">
			                                    <p style="color: #fff; position: absolute; margin-top: -440px; margin-left: 240px">

			                                        
			                                        <span id="ListViewMapa_span1_10" data-placement="right" class="tooltips label label-default" data-original-title="Cabo-Delgado">Cabo-Delgado</span>
			                                    </p>
			                                </a>
			                </map>
		            </div>
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
	</script>
	
</body>
</html>