@echo off
echo Importing structo.sql into Railway...

mysql -h turntable.proxy.rlwy.net -P 17747 --protocol=TCP -u root -p railway < structo.sql

pause
