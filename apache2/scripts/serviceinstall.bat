@echo off
rem -- Check if argument is INSTALL or REMOVE

if not ""%1"" == ""INSTALL"" goto remove

"C:/Bitnami/progresso2/apache2\bin\httpd.exe" -k install -n "redmineApache-2" -f "C:/Bitnami/progresso2/apache2\conf\httpd.conf"

net start redmineApache-2 >NUL
goto end

:remove
rem -- STOP SERVICE BEFORE REMOVING

net stop redmineApache-2 >NUL
"C:/Bitnami/progresso2/apache2\bin\httpd.exe" -k uninstall -n "redmineApache-2"

:end
exit
