@echo off
REM ==================================================================
REM Frontend Node.js - Setup Only Script
REM ==================================================================

setlocal enabledelayedexpansion

echo.
echo ====================================================
echo  Frontend Absensi QR - Setup Dependencies
echo ====================================================
echo.

REM Check if Node.js is installed
echo [*] Checking Node.js installation...
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo [ERROR] Node.js is not installed!
    echo.
    echo [SOLUTION] Please follow these steps:
    echo.
    echo 1. Visit: https://nodejs.org
    echo 2. Download LTS version (recommended)
    echo 3. Run the installer and follow instructions
    echo 4. Restart your terminal after installation
    echo 5. Run this script again
    echo.
    pause
    exit /b 1
)

echo [OK] Node.js version:
node --version
echo.

REM Check npm
echo [*] Checking npm installation...
npm --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] npm is not found!
    pause
    exit /b 1
)

echo [OK] npm version:
npm --version
echo.

REM Navigate to project directory
cd /d "%~dp0"

REM Show current directory
echo [INFO] Current directory: %cd%
echo.

REM Check if node_modules exists
echo [*] Checking dependencies...
if exist "node_modules" (
    echo [QUESTION] node_modules already exists. Reinstall? (y/n)
    set /p answer=Enter choice: 
    if /i "!answer!"=="n" (
        echo [INFO] Skipping npm install
        echo [OK] Setup complete!
        pause
        exit /b 0
    )
    echo [INFO] Removing existing node_modules...
    rmdir /s /q node_modules >nul 2>&1
)

REM Install dependencies
echo.
echo [*] Installing dependencies...
echo [INFO] This may take several minutes...
echo.

call npm install

REM Check if install was successful
if %errorlevel% neq 0 (
    echo.
    echo [ERROR] npm install failed!
    echo.
    echo [TROUBLESHOOTING]
    echo - Check your internet connection
    echo - Try: npm cache clean --force
    echo - Try: npm install --legacy-peer-deps
    echo.
    pause
    exit /b 1
)

echo.
echo ====================================================
echo  Setup Completed Successfully!
echo ====================================================
echo.
echo [INFO] Next steps:
echo.
echo 1. Make sure CodeIgniter 4 is running:
echo    cd c:\laragon\www\absensi-ci
echo    php spark serve
echo.
echo 2. Start the frontend server:
echo    cd c:\laragon\www\frontend-node
echo    npm run dev
echo.
echo 3. Open browser:
echo    http://localhost:3000
echo.
echo [OK] All dependencies installed successfully!
echo.
pause
