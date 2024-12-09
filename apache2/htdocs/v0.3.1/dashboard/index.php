<?php

include_once('../system/config.php');
require_once('../system/BD.class.php');
require '../system/api/vendor/autoload.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom styles for this template -->
    <link href="css/core.css" rel="stylesheet">
    <link href="css/colors.css" rel="stylesheet">
    <link href="css/components.css" rel="stylesheet">
    <!-- Global stylesheets -->
    <link href="css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">

    <link href="css/style.css" rel="stylesheet">
    <link href="css/timeline.css" rel="stylesheet">
    <!-- /Custom styles for this template -->

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular-sanitize.js"></script>

    <!-- dashboard scripts -->
    <script src="js/controllers/DashboardController.js"></script>

    <script type="text/javascript" src="js/core/libraries/jquery.min.js"></script>
    <script src="https://d3js.org/d3.v5.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="js/c3.js"></script>
    <!-- <script type="text/javascript" src="js/plugins/visualization/c3/c3.min.js"></script> -->

</head>

<body ng-app="appSMP">
    <div class="col-md-12 m-0 p-2" ng-controller="dashboardController">
        <div class="row m-1 ml-lg-5 mr-lg-5">
            <div class="col-12 mt-1 p-1">
                <div class="row">
                    <div class="col-md-8 col-lg-9">
                        <!-- row 1 -->
                        <div class="row mb-3 text-center">
                            <div class="col-md-3 mb-2 mb-lg-0">
                                <div class="card-block pt-4">
                                    <div class="content-group m-0">
                                        <h4 class="text-semibold no-margin"><i class="icon-calendar5 position-left text-slate"></i> {{ overview.my_tasks ? overview.my_tasks : '0' }}</h4>
                                        <span class="text-muted ">Minhas Tarefas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2 mb-lg-0">
                                <div class="card-block pt-4">
                                    <div class="content-group m-0">
                                        <h4 class="text-semibold no-margin"><i class="icon-calendar5 position-left text-slate"></i> {{ overview.tasks_assinged_to_me ? overview.tasks_assinged_to_me : '0' }}</h4>
                                        <span class="text-muted ">Tarefas atribuídas a mim</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2 mb-lg-0">
                                <div class="card-block pt-4">
                                    <div class="content-group m-0">
                                        <h4 class="text-semibold no-margin"><i class="icon-calendar5 position-left text-slate"></i> {{ overview.hotTasks ? overview.hotTasks : '0' }}</h4>
                                        <span class="text-muted ">Tarefas por terminar</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2 mb-lg-0">
                                <div class="card-block pt-4">
                                    <div class="content-group m-0">
                                        <h4 class="text-semibold no-margin"><i class="icon-calendar5 position-left text-slate"></i> {{ overview.mc_task ? overview.mc_task+' %' : '0' }}</h4>
                                        <span class="text-muted ">Margem de Contribuição</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /row 1 -->



                        <!-- row 2 -->
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-4 mb-3">
                                <div class="card-block bg-white p-3">
                                    <div class="title-card border-bottom">
                                        <div class="bg-ligth rounded">
                                            <h6>
                                                <a href="" class="text-slate-800">
                                                    <i class="icon-stack3"></i>
                                                    <span>Programas por Provincia</span>
                                                </a>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="mapping-area-wrapper">
                                        <div class="mapping-area">
                                            <img id="map" src="../../imgs/map-moz-color-zone.png" usemap="#mocambique">
                                            <map name="mocambique" id="mocambique">
                                                <a href="" itemid="1" ng-onclick="getByProvincia(a)">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Maputo-Cidade')" rev="-327,-132,37,83,48,409" id="Maputo-Cidade" alt="Maputo-Cidade" title="Maputo-Cidade" coords="80,440, 79,437, 78,434, 78,427, 72,419, 72,416, 65,415, 61,411, 55,411, 53,413, 53,414, 54,415, 55,429, 55,439, 55,449, 55,453, 52,455, 52,462, 56,465, 58,469, 56,473, 57,480, 59,490, 61,490, 63,492, 65,491, 76,491, 77,488, 78,478, 78,475, 78,472, 79,467, 78,468,76,472, 72,468, 69,465,67,466, 65,466, 66,465, 72,461, 73,457, 76,451, 82,446, 84,445, 83,445, 82,442, 80,440">
                                                    <p style="color: #fff; position: absolute; margin-top: -30px; margin-left: 70px" ng-click="getByProvincia('Maputo-Cidade')">
                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-default" data-original-title="Maputo-Cidade">Maputo-Cidade
                                                        </span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Maputo-Cidade"].projects ? projects_provincia["Maputo-Cidade"].projects : 0 }}
                                                        </span>

                                                    </p>
                                                </a>
                                                <a href="" itemid="2">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Maputo-Província')" rev="-327,-132,37,83,48,409" id="Maputo-Provincia" alt="Maputo-Provincia" title="Maputo-Provincia" coords="80,440, 79,437, 78,434, 78,427, 72,419, 72,416, 65,415, 61,411, 55,411, 53,413, 53,414, 54,415, 55,429, 55,439, 55,449, 55,453, 52,455, 52,462, 56,465, 58,469, 56,473, 57,480, 59,490, 61,490, 63,492, 65,491, 76,491, 77,488, 78,478, 78,475, 78,472, 79,467, 78,468,76,472, 72,468, 69,465,67,466, 65,466, 66,465, 72,461, 73,457, 76,451, 82,446, 84,445, 83,445, 82,442, 80,440">
                                                    <p style="color: #fff; position: absolute; margin-top: -70px; margin-left: 60px" ng-click="getByProvincia('Maputo-Província')">


                                                        <span id="ListViewMapa_span1_1" data-placement="right" class="tooltips label-default" data-original-title="Maputo-Província">Maputo-Província</span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Maputo-Província"].projects ? projects_provincia["Maputo-Província"].projects : 0 }}
                                                        </span>
                                                    </p>
                                                </a>

                                                <a href="" itemid="3">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Gaza')" rev="-228,-130,91,134,29,315" id="Gaza" alt="Gaza" title="Gaza" coords="111,427, 112,424, 116,417, 108,420, 104,415, 104,409, 101,407, 101,397, 101,393, 101,390, 99,385, 99,380, 85,360, 87,353, 87,346, 83,337, 85,330, 83,330, 78,332, 76,330, 74,330, 72,328, 64,324, 63,323, 63,324, 36,358, 41,380, 41,389, 46,394, 45,397, 48,403, 51,405,51,409, 53,413, 55,411,61,411, 65,415, 72,416, 72,419, 78,427, 78,434, 79,437, 80,440, 82,442, 83,445, 84,445, 96,439, 116,430, 116,429, 111,427">
                                                    <p style="color: #fff; position: absolute; margin-top: -135px; margin-left: 60px" ng-click="getByProvincia('Gaza')">


                                                        <span id="ListViewMapa_span1_2" data-placement="right" class="tooltips label-default" data-original-title="Gaza">Gaza</span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Gaza"].projects ? projects_provincia["Gaza"].projects : 0 }}
                                                        </span>
                                                    </p>
                                                </a>

                                                <a href="" itemid="4">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Inhambane')" rev="-152,-148,66,120,80,311" id="Inhambane" alt="Inhambane" title="Inhambane" coords="125,315, 123,317, 120,317, 116,320, 112,323, 108,323, 104,323, 100,327, 85,330, 85,330, 83,337, 87,346, 87,353, 85,360, 99,380, 99,385, 101,390, 101,393, 101,397, 101,407, 104,409, 104,415, 108,420, 116,417, 112,424, 111,427, 116,429, 116,430, 130,424, 133,420, 142,408,143,402, 143,398,142,397, 141,399, 140,400, 138,402, 139,399, 140,397, 140,396, 140,388, 142,384, 142,377, 144,373, 143,372, 143,367, 142,359, 143,353, 142,347, 141,348, 140,350, 139,352, 140,356, 139,358, 137,356, 138,348, 137,342, 137,335, 131,320, 130,317, 131,316, 131,314, 132,312, 132,312, 125,315">
                                                    <p style="color: #fff; position: absolute; margin-top: -160px; margin-left: 110px" ng-click="getByProvincia('Inhambane')">


                                                        <span id="ListViewMapa_span1_3" data-placement="right" class="tooltips label-default" data-original-title="Inhambane">Inhambane</span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Inhambane"].projects ? projects_provincia["Inhambane"].projects : 0 }}
                                                        </span>
                                                    </p>
                                                </a>

                                                <a href="" itemid="5">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Manica')" rev="-3,-118,61,164,58,174" id="Manica" alt="Manica" title="Manica" coords="72,328, 74,330, 76,330, 78,332, 83,330, 85,330, 85,330, 100,327, 104,323, 105,317, 104,314, 102,314, 102,312, 101,307, 96,304, 90,297, 91,291, 89,286, 94,281, 97,281, 100,278, 98,272, 100,272, 105,253, 100,248, 96,245, 96,243, 99,238, 99,233, 102,228, 105,231, 108,230,108,223, 107,221,106,212, 109,198, 114,193, 116,188, 115,188, 105,184, 103,182, 97,176, 93,177, 88,179, 86,185, 81,195, 79,197, 78,202, 76,204, 77,206, 76,210, 77,212, 77,218, 76,218, 76,222, 75,225, 76,231, 78,236, 78,237, 78,240, 76,239, 74,240, 76,246, 75,247, 72,247, 70,249, 70,252, 69,254, 71,255, 72,255, 74,257, 73,263, 72,266, 72,269,73,272, 73,274, 74,275, 77,274, 76,276, 79,279, 78,286, 76,286, 75,288, 74,288, 74,293, 69,301, 67,301, 64,303, 66,313, 61,318, 63,323, 64,324, 72,328">
                                                    <p style="color: #fff; position: absolute; margin-top: -260px; margin-left: 80px" ng-click="getByProvincia('Manica')">


                                                        <span id="ListViewMapa_span1_4" data-placement="right" class="tooltips label-default" data-original-title="Manica">Manica</span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Manica"].projects ? projects_provincia["Manica"].projects : 0 }}
                                                        </span>
                                                    </p>
                                                </a>

                                                <a href="" itemid="6">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Sofala')" rev="-72,-122,77,143,86,185" id="Sofala" alt="Sofala" title="Sofala" coords="159,239, 151,231, 149,226, 142,224, 137,216, 130,208, 125,194, 125,194, 116,188, 114,193, 109,198, 106,212, 107,221, 108,223, 108,230, 105,231, 102,228, 99,233, 99,238, 96,243, 96,245, 100,248, 105,253, 100,272, 98,272, 100,278, 97,281, 94,281, 89,286, 91,291, 90,297,96,304, 101,307, 102,312,102,313, 104,314, 105,316, 104,323, 108,323, 112,323, 116,320, 120,317, 123,317, 125,315, 132,312, 132,313, 132,312, 131,312, 130,310, 129,306, 127,305, 125,304, 122,300, 121,298, 121,294, 122,290, 123,289, 119,283, 125,279, 128,278, 139,267, 145,260, 150,254, 153,253, 156,251, 161,251, 161,251, 158,248, 158,239">
                                                    <p style="color: #fff; position: absolute; margin-top: -230px; margin-left: 115px" ng-click="getByProvincia('Sofala')">


                                                        <span id="ListViewMapa_span1_5" data-placement="right" class="tooltips label-default" data-original-title="Sofala">Sofala</span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Sofala"].projects ? projects_provincia["Sofala"].projects : 0 }}
                                                        </span>
                                                    </p>
                                                </a>

                                                <a href="" itemid="7">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Tete')" rev="0,0,145,120,-2,100" id="Tete" alt="Tete" title="Tete" coords="79,197, 81,195, 86,185, 88,179, 93,177, 97,176, 103,182, 105,184, 115,188, 116,188, 125,194, 125,194, 125,194, 130,208, 138,216, 137,206, 136,198, 136,197, 131,197, 131,194, 132,191, 132,188, 129,188, 128,186, 125,185, 125,182, 122,180, 120,178, 118,176, 117,173,115,173, 113,169, 114,166,113,163, 110,162, 109,159, 110,157, 112,155, 113,153, 114,152, 114,147, 116,145, 116,142, 116,136, 118,133, 117,128, 116,125, 116,121, 112,116, 109,117, 106,118, 104,119, 100,118, 99,120, 96,121, 95,119, 94,122, 93,121, 90,118, 87,115, 87,111, 86,110, 84,109, 84,107, 84,106, 81,105, 56,115, 45,119, 34,124, 19,128, 2,136,5,146, 7,151, 6,152, 6,154, 7,165, 26,165, 30,165, 34,170, 41,171, 46,175, 47,176, 58,178, 67,182, 68,184, 73,186, 76,186, 73,191, 73,192, 76,201, 76,203, 76,204, 78,202, 79,197">
                                                    <p style="color: #fff; position: absolute; margin-top: -360px; margin-left: 65px" ng-click="getByProvincia('Tete')">


                                                        <span id="ListViewMapa_span1_6" data-placement="right" class="tooltips label-default" data-original-title="Tete">Tete</span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Tete"].projects ? projects_provincia["Tete"].projects : 0 }}
                                                        </span>
                                                    </p>
                                                </a>

                                                <a href="" itemid="8">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Zambézia')" rev="-465,0,111,120,132,133" id="Zambézia" alt="Zambézia" title="Zambézia" coords="235,180, 231,170, 231,169, 231,165, 226,156, 224,155, 217,148, 212,146, 205,141, 202,140, 200,142, 195,138, 193,138, 189,138, 186,135, 173,135, 169,141, 164,144, 159,148, 150,148, 150,148, 149,165, 143,168, 139,168, 136,170, 136,175, 135,178, 134,179, 133,180, 135,183,137,185, 137,186,137,188, 136,191, 137,193, 136,197, 136,197, 136,198, 137,206, 138,216, 142,224, 149,226, 151,231, 158,239, 158,248, 161,251, 163,249, 164,246, 166,244, 169,240, 172,236, 177,230, 179,226, 181,222, 187,216, 193,212, 200,208, 209,205, 216,202, 225,198, 235,195, 237,191, 234,186, 233,184, 235,180">
                                                    <p style="color: #fff; position: absolute; margin-top: -320px; margin-left: 165px" ng-click="getByProvincia('Zambézia')">


                                                        <span id="ListViewMapa_span1_7" data-placement="right" class="tooltips label-default" data-original-title="Zambézia">Zambézia</span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Zambézia"].projects ? projects_provincia["Zambézia"].projects : 0 }}
                                                        </span>
                                                    </p>
                                                </a>

                                                <a href="" itemid="9">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Nampula')" rev="-344,-9,116,107,171,87" id="Nampula" alt="Nampula" title="Nampula" coords="266,90, 263,91, 259,95, 256,95, 249,98, 247,100, 241,103, 240,103, 239,104, 226,108, 219,108, 219,107, 218,109, 212,111, 209,112, 206,113, 205,111, 202,112, 198,112, 198,114, 197,116, 195,116, 194,118, 193,119, 186,122, 183,123, 177,131, 173,135, 186,135, 189,138,193,138, 195,138, 200,142,202,140, 206,141, 212,146, 217,148, 224,155, 226,156, 231,165, 231,169, 231,170, 235,180, 233,184, 234,186, 237,191, 237,191, 253,181, 255,179, 255,176, 257,174, 259,172, 265,163, 275,152, 279,146, 279,142, 276,142, 275,141, 276,140, 280,138, 280,136, 279,134, 282,131, 284,123, 284,119, 281,119, 278,119, 278,118, 280,116,280,111, 277,113, 276,111, 278,107, 276,95, 278,94, 274,92, 276,90, 274,90, 266,90">
                                                    <p style="color: #fff; position: absolute; margin-top: -385px; margin-left: 235px" ng-click="getByProvincia('Nampula')">


                                                        <span id="ListViewMapa_span1_8" data-placement="right" class="tooltips label-default" data-original-title="Nampula">Nampula</span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Nampula"].projects ? projects_provincia["Nampula"].projects : 0 }}
                                                        </span>
                                                    </p>
                                                </a>

                                                <a href="" itemid="10">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Niassa')" rev="-144,-2,116,131,108,20" id="Niassa" alt="Niassa" title="Niassa" coords="164,144, 169,141, 173,135, 177,130, 183,123, 186,122, 193,119, 194,118, 195,116, 197,116, 198,114, 198,112, 202,112, 205,111, 206,113, 209,112, 212,111, 218,109, 219,107, 214,99, 210,86, 213,82, 215,78, 213,79, 207,76, 208,70, 208,59, 214,51, 216,49, 221,42, 219,35,221,27, 216,23, 211,22,204,23, 203,30, 198,32, 195,33, 192,34, 190,33, 183,32, 181,33, 177,32, 172,36, 169,37, 169,36, 169,35, 164,35, 160,34, 160,31, 154,29, 151,27, 148,29, 144,32, 139,33, 118,31, 111,53, 114,63, 117,88, 125,88, 141,109, 143,111, 151,124, 152,130, 149,139, 150,148, 159,148, 164,144">
                                                    <p style="color: #fff; position: absolute; margin-top: -410px; margin-left: 170px" ng-click="getByProvincia('Niassa')">


                                                        <span id="ListViewMapa_span1_9" data-placement="right" class="tooltips label-default" data-original-title="Niassa">Niassa</span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Niassa"].projects ? projects_provincia["Niassa"].projects : 0 }}
                                                        </span>
                                                    </p>
                                                </a>

                                                <a href="" itemid="11">
                                                    <area shape="poly" style="fill: red" ng-click="getByProvincia('Cabo Delgado')" rev="-266,-6,77,111,205,-1" id="Cabo-Delgado" alt="Cabo-Delgado" title="Cabo-Delgado" coords="277,75, 274,78, 273,73, 277,69, 280,65, 274,62, 276,58, 276,51, 277,47, 274,32, 276,27, 272,25, 275,22, 278,18, 277,16, 277,14, 280,11, 275,8, 279,5, 278,3, 278,1, 274,1, 272,3, 268,4, 262,10, 255,13, 252,14, 247,17, 246,18, 243,20, 240,20, 234,20, 230,23, 226,23,221,27, 221,27, 219,35,221,42, 216,49, 214,51, 208,59, 208,70, 207,76, 212,79, 214,78, 213,82, 210,86, 214,99, 219,107, 219,108, 226,108, 239,104, 240,103, 241,103, 247,100, 249,98, 256,95, 259,95, 263,91, 266,90, 274,90, 276,90, 277,88, 277,75">
                                                    <p style="color: #fff; position: absolute; margin-top: -440px; margin-left: 240px" ng-click="getByProvincia('Cabo Delgado')">


                                                        <span id="ListViewMapa_span1_10" data-placement="right" class="tooltips label-default" data-original-title="Cabo-Delgado">Cabo-Delgado</span>

                                                        <span id="ListViewMapa_span1_0" data-placement="right" class="tooltips label-map-num">
                                                            {{ projects_provincia["Cabo-Delgado"].projects ? projects_provincia["Cabo-Delgado"].projects : 0 }}
                                                        </span>
                                                    </p>
                                                </a>
                                            </map>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Column 1 -->

                            <!-- Column 1 -->
                            <div class="col-md-8 mb-3">
                                <div class="card-block bg-white p-3">
                                    <div class="title-card border-bottom">
                                        <div class="">
                                            <a href="" class="text-slate-800">
                                                <i class="icon-stack3"></i>
                                                <span class="fw-400">Província: </span>
                                                <strong>{{ provincia }}</strong>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="table-responsive" style="max-height: 500px">
                                        <table class="table table-sm table-hover" style="font-size: 95%;">
                                            <thead class="bg-red-500 bg-slate-600 text-nowrap table-striped">
                                                <th>Programa</th>
                                                <th>Projectos</th>
                                                <th class="text-right">#Total</th>
                                            </thead>
                                            <tbody>
                                                <tr class='' ng-if="!loading" ng-repeat="(key, proProvincia) in project_Provincia">
                                                    <td>{{ key }}</td>
                                                    <td>
                                                        <!-- <div ng-bind-html="proProvincia.projects"></div> -->
                                                        <ul class="list-unstyled">
                                                            <li ng-repeat="project in proProvincia">
                                                                <a href="">
                                                                    - {{ project.child_project}}
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td class="text-center">{{ proProvincia.length }}</td>
                                                </tr>

                                                <tr ng-if="loading">
                                                    <td colspan="3" class="text-center">
                                                        <i class="spinner-grow bg-green-800"></i>
                                                        <i class="spinner-grow bg-green-800"></i>
                                                        <i class="spinner-grow bg-green-800"></i>
                                                    </td>
                                                </tr>

                                                <tr ng-if="project_Provincia.length == 0 && !loading">
                                                    <td colspan="3" class="text-center">
                                                        Não há dados para exibir...
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /Column 1 -->
                        </div>
                        <!-- /row 2 -->



                        <!-- row 3 -->
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <div class="card-block p-3">
                                    <div class="title-card border-bottom">
                                        <h6>
                                            <a href="" class="text-slate-800">
                                                <span>Tasks Overview</span>
                                            </a>
                                        </h6>
                                    </div>
                                    <div class="">
                                        <div id="task_Overview"></div>
                                    </div>

                                    <div class="d-flex" v-if="dataDashboard.taskOverview">
                                        <div class="flex-grow-1 text-center p-2">
                                            <div class="m-2 w-25 mr-auto ml-auto" style="border-top: 3px solid #ff2d62"></div>
                                            <h3 class="mb-0">{{ overview._issues.opened }}</h3>
                                            <div class="c3-legend-item text-black-50 fw-500">Abertas</div>
                                        </div>
                                        <div class="flex-grow-1 text-center p-2">
                                            <div class="m-2 w-25 mr-auto ml-auto" style="border-top: 3px solid #00CB96"></div>
                                            <h3 class="mb-0">{{ overview._issues.in_progress }}</h3>
                                            <div class="c3-legend-item text-black-50 fw-500">Em Progresso</div>
                                        </div>
                                        <div class="flex-grow-1 text-center p-2">
                                            <div class="m-2 w-25 mr-auto ml-auto" style="border-top: 3px solid #2d96ff"></div>
                                            <h3 class="mb-0">{{ overview._issues.closed }}</h3>
                                            <div class="c3-legend-item text-black-50 fw-500">Fechdas</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- column My Tasks -->
                            <div class="col-md-8 mb-3">
                                <div class="card-block p-3">
                                    <div class="title-card border-bottom mb-2 d-flex">
                                        <h6 class="flex-grow-1">
                                            <a href="" class="text-slate-800">
                                                <i class="icon-stack3 text-slate-400"></i>
                                                <span>Minhas Tarefas</span>
                                            </a>
                                        </h6>
                                        <div class="d-none">
                                            <a href="" class="text-slate-800 btn btn-sm bg-warning border-0">
                                                <span>Todas Tarefas</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="table-responsive" style="max-height:400px">
                                        <table class="table table-sm table-border table-hover nowrap">
                                            <thead class="table-active bg-slate">
                                                <th>Actividade | Projecto</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-right">Actualizado</th>
                                            </thead>
                                            <tbody>
                                                <tr v-if="dataDashboard.issues" ng-repeat="(key, issue) in response.issues">
                                                    <td class="p-1 m-0 d-block">
                                                        <div class="" style="margin-bottom: -4px; font-weight: 500">
                                                            <a href="issues/" href="'issues/1">{{ issue.subject }}</a>
                                                        </div>
                                                        <small class='text-black-50'>{{ issue.project }}</small>
                                                    </td>
                                                    <td class="p-1" style="width:110px">
                                                        <span class="label label-info bg-info">Em Progresso</span>
                                                    </td>
                                                    <td class="p-1 text-right text-nowrap">{{ issue.updated_on }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- column My Tasks -->
                        </div>
                        <!-- /row 3 -->

                        <!-- row 4 -->
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-4 mb-3">
                                <div class="card-block bg-white p-3">
                                    <div class="title-card border-bottom">
                                        <div class="">
                                            <h6>
                                                <a href="" class="text-slate-800">
                                                    <i class="icon-stack3"></i>
                                                    <span>Contribuição dos membros</span>
                                                </a>
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <th>Membro</th>
                                                <th>Concluidas</th>
                                                <th>N Concluidas</th>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="(key, member) in member_contrib">
                                                    <td>
                                                        <a href="">{{ member.firstname+' '+ member.lastname }}</a>
                                                    </td>
                                                    <td>
                                                        {{ member.tasks.done }}
                                                    </td>
                                                    <td>
                                                        {{ member.tasks.in_progress }}
                                                    </td>
                                                </tr>
                                            <tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                            <!-- /Column 1 -->

                            <!-- Column 1 -->
                            <div class="col-md-8 mb-3">
                                <div class="card-block bg-white p-3" style="height: 468px;">
                                    <div class="title-card border-bottom">
                                        <div class="">
                                            <h6>
                                                <a href="" class="text-slate-800">
                                                    <i class="icon-stack3"></i>
                                                    <span>Tarefas por terminar</span>
                                                </a>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="table-responsive" style="max-height:400px;">
                                        <table class="table table-sm text-nowrap" style="font-size: 95%;">
                                            <thead class="bg-red-500 text-right bg-slate-800">
                                                <th>Tarefa</th>
                                                <th class="text-center"> <i class="icon-calendar2"></i> Criada</th>
                                                <th><i class="icon-alarm-check"></i>A terminar</th>
                                            </thead>
                                            <tbody>
                                                <tr ng-if="!loadingHotTasks" ng-repeat="(key, hotTask) in response.hotTasks">
                                                    <td>
                                                        <div class="" style="margin-bottom: -4px; font-weight: 500">
                                                            <a ng-href="'issues/'+hotTask.id">{{ hotTask.subject }}</a>
                                                        </div>
                                                        <div class="progress mt-3 rounded-pill" style=".width: 120px; height:13px">
                                                            <div role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-purple-800" ng-style="{width:hotTask.done_ratio+'%'}">{{ hotTask.done_ratio }}%</div>
                                                        </div>
                                                        <div class="d-flex" style="display: none !important">
                                                            <div class="media-left media-middle pt-2 pl-0 pr-1">
                                                                <a href="" class="btn bg-warning-400 btn-rounded btn-icon" data-popup="popover" title="Username">
                                                                    <span class="letter-icon text-semibold">DE</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ hotTask.created_on }}</td>
                                                    <td class="text-right">{{ hotTask.due_date }}</td>
                                                </tr>

                                                <tr ng-if="loadingHotTasks">
                                                    <td colspan="3" class="text-center">
                                                        <i class="spinner-grow bg-green-800"></i>
                                                        <i class="spinner-grow bg-green-800"></i>
                                                        <i class="spinner-grow bg-green-800"></i>
                                                    </td>
                                                </tr>

                                                <tr ng-if="project_Provincia.length == 0 && !loadingHotTasks">
                                                    <td colspan="3" class="text-center">
                                                        Não há dados para exibir...
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /Column 1 -->
                        </div>
                        <!-- /row 4 -->
                    </div>

                    <div class="col-md-4 col-lg-3">
                        <div class="card-block p-3">
                            <div class="title-card border-bottom">
                                <h6>
                                    <a href="" class="text-slate-800">
                                        <i class="icon-history"></i>
                                        <span>Actividades recentes</span>
                                    </a>
                                </h6>
                            </div>
                            <!-- <div id="container" style="width: 500px; height: 400px;"></div> -->

                            <div class="my_timeline mt-2">
                                <div class="" ng-if="!loading" ng-repeat="(key, activities) in response.activities">

                                    <div class="timeline-date text-muted p-0 m-0 mb-1 small">
                                        <i class="icon-history position-left"></i> <span class="text-semibold">{{ key }}</span>
                                    </div>

                                    <div class="" ng-repeat="activitie in activities">
                                        <div class="my_timeline-container left">
                                            <div class="my_timeline-icon">
                                                <i v-if="activitie.i_status == 1" class="icon-add-to-list position-left text-success m-0"></i>
                                                <i v-if="activitie.i_status != 1" class="icon-checkmark-circle position-left text-success m-0"></i>
                                            </div>
                                            <div class="my_timeline-content">
                                                <div class="d-flex">
                                                    <div class="">
                                                        <h6>{{ activitie.tracker }}</h6>
                                                    </div>
                                                    <div class="flex-grow-1 text-right">
                                                        <h6 class="small text-black-50 fw-500">{{ activitie._time }}</h6>
                                                    </div>
                                                </div>
                                                <p class="mb-2">
                                                    <a href="" class="text-purple-800 fw-500">{{ activitie.firstname+' '+activitie.lastname }}</a> ({{ activitie.status_name }}) <a href="">{{ activitie.subject }}</a>
                                                </p>
                                                <div class="small min-l-h text-black-50" style="line-height:1.3">
                                                    {{ activitie.project }} » {{ activitie.tracker }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div ng-if="loading">
                                    <td colspan="3" class="text-center">
                                        <i class="spinner-grow bg-green-800"></i>
                                        <i class="spinner-grow bg-green-800"></i>
                                        <i class="spinner-grow bg-green-800"></i>
                                    </td>
                                </div>
                                <div ng-if="response.activities.length == 0 && !loading">
                                    <td colspan="3" class="text-center">
                                        Não há dados para exibir...
                                    </td>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

    </script>
</body>

</html>
