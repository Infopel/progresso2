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

		$get_rowObjE = $db->prepare("SELECT DISTINCT i.id, resFinal.subject AS resultadoFinal, resInter.subject AS resultadoIntermedio,
                    count(resInter.subject) as resInter
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
					WHERE i.tracker_id = 11 AND i.project_id= ? group by resultadoIntermedio Order by resInter.subject, resImed.subject");
		$get_rowObjE->execute(array($id_report));

		$tr = ""; $xi = 0;
		while ($rows = $get_rowObjE->fetchObject()) {
			// echo $rows->resultadoIntermedio."->$rows->resInter<br><br>";
			// if($xi == 0){
				$get_rowObjEst = $db->prepare("SELECT DISTINCT i.id, resFinal.subject AS resultadoFinal, resInter.subject AS resultadoIntermedio, resImed.subject AS resultadoImediato,
                    count(resInter.subject) as resImed
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
					WHERE i.tracker_id = 11 AND i.project_id= ? and resInter.subject = ? group by resInter.subject, resImed.subject Order by resInter.subject, resImed.subject");
				$get_rowObjEst->execute(array($id_report, $rows->resultadoIntermedio));
				$c = 0;

				if($c == 0){
					$x = 1;
				}
				while ($rowsEst = $get_rowObjEst->fetchObject()) {
					$tds = null; $tdEst =null; $trEst = null;

					for ($i=1; $i <= $rowsEst->resImed; $i++) {
						if($i == $rowsEst->resImed && $x != 1){

							if($rowsEst->resultadoImediato != null){

								$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, t.name AS name, ind.subject AS indicador, prod.subject AS produto,
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
									WHERE i.tracker_id = 11 AND i.project_id = ? and resInter.subject = ? and resImed.subject = ? Order by t.position ASC");
                                $get_reports->execute(array($id_report, $rowsEst->resultadoIntermedio, $rowsEst->resultadoImediato));

                                $xz  = 0;
								while ($info_report = $get_reports->fetchObject()){

                                    if(number_format((float)($info_report->metaNumerica)) == 0 && number_format((float)($info_report->realizado)) != 0){
                                        $percent = number_format((float)((($info_report->realizado) / ($info_report->metaNumerica)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
                                    }
                                    if(number_format((float)($info_report->metaNumerica)) != 0 && number_format((float)($info_report->realizado)) == 0){
                                        $percent = "0.00%";
                                    }

                                    if(number_format((float)($info_report->metaNumerica)) == 0 && number_format((float)($info_report->realizado)) == 0){
                                        $percent = "0.00%";
                                    }

                                    if(number_format((float)($info_report->metaNumerica)) != 0 && number_format((float)($info_report->realizado)) != 0){
                                        $percent = number_format((float)((($info_report->realizado) / ($info_report->metaNumerica)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
                                    }
									if($xz == 0){
										$da .= "
											<tr>
                                                <td rowspan='$i'>$rowsEst->resultadoImediato</td>
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
											</tr>
										";
									}else{
										$ds .= "<tr>
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
												<td>  </td>
												<td>  </td>
										";
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
							$get_reports = $db->prepare("SELECT DISTINCT i.id, i.subject AS actividade, t.name AS name, ind.subject AS indicador, prod.subject AS produto,
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
									WHERE i.tracker_id = 11 AND i.project_id = ? and resInter.subject = ? and resImed.subject = ? Order by t.position ASC");
                                $get_reports->execute(array($id_report, $rowsEst->resultadoIntermedio, $rowsEst->resultadoImediato));
							$xz  = 0;
							while ($info_report = $get_reports->fetchObject()){

                                	if(number_format((float)($info_report->realizado)) == 0 && number_format((float)($info_report->metaNumerica)) != 0){
                                        $percent = number_format((float)((($info_report->realizado) / ($info_report->metaNumerica)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
                                    }
                                    if(number_format((float)($info_report->realizado)) != 0 && number_format((float)($info_report->metaNumerica)) == 0){
                                        $percent = "0.00%";
                                    }

                                    if(number_format((float)($info_report->realizado)) == 0 && number_format((float)($info_report->metaNumerica)) == 0){
                                        $percent = "0.00%";
                                    }

                                    if(number_format((float)($info_report->realizado)) != 0 && number_format((float)($info_report->metaNumerica)) != 0){
                                        $percent = number_format((float)((($info_report->realizado) / ($info_report->metaNumerica)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
                                    }

								if($xz == 0){
									$da .= "<td rowspan='$rowsEst->resImed'>$rowsEst->resultadoImediato</td>
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
										";
									}else{
										$ds .= "<tr>
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
												<td>  </td>
												<td>  </td>
										";
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
												<td>  </td>
										    </tr>";
									}
								$bx++;
								}
							}
							$x = 2;
                            $rowsEst->resultadoImediato = null;
						}
                        // echo
                        $trd = $da.$ds;
					}

					if($c == 0){
						$tr.=
						"<tr>
							<td rowspan='$rows->resInter'>".$rows->resultadoFinal."</td>
							<td rowspan='$rows->resInter'>".$rows->resultadoIntermedio."</td>
							$trd
						</tr>";
					}else{
						$tr .= $trd;
					}
                    // echo $trd;
                    // $trd = null;
                    // echo '<b>'.$rows->resultadoFinal.'->'.$rows->resInter.'</b></br>';
					// $rows->objectivoGeral = null;

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

		$tab = "<thead>
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
					$tr
                </tbody>";
	echo $tab;
	$tr = null;
	$trd = null;
}
