@echo off
rem START or STOP Apache Service
rem --------------------------------------------------------
rem Check if argument is STOP or START

if not ""%1"" == ""START"" goto stop

net start redmineThin1-2

net start redmineThin2-2

goto end
:stop

net stop redmineThin1-2

net stop redmineThin2-2

:end
exit
