<?php
$servername = "127.0.0.1";
$username = "root";
$password = "webserver";
$db="bitnami_redmine";
$proj_id = $_GET['p'];
$conn = new mysqli($servername, $username, $password,$db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully<br/>";

	$sql = "SELECT DISTINCT i.id, i.subject AS actividade, t.name AS name, ind.subject AS indicador, prod.subject AS produto,
	resImed.subject AS resultadoImediato, resInter.subject AS resultadoIntermedio, resFinal.subject AS resultadoFinal,
	cv.value AS metaNumerica, mD.value AS metaDescritiva,
	obs.value AS observacoes, fv.value AS fonteVerificacao, rea.value AS realizado, var.value AS variavel
	from issues AS i
	INNER JOIN trackers AS t ON (i.tracker_id = t.id)
	LEFT JOIN issues AS ind ON ((ind.parent_id = i.id) AND ind.tracker_id=12) 	
	LEFT JOIN issues AS prod ON ((i.parent_id = prod.id) AND prod.tracker_id=2)
	LEFT JOIN issues AS resImed ON ((prod.parent_id = resImed.id OR i.parent_id = resImed.id) AND resImed.tracker_id=10)
	LEFT JOIN issues AS resInter ON ((resImed.parent_id = resInter.id OR prod.parent_id = resInter.id OR i.parent_id = resInter.id) AND resInter.tracker_id = 3)
	LEFT JOIN issues AS resFinal ON ((resInter.parent_id = resFinal.id OR resImed.parent_id = resFinal.id OR prod.parent_id = resFinal.id OR i.parent_id = resFinal.id) AND resFinal.tracker_id = 5)
	LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=ind.id)
	LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=ind.id)
	LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
	LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
	LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=ind.id)
	LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=ind.id)
	WHERE i.tracker_id = 11 AND i.project_id='".$proj_id."' Order by t.position ASC, i.root_id ASC";

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
<head>
<title>Relatórios Associacao Progresso</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
crossorigin="anonymous"></script>
<style type="text/css">
@media print {
    @page {size: A4 landscape; }
}
</style>
</head>
<body>
<? 
global $value = '0';
function formatNAN($value)
{
	return $value;
}
?>
  <h4>Relatorio de Indicadores / Metas (<?php $row=mysqli_fetch_assoc($results2); echo ($row['name'])?>)</h4>
  <input type="button" onclick="window.print();" value="Imprimir" style="background-color:green;color:white;float:right;padding-right:10px;"/>
  <br/><br/>
<table border="1" width="100%">
	<thead>
	<tr>
	        <th>Resultado Final</th>
			<th>Resultado Intermedio</th>
			<th>Resultado Imediato</th>
			<th>Produto</th>
			<th>Actividade</th>
			<th>Indicador</th>
			<th>Variavel</th>
			<th>Meta(numerica)</th>
			<th>Meta(descritiva)</th>
			<th>Fonte de verifica&ccedil;&atilde;o</th>
			<th>Realizado</th>
			<th>Percentual</th>
			<th>Observa&ccedil;&otilde;es</th>
		</tr>
	</thead>
	<tbody>
<?php
while($row=mysqli_fetch_assoc($results)){
?>

<tr>
  <td><?= ($row['resultadoFinal'])?></td>
  <td><?= ($row['resultadoIntermedio'])?></td>
  <td><?= ($row['resultadoImediato'])?></td>
  <td><?= ($row['produto'])?></td>
  <td><?= ($row['actividade'])?></td>
  <td><?= ($row['indicador'])?></td>
  <td><?= ($row['variavel'])?></td>
  <td><?= ($row['metaNumerica'])?></td>
  <td><?= ($row['metaDescritiva'])?></td>
  <td><?= ($row['fonteVerificacao'])?></td>
  <td><?= ($row['realizado'])?></td>
  <td><? = formatNAN(((($row['metaNumerica'])) / floatval(($row['realizado'])))*100); var_dump($value);
		
  ?></td>
  <td><?= ($row['observacoes'])?></td>
</tr>
<?php

}
?>
</tbody>
</table>

</body>
</html>
