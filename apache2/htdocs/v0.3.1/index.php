<?php
/**
 * @author: Edilson D. Mucanze
 * @email: edilsonhmberto@gmail.com
 * @contacto: +258 84 821 3574
 * @Last Update: Dezembro de 2018
 * @Projecto: Sistema de Monitoria de Projecto
 * @Base: MiniCrafted APi
 */

?>
<!--
  /**
 * @author: Edilson D. Mucanze
 * @email: edilsonhmberto@gmail.com
 * @contacto: +258 84 821 3574
 * @date: Dezembro de 2018
 * @Projecto: Sistema de Monitoria de Projecto
 * @Base: MiniCrafted APi
 */
-->
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Sistema de Monitoria de Projectos</title>

    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Main JS Charts -->
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
  </head>

  <body ng-app="appSMP">
    <nav class="navbar sticky-top bg-progresso flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="/projects">Sistema de Monitoria de Projectos</a>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 width-auto d-none d-md-block bg-white sidebar n_print">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#relatorio/orcamento/plano/pde/12">
                  <span data-feather="bar-chart-2"></span>
                 Orçamento PDE
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#relatorio/orcamento/projecto/17">
                  <span data-feather="bar-chart-2"></span>
                   Or&ccedil;amento Projectos
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#relatorio/beneficiarios/pde/12">
                  <span data-feather="bar-chart-2"></span>
                   Beneficiários PDE
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#relatorio/beneficiarios/projecto/18">
                  <span data-feather="bar-chart-2"></span>
                   Beneficiários Projectos
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#relatorio/actividade/provincia/Maputo-Cidade">
                <span data-feather="file-text"></span>
                   Actividades por Provincia
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#relatorio/actividade/pde/12">
                <span data-feather="file-text"></span>
                   Actividades PDE
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#relatorio/actividade/projecto/17">
                  <span data-feather="file-text"></span>
                   Actividades Projecto
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#relatorio/report/orcamento/pde/12">
                  <span data-feather="file-text"></span>
                 Orçamento PDE
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#relatorio/report/orcamento/projecto/17">
                  <span data-feather="file-text"></span>
                   Or&ccedil;amento Projectos
                </a>
              </li>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Upcoming</span>
              <a class="d-flex align-items-center text-muted" href="">
                <span data-feather="plus"></span>
              </a>
            </h6>

            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="#programar-tarefas">
                  <span data-feather="settings"></span>
                  Utilites
                </a>
              </li>
            </ul>
            <button class="rounded-circle btn btn-white close_" style="height: 42px;width: 42px; position: absolute;bottom: 10px;right: 10px;  background:#d9e0e8;">
               <span data-feather="arrow-left"></span>
            </button>
          </div>
          <div class="close_btn _to_fade">
          <button class="rounded-circle btn btn-white t_open" style="height: 42px;width: 42px;  background:#d9e0e8;">
            <span data-feather="arrow-right" class=""></span>
          </button>

        </div>
        </nav>



        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 main_print main_">
          <div ng-view >

          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <!-- <script src="bootstrap/assets/js/vendor/popper.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
     <!-- Main Scripts -->
    <script src="js/main.js"></script>


     <!-- MVC -->
          <script src="js/angular.min.js"></script>
          <script src="js/angular-resource/angular-resource.js"></script>
          <script src="js/angular-route.min.js"></script>
          <script src="routes/routing.js"></script>
          <script src="controllers/controllers.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script src="bootstrap/icons/feather.min.js"></script>
    <script>
      feather.replace();
    </script>

    <script src="http://www.chartjs.org/dist/2.7.2/Chart.bundle.js"></script>
    <script src="http://www.chartjs.org/samples/latest/utils.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>

<script>

  app.run(function ($rootScope, $route, $location) {
        //Bind the $locationChangeSuccess event on the //rootScope, so that we don't need to
        //bind in induvidual controllers.
        $rootScope.enableback = 0;
        $rootScope.$on('$locationChangeSuccess', function () {
            $rootScope.actualLocation = $location.path();
            if ($rootScope.actualLocation === '/') {
              $rootScope.actualLocation = '/smp';
            }else{
              $rootScope.enableback++;
            }
        });
        $rootScope.$watch(function () {
            return $location.path()
        }, function (newLocation) {
            if (newLocation === $rootScope.actualLocation) { //  && newLocation !== '/'
              window.location.href = "http://5.189.162.27:84/redmine/projects";
            }
            if(newLocation === '/' && $rootScope.actualLocation === '/smp' && $rootScope.enableback > 0){
              window.location.href = "http://5.189.162.27:84/redmine/projects";
            }
        });
  })

</script>

  <script>
    $(document).ready(function () {
        $('.close_').click(function () {
          $('.sidebar').addClass('fade_out');

          $('.close_btn').removeClass('_to_fade');

          $('.main_').removeClass('col-md-9');
          $('.main_').removeClass('ml-sm-auto');
          $('.main_').removeClass('col-lg-10');

          $('.main_').addClass('col-12');

        });

        $('.t_open').click(function(){
          $('.sidebar').removeClass('fade_out');
          $('.close_btn').addClass('_to_fade');

          $('.main_').addClass('col-md-9');
          $('.main_').addClass('ml-sm-auto');
          $('.main_').addClass('col-lg-10');

          $('.main_').removeClass('col-12');
        })
    })
</script>


  </body>
</html>
