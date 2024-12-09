@echo off
rem START or STOP Apache Service
rem --------------------------------------------------------
rem Check if argument is STOP or START

if not ""%1"" == ""START"" goto stop

net start redmineApache-2
goto end

:stop

"C:/Bitnami/progresso2/apache2\bin\httpd.exe" -n "redmineApache-2" -k stop

:end
exit
