@echo off
rem -- Check if argument is INSTALL or REMOVE

if not ""%1"" == ""INSTALL"" goto remove

if exist "C:/Bitnami/PROGRE~1\mysql\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\mysql\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\postgresql\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\postgresql\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\elasticsearch\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\elasticsearch\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\logstash\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\logstash\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\logstash-forwarder\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\logstash-forwarder\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\kibana\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\kibana\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\apache2\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\apache2\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\apache-tomcat\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\apache-tomcat\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\resin\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\resin\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\jboss\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\jboss\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\wildfly\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\wildfly\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\activemq\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\activemq\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\openoffice\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\openoffice\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\subversion\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\subversion\scripts\serviceinstall.bat" INSTALL)
rem redmine_code_start
if exist C:/Bitnami/PROGRE~1\apps\redmine\scripts\serviceinstall.bat (start /MIN C:/Bitnami/PROGRE~1\apps\redmine\scripts\serviceinstall.bat INSTALL)
rem redmine_code_end
rem RUBY_APPLICATION_INSTALL
if exist "C:/Bitnami/PROGRE~1\mongodb\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\mongodb\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\lucene\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\lucene\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\third_application\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\third_application\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\nginx\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\nginx\scripts\serviceinstall.bat" INSTALL)
if exist "C:/Bitnami/PROGRE~1\php\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\php\scripts\serviceinstall.bat" INSTALL)
goto end

:remove

if exist "C:/Bitnami/PROGRE~1\third_application\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\third_application\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\lucene\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\lucene\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\mongodb\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\mongodb\scripts\serviceinstall.bat")
rem redmine_code_start
if exist C:/Bitnami/PROGRE~1\apps\redmine\scripts\serviceinstall.bat (start /MIN C:/Bitnami/PROGRE~1\apps\redmine\scripts\serviceinstall.bat)
rem redmine_code_end
rem RUBY_APPLICATION_REMOVE
if exist "C:/Bitnami/PROGRE~1\subversion\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\subversion\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\openoffice\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\openoffice\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\jboss\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\jboss\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\wildfly\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\wildfly\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\resin\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\resin\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\activemq\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\activemq\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\apache-tomcat\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\apache-tomcat\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\apache2\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\apache2\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\logstash-forwarder\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\logstash-forwarder\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\kibana\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\kibana\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\logstash\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\logstash\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\elasticsearch\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\elasticsearch\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\postgresql\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\postgresql\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\mysql\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\mysql\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\php\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\php\scripts\serviceinstall.bat")
if exist "C:/Bitnami/PROGRE~1\nginx\scripts\serviceinstall.bat" (start "" /MIN "C:/Bitnami/PROGRE~1\nginx\scripts\serviceinstall.bat")
:end
