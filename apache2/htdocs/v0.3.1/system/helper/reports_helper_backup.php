<?php
/**
 * @author: Edilson H Mucanze
 * @email: edilsonhmberto@gmai.com
 * @contacto: 848213574
 *
 * @pro: Helper para gerar Reports pdf dinamicos
 * **/
	error_reporting(0);

    require_once '../system/dompdf/src/Autoloader.php';


    Dompdf\Autoloader::register();
    // Chamndo o DomPDF creator register

    use Dompdf\Dompdf;
    use Dompdf\Options;

 class reportHelper{

    private $DOMhtml = "";


    public function __construct($conn){
     // Inicializando a class
        $this->conn = $conn;
    }


	public function reportPDE($p)
	{
		$id_report = $p;


        $get_rowObjE = $this->conn->prepare("SELECT DISTINCT i.id, objEspecifico.id as id_objwe, objGeral.subject AS objectivoGeral,
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
				$get_rowObjEst = $this->conn->prepare("SELECT DISTINCT i.id, estrategia.subject AS estrategia,
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
								$get_reports = $this->conn->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS 		 objectivoGeral,
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
									$get_realizado = $this->conn->prepare("SELECT
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
									$get_relation = $this->conn->prepare("SELECT DISTINCT
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
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->fonteVerificacao</td>
											</tr>
										";
									}else{
										$ds .= "<tr>
										<td>$info_report->actividade</td>
											<td>$Rprojecto_name</td>
											<td>$info_report->indicador</td>
											<td>$info_report->variavel</td>
											<td>$info_report->metaNumerica</td>
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->fonteVerificacao</td>
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
							$get_reports = $this->conn->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS 		 objectivoGeral,
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
								$get_realizado = $this->conn->prepare("SELECT
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
									$get_relation = $this->conn->prepare("SELECT DISTINCT
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
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->fonteVerificacao</td>
										";
									}else{
										$ds .= "<tr>
										<td>$info_report->actividade</td>
											<td>$Rprojecto_name</td>
											<td>$info_report->indicador</td>
											<td>$info_report->variavel</td>
											<td>$info_report->metaNumerica</td>
											<td>$real->realizado</td>
											<td>".$percent_cal."%" ."</td>
											<td>$info_report->fonteVerificacao</td>
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
			$get_header = $this->conn->prepare("SELECT
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

		$title = "Relatório Atividade PDE";
		$DOMHtml = "<table border='1'>
						<thead>
							<tr><td colspan='11'><h4 style='text-transform: uppercase'>Associação PROGRESSO</h4></td></tr>
							<tr>
								<td colspan='11'>
									Av. Ahmed Sekou Touré Nº 1957, Maputo - Phone: + 258 21 430 485/6: Email: comunicar@progresso.co.mz Maputo - Mocamique
								</td>
							</tr>
                            <tr class='p-2 border-0' style=' font-size: 0.78rem; '>
                                <th>".$resp_header->obj_geral."</th>
                                <th>".$resp_header->obj_esp."</th>
                                <th>".$resp_header->estrategia."</th>
                                <th>".$resp_header->actividade."</th>
                                <th>Projectos Relacionados</th>
                                <th>".$resp_header->indicador."</th>
                                <th>".$resp_header->variavel."</th>
								<th>".$resp_header->metaNumerica."</th>
								<th>".$resp_header->realizado."</th>
								<th>Percentual</th>
                                <th>".$resp_header->fonteVerificacao."</th>
                            </tr>
                        </thead>
                        <tbody>
                            $tr
                        </tbody>
                        </table>
                        <div style='margin-top: 15px;'>
                                Gerado Por computador
                                <br>Script Desenvolvido por <strong>Edilson Mucanze</strong>
                            </div>
					";

		// Nome do Arquivo do Excel que será gerado
		$data=date("y-m-d h:i:s");
		$arquivo = "Relatório_Atividade_PDE".$data.".xls";

		header ('Cache-Control: no-cache, must-revalidate');
		header ('Pragma: no-cache');
		header('Content-Type: application/x-msexcel; charset=iso-8859-1');
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"");

        print chr(255) . chr(254) . mb_convert_encoding($DOMHtml, 'UTF-16LE', 'UTF-8');
        // $this->createPDF($DOMHtml);
        $tr = null;
        $trd = null;
    }


	public function reportOrcProj($p)
	{
		$id_report = $p;
		$A = null;
		$ds  = null;
		$da  = null;
		$tdFi = null;
		$tdFs  = null;
		$ZKP = null;
		$n  = null;

		$get_rowObjE = $this->conn->prepare("SELECT DISTINCT i.id, resFinal.subject AS resultadoFinal, resInter.subject AS resultadoIntermedio,
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

			// if($xi == 0){
				$get_rowObjEst = $this->conn->prepare("SELECT DISTINCT i.id, resImed.subject AS resultadoImediato, resInter.subject AS resultadoIntermedio,
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
					WHERE i.tracker_id = 11 AND i.project_id = ? and resInter.subject = ? group by resultadoIntermedio, resultadoImediato Order by resInter.subject, resImed.subject");
				$get_rowObjEst->execute(array($id_report, $rows->resultadoIntermedio));
				$c = 0;

				if($c == 0){
					$x = 1;
				}
				while ($rowsEst = $get_rowObjEst->fetchObject()) {
                    // echo $rowsEst->resultadoIntermedio."->$rowsEst->resultadoImediato<br>";
					$tds = null; $tdEst =null; $trEst = null;

					for ($i=1; $i <= $rowsEst->resImed; $i++) {
						if($i == $rowsEst->resImed && $x != 1){

							if($rowsEst->resultadoImediato != null){

								$get_reports = $this->conn->prepare("SELECT DISTINCT i.id, i.subject AS actividade, t.name AS name, ind.subject AS indicador, prod.subject AS produto,
                                    resImed.subject AS resultadoImediato, resInter.subject AS resultadoIntermedio, resFinal.subject AS resultadoFinal,
                                    orc.value AS orcamento,vg.value AS valorGasto,
                                    obs.value AS observacoes, fv.value AS fonteVerificacao
                                    from issues AS i
                                    INNER JOIN trackers AS t ON (i.tracker_id = t.id)
                                    LEFT JOIN issues AS ind ON (ind.parent_id = i.id AND ind.tracker_id=12)
                                    LEFT JOIN issues AS prod ON (i.parent_id = prod.id AND prod.tracker_id=2)
                                    LEFT JOIN issues AS resImed ON ((prod.parent_id = resImed.id OR i.parent_id = resImed.id) AND resImed.tracker_id=10)
                                    LEFT JOIN issues AS resInter ON ((resImed.parent_id = resInter.id OR prod.parent_id = resInter.id OR i.parent_id = resInter.id) AND resInter.tracker_id = 3)
                                    LEFT JOIN issues AS resFinal ON ((resInter.parent_id = resFinal.id OR resImed.parent_id = resFinal.id OR prod.parent_id = resFinal.id OR i.parent_id = resFinal.id) AND resFinal.tracker_id = 5)
                                    LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
                                    LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
                                    LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
                                    LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
                                    WHERE i.tracker_id = 11 AND i.project_id=? and resInter.subject = ? and resImed.subject = ? Order by t.position ASC");
                                $get_reports->execute(array($id_report, $rowsEst->resultadoIntermedio, $rowsEst->resultadoImediato));

                                $xz  = 0;
								while ($info_report = $get_reports->fetchObject()){
                                    if(number_format((float)($info_report->orcamentovalorGasto)) == 0 && number_format((float)($info_report->orcamento)) != 0){

                                        $percent = number_format((float)((($info_report->orcamentovalorGasto) / (number_format((float)($info_report->orcamento)))) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
                                    }
                                    if(number_format((float)($info_report->orcamentovalorGasto)) != 0 && number_format((float)($info_report->orcamento)) == 0){
                                        $percent = "0.00%";
                                    }

                                    if(number_format((float)($info_report->orcamentovalorGasto)) == 0 && number_format((float)($info_report->orcamento)) == 0){
                                        $percent = "0.00%";
                                    }

                                    if(number_format((float)($info_report->orcamentovalorGasto)) != 0 && number_format((float)($info_report->orcamento)) != 0){

                                        $percent = number_format((float)((($info_report->orcamentovalorGasto) / (number_format((float)($info_report->orcamento)))) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
                                    }
									if($xz == 0){
										$da .= "
											<tr>
                                                <td rowspan='$i'>$rowsEst->resultadoImediato</td>
                                                <td>$info_report->produto</td>
                                                <td>$info_report->actividade</td>
                                                <td>$info_report->indicador</td>
                                                <td>".number_format((float)($info_report->orcamento), 2, '.', ',')."</td>
                                                <td>".number_format((float)($info_report->valorGasto), 2, '.', ',')."</td>
                                                <td>$percent</td>
                                                <td>$info_report->fonteVerificacao</td>
											</tr>
										";
									}else{
										$ds .= "<tr>
										        <td>$info_report->produto</td>
                                                <td>$info_report->actividade</td>
                                                <td>$info_report->indicador</td>
                                                <td>".number_format((float)($info_report->orcamento), 2, '.', ',')."</td>
                                                <td>".number_format((float)($info_report->valorGasto), 2, '.', ',')."</td>
                                                <td>$percent</td>
                                                <td>$info_report->fonteVerificacao</td>
										    </tr>";

									}
									$xz++;
								}
								$x = 2;
							}
						}

						if($x == 1){
							$get_reports = $this->conn->prepare("SELECT DISTINCT i.id, i.subject AS actividade, t.name AS name, ind.subject AS indicador, prod.subject AS produto,
                                    resImed.subject AS resultadoImediato, resInter.subject AS resultadoIntermedio, resFinal.subject AS resultadoFinal,
                                    orc.value AS orcamento,vg.value AS valorGasto,
                                    obs.value AS observacoes, fv.value AS fonteVerificacao
                                    from issues AS i
                                    INNER JOIN trackers AS t ON (i.tracker_id = t.id)
                                    LEFT JOIN issues AS ind ON (ind.parent_id = i.id AND ind.tracker_id=12)
                                    LEFT JOIN issues AS prod ON (i.parent_id = prod.id AND prod.tracker_id=2)
                                    LEFT JOIN issues AS resImed ON ((prod.parent_id = resImed.id OR i.parent_id = resImed.id) AND resImed.tracker_id=10)
                                    LEFT JOIN issues AS resInter ON ((resImed.parent_id = resInter.id OR prod.parent_id = resInter.id OR i.parent_id = resInter.id) AND resInter.tracker_id = 3)
                                    LEFT JOIN issues AS resFinal ON ((resInter.parent_id = resFinal.id OR resImed.parent_id = resFinal.id OR prod.parent_id = resFinal.id OR i.parent_id = resFinal.id) AND resFinal.tracker_id = 5)
                                    LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
                                    LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
                                    LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
                                    LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
                                    WHERE i.tracker_id = 11 AND i.project_id=? and resInter.subject = ? and resImed.subject = ? Order by t.position ASC");
								$get_reports->execute(array($id_report, $rowsEst->resultadoIntermedio, $rowsEst->resultadoImediato));
							$xz  = 0;
							while ($info_report = $get_reports->fetchObject()){
                                if(number_format((float)($info_report->orcamentovalorGasto)) == 0 && number_format((float)($info_report->orcamento)) != 0){

                                        $percent = number_format((float)((($info_report->orcamentovalorGasto) / (number_format((float)($info_report->orcamento)))) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
                                    }
                                    if(number_format((float)($info_report->orcamentovalorGasto)) != 0 && number_format((float)($info_report->orcamento)) == 0){
                                        $percent = "0.00%";
                                    }

                                    if(number_format((float)($info_report->orcamentovalorGasto)) == 0 && number_format((float)($info_report->orcamento)) == 0){
                                        $percent = "0.00%";
                                    }

                                    if(number_format((float)($info_report->orcamentovalorGasto)) != 0 && number_format((float)($info_report->orcamento)) != 0){

                                        $percent = number_format((float)((($info_report->orcamentovalorGasto) / (number_format((float)($info_report->orcamento)))) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
                                    }

								if($xz == 0){
									$da .= "<td rowspan='$rowsEst->resImed'>$rowsEst->resultadoImediato</td>
											<td>$info_report->produto</td>
											<td>$info_report->actividade</td>
											<td>$info_report->indicador</td>
											<td>".number_format((float)($info_report->orcamento), 2, '.', ',')."</td>
											<td>".number_format((float)($info_report->valorGasto), 2, '.', ',')."</td>
                                            <td>$percent</td>
                                            <td>$info_report->fonteVerificacao</td>
										";
									}else{
										$ds .= "<tr>
                                                <td>$info_report->produto</td>
                                                <td>$info_report->actividade</td>
                                                <td>$info_report->indicador</td>
                                                <td>".number_format((float)($info_report->orcamento), 2, '.', ',')."</td>
                                                <td>".number_format((float)($info_report->valorGasto), 2, '.', ',')."</td>
                                                <td>$percent</td>
                                                <td>$info_report->fonteVerificacao</td>
										    </tr>";

									}
									$xz++;
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
			$get_header = $this->conn->prepare("SELECT
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

		$title = "Relatório Orçamento Projectos";

		$DOMhtml = "<table border='1'>
						<thead>
							<tr><td colspan='10'><h4 style='text-transform: uppercase'>Associação PROGRESSO</h4></td></tr>
							<tr>
								<td colspan='10'>
									Av. Ahmed Sekou Touré Nº 1957, Maputo - Phone: + 258 21 430 485/6: Email: comunicar@progresso.co.mz Maputo - Mocamique
								</td>
							</tr>
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
							</tr>
						</thead>
						<tbody>
							$tr
						</tbody>
					</table>
					<div style='margin-top: 15px;'>
                        Gerado Por computador
                        <br>Script Desenvolvido por <strong>Edilson Mucanze</strong>
                    </div>";

		// Nome do Arquivo do Excel que será gerado
		$data=date("y-m-d h:i:s");
		$arquivo = "Relatório_Orçamento_Projectos".$data.".xls";

		header ('Cache-Control: no-cache, must-revalidate');
		header ('Pragma: no-cache');
		header('Content-Type: application/x-msexcel; charset=iso-8859-1');
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"");


        print chr(255) . chr(254) . mb_convert_encoding($DOMhtml, 'UTF-16LE', 'UTF-8');
		// $this->createPDF($this->DOMhtml);
		$tr = null;
		$trd = null;
	}


	public function reportActProj($p)
	{
			$id_report = $p;
			$id_report = $p;
			$A = null;
			$ds  = null;
			$da  = null;
			$tdFi = null;
			$tdFs  = null;
			$ZKP = null;
			$n  = null;

			$get_rowObjE = $this->conn->prepare("SELECT DISTINCT i.id, resFinal.subject AS resultadoFinal, resInter.subject AS resultadoIntermedio,
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
					$get_rowObjEst = $this->conn->prepare("SELECT DISTINCT i.id, resFinal.subject AS resultadoFinal, resInter.subject AS resultadoIntermedio, resImed.subject AS resultadoImediato,
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

									$get_reports = $this->conn->prepare("SELECT DISTINCT i.id, i.subject AS actividade, t.name AS name, ind.subject AS indicador, prod.subject AS produto,
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
											$percent = number_format((float)((($info_report->metaNumerica) / ($info_report->realizado)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
										}
										if(number_format((float)($info_report->metaNumerica)) != 0 && number_format((float)($info_report->realizado)) == 0){
											$percent = "0.00%";
										}

										if(number_format((float)($info_report->metaNumerica)) == 0 && number_format((float)($info_report->realizado)) == 0){
											$percent = "0.00%";
										}

										if(number_format((float)($info_report->metaNumerica)) != 0 && number_format((float)($info_report->realizado)) != 0){
											$percent = number_format((float)((($info_report->metaNumerica) / ($info_report->realizado)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
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
													<td>".$info_report->realizado."</td>
													<td>".$percent."</td>
													<td>".$info_report->fonteVerificacao."</td>
												</tr>
											";
										}else{
											$ds .= "<tr>
													<td>".$info_report->produto."</td>
													<td>".$info_report->actividade."</td>
													<td>".$info_report->indicador."</td>
													<td>".$info_report->variavel."</td>
													<td>".$info_report->metaNumerica."</td>
													<td>".$info_report->realizado."</td>
													<td>".$percent."</td>
													<td>".$info_report->fonteVerificacao."</td>
												</tr>";

										}
										$xz++;
									}
									$x = 2;
								}
							}

							if($x == 1){
								$get_reports = $this->conn->prepare("SELECT DISTINCT i.id, i.subject AS actividade, t.name AS name, ind.subject AS indicador, prod.subject AS produto,
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
											$percent = number_format((float)((($info_report->metaNumerica) / ($info_report->realizado)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
										}
										if(number_format((float)($info_report->metaNumerica)) != 0 && number_format((float)($info_report->realizado)) == 0){
											$percent = "0.00%";
										}

										if(number_format((float)($info_report->metaNumerica)) == 0 && number_format((float)($info_report->realizado)) == 0){
											$percent = "0.00%";
										}

										if(number_format((float)($info_report->metaNumerica)) != 0 && number_format((float)($info_report->realizado)) != 0){
											$percent = number_format((float)((($info_report->metaNumerica) / ($info_report->realizado)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
										}

									if($xz == 0){
										$da .= "<td rowspan='$rowsEst->resImed'>$rowsEst->resultadoImediato</td>
												<td>".$info_report->produto."</td>
												<td>".$info_report->actividade."</td>
												<td>".$info_report->indicador."</td>
												<td>".$info_report->variavel."</td>
												<td>".$info_report->metaNumerica."</td>
												<td>".$info_report->realizado."</td>
												<td>".$percent."</td>
												<td>".$info_report->fonteVerificacao."</td>
											";
										}else{
											$ds .= "<tr>
													<td>".$info_report->produto."</td>
													<td>".$info_report->actividade."</td>
													<td>".$info_report->indicador."</td>
													<td>".$info_report->variavel."</td>
													<td>".$info_report->metaNumerica."</td>
													<td>".$info_report->realizado."</td>
													<td>".$percent."</td>
													<td>".$info_report->fonteVerificacao."</td>
												</tr>";

										}
										$xz++;
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
				$get_header = $this->conn->prepare("SELECT
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
			$title = "Relatório Atividade Projectos";

		$DOMhtml = "<table border='1'>
					<thead>
							<tr><td colspan='10'><h4 style='text-transform: uppercase'>Associação PROGRESSO</h4></td></tr>
							<tr>
								<td colspan='10'>
									Av. Ahmed Sekou Touré Nº 1957, Maputo - Phone: + 258 21 430 485/6: Email: comunicar@progresso.co.mz Maputo - Mocamique
								</td>
							</tr>
						<tr class='p-2 border-0' style=' font-size: 0.78rem; '>
							<th>".$resp_header->res_final."</th>
							<th>".$resp_header->res_intermedio."</th>
							<th>".$resp_header->res_imediato."</th>
							<th>".$resp_header->produto."</th>
							<th>".$resp_header->actividade."</th>
							<th>".$resp_header->indicador."</th>
							<th>".$resp_header->variavel."</th>
							<th>".$resp_header->metaNumerica."</th>
							<th>".$resp_header->realizado."</th>
							<th>Percentual</th>
							<th>".$resp_header->fonteVerificacao."</th>
						</tr>
					</thead>
					<tbody>
						$tr
					</tbody>
					</table>
					<div style='margin-top: 15px;'>
                        Gerado Por computador
                        <br>Script Desenvolvido por <strong>Edilson Mucanze</strong>
                    </div>";

		// Nome do Arquivo do Excel que será gerado
		$data=date("y-m-d h:i:s");
		$arquivo = "Relatório_Atividade_Projectos".$data.".xls";

		header('Content-Type: text/html; charset=utf-8');
		header ('Cache-Control: no-cache, must-revalidate');
		header ('Pragma: no-cache');
		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"");

		print chr(255) . chr(254) . mb_convert_encoding($DOMhtml, 'UTF-16LE', 'UTF-8');

		$tr = null;
		$trd = null;
	}


	public function reportOrcPDE($p)
	{
		$id_report = $p;
		$A = null;
		$ds  = null;
		$da  = null;
		$tdFi = null;
		$tdFs  = null;
		$ZKP = null;
		$n  = null;

			$get_rowObjE = $this->conn->prepare("SELECT DISTINCT i.id, objEspecifico.id as id_objwe, objGeral.subject AS objectivoGeral,
						objEspecifico.subject AS objectivoEspecifico,
						count(objEspecifico.subject) as rowObjE
						from issues AS i
						INNER JOIN trackers AS t ON (i.tracker_id = t.id)
						LEFT JOIN issues AS ind ON (ind.parent_id = i.id AND ind.tracker_id=12)
						LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
						LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
						LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
						LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
						LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
						LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
						LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
						WHERE i.tracker_id = 11 AND i.project_id= 12 group by objectivoEspecifico Order by objEspecifico.subject, estrategia.subject");
			$get_rowObjE->execute(array($id_report));

			$tr = ""; $xi = 0;
			while ($rows = $get_rowObjE->fetchObject()) {

				// if($xi == 0){
					$get_rowObjEst = $this->conn->prepare("SELECT DISTINCT i.id, estrategia.subject AS estrategia,
								objEspecifico.subject AS objectivoEspecifico,
								count(estrategia.subject) as rowEstr
								from issues AS i
								INNER JOIN trackers AS t ON (i.tracker_id = t.id)
								LEFT JOIN issues AS ind ON (ind.parent_id = i.id AND ind.tracker_id=12)
								LEFT JOIN issues AS estrategia ON (i.parent_id = estrategia.id AND estrategia.tracker_id=18)
								LEFT JOIN issues AS objEspecifico ON ((estrategia.parent_id = objEspecifico.id OR i.parent_id = objEspecifico.id) AND objEspecifico.tracker_id=16)
								LEFT JOIN issues AS objGeral ON ((objEspecifico.parent_id = objGeral.id OR estrategia.parent_id = objGeral.id OR i.parent_id = objGeral.id) AND objGeral.tracker_id = 13)
								LEFT JOIN custom_values AS orc ON (orc.custom_field_id=29 AND orc.customized_id=i.id)
								LEFT JOIN custom_values AS vg ON (vg.custom_field_id=108 AND vg.customized_id=i.id)
								LEFT JOIN custom_values AS obs ON (obs.custom_field_id=51 AND obs.customized_id=ind.id)
								LEFT JOIN custom_values AS fv ON (fv.custom_field_id=46 AND fv.customized_id=ind.id)
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
									$get_reports = $this->conn->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS  objectivoGeral,
										objEspecifico.subject AS objectivoEspecifico,
										t.name AS name, ind.subject AS indicador, orc.value AS orcamento,vg.value AS valorGasto,
										obs.value AS observacoes, fv.value AS fonteVerificacao
										from issues AS i
										INNER JOIN trackers AS t ON (i.tracker_id = t.id)
										LEFT JOIN issues AS ind ON (ind.parent_id = i.id AND ind.tracker_id=12)
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
										$get_realizado = $this->conn->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
											inner join custom_values as val_gasto on (val_gasto.customized_id =  act_rel.customized_id)
											WHERE
											orcamento.custom_field_id = 29
											and val_gasto.custom_field_id = 108
											");
											$get_realizado->execute(array($info_report->id));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $this->conn->prepare("SELECT DISTINCT
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

											if(number_format((float)($real->valorGasto)) == 0 && number_format((float)($real->orcamentoPrev)) != 0){
												$percent = number_format((float)((($real->valorGasto) / ($info_report->realizado)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
											}
											if(number_format((float)($real->valorGasto)) != 0 && number_format((float)($real->orcamentoPrev)) == 0){
												$percent = "0.00%";
											}

											if(number_format((float)($real->valorGasto)) == 0 && number_format((float)($real->orcamentoPrev)) == 0){
												$percent = "0.00%";
											}

											if(number_format((float)($real->valorGasto)) != 0 && number_format((float)($real->orcamentoPrev)) != 0){
												$percent = number_format((float)((($real->valorGasto) / ($real->orcamentoPrev)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
											}

										if($xz == 0){
											$da .= "
												<tr>
												<td rowspan='$i'>$rowsEst->estrategia</td>
												<td>".$info_report->actividade."</td>
												<td>".$Rprojecto_name.".</td>
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
												<td>".$Rprojecto_name.".</td>
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
								}
							}

							if($x == 1){

								// echo $a = "B->".$rowsEst->rowEstr."->$rowsEst->estrategia->$i</br>";
								$get_reports = $this->conn->prepare("SELECT DISTINCT i.id, i.subject AS actividade, estrategia.subject AS estrategia, objGeral.subject AS  objectivoGeral,
										objEspecifico.subject AS objectivoEspecifico,
										t.name AS name, ind.subject AS indicador, orc.value AS orcamento,vg.value AS valorGasto,
										obs.value AS observacoes, fv.value AS fonteVerificacao
										from issues AS i
										INNER JOIN trackers AS t ON (i.tracker_id = t.id)
										LEFT JOIN issues AS ind ON (ind.parent_id = i.id AND ind.tracker_id=12)
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
										$get_realizado = $this->conn->prepare("SELECT
											COALESCE(SUM(case when orcamento.value = null or orcamento.value = '' or orcamento.value like '%a%' or orcamento.value like '%b%' or orcamento.value like '%c%' then '0' else orcamento.value end), 0) as orcamentoPrev,
											COALESCE(SUM(case when val_gasto.value = null or val_gasto.value = '' or val_gasto.value like '%a%' or val_gasto.value like '%b%' or val_gasto.value like '%c%' then '0' else val_gasto.value end), 0) as valorGasto
											FROM
											issues AS act_p
											inner join custom_values as act_rel on ( value LIKE CONCAT('%',act_p.id,'%') and act_p.id = ?)
											inner join custom_values as orcamento on (orcamento.customized_id =  act_rel.customized_id)
											inner join custom_values as val_gasto on (val_gasto.customized_id =  act_rel.customized_id)
											WHERE
											orcamento.custom_field_id = 29
											and val_gasto.custom_field_id = 108
											");
											$get_realizado->execute(array($info_report->id));
											$real = $get_realizado->fetchObject();

										// Pegar Actividade Relacionada -> id_actividade_relacionda -> nomeProjecto
										$get_relation = $this->conn->prepare("SELECT DISTINCT
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

											if(number_format((float)($real->valorGasto)) == 0 && number_format((float)($real->orcamentoPrev)) != 0){
												$percent = number_format((float)((($real->valorGasto) / ($info_report->realizado)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
											}
											if(number_format((float)($real->valorGasto)) != 0 && number_format((float)($real->orcamentoPrev)) == 0){
												$percent = "0.00%";
											}

											if(number_format((float)($real->valorGasto)) == 0 && number_format((float)($real->orcamentoPrev)) == 0){
												$percent = "0.00%";
											}

											if(number_format((float)($real->valorGasto)) != 0 && number_format((float)($real->orcamentoPrev)) != 0){
												$percent = number_format((float)((($real->valorGasto) / ($real->orcamentoPrev)) * 100), 2, '.', '') ."%"; // Formatando o valor para percentual
											}
									if($xz == 0){
										$da .= "<td rowspan='$rowsEst->rowEstr'>$rowsEst->estrategia</td>
												<td>".$info_report->actividade."</td>
												<td>".$Rprojecto_name.".</td>
												<td>".$info_report->indicador."</td>
												<td>".number_format((float)($real->orcamentoPrev), 2, '.', ',')."</td>
												<td>".number_format((float)($real->valorGasto), 2, '.', ',')."</td>
												<td>".$percent."</td>
												<td>".$info_report->fonteVerificacao."</td>
											";
										}else{
											$ds .= "<tr>
												<td>".$info_report->actividade."</td>
												<td>".$Rprojecto_name.".</td>
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
				$get_header = $this->conn->prepare("SELECT
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

		$title = "Relatório Orçamento PDE";
		$DOMhtml = "<table border='1'>
						<thead>
							<tr><td colspan='10'><h4 style='text-transform: uppercase'>Associação PROGRESSO</h4></td></tr>
							<tr>
								<td colspan='10'>
									Av. Ahmed Sekou Touré Nº 1957, Maputo - Phone: + 258 21 430 485/6: Email: comunicar@progresso.co.mz Maputo - Mocamique
								</td>
							</tr>
							<tr class='p-2 border-0' style=' font-size: 0.78rem; '>
								<th>".$resp_header->obj_geral."</th>
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
						</tbody>
						</table>
						<div style='margin-top: 15px;'>
							Gerado Por computador
							<br>Script Desenvolvido por <strong>Edilson Mucanze</strong>
						</div>";

		// Nome do Arquivo do Excel que será gerado
		$data=date("y-m-d h:i:s");
		$arquivo = "Relatório_Orçamento_PDE".$data.".xls";

		header ('Cache-Control: no-cache, must-revalidate');
		header ('Pragma: no-cache');
		header('Content-Type: application/x-msexcel; charset=iso-8859-1');
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"");

        print chr(255) . chr(254) . mb_convert_encoding($DOMhtml, 'UTF-16LE', 'UTF-8');
		// $this->createPDF($this->DOMhtml);
		$tr = null;
		$trd = null;
	}

    private function createPDF($DOMHtml){
        // echo $DOMHtml;
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        $options->set('debugKeepTemp', TRUE);
        $options->set('isHtml5ParserEnabled', true);

        //$options->set('chroot', '');
        $dompdf = new Dompdf($options);
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($DOMHtml);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A2', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream("fileName", array("Attachment" => 0));
    }

 }




