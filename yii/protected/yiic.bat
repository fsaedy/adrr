@echo off

rem -------------------------------------------------------------
rem  Yii command line script for Windows.
rem  This is the bootstrap script for running yiic on Windows.
rem -------------------------------------------------------------

@setlocal

set BIN_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=D:\Raeef\Programs\wamp\bin\php\php5.4.3\php.exe

"%PHP_COMMAND%" "%BIN_PATH%yiic.php" %*

@endlocal