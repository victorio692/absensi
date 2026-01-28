@echo off
REM =========================================================
REM Frontend Node.js Setup Script
REM Automated Node.js Installation & npm install
REM =========================================================

setlocal enabledelayedexpansion

cls
echo.
echo =========================================================
echo   Frontend Node.js - Automated Setup
echo =========================================================
echo.

REM =========================================================
REM Step 1: Check if Node.js is installed
REM =========================================================
echo [1/5] Checking Node.js installation...

node --version >nul 2>&1
if %errorlevel% equ 0 (
    for /f "tokens=*" %%i in ('node --version') do set NodeVersion=%%i
    for /f "tokens=*" %%i in ('npm --version') do set NpmVersion=%%i
    
    echo.
    echo [OK] Node.js is already installed
    echo      Node.js version: !NodeVersion!
    echo      npm version: !NpmVersion!
) else (
    echo.
    echo [ERROR] Node.js not found
    echo.
    echo [INFO] Please install Node.js manually:
    echo      1. Visit: https://nodejs.org
    echo      2. Download LTS version
    echo      3. Run installer and select "Add to PATH"
    echo      4. Restart this script
    echo.
    pause
    exit /b 1
)

REM =========================================================
REM Step 2: Navigate to frontend-node folder
REM =========================================================
echo.
echo [2/5] Navigating to frontend folder...

cd /d "c:\laragon\www\frontend-node"
if %errorlevel% neq 0 (
    echo [ERROR] Frontend folder not found
    pause
    exit /b 1
)

echo [OK] Current directory: %cd%

REM =========================================================
REM Step 3: Check node_modules
REM =========================================================
echo.
echo [3/5] Checking dependencies...

if exist "node_modules" (
    echo [INFO] node_modules already exists
    echo.
    set /p reinstall="Reinstall dependencies? (y/n): "
    if /i "!reinstall!"=="y" (
        echo Removing old node_modules...
        rmdir /s /q node_modules
        echo Installing dependencies...
        call npm install
    ) else (
        echo [SKIP] npm install skipped
    )
) else (
    echo Installing dependencies...
    echo This may take a few minutes...
    echo.
    call npm install
    
    if %errorlevel% neq 0 (
        echo.
        echo [ERROR] npm install failed
        echo.
        echo Troubleshooting:
        echo  1. Check internet connection
        echo  2. Try: npm cache clean --force
        echo  3. Try: npm install --legacy-peer-deps
        echo.
        pause
        exit /b 1
    )
    echo [OK] Dependencies installed successfully
)

REM =========================================================
REM Step 4: Verify configuration
REM =========================================================
echo.
echo [4/5] Verifying configuration...

if exist "package.json" (
    echo [OK] package.json found
) else (
    echo [ERROR] package.json not found
    pause
    exit /b 1
)

if exist ".env" (
    echo [OK] .env configuration found
) else (
    echo [WARN] .env not found (using defaults)
)

REM =========================================================
REM Step 5: Summary and next steps
REM =========================================================
echo.
echo [5/5] Setup complete!

echo.
echo =========================================================
echo   [OK] SETUP SUCCESSFUL
echo =========================================================

echo.
echo [INFO] Next steps:

echo.
echo 1. Start backend (CodeIgniter 4) in Terminal 1:
echo    cd c:\laragon\www\absensi-ci
echo    php spark serve

echo.
echo 2. Start frontend (in another Terminal) Terminal 2:
echo    cd c:\laragon\www\frontend-node
echo    npm run dev

echo.
echo 3. Open browser:
echo    http://localhost:3000

echo.
echo [INFO] Environment Info:
node --version
npm --version
echo Current Path: %cd%

echo.
echo [OK] Ready to launch!
echo.

REM =========================================================
REM Optional: Ask to start server
REM =========================================================
set /p startNow="Start frontend server now? (y/n): "
if /i "!startNow!"=="y" (
    echo.
    echo Starting server...
    call npm run dev
) else (
    echo.
    echo Setup complete! Run 'npm run dev' to start the server.
    echo.
    pause
)
