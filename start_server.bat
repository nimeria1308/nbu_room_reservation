@echo off

setlocal EnableDelayedExpansion

rem include config
call config.cmd

rem init database if not there
%XAMPP_DIR%/php/php setup_database.php
if %errorlevel% neq 0 exit /b %errorlevel%

rem compile external dependencies
echo Compiling external dependencies
start /b /w /d external cmd.exe /c build.bat
if %errorlevel% neq 0 exit /b %errorlevel%

rem create the logs dir if missing
if not exist logs\ mkdir logs

rem export the projects directory
set PROJECT_DIR=%CD%

rem run apache
echo Starting Apache
%XAMPP_DIR%/apache/bin/httpd -d .
