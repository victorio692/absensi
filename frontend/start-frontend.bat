@echo off
REM =========================================================
REM Start Frontend Server
REM =========================================================

cls
echo.
echo =========================================================
echo   Frontend Node.js Server Launcher
echo =========================================================
echo.

REM Check Node.js
echo [*] Checking Node.js...
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Node.js not found!
    echo Please run setup-nodejs.bat first
    pause
    exit /b 1
)

REM Check npm
echo [*] Checking npm...
npm --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] npm not found!
    pause
    exit /b 1
)

REM Navigate to folder
echo [*] Navigating to frontend folder...
cd /d "c:\laragon\www\frontend-node"

REM Check if node_modules exists
if not exist "node_modules" (
    echo [WARNING] node_modules not found
    echo Installing dependencies first...
    call npm install
    if %errorlevel% neq 0 (
        echo [ERROR] Installation failed
        pause
        exit /b 1
    )
)

REM Start server
echo.
echo =========================================================
echo   Starting Frontend Server...
echo =========================================================
echo.
echo [INFO] Backend URL: http://localhost:8080
echo [INFO] Frontend URL: http://localhost:3000
echo.
echo Press Ctrl+C to stop the server
echo.

npm run dev
