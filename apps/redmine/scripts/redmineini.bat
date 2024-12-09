@echo off
rem Populate databases and load default data

CALL C:\Bitnami\PROGRE~1\scripts\setenv.bat

cd C:\Bitnami\progresso2/apps/redmine\htdocs
cmd /C bundle install --local --without development test sqlite --binstubs --deployment
C:\Bitnami\progresso2/ruby\bin\ruby.exe bin\rake generate_secret_token
C:\Bitnami\progresso2/ruby\bin\ruby.exe bin\rake db:migrate RAILS_ENV="production"

C:\Bitnami\progresso2/ruby\bin\ruby.exe bin\rake redmine:load_default_data RAILS_ENV="production" < lng.txt
              