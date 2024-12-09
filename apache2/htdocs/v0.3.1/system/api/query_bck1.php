<?php
/**
 * @author: Edilson D. Mucanze
 * @email: edilsonhmberto@gmail.com
 * @contacto: +258 84 821 3574
 * @date: Fevereiro de 2019
 * @Projecto: Sistema de Monitoria de Projecto
 * @Base: Merge Cells Script
 */


 // Turn off all error reporting
error_reporting(0);
 /** Connect Sistem backEnd*/
    include_once('../config.php');
	require_once('../BD.class.php');

	$db = BD::conn();
    // Get rowSpan for Objectivo Especifico with assign to objGeral


if(isset($_GET['p'])){
	$id_report = $_GET['p'];

		$get_rowObjE = $db->prepare("SELECT DISTINCT i.id, objEspecifico.id as id_objwe, objGeral.subject AS objectivoGeral,
							objEspecifico.subject AS objectivoEspecifico,
							count(objEspecifico.subject) as rowObjE
							from issues AS i
							INNER JOIN trackers AS t ON (i.tracker_id = t.id)
							LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
							LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
							LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
							LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
							LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
							LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
							LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
							LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
							LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
							LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
							WHERE i.tracker_id = 11 AND i.project_id= ? group by objectivoEspecifico Order by objEspecifico.subject, estrategia.subject");
		$get_rowObjE->execute(array($id_report));

		$tr = ""; $xi = 0;
		while ($rows = $get_rowObjE->fetchObject()) {

			// if($xi == 0){
				$get_rowObjEst = $db->prepare("SELECT DISTINCT i.id, estrategia.subject AS estrategia,
							objEspecifico.subject AS objectivoEspecifico,
							count(estrategia.subject) as rowEstr
							from issues AS i
							INNER JOIN trackers AS t ON (i.tracker_id = t.id)
							LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
							LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
							LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
							LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
							LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
							LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
							LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
							LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
							LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
							LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
							WHERE i.tracker_id = 11 AND i.project_id= ? and objEspecifico.subject = ? group by objectivoEspecifico, estrategia Order by objEspecifico.subject, estrategia.subject");
				$get_rowObjEst->execute(array($id_report, $rows->objectivoEspecifico));
				$c = 0;

				if($c == 0){
					$x = 1;
				}
				while ($rowsEst = $get_rowObjEst->fetchObject()) {

					$tds = null; $tdEst =null; $trEst = null;

					for ($i=1; $i <= $rowsEst->rowEstr; $i++) {

						if($i == $rowsEst->rowEstr && $x != 1){

							if($rowsEst->estrategia != null){
								// echo $a = "B->".$rowsEst->rowEstr."->$rowsEst->estrategia->$i</br>";
								$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS 		 objectivoGeral,
										objEspecifico.subject AS objectivoEspecifico,
										t.name AS name, ind.value AS indicador,
										cv.value AS metaNumerica, mD.value AS metaDescritiva,
										obs.value AS observacoes, fv.value AS fonteVerificacao, rea.value AS realizado, var.value AS variavel
										from issues AS i
										INNER JOIN trackers AS t ON (i.tracker_id = t.id)
										LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
										LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
										LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
										LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
										LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
										LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
										LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
										LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
										LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
										LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
										WHERE i.tracker_id = 11 AND i.project_id= ? and estrategia.subject = ? and objEspecifico.subject = ? Order by t.position ASC, i.root_id ASC");
								$get_reports->execute(array($id_report, $rowsEst->estrategia, $rowsEst->objectivoEspecifico));

								$xz  = 0;
								while ($info_report = $get_reports->fetchObject()){
									// Get Valor -> Realizado
									$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when val_real.value = null or val_real.value = '' or val_real.value like '%a%' or val_real.value like '%b%' or val_real.value like '%c%' then '0' else val_real.value end), 0) as realizado
										FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
										WHERE
											val_real.custom_field_id = 105");
									$get_realizado->execute(array($info_report->id));
									$real = $get_realizado->fetchObject();
									// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
									$get_relation = $db->prepare("SELECT DISTINCT
											projecto.name as  nome_projecto,
											sum(val_real.value) as realizado_projecto
										FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
										WHERE
											val_real.custom_field_id = 105
											GROUP BY nome_projecto
											");
									$get_relation->execute(array($info_report->id));

									//Store projectos
									$Rprojecto_name = "";
									while($relation = $get_relation->fetchObject()){

										$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';

									}

									// Se a metaNumerica for = 0 entao sera igual ao realizado de talforma que a percentagem seja de 100%.
									// Why??? Porque sem meta difinida o que for realizado sera a percentagem total
									$divsor_meta = $info_report->metaNumerica;
									$meta =  $info_report->metaNumerica;

									if($info_report->metaNumerica == 0 && $real->realizado != 0){
										$divsor_meta = $real->realizado;
									}

									if($info_report->metaNumerica == '' && $real->realizado == 0){
										$divsor_meta = 1;
										$meta = 1;
									}

									$percent_cal = number_format((($real->realizado / $divsor_meta) * 100), 2, '.', '');

									if($xz == 0){
										$da .= "
											<tr>
											<td rowspan='$i'>$rowsEst->estrategia</td>
											<td>$info_report->actividade</td>
											<td>$Rprojecto_name</td>
											<td>$info_report->indicador</td>
											<td>$info_report->variavel</td>
											<td>$info_report->metaNumerica</td>
											<td>$info_report->fonteVerificacao</td>
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->observacoes</td>
											</tr>
										";
									}else{
										$ds .= "<tr>
										<td>$info_report->actividade</td>
											<td>$Rprojecto_name</td>
											<td>$info_report->indicador</td>
											<td>$info_report->variavel</td>
											<td>$info_report->metaNumerica</td>
											<td>$info_report->fonteVerificacao</td>
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->observacoes</td>
										</tr>";

									}
									$xz++;
								}

								if($get_reports->rowCount() < 1){
									$bx  = 0;
									for ($ax=1; $ax <= $rowsEst->resImed; $ax++){
										if($bx == 0){
											$da .= "
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>";
										}else{
											$ds .= "<tr>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
												</tr>";
										}
										$bx++;
									}
								}
								$x = 2;
							}
						}

						if($x == 1){

							// echo $a = "B->".$rowsEst->rowEstr."->$rowsEst->estrategia->$i</br>";
							$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS 		 objectivoGeral,
										objEspecifico.subject AS objectivoEspecifico,
										t.name AS name, ind.value AS indicador,
										cv.value AS metaNumerica, mD.value AS metaDescritiva,
										obs.value AS observacoes, fv.value AS fonteVerificacao, rea.value AS realizado, var.value AS variavel
										from issues AS i
										INNER JOIN trackers AS t ON (i.tracker_id = t.id)
										LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
										LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
										LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
										LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
										LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
										LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
										LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
										LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
										LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
										LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
										WHERE i.tracker_id = 11 AND i.project_id= ? and estrategia.subject = ? and objEspecifico.subject = ? Order by t.position ASC, i.root_id ASC");
							$get_reports->execute(array($id_report, $rowsEst->estrategia, $rowsEst->objectivoEspecifico));
							$xz  = 0;
							while ($info_report = $get_reports->fetchObject()){
								$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when val_real.value = null or val_real.value = '' or val_real.value like '%a%' or val_real.value like '%b%' or val_real.value like '%c%' then '0' else val_real.value end), 0) as realizado
										FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
										WHERE
											val_real.custom_field_id = 105");
									$get_realizado->execute(array($info_report->id));
									$real = $get_realizado->fetchObject();
									// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
									$get_relation = $db->prepare("SELECT DISTINCT
											projecto.name as  nome_projecto,
											sum(val_real.value) as realizado_projecto
										FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
										WHERE
											val_real.custom_field_id = 105
											GROUP BY nome_projecto
											");
									$get_relation->execute(array($info_report->id));

									//Store projectos
									$Rprojecto_name = "";
									while($relation = $get_relation->fetchObject()){

										$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';

									}

								// Se a metaNumerica for = 0 entao sera igual ao realizado de talforma que a percentagem seja de 100%.
								// Why??? Porque sem meta difinida o que for realizado sera a percentagem total
								$divsor_meta = $info_report->metaNumerica;
								$meta =  $info_report->metaNumerica;

								if($info_report->metaNumerica == 0 && $real->realizado != 0){
									$divsor_meta = $real->realizado;
								}

								if($info_report->metaNumerica == '' && $real->realizado == 0){
									$divsor_meta = 1;
									$meta = 1;
								}

								$percent_cal = number_format((($real->realizado / $divsor_meta) * 100), 2, '.', '');
								if($xz == 0){
									$da .= "<td rowspan='$rowsEst->rowEstr'>$rowsEst->estrategia</td>
											<td>$info_report->actividade</td>
											<td>$Rprojecto_name</td>
											<td>$info_report->indicador</td>
											<td>$info_report->variavel</td>
											<td>$info_report->metaNumerica</td>
											<td>$info_report->fonteVerificacao</td>
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->observacoes</td>
										";
									}else{
										$ds .= "<tr>
										<td>$info_report->actividade</td>
											<td>$Rprojecto_name</td>
											<td>$info_report->indicador</td>
											<td>$info_report->variavel</td>
											<td>$info_report->metaNumerica</td>
											<td>$info_report->fonteVerificacao</td>
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->observacoes</td>
										</tr>";

									}
									$xz++;
							}

							if($get_reports->rowCount() < 1){
								$bx  = 0;
								for ($ax=1; $ax <= $rowsEst->resImed; $ax++){
									if($bx == 0){
										$da .= "
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>";
									}else{
										$ds .= "<tr>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
											</tr>";
									}
									$bx++;
								}
							}
							$x = 2;
							$rowsEst->estrategia = null;
						}
						$trd = $da.$ds;
					}

					if($c == 0){
						$tr.=
						"<tr>
							<td rowspan='$rows->rowObjE'>".$rows->objectivoGeral."</td>
							<td rowspan='$rows->rowObjE'>".$rows->objectivoEspecifico."</td>
							$trd
						</tr>";
					}else{
						$tr .= $trd;
					}

					// $rows->objectivoGeral = null;
					// $rows->objectivoEspecifico = null;

					//echo '<b>'.$rows->objectivoEspecifico.'</b></br>'.$trd.'</br>';
					$A .= $trd;
					$ds = null;
					$da = null;
					$trd = null;
					$d = null;
					$a = null;
					$tdEst .= $tdFi;
					$trEst .= $tdFs;
					// $tdFi = null; $tdFs = null;
					$c++;
				}
			// }
			if($xi == $n){
				// echo $tr;
			}

			$ZKP .= $A;

			$tab = null;
			$A = null;
			// $tr = null;
			$xi++;
		}
		//Header query
			$get_header = $db->prepare("SELECT
					campo0.name AS obj_geral, campo1.name AS obj_esp, campo2.name AS estrategia, campo3.name AS actividade, campo4.name AS indicador, campo5.name AS variavel, campo6.name AS metaNumerica, campo7.name AS metaDescritiva, campo8.name AS fonteVerificacao, campo9.name AS realizado, campo10.name AS observacoes
				FROM trackers AS campo0
					INNER JOIN trackers AS campo1 ON (campo1.id = 16)
					INNER JOIN trackers AS campo2 ON (campo2.id = 18)
					INNER JOIN trackers AS campo3 ON (campo3.id = 11)
					LEFT JOIN custom_fields AS campo4 ON (campo4.id = 97)
					LEFT JOIN custom_fields AS campo5 ON (campo5.id = 99)
					LEFT JOIN custom_fields AS campo6 ON (campo6.id = 100)
					LEFT JOIN custom_fields AS campo7 ON (campo7.id = 103)
					LEFT JOIN custom_fields AS campo8 ON (campo8.id = 46)
					LEFT JOIN custom_fields AS campo9 ON (campo9.id = 105)
					LEFT JOIN custom_fields AS campo10 ON (campo10.id = 51)
				WHERE campo0.id = 13");
			$get_header->execute(array());
			$resp_header = $get_header->fetchObject();

		$tab = "
					<thead>
						<tr class='p-2 border-0' style=' font-size: 0.78rem; '>
							<th>".$resp_header->obj_geral."</th>
							<th>".$resp_header->obj_esp."</th>
							<th>".$resp_header->estrategia."</th>
							<th>".$resp_header->actividade."</th>
							<th>Projectos Relacionados</th>
							<th>".$resp_header->indicador."</th>
							<th>".$resp_header->variavel."</th>
							<th>".$resp_header->metaNumerica."</th>
							<th>".$resp_header->fonteVerificacao."</th>
							<th>".$resp_header->realizado."</th>
							<th>Percentual</th>
							<th>".$resp_header->observacoes."</th>
						</tr>
					</thead>
					<tbody>
						$tr
					</tbody>";
	echo $tab;
	$tr = null;
	$trd = null;
}

if(isset($_GET['ano']) && !isset($_GET['periodo'])){
	getByYear($db, $_GET['ano']);
}

if(isset($_GET['periodo']) && !isset($_GET['ano'])){
	$Px = $_GET['Px'];
	$Py = $_GET['Py'];
	getByPeriodo($db, $Px, $Py);
}

if(isset($_GET['Py'], $_GET['Px'])){
	$Px = $_GET['Px'];
	$Py = $_GET['Py'];
	getByYearPeriodo($db, $_GET['ano'], $Px, $Py);
}

function getByYear($db, $ano){
	$id_report = $_GET['p'];

		$get_rowObjE = $db->prepare("SELECT DISTINCT i.id, objEspecifico.id as id_objwe, objGeral.subject AS objectivoGeral,
							objEspecifico.subject AS objectivoEspecifico,
							count(objEspecifico.subject) as rowObjE
							from issues AS i
							INNER JOIN trackers AS t ON (i.tracker_id = t.id)
							LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
							LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
							LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
							LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
							LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
							LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
							LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
							LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
							LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
							LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
							WHERE i.tracker_id = 11 AND i.project_id= ? group by objectivoEspecifico Order by objEspecifico.subject, estrategia.subject");
		$get_rowObjE->execute(array($id_report));

		$tr = ""; $xi = 0;
		while ($rows = $get_rowObjE->fetchObject()) {

			// if($xi == 0){
				$get_rowObjEst = $db->prepare("SELECT DISTINCT i.id, estrategia.subject AS estrategia,
							objEspecifico.subject AS objectivoEspecifico,
							count(estrategia.subject) as rowEstr
							from issues AS i
							INNER JOIN trackers AS t ON (i.tracker_id = t.id)
							LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
							LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
							LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
							LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
							LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
							LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
							LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
							LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
							LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
							LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
							WHERE i.tracker_id = 11 AND i.project_id= ? and objEspecifico.subject = ? group by objectivoEspecifico, estrategia Order by objEspecifico.subject, estrategia.subject");
				$get_rowObjEst->execute(array($id_report, $rows->objectivoEspecifico));
				$c = 0;

				if($c == 0){
					$x = 1;
				}
				while ($rowsEst = $get_rowObjEst->fetchObject()) {

					$tds = null; $tdEst =null; $trEst = null;

					for ($i=1; $i <= $rowsEst->rowEstr; $i++) {

						if($i == $rowsEst->rowEstr && $x != 1){

							if($rowsEst->estrategia != null){
								// echo $a = "B->".$rowsEst->rowEstr."->$rowsEst->estrategia->$i</br>";
								$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS 		 objectivoGeral,
										objEspecifico.subject AS objectivoEspecifico,
										t.name AS name, ind.value AS indicador,
										cv.value AS metaNumerica, mD.value AS metaDescritiva,
										obs.value AS observacoes, fv.value AS fonteVerificacao, rea.value AS realizado, var.value AS variavel
										from issues AS i
										INNER JOIN trackers AS t ON (i.tracker_id = t.id)
										LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
										LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
										LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
										LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
										LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
										LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
										LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
										LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
										LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
										LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
										WHERE i.tracker_id = 11 AND i.project_id= ? and estrategia.subject = ? and objEspecifico.subject = ? Order by t.position ASC, i.root_id ASC");
								$get_reports->execute(array($id_report, $rowsEst->estrategia, $rowsEst->objectivoEspecifico));

								$xz  = 0;
								while ($info_report = $get_reports->fetchObject()){
									// Get Valor -> Realizado

									// Pegar o realizado pelo ano
									if(isset($_GET['ano']) && !isset($_GET['Px']) && !isset($_GET['Py']){
										$ano = $_GET['ano'];

										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when val_real.value = null or val_real.value = '' or val_real.value like '%a%' or val_real.value like '%b%' or val_real.value like '%c%' then '0' else val_real.value end), 0) as realizado
											FROM
												issues AS act_p
												inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
												inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
												inner join issues as timelines on (timelines.id = val_real.customized_id)
											WHERE
												val_real.custom_field_id = 105
												and year(timelines.start_date) = ?");

										$get_realizado->execute(array($info_report->id, $ano));
										$real = $get_realizado->fetchObject();


										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT DISTINCT
											projecto.name as  nome_projecto,
											sum(val_real.value) as realizado_projecto
										FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
										WHERE
											val_real.custom_field_id = 105
											and year(timelines.start_date) = ? and month(timelines.start_date) >= ? and month(timelines.start_date) <= ?
											GROUP BY nome_projecto
											");
										$get_relation->execute(array($info_report->id, $ano));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){

											$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';

										}

									}else if(isset($_GET['ano'], $_GET['Px'], $_GET['Py'])){ // Pegar pelo periodo
										
										$ano = $_GET['ano'];
										$Px = $_GET['Px'];
										$Py = $_GET['Py'];

										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when val_real.value = null or val_real.value = '' or val_real.value like '%a%' or val_real.value like '%b%' or val_real.value like '%c%' then '0' else val_real.value end), 0) as realizado
											FROM
												issues AS act_p
												inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
												inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
												inner join issues as timelines on (timelines.id = val_real.customized_id)
											WHERE
												val_real.custom_field_id = 105
												and year(timelines.start_date) = ? and month(timelines.start_date) >= ? and month(timelines.start_date) <= ?");
										$get_realizado->execute(array($info_report->id, $ano, $Px, $Py));
										$real = $get_realizado->fetchObject();


										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT DISTINCT
											projecto.name as  nome_projecto,
											sum(val_real.value) as realizado_projecto
										FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
										WHERE
											val_real.custom_field_id = 105
											and year(timelines.start_date) = ? and month(timelines.start_date) >= ? and month(timelines.start_date) <= ?
											GROUP BY nome_projecto
											");
										$get_relation->execute(array($info_report->id, $ano, $Px, $Py));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){

											$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';

										}
									}else{

										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when val_real.value = null or val_real.value = '' or val_real.value like '%a%' or val_real.value like '%b%' or val_real.value like '%c%' then '0' else val_real.value end), 0) as realizado
											FROM
												issues AS act_p
												inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
												inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											WHERE
												val_real.custom_field_id = 105");
										$get_realizado->execute(array($info_report->id));
										$real = $get_realizado->fetchObject();


										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT DISTINCT
											projecto.name as  nome_projecto,
											sum(val_real.value) as realizado_projecto
										FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
										WHERE
											val_real.custom_field_id = 105
											GROUP BY nome_projecto
											");
										$get_relation->execute(array($info_report->id));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){

											$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';

										}
									}

									// Se a metaNumerica for = 0 entao sera igual ao realizado de talforma que a percentagem seja de 100%.
									// Why??? Porque sem meta difinida o que for realizado sera a percentagem total
									$divsor_meta = $info_report->metaNumerica;
									$meta =  $info_report->metaNumerica;

									if($info_report->metaNumerica == 0 && $real->realizado != 0){
										$divsor_meta = $real->realizado;
									}

									if($info_report->metaNumerica == '' && $real->realizado == 0){
										$divsor_meta = 1;
										$meta = 1;
									}

									$percent_cal = number_format((($real->realizado / $divsor_meta) * 100), 2, '.', '');

									if($xz == 0){
										$da .= "
											<tr>
											<td rowspan='$i'>$rowsEst->estrategia</td>
											<td>$info_report->actividade</td>
											<td>$Rprojecto_name</td>
											<td>$info_report->indicador</td>
											<td>$info_report->variavel</td>
											<td>$info_report->metaNumerica</td>
											<td>$info_report->fonteVerificacao</td>
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->observacoes</td>
											</tr>
										";
									}else{
										$ds .= "<tr>
										<td>$info_report->actividade</td>
											<td>$Rprojecto_name</td>
											<td>$info_report->indicador</td>
											<td>$info_report->variavel</td>
											<td>$info_report->metaNumerica</td>
											<td>$info_report->fonteVerificacao</td>
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->observacoes</td>
										</tr>";

									}
									$xz++;
								}

								if($get_reports->rowCount() < 1){
									$bx  = 0;
									for ($ax=1; $ax <= $rowsEst->resImed; $ax++){
										if($bx == 0){
											$da .= "
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>";
										}else{
											$ds .= "<tr>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
													<td>  </td>
												</tr>";
										}
										$bx++;
									}
								}
								$x = 2;
							}
						}

						if($x == 1){

							// echo $a = "B->".$rowsEst->rowEstr."->$rowsEst->estrategia->$i</br>";
							$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS 		 objectivoGeral,
										objEspecifico.subject AS objectivoEspecifico,
										t.name AS name, ind.value AS indicador,
										cv.value AS metaNumerica, mD.value AS metaDescritiva,
										obs.value AS observacoes, fv.value AS fonteVerificacao, rea.value AS realizado, var.value AS variavel
										from issues AS i
										INNER JOIN trackers AS t ON (i.tracker_id = t.id)
										LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
										LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
										LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
										LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
										LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
										LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
										LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
										LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
										LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
										LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
										WHERE i.tracker_id = 11 AND i.project_id= ? and estrategia.subject = ? and objEspecifico.subject = ? Order by t.position ASC, i.root_id ASC");
							$get_reports->execute(array($id_report, $rowsEst->estrategia, $rowsEst->objectivoEspecifico));
							$xz  = 0;
							while ($info_report = $get_reports->fetchObject()){
								$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when val_real.value = null or val_real.value = '' or val_real.value like '%a%' or val_real.value like '%b%' or val_real.value like '%c%' then '0' else val_real.value end), 0) as realizado
										FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
										WHERE
											val_real.custom_field_id = 105");
									$get_realizado->execute(array($info_report->id));
									$real = $get_realizado->fetchObject();
									// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
									$get_relation = $db->prepare("SELECT DISTINCT
											projecto.name as  nome_projecto,
											sum(val_real.value) as realizado_projecto
										FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
										WHERE
											val_real.custom_field_id = 105
											GROUP BY nome_projecto
											");
									$get_relation->execute(array($info_report->id));

									//Store projectos
									$Rprojecto_name = "";
									while($relation = $get_relation->fetchObject()){

										$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';

									}

								// Se a metaNumerica for = 0 entao sera igual ao realizado de talforma que a percentagem seja de 100%.
								// Why??? Porque sem meta difinida o que for realizado sera a percentagem total
								$divsor_meta = $info_report->metaNumerica;
								$meta =  $info_report->metaNumerica;

								if($info_report->metaNumerica == 0 && $real->realizado != 0){
									$divsor_meta = $real->realizado;
								}

								if($info_report->metaNumerica == '' && $real->realizado == 0){
									$divsor_meta = 1;
									$meta = 1;
								}

								$percent_cal = number_format((($real->realizado / $divsor_meta) * 100), 2, '.', '');
								if($xz == 0){
									$da .= "<td rowspan='$rowsEst->rowEstr'>$rowsEst->estrategia</td>
											<td>$info_report->actividade</td>
											<td>$Rprojecto_name</td>
											<td>$info_report->indicador</td>
											<td>$info_report->variavel</td>
											<td>$info_report->metaNumerica</td>
											<td>$info_report->fonteVerificacao</td>
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->observacoes</td>
										";
									}else{
										$ds .= "<tr>
										<td>$info_report->actividade</td>
											<td>$Rprojecto_name</td>
											<td>$info_report->indicador</td>
											<td>$info_report->variavel</td>
											<td>$info_report->metaNumerica</td>
											<td>$info_report->fonteVerificacao</td>
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->observacoes</td>
										</tr>";

									}
									$xz++;
							}

							if($get_reports->rowCount() < 1){
								$bx  = 0;
								for ($ax=1; $ax <= $rowsEst->resImed; $ax++){
									if($bx == 0){
										$da .= "
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>
											<td>  </td>";
									}else{
										$ds .= "<tr>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
												<td>  </td>
											</tr>";
									}
									$bx++;
								}
							}
							$x = 2;
							$rowsEst->estrategia = null;
						}
						$trd = $da.$ds;
					}

					if($c == 0){
						$tr.=
						"<tr>
							<td rowspan='$rows->rowObjE'>".$rows->objectivoGeral."</td>
							<td rowspan='$rows->rowObjE'>".$rows->objectivoEspecifico."</td>
							$trd
						</tr>";
					}else{
						$tr .= $trd;
					}

					// $rows->objectivoGeral = null;
					// $rows->objectivoEspecifico = null;

					//echo '<b>'.$rows->objectivoEspecifico.'</b></br>'.$trd.'</br>';
					$A .= $trd;
					$ds = null;
					$da = null;
					$trd = null;
					$d = null;
					$a = null;
					$tdEst .= $tdFi;
					$trEst .= $tdFs;
					// $tdFi = null; $tdFs = null;
					$c++;
				}
			// }
			if($xi == $n){
				// echo $tr;
			}

			$ZKP .= $A;

			$tab = null;
			$A = null;
			// $tr = null;
			$xi++;
		}
		//Header query
			$get_header = $db->prepare("SELECT
					campo0.name AS obj_geral, campo1.name AS obj_esp, campo2.name AS estrategia, campo3.name AS actividade, campo4.name AS indicador, campo5.name AS variavel, campo6.name AS metaNumerica, campo7.name AS metaDescritiva, campo8.name AS fonteVerificacao, campo9.name AS realizado, campo10.name AS observacoes
				FROM trackers AS campo0
					INNER JOIN trackers AS campo1 ON (campo1.id = 16)
					INNER JOIN trackers AS campo2 ON (campo2.id = 18)
					INNER JOIN trackers AS campo3 ON (campo3.id = 11)
					LEFT JOIN custom_fields AS campo4 ON (campo4.id = 97)
					LEFT JOIN custom_fields AS campo5 ON (campo5.id = 99)
					LEFT JOIN custom_fields AS campo6 ON (campo6.id = 100)
					LEFT JOIN custom_fields AS campo7 ON (campo7.id = 103)
					LEFT JOIN custom_fields AS campo8 ON (campo8.id = 46)
					LEFT JOIN custom_fields AS campo9 ON (campo9.id = 105)
					LEFT JOIN custom_fields AS campo10 ON (campo10.id = 51)
				WHERE campo0.id = 13");
			$get_header->execute(array());
			$resp_header = $get_header->fetchObject();

		$tab = "
					<thead>
						<tr class='p-2 border-0' style=' font-size: 0.78rem; '>
							<th>".$resp_header->obj_geral."</th>
							<th>".$resp_header->obj_esp."</th>
							<th>".$resp_header->estrategia."</th>
							<th>".$resp_header->actividade."</th>
							<th>Projectos Relacionados</th>
							<th>".$resp_header->indicador."</th>
							<th>".$resp_header->variavel."</th>
							<th>".$resp_header->metaNumerica."</th>
							<th>".$resp_header->fonteVerificacao."</th>
							<th>".$resp_header->realizado."</th>
							<th>Percentual</th>
							<th>".$resp_header->observacoes."</th>
						</tr>
					</thead>
					<tbody>
						$tr
					</tbody>";
	echo $tab;
	$tr = null;
	$trd = null;
}

function getByPeriodo($db, $Px = 1, $Py = 3){
	$id_report = $_GET['pID'];
		if(empty($id_report)){
			$id_report = 12; // ID do projecto pre-indicado
		}else{
			$id_report = $_GET['pID'];
		}
		$report_results =null;
		//Header query
				$get_header = $db->prepare("SELECT
					campo0.name AS obj_geral, campo1.name AS obj_esp, campo2.name AS estrategia, campo3.name AS actividade, campo4.name AS indicador, campo5.name AS variavel, campo6.name AS metaNumerica, campo7.name AS metaDescritiva, campo8.name AS fonteVerificacao, campo9.name AS realizado, campo10.name AS observacoes
					FROM trackers AS campo0
						INNER JOIN trackers AS campo1 ON (campo1.id = 16)
						INNER JOIN trackers AS campo2 ON (campo2.id = 18)
						INNER JOIN trackers AS campo3 ON (campo3.id = 11)
						LEFT JOIN custom_fields AS campo4 ON (campo4.id = 97)
						LEFT JOIN custom_fields AS campo5 ON (campo5.id = 99)
						LEFT JOIN custom_fields AS campo6 ON (campo6.id = 100)
						LEFT JOIN custom_fields AS campo7 ON (campo7.id = 103)
						LEFT JOIN custom_fields AS campo8 ON (campo8.id = 46)
						LEFT JOIN custom_fields AS campo9 ON (campo9.id = 105)
						LEFT JOIN custom_fields AS campo10 ON (campo10.id = 51)
						WHERE campo0.id = 13");

				$get_header->execute(array());

				$resp_header = $get_header->fetchObject();

				// Staring the Query
				$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS 		 objectivoGeral,
						objEspecifico.subject AS objectivoEspecifico,
						t.name AS name, ind.value AS indicador,
						cv.value AS metaNumerica, mD.value AS metaDescritiva,
						obs.value AS observacoes, fv.value AS fonteVerificacao, rea.value AS realizado, var.value AS variavel
						from issues AS i
						INNER JOIN trackers AS t ON (i.tracker_id = t.id)
						LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
						LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
						LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
						LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
						LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
						LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
						LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
						LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
						LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
						LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
						WHERE month(i.start_date) >= ? and month(i.start_date) <= ? and i.tracker_id = 11 AND i.project_id= ? Order by t.position ASC, i.root_id ASC");
				$get_reports->execute(array($Px, $Py, $id_report));
				$report_result = '';
				while ($info_report = $get_reports->fetchObject()) {
						// Get Valor -> Realizado
						$get_realizado = $db->prepare("SELECT
								COALESCE(SUM(case when val_real.value = null or val_real.value = '' or val_real.value like '%a%' or val_real.value like '%b%' or val_real.value like '%c%' then '0' else val_real.value end), 0) as realizado
							FROM
								issues AS act_p
								inner join custom_values as act_rel on ( value LIKE CONCAT('%',act_p.id,'%') and act_p.id = ?)
    							inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
							WHERE
								val_real.custom_field_id = 105");
						$get_realizado->execute(array($info_report->id));
						$real = $get_realizado->fetchObject();

						// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
						$get_relation = $db->prepare("SELECT DISTINCT
								projecto.name as  nome_projecto,
    							sum(val_real.value) as realizado_projecto
							FROM
								issues AS act_p
								inner join custom_values as act_rel on ( value LIKE CONCAT('%',act_p.id,'%') and act_p.id = ?)
								inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
								inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
								inner join projects as projecto on (projecto.id = id_projecto.project_id)
							WHERE
								val_real.custom_field_id = 105
								GROUP BY nome_projecto
								");
						$get_relation->execute(array($info_report->id));

						//Store projectos
						$Rprojecto_name = "";

						while($relation = $get_relation->fetchObject()){

							$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';

						}


						// Se a metaNumerica for = 0 enta sera igual ao realizado de talforma que a percentagem seja de 100%.
						// Why??? Por que o sem meta difinida o que for realizado sera a percentagem total
						$divsor_meta = $info_report->metaNumerica;
						$meta =  $info_report->metaNumerica;

						if($info_report->metaNumerica == 0 && $real->realizado != 0){
							$divsor_meta = $real->realizado;
						}

						if($info_report->metaNumerica == '' && $real->realizado == 0){
							$divsor_meta = 1;
							$meta = 1;
						}

						$percent_cal = number_format((($real->realizado / $divsor_meta) * 100), 2, '.', '');

						if($percent_cal > 100){
							//$percent_cal = "+ 100";
						}

						//$report_results .= "__Soma_: " .$real->realizado." =>__ ID_:".$info_report->id."<br>";
						// Teste Percent calc
						$report_results .=
						"ID__".$info_report->id."
						--> Realizado__:".$real->realizado."
						--> Meta Numerica:" .$divsor_meta."
						--> Percent___:" .$percent_cal."%"."
						-->Projecto_relacionado: <br>".$Rprojecto_name."<br>";

						// Formatando o valor para percentual

					$report_result .= "<tr>
						<td>".$info_report->objectivoGeral."</td>
						<td>".$info_report->objectivoEspecifico."</td>
						<td>".$info_report->estrategia."</td>
						<td>".$info_report->actividade."</td>
						<td>".$Rprojecto_name."</br>
						<td>".$info_report->indicador."</td>
						<td>".$info_report->variavel."</td>
						<td>".$info_report->metaNumerica."</td>
						<td>".$info_report->metaDescritiva."</td>
						<td>".$info_report->fonteVerificacao."</td>
						<td>".$real->realizado."</td>
						<td>".$percent_cal."%" ."</td>
						<td>".$info_report->observacoes."</td>
						</tr>";
				}
				$response_table =
				"<thead>
					 <tr class='p-2 border-0' style=' font-size: 0.78rem; '>
						<th>".$resp_header->obj_geral."</th>
						<th>".$resp_header->obj_esp."</th>
						<th>".$resp_header->estrategia."</th>
						<th>".$resp_header->actividade."</th>
						<th>Projectos Relacionados</th>
						<th>".$resp_header->indicador."</th>
						<th>".$resp_header->variavel."</th>
						<th>".$resp_header->metaNumerica."</th>
						<th>".$resp_header->metaDescritiva."</th>
						<th>".$resp_header->fonteVerificacao."</th>
						<th>".$resp_header->realizado."</th>
						<th>Percentual</th>
						<th>".$resp_header->observacoes."</th>
					</tr>
				</thead>
				<tbody>
					".$report_result."
				</tbody>";
					//echo $response_table;
					echo $response_table;
}

function getByYearPeriodo($db, $ano = 2019, $Px = 1, $Py = 3){
	$id_report = $_GET['pID'];
		if(empty($id_report)){
			$id_report = 12; // ID do projecto pre-indicado
		}else{
			$id_report = $_GET['pID'];
		}
		$report_results =null;
		//Header query
				$get_header = $db->prepare("SELECT
					campo0.name AS obj_geral, campo1.name AS obj_esp, campo2.name AS estrategia, campo3.name AS actividade, campo4.name AS indicador, campo5.name AS variavel, campo6.name AS metaNumerica, campo7.name AS metaDescritiva, campo8.name AS fonteVerificacao, campo9.name AS realizado, campo10.name AS observacoes
					FROM trackers AS campo0
						INNER JOIN trackers AS campo1 ON (campo1.id = 16)
						INNER JOIN trackers AS campo2 ON (campo2.id = 18)
						INNER JOIN trackers AS campo3 ON (campo3.id = 11)
						LEFT JOIN custom_fields AS campo4 ON (campo4.id = 97)
						LEFT JOIN custom_fields AS campo5 ON (campo5.id = 99)
						LEFT JOIN custom_fields AS campo6 ON (campo6.id = 100)
						LEFT JOIN custom_fields AS campo7 ON (campo7.id = 103)
						LEFT JOIN custom_fields AS campo8 ON (campo8.id = 46)
						LEFT JOIN custom_fields AS campo9 ON (campo9.id = 105)
						LEFT JOIN custom_fields AS campo10 ON (campo10.id = 51)
						WHERE campo0.id = 13");

				$get_header->execute(array());

				$resp_header = $get_header->fetchObject();

				// Staring the Query
				$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS 		 objectivoGeral,
						objEspecifico.subject AS objectivoEspecifico,
						t.name AS name, ind.value AS indicador,
						cv.value AS metaNumerica, mD.value AS metaDescritiva,
						obs.value AS observacoes, fv.value AS fonteVerificacao, rea.value AS realizado, var.value AS variavel
						from issues AS i
						INNER JOIN trackers AS t ON (i.tracker_id = t.id)
						LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
						LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
						LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
						LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
						LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
						LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
						LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
						LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
						LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
						LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
						WHERE year(i.start_date) = ? and month(i.start_date) >= ? and month(i.start_date) <= ? and i.tracker_id = 11 AND i.project_id= ? Order by t.position ASC, i.root_id ASC");
				$get_reports->execute(array($ano, $Px, $Py, $id_report));
				$report_result = '';
				while ($info_report = $get_reports->fetchObject()) {
						// Get Valor -> Realizado
						$get_realizado = $db->prepare("SELECT
								COALESCE(SUM(case when val_real.value = null or val_real.value = '' or val_real.value like '%a%' or val_real.value like '%b%' or val_real.value like '%c%' then '0' else val_real.value end), 0) as realizado
							FROM
								issues AS act_p
								inner join custom_values as act_rel on ( value LIKE CONCAT('%',act_p.id,'%') and act_p.id = ?)
    							inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
							WHERE
								val_real.custom_field_id = 105");
						$get_realizado->execute(array($info_report->id));
						$real = $get_realizado->fetchObject();

						// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
						$get_relation = $db->prepare("SELECT DISTINCT
								projecto.name as  nome_projecto,
    							sum(val_real.value) as realizado_projecto
							FROM
								issues AS act_p
								inner join custom_values as act_rel on ( value LIKE CONCAT('%',act_p.id,'%') and act_p.id = ?)
								inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
								inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
								inner join projects as projecto on (projecto.id = id_projecto.project_id)
							WHERE
								val_real.custom_field_id = 105
								GROUP BY nome_projecto
								");
						$get_relation->execute(array($info_report->id));

						//Store projectos
						$Rprojecto_name = "";

						while($relation = $get_relation->fetchObject()){

							$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';

						}


						// Se a metaNumerica for = 0 enta sera igual ao realizado de talforma que a percentagem seja de 100%.
						// Why??? Por que o sem meta difinida o que for realizado sera a percentagem total
						$divsor_meta = $info_report->metaNumerica;
						$meta =  $info_report->metaNumerica;

						if($info_report->metaNumerica == 0 && $real->realizado != 0){
							$divsor_meta = $real->realizado;
						}

						if($info_report->metaNumerica == '' && $real->realizado == 0){
							$divsor_meta = 1;
							$meta = 1;
						}

						$percent_cal = number_format((($real->realizado / $divsor_meta) * 100), 2, '.', '');

						if($percent_cal > 100){
							//$percent_cal = "+ 100";
						}

						//$report_results .= "__Soma_: " .$real->realizado." =>__ ID_:".$info_report->id."<br>";
						// Teste Percent calc
						$report_results .=
						"ID__".$info_report->id."
						--> Realizado__:".$real->realizado."
						--> Meta Numerica:" .$divsor_meta."
						--> Percent___:" .$percent_cal."%"."
						-->Projecto_relacionado: <br>".$Rprojecto_name."<br>";

						// Formatando o valor para percentual

					$report_result .= "<tr>
						<td>".$info_report->objectivoGeral."</td>
						<td>".$info_report->objectivoEspecifico."</td>
						<td>".$info_report->estrategia."</td>
						<td>".$info_report->actividade."</td>
						<td>".$Rprojecto_name."</br>
						<td>".$info_report->indicador."</td>
						<td>".$info_report->variavel."</td>
						<td>".$info_report->metaNumerica."</td>
						<td>".$info_report->metaDescritiva."</td>
						<td>".$info_report->fonteVerificacao."</td>
						<td>".$real->realizado."</td>
						<td>".$percent_cal."%" ."</td>
						<td>".$info_report->observacoes."</td>
						</tr>";
				}
				$response_table =
				"<thead>
					 <tr class='p-2 border-0' style=' font-size: 0.78rem; '>
						<th>".$resp_header->obj_geral."</th>
						<th>".$resp_header->obj_esp."</th>
						<th>".$resp_header->estrategia."</th>
						<th>".$resp_header->actividade."</th>
						<th>Projectos Relacionados</th>
						<th>".$resp_header->indicador."</th>
						<th>".$resp_header->variavel."</th>
						<th>".$resp_header->metaNumerica."</th>
						<th>".$resp_header->metaDescritiva."</th>
						<th>".$resp_header->fonteVerificacao."</th>
						<th>".$resp_header->realizado."</th>
						<th>Percentual</th>
						<th>".$resp_header->observacoes."</th>
					</tr>
				</thead>
				<tbody>
					".$report_result."
				</tbody>";
					//echo $response_table;
					echo $response_table;
}
