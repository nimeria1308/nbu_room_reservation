@echo off

setlocal EnableDelayedExpansion

rem include config
call config.cmd

rem compile external dependencies
echo Compiling external dependencies
start /b /w /d external cmd.exe /c build.bat
if %errorlevel% neq 0 exit /b %errorlevel%

rem create the logs dir if missing
if not exist logs\ mkdir logs

rem add https to path
set PATH=%PATH%;%XAMPP_DIR_DEFAULT%/apache/bin

rem export the projects directory
set PROJECT_DIR=%CD%

rem run apache
echo Starting Apache
httpd -d .
