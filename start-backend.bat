@echo off
REM =========================================================
REM Start Backend Server (CodeIgniter 4)
REM =========================================================

cls
echo.
echo =========================================================
echo   Backend CodeIgniter 4 Server Launcher
echo =========================================================
echo.

REM Navigate to folder
echo [*] Navigating to backend folder...
cd /d "c:\laragon\www\absensi-ci"

REM Check if spark exists
if not exist "spark" (
    echo [ERROR] spark file not found in absensi-ci folder
    echo Make sure you are in the right directory
    pause
    exit /b 1
)

REM Check PHP
echo [*] Checking PHP...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP not found!
    echo Make sure PHP is installed and in PATH
    pause
    exit /b 1
)

REM Start server
echo.
echo =========================================================
echo   Starting Backend Server...
echo =========================================================
echo.
echo [INFO] Backend URL: http://localhost:8080
echo [INFO] Database: absensi_ci (make sure it exists)
echo.
echo Press Ctrl+C to stop the server
echo.

php spark serve
