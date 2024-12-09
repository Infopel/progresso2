<?php
/**
 * @author: Edilson H Mucanze
 * @email: edilsonhmberto@gmai.com
 * @contacto: 848213574
 *
 * @pro: Helper para gerar Reports pdf dinamicos
 * **/

error_reporting(-1);

    // Conexao com a base de dados
    include "config.php";
    require_once "BD.class.php";
    require_once 'helper/reports_helper.php';
    $db = BD::conn();


    // Inicializado a class;
    $reportHelper = new reportHelper($db);

    if(isset($_GET['report'], $_GET['p']) && $_GET['report'] == 'actividadePROJ'){
        $p_ = $_GET['p'];
        $reportHelper->reportActProj($p_);
    }

    if(isset($_GET['report'], $_GET['p']) && $_GET['report'] == 'actividadePDE'){
       $p_ = $_GET['p'];
       echo $reportHelper->reportPDE($p_);

    }

    if(isset($_GET['report'], $_GET['p']) && $_GET['report'] == 'reportOrcamento'){
        $p_ = $_GET['p'];
        $reportHelper->reportOrcProj($p_);
    }

    if(isset($_GET['report'], $_GET['p']) && $_GET['report'] == 'reportOrcamentoPDE'){
         $p_ = $_GET['p'];
        $reportHelper->reportOrcPDE($p_);
    }
