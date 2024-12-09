<?php


function template($name, $issue, $task_num)
{

    if($task_num > 1){
        $string = "(Progresso) encontrou $task_num atividades atribuídas a si por reportar";
    }else{
        $string = "(Progresso) encontrou $task_num atividade atribuída a si por reportar";
    }

    $template = "<!doctype html>
            <html>
            <head>
                <meta name='viewport' content='width=device-width' />
                <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                <title>Simple Transactional Email</title>
                <style>
                /* -------------------------------------
                    GLOBAL RESETS
                ------------------------------------- */

                /*All the styling goes here*/

                img {
                    border: none;
                    -ms-interpolation-mode: bicubic;
                    max-width: 100%;
                }

                body {
                    background-color: #f6f6f6;
                    font-family: sans-serif;
                    -webkit-font-smoothing: antialiased;
                    font-size: 14px;
                    line-height: 1.4;
                    margin: 0;
                    padding: 0;
                    -ms-text-size-adjust: 100%;
                    -webkit-text-size-adjust: 100%;
                }
                li{
                    list-style: none;
                }
                table {
                    border-collapse: separate;
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                    width: 100%; }
                    table td {
                    font-family: sans-serif;
                    font-size: 14px;
                    vertical-align: top;
                }

                /* -------------------------------------
                    BODY & CONTAINER
                ------------------------------------- */

                .body {
                    background-color: #f6f6f6;
                    width: 100%;
                }

                /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                .container {
                    display: block;
                    margin: 0 auto !important;
                    /* makes it centered */
                    max-width: 680px;
                    padding: 10px;
                    width: 680px;
                }

                /* This should also be a block element, so that it will fill 100% of the .container */
                .content {
                    box-sizing: border-box;
                    display: block;
                    margin: 0 auto;
                    max-width: 680px;
                    padding: 10px;
                }

                /* -------------------------------------
                    HEADER, FOOTER, MAIN
                ------------------------------------- */
                .main {
                    background: #ffffff;
                    border-radius: 3px;
                    width: 100%;
                }

                .wrapper {
                    box-sizing: border-box;
                    padding: 20px;
                }

                .content-block {
                    padding-bottom: 10px;
                    padding-top: 10px;
                }

                .footer {
                    clear: both;
                    margin-top: 10px;
                    text-align: center;
                    width: 100%;
                }
                    .footer td,
                    .footer p,
                    .footer span,
                    .footer a {
                    color: #999999;
                    font-size: 12px;
                    text-align: center;
                }

                /* -------------------------------------
                    TYPOGRAPHY
                ------------------------------------- */
                h1,
                h2,
                h3,
                h4 {
                    color: #000000;
                    /* font-family: sans-serif; */
                    font-family: 'Proxima Nova',Roboto,Helvetica,Helvetica Neue,Arial,sans-serif!important;
                    font-weight: 400;
                    line-height: 1.4;
                    margin: 0;
                    margin-bottom: 30px;
                }

                h1 {
                    font-size: 35px;
                    font-weight: 300;
                    text-align: center;
                    text-transform: capitalize;
                }

                p,
                ul,
                ol {
                    font-family: sans-serif;
                    font-size: 14px;
                    font-weight: normal;
                    margin: 0;
                    margin-bottom: 15px;
                }
                    p li,
                    ul li,
                    ol li {
                    list-style-position: inside;
                    margin-left: 5px;
                }

                a {
                    color: #3498db;
                    text-decoration: underline;
                }

                /* -------------------------------------
                    BUTTONS
                ------------------------------------- */
                .btn {
                    box-sizing: border-box;
                    width: 100%;
                    margin-top: 10px;
                }
                .btn {
                    width: auto;
                }
                .btn {
                    background-color: #ffffff;
                    border: solid 1px #87b731;
                    border-radius: 5px;
                    box-sizing: border-box;
                    color: #87b731;
                    cursor: pointer;
                    display: inline-block;
                    font-size: 14px;
                    font-weight: bold;
                    margin: 0;
                    padding: 8px 25px;
                    text-decoration: none;
                    text-transform: capitalize;
                    text-align: center;
                }

                .btn-primary{
                    background-color: #87b731;
                    border-color: #87b731;
                    color: #ffffff;
                }

                /* -------------------------------------
                    OTHER STYLES THAT MIGHT BE USEFUL
                ------------------------------------- */
                .last {
                    margin-bottom: 0;
                }

                .first {
                    margin-top: 0;
                }

                .align-center {
                    text-align: center;
                }

                .align-right {
                    text-align: right;
                }

                .align-left {
                    text-align: left;
                }

                .clear {
                    clear: both;
                }

                .mt0 {
                    margin-top: 0;
                }

                .mb0 {
                    margin-bottom: 0;
                }

                .preheader {
                    color: transparent;
                    display: none;
                    height: 0;
                    max-height: 0;
                    max-width: 0;
                    opacity: 0;
                    overflow: hidden;
                    mso-hide: all;
                    visibility: hidden;
                    width: 0;
                }

                .powered-by a {
                    text-decoration: none;
                }

                hr {
                    border: 0;
                    border-bottom: 1px solid #d6d6d6;
                    margin: 10px 0;
                }

                /* -------------------------------------
                    RESPONSIVE AND MOBILE FRIENDLY STYLES
                ------------------------------------- */
                @media only screen and (max-width: 620px) {
                    table[class=body] h1 {
                    font-size: 28px !important;
                    margin-bottom: 10px !important;
                    }
                    table[class=body] p,
                    table[class=body] ul,
                    table[class=body] ol,
                    table[class=body] td,
                    table[class=body] span,
                    table[class=body] a {
                    font-size: 16px !important;
                    }
                    table[class=body] .wrapper,
                    table[class=body] .article {
                    padding: 10px !important;
                    }
                    table[class=body] .content {
                    padding: 0 !important;
                    }
                    table[class=body] .container {
                    padding: 0 !important;
                    width: 100% !important;
                    }
                    table[class=body] .main {
                    border-left-width: 0 !important;
                    border-radius: 0 !important;
                    border-right-width: 0 !important;
                    }
                    table[class=body] .btn table {
                    width: 100% !important;
                    }
                    table[class=body] .btn a {
                    width: 100% !important;
                    }
                    table[class=body] .img-responsive {
                    height: auto !important;
                    max-width: 100% !important;
                    width: auto !important;
                    }
                }

                /* -------------------------------------
                    PRESERVE THESE STYLES IN THE HEAD
                ------------------------------------- */
                @media all {
                    .ExternalClass {
                    width: 100%;
                    }
                    .ExternalClass,
                    .ExternalClass p,
                    .ExternalClass span,
                    .ExternalClass font,
                    .ExternalClass td,
                    .ExternalClass div {
                    line-height: 100%;
                    }
                    .apple-link a {
                    color: inherit !important;
                    font-family: inherit !important;
                    font-size: inherit !important;
                    font-weight: inherit !important;
                    line-height: inherit !important;
                    text-decoration: none !important;
                    }
                    #MessageViewBody a {
                    color: inherit;
                    text-decoration: none;
                    font-size: inherit;
                    font-family: inherit;
                    font-weight: inherit;
                    line-height: inherit;
                    }

                    .btn-primary:hover {
                    background-color: #78a030 !important;
                    border-color: #78a030 !important;
                    }
                }

                </style>
            </head>
            <body class=''>
                <span class='preheader'>Alguns clientes mostrarão este texto como uma visualização.</span>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='body'>
                <tr>
                    <td>&nbsp;</td>
                    <td class='container'>
                    <div class='content'>

                        <!-- START CENTERED WHITE CONTAINER -->
                        <table role='presentation' class='main'>

                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td class='wrapper'>
                            <table cellpadding='0' cellspacing='0' border='0'>
                                <tr>
                                <td align='left' valign='top'>
                                    <div style='height: 39px; line-height: 39px; font-size: 37px;'>&nbsp;</div>
                                    <a href='#' target='_blank' style='display: block; max-width: 128px;'>
                                    <img src='http://18.219.77.220/report/v0.3.1/logo-medium.png' alt='img' width='128' border='0'
                                        style='display: block; width: 128px;' />
                                    </a>
                                    <div style='height: 25px; line-height: 25px; font-size: 20px;'>&nbsp;</div>
                                </td>
                                </tr>
                            </table>
                            <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                                <tr>
                                <td>

                                    <p>
                                    <h4 style='color: #222222; font-size: 14pt; line-height: 15px; font-weight: 300;'>Olá
                                        <span style='font-weight: 500;'>".$name."</span>,
                                    </h4>
                                    </p>
                                    <p>O Sistema de Monitoria de Projectos ".$string.". Recomendamos que reporte a tarefa no sistema antes da data do termino.</p>
                                    <p style='margin-bottom: 0px; color: #909090;'>Segue-se a lista das atividades e mais detalhes:</p>
                                    <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                                    <tbody>
                                        ".$issue."
                                    </tbody>
                                    </table>
                                    <p></p>
                                    <hr>
                                </td>
                                </tr>
                            </table>
                            <table cellpadding='0' cellspacing='0' border='0'>
                                <tr>
                                <td valign='top'>
                                    <!--[if (gte mso 9)|(IE)]>
                                    <table border='0' cellspacing='0' cellpadding='0'>
                                    <tr><td align='center' valign='top' width='50'><![endif]-->
                                    <div style='display: inline-block; vertical-align: top; margin-top: 10px'>
                                    <table cellpadding='0' cellspacing='0' border='0' width='100%' style='width: 100% !important; min-width: 100%; max-width: 100%;'>
                                        <tr>
                                        <td align='center' valign='top'>
                                            <div style='display: block; max-width: 62px;'>
                                            <img src='http://18.219.77.220/report/v0.3.1/logo_smp.png' alt='img' width='62' border='0' style='display: block; width: 62px;' />
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                    </div>

                                </td>
                                <td><!--[if (gte mso 9)|(IE)]></td><td align='left' valign='top' width='390'><![endif]-->
                                <div class='mob_div' style='display: inline-block; vertical-align: top;'>
                                    <table cellpadding='0' cellspacing='0' border='0' style='width: 100% !important; min-width: 100%;'>
                                    <tr>
                                        <td class='mob_center' align='left' valign='top'>
                                        <div style='height: 13px; line-height: 13px; font-size: 11px;'>&nbsp;</div>
                                        <span style='color: #000000; font-size: 13px !important; line-height: 23px; font-weight: 600;'>SISTEMA DE MONITORIA DE
                                            PROJECTOS</span>
                                        <div style='height: 1px; line-height: 1px; font-size: 1px;'>&nbsp;</div>
                                        <span style='color: #7f7f7f; font-size: 13px; line-height: 23px;'>Relatorio
                                            programado</span>
                                        </td>
                                        <td width='18' style='width: 18px; max-width: 18px; min-width: 18px;'>&nbsp;</td>
                                    </tr>
                                    </table>
                                </div></td>
                                </tr>
                            </table>
                            </td>
                        </tr>

                        <!-- END MAIN CONTENT AREA -->
                        </table>
                        <!-- END CENTERED WHITE CONTAINER -->

                        <!-- START FOOTER -->
                        <div class='footer'>
                        <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                            <tr>
                            <td class='content-block'>
                                <span class='apple-link' style='color: #868686; font-size: 14px; line-height: 20px;'>Associação Progresso</span><br>
                                <span style='color: #868686; font-size: 14px; line-height: 20px;'>Copyright
                                &copy; 2019 SMP. Todos&nbsp;Direitos&nbsp;Reservados.</span>
                                <div style='height: 2px; line-height: 2px; font-size: 1px;'>&nbsp;</div>
                            </td>
                            </tr>
                            <tr>
                            <td class='content-block powered-by'>
                                Powered by <a href=''><b>InfoPel</b></a>.
                            </td>
                            </tr>
                        </table>
                        </div>
                        <!-- END FOOTER -->

                    </div>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                </table>
            </body>
            </html>
            ";

    return $template;
}
