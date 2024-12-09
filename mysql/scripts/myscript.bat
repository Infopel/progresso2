@echo off
"C:/Bitnami/progresso2/mysql\bin\mysql.exe" --defaults-file="C:/Bitnami/progresso2/mysql\my.ini" -u root -e "UPDATE mysql.user SET Password=password('%1') WHERE User='root';"
"C:/Bitnami/progresso2/mysql\bin\mysql.exe" --defaults-file="C:/Bitnami/progresso2/mysql\my.ini" -u root -e "DELETE FROM mysql.user WHERE User='';"
"C:/Bitnami/progresso2/mysql\bin\mysql.exe" --defaults-file="C:/Bitnami/progresso2/mysql\my.ini" -u root -e "FLUSH PRIVILEGES;"
