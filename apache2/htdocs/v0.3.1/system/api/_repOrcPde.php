<?php
/**
 * @author: Edilson D. Mucanze
 * @email: edilsonhmberto@gmail.com
 * @contacto: +258 84 821 3574
 * @date: Fevereiro de 2019
 * @Projecto: Sistema de Monitoria de Projecto
 * @Base: Merge Cells Script
 */

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
					LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
					LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
					LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
					LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
					LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
					LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
					LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
					LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
					WHERE i.tracker_id = 11 AND i.project_id = ? group by objectivoEspecifico Order by estrategia.id ASC");
		$get_rowObjE->execute(array($id_report));

		$tr = ""; $xi = 0;
		$da = null;
		$ds = null;
		$A = null; $tab = null; $tdEst = null; $tdFi = null; $tdFs = null; $ZKP = null; $n = null;
		while ($rows = $get_rowObjE->fetchObject()) {

			// if($xi == 0){
				$get_rowObjEst = $db->prepare("SELECT DISTINCT i.id, estrategia.subject AS estrategia,
							objEspecifico.subject AS objectivoEspecifico,
							count(estrategia.subject) as rowEstr
							from issues AS i
							INNER JOIN trackers AS t ON (i.tracker_id = t.id)
							LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
							LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
							LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
							LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
							LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
							LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
							LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
							LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
							WHERE i.tracker_id = 11 AND i.project_id= ? and objEspecifico.subject = ? group by objectivoEspecifico, estrategia Order by estrategia.id ASC");
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
								$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, 	objGeral.subject AS  objectivoGeral,
									objEspecifico.subject AS objectivoEspecifico,
									t.name AS name, ind.value AS indicador, orc.value AS orcamento,vg.value AS valorGasto,
									obs.value AS observacoes, fv.value AS fonteVerificacao
									from issues AS i
									INNER JOIN trackers AS t ON (i.tracker_id = t.id)
									LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
									LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
									LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
									LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
									LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
									LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
									LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
									LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
									WHERE i.tracker_id = 11 AND i.project_id= ? and estrategia.subject = ? and objEspecifico.subject = ? Order by estrategia.id ASC");
								$get_reports->execute(array($id_report, $rowsEst->estrategia, $rowsEst->objectivoEspecifico));

								$xz  = 0;
								while ($info_report = $get_reports->fetchObject()){
									// Get Valor -> Realizado
									if(isset($_GET['ano']) && !isset($_GET['Px']) && !isset($_GET['Py'])){
										$ano = $_GET['ano'];
										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29 and year(timelines.start_date) = ?");

											$get_realizado->execute(array($info_report->id, $ano));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120)
											WHERE val_real.custom_field_id = 105 and year(timelines.start_date) = ? GROUP BY nome_projecto");
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
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29 and year(timelines.start_date) = ? and month(timelines.start_date) >= ? and month(timelines.start_date) <= ?");

											$get_realizado->execute(array($info_report->id, $ano, $Px, $Py));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120)
											WHERE val_real.custom_field_id = 105 and year(timelines.start_date) = ? and month(timelines.start_date) >= ? and month(timelines.start_date) <= ? GROUP BY nome_projecto");
										$get_relation->execute(array($info_report->id, $ano, $Px, $Py));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';
										}
									}else if(isset($_GET['startDate']) && isset($_GET['endDate'])){ // Por Data de Inicio e Data de Fim
										$startDate = date('Y-m-d', strtotime($_GET['startDate']));
										$endDate = date('Y-m-d', strtotime($_GET['endDate']));

										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29 and timelines.start_date >= ? and timelines.due_date <= ?");

											$get_realizado->execute(array($info_report->id, $startDate, $endDate));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120)
											WHERE val_real.custom_field_id = 105 and timelines.start_date >= ? and timelines.due_date <= ? GROUP BY nome_projecto
											");
										$get_relation->execute(array($info_report->id, $startDate, $endDate));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';
										}
									}else if(isset($_GET['startDate']) && !isset($_GET['endDate'])){ // Por Data de Inicio
										$startDate = date('Y-m-d', strtotime($_GET['startDate']));

										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29 and timelines.start_date >= ?");

											$get_realizado->execute(array($info_report->id, $startDate));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120)
											WHERE val_real.custom_field_id = 105 and timelines.start_date >= ? GROUP BY nome_projecto
											");
										$get_relation->execute(array($info_report->id, $startDate));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';
										}
									}else if(!isset($_GET['startDate']) && isset($_GET['endDate'])){ // Por data de FIM
										// $startDate = date('Y-m-d', strtotime($_GET['starDate']));
										$endDate = date('Y-m-d', strtotime($_GET['endDate']));

										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29 and timelines.due_date <= ?");

											$get_realizado->execute(array($info_report->id, $endDate));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120)
											WHERE val_real.custom_field_id = 105 and timelines.due_date <= ? GROUP BY nome_projecto");
										$get_relation->execute(array($info_report->id, $endDate));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->realizado_projecto.'</b>) '.$relation->nome_projecto.'</br>';
										}
									}else{
										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29");
										$get_realizado->execute(array($info_report->id));
										$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120) GROUP BY nome_projecto");
										$get_relation->execute(array($info_report->id));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->valorGasto.'</b>) '.$relation->nome_projecto.'</br>';
										}
									}

									if(number_format((float)($real->valorGasto)) == 0 && number_format((float)($real->orcamentoPrev)) != 0){
										$percent = number_format((float)((($real->valorGasto) / ($real->orcamentoPrev)) * 100), 2, '.', '') ."%"; //Formatando o valor para percentual
									}
									if(number_format((float)($real->valorGasto)) != 0 && number_format((float)($real->orcamentoPrev)) == 0){
										$percent = "0.00%";
									}

									if(number_format((float)($real->valorGasto)) == 0 && number_format((float)($real->orcamentoPrev)) == 0){
										$percent = "0.00%";
									}

									if(number_format((float)($real->valorGasto)) != 0 && number_format((float)($real->orcamentoPrev)) != 0){
										$percent = number_format((float)((($real->valorGasto) / ($real->orcamentoPrev)) * 100), 2, '.', '') ."%"; //Formatando o valor para percentual
									}

									if($xz == 0){
										$da .= "
											<tr>
											<td rowspan='$i'>$rowsEst->estrategia</td>
											<td>".$info_report->actividade."</td>
											<td>".$Rprojecto_name."</td>
											<td>".$info_report->indicador."</td>
											<td>".number_format((float)($real->orcamentoPrev), 2, '.', ',')."</td>
											<td>".number_format((float)($real->valorGasto), 2, '.', ',')."</td>
											<td>".$percent."</td>
											<td>".$info_report->fonteVerificacao."</td>
											</tr>
										";
									}else{
										$ds .= "<tr>
											<td>".$info_report->actividade."</td>
											<td>".$Rprojecto_name."</td>
											<td>".$info_report->indicador."</td>
											<td>".number_format((float)($real->orcamentoPrev), 2, '.', ',')."</td>
											<td>".number_format((float)($real->valorGasto), 2, '.', ',')."</td>
											<td>".$percent."</td>
											<td>".$info_report->fonteVerificacao."</td>
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
							$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS  objectivoGeral,
									objEspecifico.subject AS objectivoEspecifico,
									t.name AS name, ind.value AS indicador, orc.value AS orcamento,vg.value AS valorGasto,
									obs.value AS observacoes, fv.value AS fonteVerificacao
									from issues AS i
									INNER JOIN trackers AS t ON (i.tracker_id = t.id)
									LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
									LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
									LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
									LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
									LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
									LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
									LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
									LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
									WHERE i.tracker_id = 11 AND i.project_id= ? and estrategia.subject = ? and objEspecifico.subject = ? Order by t.position ASC, i.root_id ASC");
							$get_reports->execute(array($id_report, $rowsEst->estrategia, $rowsEst->objectivoEspecifico));
							$xz  = 0;
							while ($info_report = $get_reports->fetchObject()){
									// Get Valor -> Realizado
									// Get Valor -> Realizado
									if(isset($_GET['ano']) && !isset($_GET['Px']) && !isset($_GET['Py'])){
										$ano = $_GET['ano'];
										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29 and year(timelines.start_date) = ?");

											$get_realizado->execute(array($info_report->id, $ano));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120) and year(timelines.start_date) = ? GROUP BY nome_projecto");
										$get_relation->execute(array($info_report->id, $ano));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->valorGasto.'</b>) '.$relation->nome_projecto.'</br>';
										}

									}else if(isset($_GET['ano'], $_GET['Px'], $_GET['Py'])){ // Pegar pelo periodo
										$ano = $_GET['ano'];
										$Px = $_GET['Px'];
										$Py = $_GET['Py'];

										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29 and year(timelines.start_date) = ? and month(timelines.start_date) >= ? and month(timelines.start_date) <= ?");

											$get_realizado->execute(array($info_report->id, $ano, $Px, $Py));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120)
											WHERE val_real.custom_field_id = 105 and year(timelines.start_date) = ? and month(timelines.start_date) >= ? and month(timelines.start_date) <= ? GROUP BY nome_projecto");
										$get_relation->execute(array($info_report->id, $ano, $Px, $Py));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->valorGasto.'</b>) '.$relation->nome_projecto.'</br>';
										}
									}else if(isset($_GET['startDate']) && isset($_GET['endDate'])){ // Por Data de Inicio e Data de Fim
										$startDate = date('Y-m-d', strtotime($_GET['startDate']));
										$endDate = date('Y-m-d', strtotime($_GET['endDate']));

										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29 and timelines.start_date >= ? and timelines.due_date <= ?");

											$get_realizado->execute(array($info_report->id, $startDate, $endDate));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120)
											WHERE val_real.custom_field_id = 105 and timelines.start_date >= ? and timelines.due_date <= ? GROUP BY nome_projecto
											");
										$get_relation->execute(array($info_report->id, $startDate, $endDate));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->valorGasto.'</b>) '.$relation->nome_projecto.'</br>';
										}
									}else if(isset($_GET['startDate']) && !isset($_GET['endDate'])){ // Por Data de Inicio
										$startDate = date('Y-m-d', strtotime($_GET['startDate']));

										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29 and timelines.start_date >= ?");

											$get_realizado->execute(array($info_report->id, $startDate));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120)
											WHERE val_real.custom_field_id = 105 and timelines.start_date >= ? GROUP BY nome_projecto
											");
										$get_relation->execute(array($info_report->id, $startDate));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->valorGasto.'</b>) '.$relation->nome_projecto.'</br>';
										}
									}else if(!isset($_GET['startDate']) && isset($_GET['endDate'])){ // Por data de FIM
										// $startDate = date('Y-m-d', strtotime($_GET['starDate']));
										$endDate = date('Y-m-d', strtotime($_GET['endDate']));

										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29 and timelines.due_date <= ?");

											$get_realizado->execute(array($info_report->id, $endDate));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = val_real.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120)
											WHERE val_real.custom_field_id = 105 and timelines.due_date <= ? GROUP BY nome_projecto");
										$get_relation->execute(array($info_report->id, $endDate));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->valorGasto.'</b>) '.$relation->nome_projecto.'</br>';
										}
									}else{
										$get_realizado = $db->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
                                            inner join issues as id_projecto on (id_projecto.id = act_rel.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
											inner join issues as timelines on (timelines.id = orcamento.customized_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS val_gasto ON (val_gasto.customized_type = 'TimeEntry' AND t_entrie.id = val_gasto.customized_id AND val_gasto.custom_field_id = 119)
											WHERE orcamento.custom_field_id = 29");
										$get_realizado->execute(array($info_report->id));
										$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $db->prepare("SELECT id_projecto.id as id_projecto, act_rel.value, projecto.name as  nome_projecto,
                                            COALESCE(SUM(case when vg.value is null or vg.value = '' then '0' else vg.value end)) as valorGasto,
                                            COALESCE(SUM(case when rea.value is null or rea.value = '' then '0' else rea.value end)) as realizado_projecto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( act_rel.value LIKE CONCAT('%:#',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as val_real on (val_real.customized_id =  act_rel.customized_id)
											inner join issues as id_projecto on (id_projecto.id = val_real.customized_id)
											inner join projects as projecto on (projecto.id = id_projecto.project_id)
                                            LEFT JOIN time_entries AS t_entrie ON (t_entrie.issue_id = id_projecto.id)
											LEFT JOIN custom_values AS vg ON (vg.customized_type = 'TimeEntry' AND t_entrie.id = vg.customized_id AND vg.custom_field_id = 119)
                                            LEFT JOIN custom_values AS rea ON (rea.customized_type = 'TimeEntry' AND t_entrie.id = rea.customized_id AND rea.custom_field_id = 120)
											WHERE val_real.custom_field_id = 105 GROUP BY nome_projecto");
										$get_relation->execute(array($info_report->id));

										//Store projectos
										$Rprojecto_name = "";
										while($relation = $get_relation->fetchObject()){
											$Rprojecto_name .= '--- (<b>'.$relation->valorGasto.'</b>) '.$relation->nome_projecto.'</br>';
										}
									}

									if(number_format((float)($real->valorGasto)) == 0 && number_format((float)($real->orcamentoPrev)) != 0){
										$percent = number_format((float)((($real->valorGasto) / ($real->orcamentoPrev)) * 100), 2, '.', '') ."%"; //Formatando o valor para percentual
									}
									if(number_format((float)($real->valorGasto)) != 0 && number_format((float)($real->orcamentoPrev)) == 0){
										$percent = "0.00%";
									}

									if(number_format((float)($real->valorGasto)) == 0 && number_format((float)($real->orcamentoPrev)) == 0){
										$percent = "0.00%";
									}

									if(number_format((float)($real->valorGasto)) != 0 && number_format((float)($real->orcamentoPrev)) != 0){
										$percent = number_format((float)((($real->valorGasto) / ($real->orcamentoPrev)) * 100), 2, '.', '') ."%"; //Formatando o valor para percentual
									}
								if($xz == 0){
									$da .= "<td rowspan='$rowsEst->rowEstr'>$rowsEst->estrategia</td>
											<td>".$info_report->actividade."</td>
											<td>".$Rprojecto_name."</td>
											<td>".$info_report->indicador."</td>
											<td>".number_format((float)($real->orcamentoPrev), 2, '.', ',')."</td>
											<td>".number_format((float)($real->valorGasto), 2, '.', ',')."</td>
											<td>".$percent."</td>
											<td>".$info_report->fonteVerificacao."</td>
										";
									}else{
										$ds .= "<tr>
											<td>".$info_report->actividade."</td>
											<td>".$Rprojecto_name."</td>
											<td>".$info_report->indicador."</td>
											<td>".number_format((float)($real->orcamentoPrev), 2, '.', ',')."</td>
											<td>".number_format((float)($real->valorGasto), 2, '.', ',')."</td>
											<td>".$percent."</td>
											<td>".$info_report->fonteVerificacao."</td>
										</tr>";

									}
									$xz++;
							}
							$x = 2;
							$rowsEst->estrategia = null;
						}
						$trd = $da.$ds;
					}

					if($c == 0){
						$tr.=
						"<tr>
							<td rowspan='$rows->rowObjE'>".$rows->objectivoEspecifico."</td>
							$trd
						</tr>";
							// <td rowspan='$rows->rowObjE'>".$rows->objectivoGeral."</td>
						$OjectivoGeral = $rows->objectivoGeral;
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
					campo0.name AS obj_geral, campo1.name AS obj_esp, campo2.name AS estrategia, campo3.name AS actividade, campo4.name AS indicador, campo5.name AS orcamento, campo6.name AS valor_gasto, campo7.name AS fonteVerificacao, campo8.name AS observacoes
					FROM trackers AS campo0
						INNER JOIN trackers AS campo1 ON (campo1.id = 16)
						INNER JOIN trackers AS campo2 ON (campo2.id = 18)
						INNER JOIN trackers AS campo3 ON (campo3.id = 11)
						LEFT JOIN custom_fields AS campo4 ON (campo4.id = 97)
						LEFT JOIN custom_fields AS campo5 ON (campo5.id = 29)
						LEFT JOIN custom_fields AS campo6 ON (campo6.id = 108)
						LEFT JOIN custom_fields AS campo7 ON (campo7.id = 46)
						LEFT JOIN custom_fields AS campo8 ON (campo8.id = 51)
						WHERE campo0.id = 13");
			$get_header->execute(array());
			$resp_header = $get_header->fetchObject();

		$tab = "
					<thead>
						<tr class='p-2 border-0' style=' font-size: 0.78rem; '>
							<th>".$resp_header->obj_esp."</th>
							<th>".$resp_header->estrategia."</th>
							<th>".$resp_header->actividade."</th>
							<th>Projectos Relacionados</th>
							<th>".$resp_header->indicador."</th>
							<th>".$resp_header->orcamento."</th>
							<th>".$resp_header->valor_gasto."</th>
							<th>Percentual</th>
							<th>".$resp_header->fonteVerificacao."</th>
						</tr>
					</thead>
					<tbody>
						$tr
					</tbody>";
	header("Content-Type:"."application/json");
	echo json_encode([
		'OjectivoGeral' => $OjectivoGeral,
		'tableContent' => $tab
	]);
	$tr = null;
	$trd = null;
}
