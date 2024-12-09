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
    error_reporting(1);
 /** Connect Sistem backEnd*/
    include_once('../config.php');
	require_once('../BD.class.php');

	$db = BD::conn();
    header('Content-Type:'.'application/json');
    if(isset($_GET['getVars']) && $_GET['getVars'] == 0){
        /**
         * Get Variaveis dos Beneficiarios
         */
        function getBenfVars($db){
            $get_vars = $db->prepare("SELECT possible_values as val FROM custom_fields where name = 'Beneficiarios' and type = 'TimeEntryCustomField'");
            $get_vars->execute(array());

            $array = array();
            while ($vars = $get_vars->fetchObject()){
                $array = str_replace("---", "", $vars->val);
                $array = str_replace("- ", "*", $array);
                $array = str_replace("\n", "", $array);
                $array = str_replace("\r", "", $array);
                $array = str_replace("\t", "", $array);
                $array = explode('*', $array);
            }

            $i = 0;
            foreach ($array as $key => $value) {
                if($i){
                    $response[] = array(
                        'var' => $array[$key]
                    );
                }
                $i++;
            }
            return $response;
        }

        /**
         * Get Variaveis dos Materiais
         */
        function getMaterialVars($db)
        {
            $get_Material_vars = $db->prepare("SELECT possible_values as val FROM custom_fields where name = 'Material' and type = 'TimeEntryCustomField'");
            $get_Material_vars->execute(array());

            $array = array();
            while ($vars = $get_Material_vars->fetchObject()){
                $array = str_replace("---", "", $vars->val);
                $array = str_replace("- ", "*", $array);
                $array = str_replace("\n", "", $array);
                $array = str_replace("\r", "", $array);
                $array = str_replace("\t", "", $array);
                $array = explode('*', $array);
            }

            $i = 0;
            foreach ($array as $key => $value) {
                if($i){
                    $response[] = array(
                        'var' => $array[$key]
                    );
                }
                $i++;
            }
            return $response;
        }

        /**
         * Get Projectos
         */
        function getProjectos($db)
        {
            $get_projects = $db->prepare("SELECT DISTINCT
                proj.name as programa, projects.id, projects.name as nome_projecto, projects.parent_id
            FROM projects
                INNER JOIN projects AS proj ON proj.id = projects.parent_id
            where projects.id != 12 and proj.id != 12 group by proj.name, projects.name");

            $get_projects->execute(array());

            $array = [];
            while ($projecto = $get_projects->fetchObject()) {

                $key = $projecto->programa;

                $array[$key][] = array(
                    "id_programa" => $projecto->id,
                    "parent_id" => $projecto->parent_id,
                    "programa" => $projecto->nome_projecto
                );
            }

            return $array;
        }

        echo json_encode([
                'benf_vars' => getBenfVars($db),
                'material_vars' => getMaterialVars($db),
                'programas' =>getProjectos($db)
            ]);
    }

    if(isset($_GET['getActs']) && $_GET['getActs'] == 0){

        /**
         * get actividades - by programa
         */

        function getActividades($db)
        {
            $get_actividades = $db->prepare("SELECT i.id, i.project_id as parent, projects.name, i.subject, i.assigned_to_id, i.author_id FROM issues as i inner join projects on projects.id = i.project_id WHERE project_id = ?");
            $get_actividades->execute(array($_GET['id']));

            $response = [];
            while ($actividade = $get_actividades->fetchObject()) {

                $response[$actividade->name][] = array(
                    'id' => $actividade->id,
                    'subject' => $actividade->subject,
                    'assigned_to_id' => $actividade->aassigned_to_id,
                    'author_id' => $actividade->author_id,
                    'parent' => $actividade->parent
                );
            }
            return json_encode(['actividades' => $response]);
        }
        echo getActividades($db);
    }

    // Pegar a actividade para new Entries by ID
    if(isset($_GET['getAct']) && $_GET['getAct'] == 0){
            /**
             * get actividade - by id
             */

        function getActividades($db)
        {
            $get_actividades = $db->prepare("SELECT i.id, i.subject, i.project_id as parent, i.assigned_to_id, i.author_id FROM issues as i where id = ? LIMIT 1");
            $get_actividades->execute(array($_GET['id_actividade']));

            $response = [];
            while ($actividade = $get_actividades->fetchObject()) {

                $response = array(
                    'id' => $actividade->id,
                    'subject' => $actividade->subject,
                    'assigned_to_id' => $actividade->aassigned_to_id,
                    'author_id' => $actividade->author_id,
                    'parent' => $actividade->parent
                );
            }
            return json_encode(['actividade' => $response]);
        }
        echo getActividades($db);
    }

    if(isset($_GET['store']) && $_GET['store'] == 0){

        $data = array();

        $user_id = $_POST['user_id'] ? : 67;
        $programa = $_POST['programa'];
        $actividade = $_POST['actividade'];
        $beneficiarios = $_POST['benf'];
        $material = $_POST['material'];
        $horas = $_POST['horas'] ? : 0;
        $startDate =  date('Y-m-d', strtotime($_POST['startDate']));
        $endDate =  date('Y-m-d', strtotime($_POST['endDate']));
        $comment = $_POST['comment'] ? : "Dados gravados no em ".date('d-M-Y h:m:s');
        $vGasto = $_POST['vGasto'] ? : 0;

        $beneficiarios = json_decode($beneficiarios);
        $material = json_decode($material);

        $data = array(
            'user_id' =>$user_id,
            'beneficiarios' => $beneficiarios,
            'material' => $material,
            'project_id' => $programa,
            'actividade' =>$actividade,
            'comment' =>$comment,
            'horas' =>$horas,
            'startDate' =>$startDate,
            'endDate' =>$endDate,
            'vGasto' =>$vGasto
        );

        
        function saveData($db, array $data){
            $success = true;

            
            try {
                $db->beginTransaction();
                $storeTimeJob->user_id = $data['user_id'];

                $storeTimeJob->project_id = $data['project_id'];
                $storeTimeJob->issue_id = $data['actividade'];
                $storeTimeJob->actividade_id = 8;
                $storeTimeJob->comment = $data['comment'];
                $storeTimeJob->horas = $data['horas'];
                $storeTimeJob->startDate = $data['startDate'];
                $storeTimeJob->endDate = $data['endDate'];
                $storeTimeJob->vGasto = $data['vGasto'];


                if($data['beneficiarios']!= []){
                    foreach ($data['beneficiarios'] as $beneficiario) {

                        // here we insert the very first value to the dataBase on TimesEntry Jobs
                        $storeTimeJob = storeTimeJob($db, $storeTimeJob);

                        $realizadoBenf->customized_id = $storeTimeJob->id;
                        $realizadoBenf->var = $beneficiario->var;
                        $realizadoBenf->var_field_id = 121;

                        $realizadoBenf->realizado = $beneficiario->realizado;
                        $realizadoBenf->realizado_field_id = 120;

                        storeValues($db, $realizadoBenf, $storeTimeJob);
                    }
                }

                if($data['material']!= []){
                    $Material = array();
                    foreach ($data['material'] as $materail) {
                        // here we insert the very first value to the dataBase on TimesEntry Jobs
                        $storeTimeJob = storeTimeJob($db, $storeTimeJob);

                        $realizadoMaterial->customized_id = $storeTimeJob->id;
                        $realizadoMaterial->var = $materail->var;
                        $realizadoMaterial->var_field_id = 122;

                        $realizadoMaterial->realizado = $materail->realizado;
                        $realizadoMaterial->realizado_field_id = 120;

                        storeValues($db, $realizadoMaterial, $storeTimeJob);
                    }
                }



                $store = $db->prepare("INSERT INTO custom_values (`customized_type`, `customized_id`, `custom_field_id`, `value`) VALUES (?, ?, ?, ?)");
                try {
                    $store->execute(array(
                        'TimeEntry',
                        $storeTimeJob->id,
                        119,
                        $storeTimeJob->vGasto
                    ));
                }catch (\PDOException $e) {
                    echo "Error: ".$e->getMessage();
                }


                $db->commit();
            } catch (\PDOException $e) {
                $success = false;
                $db->rollback();
                echo json_encode([
                    'status' => 0,
                    'message'=> 'Error on saving data! '.$e->getMessage()
                    ]);
            }

            if($success){
                echo json_encode([
                    'status' => true,
                    'message' => "Dados Gravados com sucesso!"
                ]);
            }
        }

        function storeTimeJob($db, $timeJobs)
        {

            $dueDate = new DateTime($timeJobs->startDate);
            $week = $dueDate->format("W");
            $year = $dueDate->format("Y");
            $month = number_format($dueDate->format("m"));

            // echo $week; return;

            $store = $db->prepare("INSERT INTO time_entries (`project_id`, `user_id`, `issue_id`, `hours`, `comments`, `activity_id`, `spent_on`, `tyear`, `tmonth`, `tweek`, `start_date`, `due_date`, `created_on`, `updated_on`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, $week, ?, ?, now(), now())");
            try {
                $store->execute(array(
                    $timeJobs->project_id,
                    $timeJobs->user_id,
                    $timeJobs->issue_id,
                    $timeJobs->horas,
                    $timeJobs->comment,
                    $timeJobs->actividade_id,
                    $timeJobs->startDate,
                    $year,
                    $month,
                    $timeJobs->startDate,
                    $timeJobs->endDate,
                ));
            } catch (\PDOException $e) {
                echo "Error: ".$e->getMessage();
            }

            $timeJobs->id = $db->lastInsertId();
            return $timeJobs;
        }

        function storeValues($db, $realizado, $timeJobs){

            // $data = array();
            for($i=0; $i < 2; $i++){
                if($i == 0){
                    $data[] = array(
                        'customized_id' => $realizado->customized_id,
                        'var' => $realizado->var,
                        'var_field_id' => $realizado->var_field_id
                    );

                    $store = $db->prepare("INSERT INTO custom_values (`customized_type`, `customized_id`, `custom_field_id`, `value`) VALUES (?, ?, ?, ?)");
                    try {
                        $store->execute(array(
                            'TimeEntry',
                            $realizado->customized_id,
                            $realizado->var_field_id,
                            $realizado->var
                        ));

                    }catch (\PDOException $e) {
                        echo "Error: ".$e->getMessage();
                    }

                }else{
                    // $storeTimeJob = storeTimeJob($db, $timeJobs);
                    $data[] = array(
                        'customized_id' => $realizado->customized_id,
                        'realizado_field_id' => $realizado->realizado_field_id,
                        'realizado' => $realizado->realizado
                    );

                    $store = $db->prepare("INSERT INTO custom_values (`customized_type`, `customized_id`, `custom_field_id`, `value`) VALUES (?, ?, ?, ?)");
                    try {
                        $store->execute(array(
                            'TimeEntry',
                            $realizado->customized_id,
                            $realizado->realizado_field_id,
                            $realizado->realizado
                        ));
                    }catch (\PDOException $e) {
                        echo "Error: ".$e->getMessage();
                    }
                }
            }
        }

        saveData($db, $data);
    }

