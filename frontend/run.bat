@echo off
REM ==================================================================
REM Frontend Node.js - Setup & Run Script
REM ==================================================================

echo.
echo ====================================================
echo  Frontend Absensi QR - Setup & Run Script
echo ====================================================
echo.

REM Check if Node.js is installed
echo [*] Checking Node.js installation...
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo [ERROR] Node.js is not installed!
    echo [INFO] Please install Node.js from https://nodejs.org
    echo [INFO] Download LTS version and run installer
    pause
    exit /b 1
)

echo [OK] Node.js is installed
node --version

echo.
echo [*] Checking npm installation...
npm --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] npm is not found!
    pause
    exit /b 1
)

echo [OK] npm is installed
npm --version

REM Navigate to project directory
cd /d "%~dp0"

echo.
echo [*] Checking if node_modules exists...
if not exist "node_modules" (
    echo [INFO] node_modules not found. Installing dependencies...
    echo.
    call npm install
    if %errorlevel% neq 0 (
        echo [ERROR] npm install failed!
        pause
        exit /b 1
    )
    echo [OK] Dependencies installed successfully
) else (
    echo [OK] node_modules already exists
)

echo.
echo ====================================================
echo  Starting Frontend Server...
echo ====================================================
echo.
echo [INFO] Server URL: http://localhost:3000
echo [INFO] Backend API: http://localhost:8080
echo.
echo [IMPORTANT] Make sure CodeIgniter 4 backend is running!
echo [TIP] Run "php spark serve" in absensi-ci folder
echo.

REM Start the server
npm run dev

pause
