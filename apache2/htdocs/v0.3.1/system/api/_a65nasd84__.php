<?php
/**
 * @author: Edilson D. Mucanze
 * @email: edilsonhmberto@gmail.com
 * @contacto: +258 84 821 3574
 * @date: Dezembro de 2018
 * @Projecto: Sistema de Monitoria de Projecto
 * @Base: MiniCrafted APi
 */
 // Turn off all error reporting
error_reporting(0);
 /** Connect Sistem backEnd*/
    include_once('../config.php');
	require_once('../BD.class.php');

	$db = BD::conn();
/** Get Info for Dasboard-Home page */
/** Info de Provincias, projectos e programas **/

$value = 0;
function formatNAN($value)
{
	return $value;
}

//

    // Switch on each action
    switch ($_GET['report']) {

			case 'dashboard':

				$provincia = $_GET['prov'];
				if($provincia = ""){
					$provincia = "Maputo-Cidade";
				}else{
					$provincia = $_GET['prov'];
				}

					$get_projectos = $db->prepare('SELECT id, name AS nome_programa FROM projects AS projectos WHERE parent_id = 12');
					$get_projectos->execute(array());

					while ($programa = $get_projectos->fetchObject()) {

					$dataListChart .= '"'.$programa->nome_programa.'",';


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

					$programa_desc = "";

					while ($prog_desc = $get_programas->fetchObject()) {
						$programa_desc .= '--- '.$prog_desc->nome_projecto.'</br>';
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

					$dataListPro .= '<tr><td>'.$programa->nome_programa.'</td><td>'.$programa_desc.'</td><td>'.$return_nums->num_projectos.'</td></tr>';


					$result  =  array(
						'nome' => $programa->nome_programa,
						'apelido' => $programa_desc,
						'num_projectos' => $return_nums->num_projectos
					);
					array_push($result, $result);

					$row_array ['programa'] = $programa->nome_programa;
					$row_array ['desc_projectos'] = $programa_desc;
					$row_array ['num_projectos'] = $return_nums->num_projectos;
					$r [] .= $r;
					array_push($r, $row_array);
				}
				//echo json_encode($r);
				echo $dataListPro;
				break;
			case 'actividadePDE':
				$id_report = $_GET['p'];
				if(empty($id_report)){
					$id_report = 12; // ID do projecto pre-indicado
				}else{
					$id_report = $_GET['p'];
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

				// Staring the Query
				$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS objectivoGeral,
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
						WHERE i.tracker_id = 11 AND i.project_id= ? Order by t.position ASC, i.root_id ASC");
				$get_reports->execute(array($id_report));

				while ($info_report = $get_reports->fetchObject()) {

					$test = $db->prepare("SELECT * from custom_values  where value like '%' ".$a." '%'");
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
					echo $response_table;
				break;

			case 'actividadePROJ':

				$id_report = $_GET['p'];
				if(empty($id_report)){
					$id_report = 12; // ID do projecto pre-indicado
				}else{
					$id_report = $_GET['p'];
				}

				//Header query
				$get_header = $db->prepare("SELECT
					campo0.name AS res_final, campo1.name AS res_intermedio, campo2.name AS res_imediato, campo3.name AS produto, campo4.name AS actividade, campo5.name AS indicador, campo6.name AS variavel, campo7.name AS metaNumerica, campo8.name AS metaDescritiva, campo9.name AS fonteVerificacao,  campo10.name as realizado, campo11.name AS observacoes
					FROM trackers AS campo0
						INNER JOIN trackers AS campo1 ON (campo1.id = 3)
						INNER JOIN trackers AS campo2 ON (campo2.id = 10)
						INNER JOIN trackers AS campo3 ON (campo3.id = 2)
						INNER JOIN trackers AS campo4 ON (campo4.id = 11)
						LEFT JOIN custom_fields AS campo5 ON (campo5.id = 97)
						LEFT JOIN custom_fields AS campo6 ON (campo6.id = 99)
						LEFT JOIN custom_fields AS campo7 ON (campo7.id = 100)
						LEFT JOIN custom_fields AS campo8 ON (campo8.id = 103)
						LEFT JOIN custom_fields AS campo9 ON (campo9.id = 46)
						LEFT JOIN custom_fields AS campo10 ON (campo10.id = 105)
						LEFT JOIN custom_fields AS campo11 ON (campo4.id = 51)
						WHERE campo0.id = 5");

				$get_header->execute(array());

				$resp_header = $get_header->fetchObject();

				// Staring the Query

				$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, t.name AS name, ind.value AS indicador, prod.subject AS produto,
					resImed.subject AS resultadoImediato, resInter.subject AS resultadoIntermedio, resFinal.subject AS resultadoFinal,
					cv.value AS metaNumerica, mD.value AS metaDescritiva,
					obs.value AS observacoes, fv.value AS fonteVerificacao, rea.value AS realizado, var.value AS variavel
					from issues AS i
					INNER JOIN trackers AS t ON (i.tracker_id = t.id)
					LEFT JOIN issues AS prod ON ((i.parent_id = prod.id) AND prod.tracker_id=2)
					LEFT JOIN issues AS resImed ON ((prod.parent_id = resImed.id OR i.parent_id = resImed.id) AND resImed.tracker_id=10)
					LEFT JOIN issues AS resInter ON ((resImed.parent_id = resInter.id OR prod.parent_id = resInter.id OR i.parent_id = resInter.id) AND resInter.tracker_id = 3)
					LEFT JOIN issues AS resFinal ON ((resInter.parent_id = resFinal.id OR resImed.parent_id = resFinal.id OR prod.parent_id = resFinal.id OR i.parent_id = resFinal.id) AND resFinal.tracker_id = 5)
					LEFT JOIN custom_values AS cv ON (cv.custom_field_id=100 AND cv.customized_id=i.id)
					LEFT JOIN custom_values AS mD ON (md.custom_field_id=103 AND md.customized_id=i.id)
					LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
					LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
					LEFT JOIN custom_values AS rea ON (rea.custom_field_id=105 AND rea.customized_id=i.id)
					LEFT JOIN custom_values AS var ON (var.custom_field_id=99 AND var.customized_id=i.id)
					LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
					WHERE i.tracker_id = 11 AND i.project_id= ? Order by t.position ASC, i.root_id ASC");
				$get_reports->execute(array($id_report));

				while ($info_report = $get_reports->fetchObject()) {

					$percent = number_format((float)((($info_report->realizado) / ($info_report->metaNumerica)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
					$report_result .= "<tr>
						<td>".$info_report->resultadoFinal."</td>
						<td>".$info_report->resultadoIntermedio."</td>
						<td>".$info_report->resultadoImediato."</td>
						<td>".$info_report->produto."</td>
						<td>".$info_report->actividade."</td>
						<td>".$info_report->indicador."</td>
						<td>".$info_report->variavel."</td>
						<td>".$info_report->metaNumerica."</td>
						<td>".$info_report->metaDescritiva."</td>
						<td>".$info_report->fonteVerificacao."</td>
						<td>".$info_report->realizado."</td>
						<td>".$percent."</td>
						<td>".$info_report->observacoes."</td>
						</tr>";

						//$result_test .= "<h3>". $info_report->resultadoIntermedio . "</h3>";
				}
				$response_table =
				"<thead>
					 <tr class='p-2 border-0' style=' font-size: 0.78rem; '>
						<th>".$resp_header->res_final."</th>
						<th>".$resp_header->res_intermedio."</th>
						<th>".$resp_header->res_imediato."</th>
						<th>".$resp_header->produto."</th>
						<th>".$resp_header->actividade."</th>
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
					echo $response_table;
				break;

			case 'actividadePROV':

				// Staring the Query
				$listInfoProv = "";
				$provincia = "";
				$listaProj = "";

				if(isset($_GET['p'])){
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

					$get_projectos->execute(array($_GET['p']));

					$provincia = ($_GET['p']);

					while ($info = $get_projectos->fetchObject()) {

						$Indicadores = $db->prepare("SELECT
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

							$report_result .= "<tr>
							<td>".$info->actividade."</td>
							<td>".$info->nome_projecto."</td>
							<td>".$info->provincia."</td>
							<td>".$getIndicador->indicador."</td>
							<td>".$getIndicador->meta."</td>
							<td>".$getIndicador->realizado."</td></tr>";

							$listaProj .= "# ".$info->nome_projecto." ";

						}//End While
					}//End if(isset())--->
				echo $report_result;
					break;


			case 'projectos':

				$get_projectos = $db->prepare('SELECT id as id_projecto, name AS nome_projecto FROM projects AS projectos
				WHERE parent_id != 12 and status = 1 order by name');
				$get_projectos->execute(array());

				while ($projecto = $get_projectos->fetchObject()) {
					$listProjectos .= "<option value='".$projecto->id_projecto."'>".$projecto->nome_projecto."</option>";
				}
					echo "<option> --- Selecione o Projecto</option>".$listProjectos;
					break;

			case 'projecto':

				$get_projectos = $db->prepare('SELECT id as id_projecto, name AS nome_projecto FROM projects AS projectos
				WHERE parent_id != 12 and status = 1 and id = ?');
				$get_projectos->execute(array($_GET['p']));

				$projecto = $get_projectos->fetchObject();
				$nome_projecto = $projecto->nome_projecto;

				echo $nome_projecto;
				break;

			case 'planos':

				$get_planoEst = $db->prepare('SELECT id AS id_proj, name AS nome_plano FROM projects AS main_plan WHERE parent_id is null and status = 1');
				$get_planoEst->execute();

				while ($planos = $get_planoEst->fetchObject()) {
					$listPlans .= "<option value='".$planos->id_proj."'>".$planos->nome_plano."</option>";
				}

				echo "<option> --- Selecione o Plano Estratégico </option>".$listPlans;
				break;

			case 'plano':

				$get_planoEst = $db->prepare('SELECT id AS id_proj, name AS nome_plano FROM projects AS main_plan WHERE parent_id is null and id = ? ');
				$get_planoEst->execute(array($_GET['p']));

				$planos = $get_planoEst->fetchObject();
				echo  $planos->nome_plano;
				break;
			case 'OrcamentoPDE':
				$id_report = $_GET['p'];
				if(empty($id_report)){
					$id_report = 12; // ID do projecto pre-indicado
				}else{
					$id_report = $_GET['p'];
				}
				// Staring the Query

				$get_projectos = $db->prepare('SELECT id, name AS nome_programa FROM projects AS projectos WHERE parent_id = ? and status = 1 order by id = 4, id = 2');
				$get_projectos->execute(array($_GET['p']));

					//$orc_objEspecifico ="<td><b>". number_format($orcObjectivos->orcamento)."</b> Meticais</td>";
					$guid = 150;
					while ($programa = $get_projectos->fetchObject()) {

						$id_issue = $guid + 1;
						$guid = $id_issue;

						$dataListChart .= '"'.$programa->nome_programa.'",';

						$get_orcObjectivos = $db->prepare("
						SELECT
							i.subject as obj_especifico,
							orc_esp.value as orc_objectivo
						FROM
							bitnami_redmine.issues as i
							left join custom_values as orc_esp on (orc_esp.customized_id = i.id and custom_field_id = 29)
						WHERE
							tracker_id = 16
							and i.project_id = 12
							and i.status_id = 1
							and i.id = ?
						ORDER BY obj_especifico"
						);

				$get_orcObjectivos->execute(array($id_issue));

				$orcObjectivos = $get_orcObjectivos->fetchObject();
				$dataListvObjesp .='"'.$orcObjectivos->orc_objectivo.'",';
				//echo $orcObjectivos->obj_especifico;

				$get_orcamentos = $db->prepare(" SELECT sum(orc.value) as total_orcamento, sum(vgasto.value) as valor_gasto FROM projects AS projectos LEFT JOIN custom_values AS orc ON (orc.custom_field_id = 23 AND orc.customized_id = projectos.id) LEFT JOIN custom_values AS vgasto ON (vgasto.custom_field_id = 108 AND vgasto.customized_id = projectos.id) where parent_id = ? and status = 1");
				$get_orcamentos->execute(array($programa->id));

				$orcamento = $get_orcamentos->fetchObject();

				$dataListOrc .= '"'.$orcamento->total_orcamento.'",';
				$dataListvGasto .= '"'.$orcamento->valor_gasto.'",';

				$listProgramas .= "<tr><td>".$programa->nome_programa."</td><td><b>".number_format($orcObjectivos->orc_objectivo)."</b> Meticais</td><td><b>". number_format($orcamento->total_orcamento)."</b> Meticais</td><td><b>".number_format($orcamento->valor_gasto)."</b> Meticais</td></tr>";


				$json_response[] = array(
					"porgrama" => $programa->nome_programa,
					"orcamentoPD" => number_format((float)(($orcObjectivos->orc_objectivo)), 2, '.', ','),
					"orcamentoObj" => number_format((float)(($orcamento->total_orcamento)), 2, '.', ','),
					"valor_gasto" => number_format((float)(($orcamento->valor_gasto)), 2, '.', ',')
				);


				$orcamentoPDs .= number_format((float)(($orcObjectivos->orc_objectivo)), 2, '.', '') .", ";
				$orcamentoProjectos .= number_format((float)(($orcamento->total_orcamento)), 2, '.', '') .", ";
				$valorGastoProjectos .= number_format((float)(($orcamento->valor_gasto)), 2, '.', '') .", ";
				$listaProg .= "'".$programa->nome_programa."', ";
				}
				header('Content-Type:'. "application/json");

				$array2[] = array(
					"orcamento_PDE" => $orcamentoPDs,
					"orcamento_Programas" => $orcamentoProjectos,
					"orcamento_valorGasto" => $valorGastoProjectos,
					"progrmas_" => $listaProg,
					"load" => true
				);

				$result = json_encode($json_response);
				$json_[] = array(
					"response_orcamento" => $json_response,
					"orcamento" => $array2,
				);
				echo json_encode($json_);

			break;
			case 'OrcamentoPROJ':
				$id_projecto = $_GET['p'];
				if(empty($id_projecto)){
					$id_projecto = 17; // ID do projecto pre-indicado
				}else{
					$id_projecto = $_GET['p'];
				}
				// Staring the Query

					$get_projectos = $db->prepare('SELECT
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
					$get_projectos->execute(array($id_projecto));
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
					$get_OrcamentoProj->execute(array($id_projecto));

					$info_Orcamento = $get_OrcamentoProj->fetchObject();
					$orc_programa = "<td>".$projecto->nome_projecto." Meticais</td><td><b>".number_format($projecto->orc_previsto)."</b> Meticais</td><td><b>".number_format($info_Orcamento->v_gasto_progama)."</b> Meticais</td>";

					$response[] = array(
						"projecto" => $projecto->nome_projecto,
						"orcamnetoPrograma" => number_format((float)(($projecto->orc_previsto)), 2, '.', ','), // Orcamento Previsto do Projecto
						"valorGasto" => number_format((float)(($info_Orcamento->v_gasto_progama)), 2, '.', ','), // Orcamento Gasto no Projecto
						"graf_0dg48db83hd8" =>number_format((float)(($projecto->orc_previsto)), 0, '', ''), //Orcamento Previsto - Custom Format
						"graf_95jd8d8hd8hd" => number_format((float)(($info_Orcamento->v_gasto_progama)), 0, '', ''), // Vallor gasto Custom Format
					);

					$json_[] = array(
						"orcamentoProj" => $response
					);

					header('Content-Type:'. "application/json");
					echo json_encode($json_);
				break;

			// Report Orcamento Projecto
			case 'reportOrcamento':
					$id_report = $_GET['p'];
				if(empty($id_report)){
					$id_report = 12; // ID do projecto pre-indicado
				}else{
					$id_report = $_GET['p'];
				}

				//Header query
				$get_header = $db->prepare("SELECT
					campo0.name AS res_final, campo1.name AS res_intermedio, campo2.name AS res_imediato, campo3.name AS produto, campo4.name AS actividade, campo5.name AS indicador, campo6.name AS orcamento, campo7.name AS valor_gasto, campo8.name AS fonteVerificacao, campo9.name AS observacoes
					FROM trackers AS campo0
						INNER JOIN trackers AS campo1 ON (campo1.id = 3)
						INNER JOIN trackers AS campo2 ON (campo2.id = 10)
						INNER JOIN trackers AS campo3 ON (campo3.id = 2)
						INNER JOIN trackers AS campo4 ON (campo4.id = 11)
						LEFT JOIN custom_fields AS campo5 ON (campo5.id = 97)
						LEFT JOIN custom_fields AS campo6 ON (campo6.id = 29)
						LEFT JOIN custom_fields AS campo7 ON (campo7.id = 108)
						LEFT JOIN custom_fields AS campo8 ON (campo8.id = 46)
						LEFT JOIN custom_fields AS campo9 ON (campo9.id = 51)
						WHERE campo0.id = 5");

				$get_header->execute(array());

				$resp_header = $get_header->fetchObject();

				// Staring the Query
			$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, t.name AS name, ind.value AS indicador, prod.subject AS produto,
					resImed.subject AS resultadoImediato, resInter.subject AS resultadoIntermedio, resFinal.subject AS resultadoFinal,
					orc.value AS orcamento,vg.value AS valorGasto,
					obs.value AS observacoes, fv.value AS fonteVerificacao
					from issues AS i
					INNER JOIN trackers AS t ON (i.tracker_id = t.id)
					LEFT JOIN issues AS prod ON (i.parent_id = prod.id AND prod.tracker_id=2)
					LEFT JOIN issues AS resImed ON ((prod.parent_id = resImed.id OR i.parent_id = resImed.id) AND resImed.tracker_id=10)
					LEFT JOIN issues AS resInter ON ((resImed.parent_id = resInter.id OR prod.parent_id = resInter.id OR i.parent_id = resInter.id) AND resInter.tracker_id = 3)
					LEFT JOIN issues AS resFinal ON ((resInter.parent_id = resFinal.id OR resImed.parent_id = resFinal.id OR prod.parent_id = resFinal.id OR i.parent_id = resFinal.id) AND resFinal.tracker_id = 5)
					LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
					LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
					LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
					LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
					LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
					WHERE i.tracker_id = 11 AND i.project_id=? Order by t.position ASC");
				$get_reports->execute(array($id_report));

				while ($info_report = $get_reports->fetchObject()) {
					$percent = number_format((float)((($info_report->orcamentovalorGasto) / ($info_report->orcamento)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual

					$report_result .= "<tr>
						<td>".$info_report->resultadoFinal."</td>
						<td>".$info_report->resultadoIntermedio."</td>
						<td>".$info_report->resultadoImediato."</td>
						<td>".$info_report->produto."</td>
						<td>".$info_report->actividade."</td>
						<td>".$info_report->indicador."</td>
						<td>".number_format((float)($info_report->orcamento), 2, '.', ',')."</td>
						<td>".number_format((float)($info_report->valorGasto), 2, '.', ',')."</td>
						<td>".$info_report->percent."</td>
						<td>".$info_report->fonteVerificacao."</td>
						<td>".$info_report->observacoes."</td>
						</tr>";
				}
				$response_table =
					"<thead>
						<tr class='p-2 border-0' style=' font-size: 0.78rem; '>
							<th>".$resp_header->res_final."</th>
							<th>".$resp_header->res_intermedio."</th>
							<th>".$resp_header->res_imediato."</th>
							<th>".$resp_header->produto."</th>
							<th>".$resp_header->actividade."</th>
							<th>".$resp_header->indicador."</th>
							<th>".$resp_header->orcamento."</th>
							<th>".$resp_header->valor_gasto."</th>
							<th>Percentual</th>
							<th>".$resp_header->fonteVerificacao."</th>
							<th>".$resp_header->observacoes."</th>
						</tr>
					</thead>
					<tbody>
						".$report_result."
					</tbody>";
						echo $response_table;
				break;
				// Report Orcamento Plano Estrategico
			case 'reportOrcamentoPDE':
				$id_report = $_GET['p'];
				if(empty($id_report)){
					$id_report = 12; // ID do projecto pre-indicado
				}else{
					$id_report = $_GET['p'];
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

				// Staring the Query

				$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS objectivoGeral,
					objEspecifico.subject AS objectivoEspecifico,
					t.name AS name, ind.value AS indicador, orc.value AS orcamento,vg.value AS valorGasto,
					obs.value AS observacoes, fv.value AS fonteVerificacao
					from issues AS i
					INNER JOIN trackers AS t ON (i.tracker_id = t.id)
					LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
					LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
					LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
					LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
					LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
					LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=i.id)
					LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=i.id)
					LEFT JOIN custom_values AS ind ON (ind.custom_field_id=97 AND ind.customized_id=i.id)
					WHERE i.tracker_id = 11 AND i.project_id= ? Order by t.position ASC");
				$get_reports->execute(array($id_report));

				while ($info_report = $get_reports->fetchObject()) {
					$percent = number_format((float)((($info_report->valorGasto) / ($info_report->orcamento)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
					$report_result .= "<tr>
						<td>".$info_report->objectivoGeral."</td>
						<td>".$info_report->objectivoEspecifico."</td>
						<td>".$info_report->estrategia."</td>
						<td>".$info_report->actividade."</td>
						<td>".$info_report->indicador."</td>
						<td>".number_format((float)($info_report->orcamento), 2, '.', ',')."</td>
						<td>".number_format((float)($info_report->valorGasto), 2, '.', ',')."</td>
						<td>".$info_report->percent."</td>
						<td>".$info_report->fonteVerificacao."</td>
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
							<th>".$resp_header->indicador."</th>
							<th>".$resp_header->orcamento."</th>
							<th>".$resp_header->valor_gasto."</th>
							<th>Percentual</th>
							<th>".$resp_header->fonteVerificacao."</th>
							<th>".$resp_header->observacoes."</th>
						</tr>
					</thead>
					<tbody>
						".$report_result."
					</tbody>";
					echo $response_table;
				break;

			case "beneficiariosPDE":
				$infoBeneficiarios = null;
				$infoProjectos = null;
				$id_report = $_GET['p'];
				if(empty($id_report)){
					$id_report = 12; // ID do projecto pre-indicado
				}else{
					$id_report = $_GET['p'];
				}

				$get_beneficiarios = $db->prepare("SELECT
						projecto.name as nome_projecto,
						fEtaria_bnf.value as fEtaria_bnf,
						num_bnf.value num_bnf
					FROM
						issues as i
						left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
						left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
						LEFT JOIN projects as projecto on (projecto.id = i.project_id)
                        LEFT JOIN projects as pde on (pde.id = projecto.parent_id)
					WHERE
						i.status_id = 1
						and tracker_id = 11
						and projecto.parent_id is not null
						and pde.parent_id = ?
						and fEtaria_bnf.value != ' '
						and num_bnf.value != ' ';
						");
				$get_beneficiarios->execute(array($id_report));
				$ben_GraphFEtaria = null;
				$ben_GraphBeneficiarios = null;
				while ($beneficiarios = $get_beneficiarios->fetchObject()) {
						$infoBeneficiarios .= "<tr><td>".$beneficiarios->fEtaria_bnf."</td><td>".$beneficiarios->num_bnf."</td></tr>";
						$infoProjectos .= "<tr><td>".$beneficiarios->nome_projecto."</td><td>".$beneficiarios->fEtaria_bnf."</td><td>".$beneficiarios->num_bnf."</td></tr>";

					// Soma todos bnf do sexo Femenino
					$get_bnfMulheres = $db->prepare("SELECT
								sum(num_bnf.value) as bnf_mulheres
							FROM
								issues as i
								left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
								left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
								LEFT JOIN projects as projecto on (projecto.id = i.project_id)
								LEFT JOIN projects as pde on (pde.id = projecto.parent_id)
							WHERE
								i.status_id = 1
								and tracker_id = 11
								and project_id != 12
								and pde.parent_id = ?
								and fEtaria_bnf.value != ' '
								and num_bnf.value != ' '
								and fEtaria_bnf.value like '%mulher%'
							");

							$get_bnfMulheres->execute(array($id_report));

							$bnfMulheres = $get_bnfMulheres->fetchObject();


					// Soma todos bnf do sexo Masculino
					$get_bnfHomens = $db->prepare("SELECT
								sum(num_bnf.value) as bnf_homens
							FROM
								issues as i
								left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
								left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
								LEFT JOIN projects as projecto on (projecto.id = i.project_id)
								LEFT JOIN projects as pde on (pde.id = projecto.parent_id)
							WHERE
								i.status_id = 1
								and tracker_id = 11
								and project_id != 12
								and pde.parent_id = ?
								and fEtaria_bnf.value != ' '
								and num_bnf.value != ' '
								and fEtaria_bnf.value like '%hom%'
							");

							$get_bnfHomens->execute(array($id_report));

							$bnfHomens = $get_bnfHomens->fetchObject();



					// Soma de Beneficiarios Homens e Mulheres Criancas

					$get_bnf_criancas = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
							FROM
								issues as i
								left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
								left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
								LEFT JOIN projects as projecto on (projecto.id = i.project_id)
								LEFT JOIN projects as pde on (pde.id = projecto.parent_id)
							WHERE
								i.status_id = 1
								and tracker_id = 11
								and project_id != 12
								and pde.parent_id = ?
								and fEtaria_bnf.value != ' '
								and num_bnf.value != ' '
								and fEtaria_bnf.value like '%crianças%'
							");

							$get_bnf_criancas->execute(array($id_report));

							$bnf_criancas = $get_bnf_criancas->fetchObject();



					// Soma de Beneficiarios Homens e Mulheres de 0 a 5 anos

					$get_bnfHM_05 = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
							FROM
								issues as i
								left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
								left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
								LEFT JOIN projects as projecto on (projecto.id = i.project_id)
								LEFT JOIN projects as pde on (pde.id = projecto.parent_id)
							WHERE
								i.status_id = 1
								and tracker_id = 11
								and project_id != 12
								and pde.parent_id = ?
								and fEtaria_bnf.value != ' '
								and num_bnf.value != ' '
								and fEtaria_bnf.value like '%0-5%'
							");

							$get_bnfHM_05->execute(array($id_report));

							$bnfHM_05 = $get_bnfHM_05->fetchObject();


					// Soma de Beneficiarios Homens e Mulheres de 6 a 14 anos

					$get_bnfHM_06 = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
							FROM
								issues as i
								left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
								left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
								LEFT JOIN projects as projecto on (projecto.id = i.project_id)
								LEFT JOIN projects as pde on (pde.id = projecto.parent_id)
							WHERE
								i.status_id = 1
								and tracker_id = 11
								and project_id != 12
								and pde.parent_id = ?
								and fEtaria_bnf.value != ' '
								and num_bnf.value != ' '
								and fEtaria_bnf.value like '%6-14%'
							");

							$get_bnfHM_06->execute(array($id_report));

							$bnfHM_06 = $get_bnfHM_06->fetchObject();


					// Soma de Beneficiarios Homens e Mulheres de 15 a 24 anos

					$get_bnfHM_15 = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
							FROM
								issues as i
								left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
								left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
								LEFT JOIN projects as projecto on (projecto.id = i.project_id)
								LEFT JOIN projects as pde on (pde.id = projecto.parent_id)
							WHERE
								i.status_id = 1
								and tracker_id = 11
								and project_id != 12
								and pde.parent_id = ?
								and fEtaria_bnf.value != ' '
								and num_bnf.value != ' '
								and fEtaria_bnf.value like '%15-24%'
							");

							$get_bnfHM_15->execute(array());

							$bnfHM_15 = $get_bnfHM_15->fetchObject();


					// Soma de Beneficiarios Homens e Mulheres de 25 a 49 anos

					$get_bnfHM_25 = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
							FROM
								issues as i
								left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
								left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
								LEFT JOIN projects as projecto on (projecto.id = i.project_id)
								LEFT JOIN projects as pde on (pde.id = projecto.parent_id)
							WHERE
								i.status_id = 1
								and tracker_id = 11
								and project_id != 12
								and pde.parent_id = ?
								and fEtaria_bnf.value != ' '
								and num_bnf.value != ' '
								and fEtaria_bnf.value like '%25-49%'
							");

							$get_bnfHM_25->execute(array($id_report));

							$bnfHM_25 = $get_bnfHM_25->fetchObject();


					// Soma de Beneficiarios Homens e Mulheres de 25 a 49 anos

					$get_bnfHM_50 = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
							FROM
								issues as i
								left join custom_values as num_bnf on (num_bnf.customized_id = i.id and num_bnf.custom_field_id = 105)
								left join custom_values as fEtaria_bnf on (fEtaria_bnf.customized_id = i.id and fEtaria_bnf.custom_field_id = 99)
								LEFT JOIN projects as projecto on (projecto.id = i.project_id)
								LEFT JOIN projects as pde on (pde.id = projecto.parent_id)
							WHERE
								i.status_id = 1
								and tracker_id = 11
								and project_id != 12
								and pde.parent_id = ?
								and fEtaria_bnf.value != ' '
								and num_bnf.value != ' '
								and fEtaria_bnf.value like '%>50%'
							");

							$get_bnfHM_50->execute(array());

							$bnfHM_50 = $get_bnfHM_50->fetchObject();

					//Armanzena Beneficiarios por faixa etaria

					$resp_benFaixaEtaria [] = array(
								"_projecto" => $beneficiarios->nome_projecto,
								"_faixaEtaria" => $beneficiarios->fEtaria_bnf,
								"_beneficarios" => $beneficiarios->num_bnf
						);
					$ben_GraphFEtaria .= "'".$beneficiarios->fEtaria_bnf."', ";
					$ben_GraphBeneficiarios .= $beneficiarios->num_bnf.", ";

					// ['(>50) Homens e Mulheres', 2]
					}// End Loop While

					$dataGraphic = "".number_format($bnf_criancas->bnf).", ".number_format($bnfHM_05->bnf).", ".number_format($bnfHM_06->bnf).", ".number_format($bnfHM_15->bnf).", ".number_format($bnfHM_25->bnf).", ".number_format($bnfHM_50->bnf)."";

					$resp_benGenero [] = array(
						"_homens" => $bnfHomens->bnf_homens,
						"_mulheres" => $bnfMulheres->bnf_mulheres,
					);

					$dataGraph [] = array(
						"infoGrafico" => $dataGraphic
					);

					$json_ [] = array(
						"b_genero" => $resp_benGenero,
						"b_faixaEtaria" => $resp_benFaixaEtaria,
						"data_graph" => $dataGraph
					);

					header('Content-Type:'. "application/json");
					echo json_encode($json_);

				break;
			case "beneficiariosPROJ":
				$ben_GraphFEtaria = null;
				$ben_GraphBeneficiarios  = null;
				$id_report = $_GET['p'];
				if(empty($id_report)){
					$id_report = 18; // ID do projecto pre-indicado
				}else{
					$id_report = $_GET['p'];
				}
				// Staring the Query
					$get_beneficiarios = $db->prepare("SELECT
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
				$get_beneficiarios->execute(array($id_report));

				while ($beneficiarios = $get_beneficiarios->fetchObject()) {
						$infoBeneficiarios .= "<tr><td>".$beneficiarios->fEtaria_bnf."</td><td>".$beneficiarios->num_bnf."</td></tr>";
						$infoProjectos .= "<tr><td>".$beneficiarios->nome_projecto."</td><td>".$beneficiarios->fEtaria_bnf."</td><td>".$beneficiarios->num_bnf."</td></tr>";

					// Soma todos bnf do sexo Femenino
					$get_bnfMulheres = $db->prepare("SELECT
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

							$get_bnfMulheres->execute(array($id_report));

							$bnfMulheres = $get_bnfMulheres->fetchObject();


					// Soma todos bnf do sexo Masculino
					$get_bnfHomens = $db->prepare("SELECT
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

							$get_bnfHomens->execute(array(12));

							$bnfHomens = $get_bnfHomens->fetchObject();



					// Soma de Beneficiarios Homens e Mulheres Criancas

					$get_bnf_criancas = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
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
								and fEtaria_bnf.value like '%crianças%'
							");

							$get_bnf_criancas->execute(array($id_report));

							$bnf_criancas = $get_bnf_criancas->fetchObject();



					// Soma de Beneficiarios Homens e Mulheres de 0 a 5 anos

					$get_bnfHM_05 = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
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
								and fEtaria_bnf.value like '%0-5%'
							");

							$get_bnfHM_05->execute(array($id_report));

							$bnfHM_05 = $get_bnfHM_05->fetchObject();


					// Soma de Beneficiarios Homens e Mulheres de 6 a 14 anos

					$get_bnfHM_06 = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
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
								and fEtaria_bnf.value like '%6-14%'
							");

							$get_bnfHM_06->execute(array($id_report));

							$bnfHM_06 = $get_bnfHM_06->fetchObject();


					// Soma de Beneficiarios Homens e Mulheres de 15 a 24 anos

					$get_bnfHM_15 = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
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
								and fEtaria_bnf.value like '%15-24%'
							");

							$get_bnfHM_15->execute(array($id_report));

							$bnfHM_15 = $get_bnfHM_15->fetchObject();


					// Soma de Beneficiarios Homens e Mulheres de 25 a 49 anos

					$get_bnfHM_25 = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
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
								and fEtaria_bnf.value like '%25-49%'
							");

							$get_bnfHM_25->execute(array($id_report));

							$bnfHM_25 = $get_bnfHM_25->fetchObject();


					// Soma de Beneficiarios Homens e Mulheres de 25 a 49 anos

					$get_bnfHM_50 = $db->prepare("SELECT
								sum(num_bnf.value) as bnf
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
								and fEtaria_bnf.value like '%>50%'
							");

							$get_bnfHM_50->execute(array($id_report));

							$bnfHM_50 = $get_bnfHM_50->fetchObject();

					//Armanzena Beneficiarios por faixa etaria

					$resp_benFaixaEtaria [] = array(
								"_projecto" => $beneficiarios->nome_projecto,
								"_faixaEtaria" => $beneficiarios->fEtaria_bnf,
								"_beneficarios" => $beneficiarios->num_bnf
						);
					$ben_GraphFEtaria .= "'".$beneficiarios->fEtaria_bnf."', ";
					$ben_GraphBeneficiarios .= $beneficiarios->num_bnf.", ";

					}// End Loop While


					$dataGraphic = "".number_format($bnf_criancas->bnf).", ".number_format($bnfHM_05->bnf).", ".number_format($bnfHM_06->bnf).", ".number_format($bnfHM_15->bnf).", ".number_format($bnfHM_25->bnf).", ".number_format($bnfHM_50->bnf)."";

					$dataBeneficiarios [] = array(
						"_homens" => number_format($bnfHomens->bnf_homens),
						"_mulheres" => number_format($bnfMulheres->bnf_mulheres),
					);

					$data_ [] = array(
						"graphic_data" => $dataGraphic
					);

					$json_ [] = array(
						"_dataGraf_1" => $dataBeneficiarios,
						"_dataGraf_2" => $data_,
						"_info" => $resp_benFaixaEtaria
					);
					header('Content-Type:'. "application/json");
					echo json_encode($json_);

				break;


			default:
            	# code...
            	break;
    }
