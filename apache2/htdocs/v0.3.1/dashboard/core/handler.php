<?php

/**
 * @author: Edilson D. Mucanze
 * @email: edilsonhmberto@gmail.com
 * @contacto: +258 84 821 3574
 * @date: Dezembro de 2019
 * @Projecto: Sistema de Monitoria de Projecto
 * @Base: MiniCrafted APi
 */

header('Content-Type:' . "application/json");

/** Connect Sistem backEnd*/
include_once('../../system/config.php');
require_once('../../system/BD.class.php');
require '../../system/api/vendor/autoload.php';

require 'DashboardController.php';
use App\dashboard\DashboardController;

$user = $_GET['u'] ?? 25;
$project_parent = $_GET['parent_id'] ?? 12;

$DashboardController = new DashboardController(BD::conn(), $user, $project_parent);

if(isset($_GET['q'])){
    switch ($_GET['q']) {
        case 'prov':
            $parent = $_GET['parent'];
            $prov = $_GET['provincia'] ?? 'Maputo-Cidade';

            $provincia = $DashboardController->prov_programas($prov, $parent, 12);
            return_response($provincia);
            break;

        default:
            # code...
            break;
    }
}elseif(isset($_GET['p'])){
    $overview = $DashboardController->dataProvincias();
    return_response($overview);
}elseif(isset($_GET['m'])){
    $overview = $DashboardController->memberProgress();
    return_response($overview);
}

else{
    $overview = $DashboardController->index();
    return_response($overview);
}

/**
 * Json response asserrtion
 */
function return_response($response = null){
    print_r(json_encode($response));
}
