# ===============================================
# Frontend Node.js Setup Script
# Automated Node.js Installation & npm install
# ===============================================

Write-Host "`n" -ForegroundColor Green
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  Frontend Node.js - Automated Setup" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "`n"

# Colors
$Success = "Green"
$Error = "Red"
$Info = "Yellow"
$Header = "Cyan"

# ===============================================
# Step 1: Check if Node.js is installed
# ===============================================
Write-Host "[1/5] Checking Node.js installation..." -ForegroundColor $Header

$nodeVersion = node --version 2>$null
$npmVersion = npm --version 2>$null

if ($nodeVersion -and $npmVersion) {
    Write-Host "✅ Node.js already installed" -ForegroundColor $Success
    Write-Host "   Node.js version: $nodeVersion" -ForegroundColor Gray
    Write-Host "   npm version: $npmVersion" -ForegroundColor Gray
} else {
    Write-Host "❌ Node.js not found" -ForegroundColor $Error
    Write-Host "`n[!] Installing Node.js..." -ForegroundColor $Info
    
    $nodeUrl = "https://nodejs.org/dist/v18.19.0/node-v18.19.0-x64.msi"
    $installerPath = "$env:TEMP\nodejs-installer.msi"
    
    # Download Node.js
    Write-Host "   Downloading Node.js LTS..." -ForegroundColor Gray
    try {
        [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
        Invoke-WebRequest -Uri $nodeUrl -OutFile $installerPath -UseBasicParsing
        Write-Host "   ✓ Downloaded successfully" -ForegroundColor $Success
    } catch {
        Write-Host "   ⚠ Download failed - Please install manually from https://nodejs.org" -ForegroundColor $Error
        Write-Host "   Then run this script again" -ForegroundColor $Info
        exit 1
    }
    
    # Install Node.js
    Write-Host "   Installing Node.js..." -ForegroundColor Gray
    try {
        Start-Process msiexec.exe -ArgumentList "/i `"$installerPath`" /quiet /qn /norestart" -Wait
        Remove-Item $installerPath -Force
        
        # Refresh PATH
        $env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path","User")
        
        # Verify installation
        Start-Sleep -Seconds 2
        $nodeVersion = node --version 2>$null
        $npmVersion = npm --version 2>$null
        
        if ($nodeVersion -and $npmVersion) {
            Write-Host "   ✓ Node.js installed successfully" -ForegroundColor $Success
            Write-Host "   Node.js version: $nodeVersion" -ForegroundColor Gray
            Write-Host "   npm version: $npmVersion" -ForegroundColor Gray
        } else {
            Write-Host "   ⚠ Installation verification failed" -ForegroundColor $Error
            Write-Host "   Please restart PowerShell and try again" -ForegroundColor $Info
            exit 1
        }
    } catch {
        Write-Host "   ❌ Installation failed: $_" -ForegroundColor $Error
        exit 1
    }
}

# ===============================================
# Step 2: Navigate to frontend-node folder
# ===============================================
Write-Host "`n[2/5] Navigating to frontend folder..." -ForegroundColor $Header

$frontendPath = "c:\laragon\www\frontend-node"
if (Test-Path $frontendPath) {
    Set-Location $frontendPath
    Write-Host "✅ Current directory: $(Get-Location)" -ForegroundColor $Success
} else {
    Write-Host "❌ Frontend folder not found: $frontendPath" -ForegroundColor $Error
    exit 1
}

# ===============================================
# Step 3: Check node_modules
# ===============================================
Write-Host "`n[3/5] Checking dependencies..." -ForegroundColor $Header

if (Test-Path "node_modules") {
    Write-Host "ℹ node_modules already exists" -ForegroundColor $Info
    $reinstall = Read-Host "Reinstall dependencies? (y/n)"
    if ($reinstall -ne "y") {
        Write-Host "⊘ Skipping npm install" -ForegroundColor Gray
    } else {
        Write-Host "Removing old node_modules..." -ForegroundColor Gray
        Remove-Item -Path "node_modules" -Recurse -Force
        Write-Host "Installing dependencies..." -ForegroundColor Gray
        npm install
    }
} else {
    Write-Host "Installing dependencies..." -ForegroundColor Gray
    npm install
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Dependencies installed successfully" -ForegroundColor $Success
    } else {
        Write-Host "❌ npm install failed" -ForegroundColor $Error
        Write-Host "Troubleshooting:" -ForegroundColor $Info
        Write-Host "  1. Check internet connection" -ForegroundColor Gray
        Write-Host "  2. Try: npm cache clean --force" -ForegroundColor Gray
        Write-Host "  3. Try: npm install --legacy-peer-deps" -ForegroundColor Gray
        exit 1
    }
}

# ===============================================
# Step 4: Verify package.json
# ===============================================
Write-Host "`n[4/5] Verifying configuration..." -ForegroundColor $Header

if (Test-Path "package.json") {
    Write-Host "✅ package.json found" -ForegroundColor $Success
} else {
    Write-Host "❌ package.json not found" -ForegroundColor $Error
    exit 1
}

if (Test-Path ".env") {
    Write-Host "✅ .env configuration found" -ForegroundColor $Success
} else {
    Write-Host "⚠ .env not found (will use defaults)" -ForegroundColor $Info
}

# ===============================================
# Step 5: Summary and next steps
# ===============================================
Write-Host "`n[5/5] Setup complete!" -ForegroundColor $Header

Write-Host "`n" -ForegroundColor Green
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "  ✅ SETUP SUCCESSFUL" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan

Write-Host "`n📋 Next steps:" -ForegroundColor $Header
Write-Host "  1. Start backend (CodeIgniter 4):" -ForegroundColor White
Write-Host "     cd c:\laragon\www\absensi-ci" -ForegroundColor Gray
Write-Host "     php spark serve" -ForegroundColor Gray

Write-Host "`n  2. Start frontend (in another terminal):" -ForegroundColor White
Write-Host "     cd c:\laragon\www\frontend-node" -ForegroundColor Gray
Write-Host "     npm run dev" -ForegroundColor Gray

Write-Host "`n  3. Open browser:" -ForegroundColor White
Write-Host "     http://localhost:3000" -ForegroundColor Gray

Write-Host "`n📊 Environment Info:" -ForegroundColor $Header
Write-Host "  Node.js: $(node --version)" -ForegroundColor Gray
Write-Host "  npm: $(npm --version)" -ForegroundColor Gray
Write-Host "  Path: $(Get-Location)" -ForegroundColor Gray

Write-Host "`n✨ Ready to launch!" -ForegroundColor Green
Write-Host "`n"

# ===============================================
# Optional: Ask to start the server now
# ===============================================
$startNow = Read-Host "Start frontend server now? (y/n)"
if ($startNow -eq "y") {
    Write-Host "`nStarting server..." -ForegroundColor $Info
    npm run dev
} else {
    Write-Host "`nSetup complete! Run 'npm run dev' to start the server." -ForegroundColor $Success
}
