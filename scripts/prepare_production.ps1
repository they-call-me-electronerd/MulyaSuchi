# MulyaSuchi - Pre-Deployment Cleanup Script (Windows PowerShell)
# This script removes development files and prepares for production

Write-Host "🧹 Starting cleanup for production deployment..." -ForegroundColor Cyan

# Remove development documentation files
Write-Host "Removing development documentation..." -ForegroundColor Yellow
$devDocs = @(
    "COLOR_CONVERSION_SUMMARY.md",
    "VISUAL_COLOR_GUIDE.md",
    "REBRANDING_CHECKLIST.md",
    "QUICK_REFERENCE.md",
    "QUICK_START_ADS.md",
    "DEPLOYMENT_SUMMARY.md",
    "convert_to_green_theme.ps1"
)

foreach ($file in $devDocs) {
    if (Test-Path $file) {
        Remove-Item $file -Force
        Write-Host "  Removed: $file" -ForegroundColor DarkGray
    }
}

# Remove development documentation from docs/
Write-Host "Removing development docs from docs/..." -ForegroundColor Yellow
$devDocsDocs = @(
    "docs/AD_CAROUSEL_VISUAL_GUIDE.md",
    "docs/DASHBOARD_VISUAL_GUIDE.md"
)

foreach ($file in $devDocsDocs) {
    if (Test-Path $file) {
        Remove-Item $file -Force
        Write-Host "  Removed: $file" -ForegroundColor DarkGray
    }
}

# Clean up test files
Write-Host "Cleaning test files..." -ForegroundColor Yellow
if (Test-Path "admin/test_upload.php") {
    Remove-Item "admin/test_upload.php" -Force
    Write-Host "  Removed: admin/test_upload.php" -ForegroundColor DarkGray
}

# Clean up temporary database files
Write-Host "Cleaning SQL backup files..." -ForegroundColor Yellow
$sqlBackups = @(
    "sql/items_fixed.sql",
    "sql/fresh_500_products_part2.sql",
    "sql/fresh_500_products_part3.sql",
    "sql/seed_500_products_master.sql",
    "sql/fix_nepali_text.php"
)

foreach ($file in $sqlBackups) {
    if (Test-Path $file) {
        Remove-Item $file -Force
        Write-Host "  Removed: $file" -ForegroundColor DarkGray
    }
}

# Clean up logs (keep directory)
Write-Host "Cleaning logs..." -ForegroundColor Yellow
if (Test-Path "logs/rate_limits.json") {
    "{}" | Out-File "logs/rate_limits.json" -Encoding utf8 -NoNewline
    Write-Host "  Cleaned: logs/rate_limits.json" -ForegroundColor DarkGray
}

# Clean uploads (keep structure)
Write-Host "Cleaning uploads directory..." -ForegroundColor Yellow
if (Test-Path "assets/uploads/") {
    Get-ChildItem "assets/uploads/" -Recurse -File | 
        Where-Object { $_.Name -ne ".gitkeep" } | 
        Remove-Item -Force
    Write-Host "  Cleaned uploads directory" -ForegroundColor DarkGray
}

# Remove any backup files
Write-Host "Removing backup files..." -ForegroundColor Yellow
Get-ChildItem -Recurse -File -Include "*.backup", "*.bak", "*.old" | Remove-Item -Force
Write-Host "  Removed backup files" -ForegroundColor DarkGray

# Remove development editor files
Write-Host "Removing editor files..." -ForegroundColor Yellow
if (Test-Path ".vscode/") {
    Remove-Item ".vscode/" -Recurse -Force
    Write-Host "  Removed: .vscode/" -ForegroundColor DarkGray
}
if (Test-Path ".idea/") {
    Remove-Item ".idea/" -Recurse -Force
    Write-Host "  Removed: .idea/" -ForegroundColor DarkGray
}
Get-ChildItem -Recurse -File -Include "*.swp", "*.swo", ".DS_Store", "Thumbs.db" | Remove-Item -Force

# Clean node_modules if exists
if (Test-Path "node_modules/") {
    Write-Host "Removing node_modules..." -ForegroundColor Yellow
    Remove-Item "node_modules/" -Recurse -Force
    Write-Host "  Removed: node_modules/" -ForegroundColor DarkGray
}

# Create production .env reminder
if (-not (Test-Path ".env")) {
    Write-Host ""
    Write-Host "⚠️  WARNING: .env file not found! Copy from .env.example" -ForegroundColor Red
}

Write-Host ""
Write-Host "✅ Cleanup complete!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Next steps:" -ForegroundColor Cyan
Write-Host "1. Review and update .env file for production" -ForegroundColor White
Write-Host "2. Update config/database.php with production credentials" -ForegroundColor White
Write-Host "3. Ensure assets/uploads/ is writable" -ForegroundColor White
Write-Host "4. Ensure logs/ is writable" -ForegroundColor White
Write-Host "5. Test the application thoroughly" -ForegroundColor White
Write-Host "6. Run database migrations if needed" -ForegroundColor White
Write-Host ""
Write-Host "🚀 Ready for deployment!" -ForegroundColor Green
