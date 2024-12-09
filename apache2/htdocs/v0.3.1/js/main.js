/*
Autor: Edislon Muncaze

server webconfig:<cmd -admintrator run/ cd inetsrv/>appcmd set config "Default Web Site/your app" -section:requestFiltering -requestLimits.maxAllowedContentLength:1000000
*/

$(document).ready(function (){

    // Links heligth
    $(".nav-link").click(function(){
        $(".nav-link").removeClass("active");
        $(this).addClass("active");
    });
});
