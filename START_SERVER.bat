@echo off
REM Calamba PopDev Resource Network - Startup Script

setlocal enabledelayedexpansion

echo ═════════════════════════════════════════════════════════════
echo   CALAMBA POPDEV RESOURCE NETWORK - STARTUP
echo ═════════════════════════════════════════════════════════════
echo.
echo Starting services...
echo.

REM Start MySQL in a separate window
echo Starting MySQL...
start "MySQL - Calamba PopDev" /MIN cmd /c "C:\xampp\mysql_start.bat"
timeout /t 3 /nobreak

REM Start PHP Development Server
echo.
echo Starting PHP Development Server on localhost:8080...
echo.
cd /d "%~dp0public"
php -S localhost:8080 -t . router.php

REM If user closes the PHP server window, we'll reach here
echo.
echo PHP Development Server stopped.
pause

pause
