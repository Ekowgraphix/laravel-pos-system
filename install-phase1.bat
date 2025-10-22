@echo off
echo ========================================
echo  Laravel POS - Phase 1 Installation
echo ========================================
echo.

echo [1/5] Installing DOMPDF for invoice generation...
call composer require barryvdh/laravel-dompdf
if %errorlevel% neq 0 goto error

echo.
echo [2/5] Installing Laravel Sanctum (API authentication)...
call php artisan install:api
if %errorlevel% neq 0 goto error

echo.
echo [3/5] Installing Scribe (API documentation)...
call composer require knuckleswtf/scribe --dev
if %errorlevel% neq 0 goto error

echo.
echo [4/5] Publishing configuration files...
call php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
call php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
call php artisan vendor:publish --tag=scribe-config

echo.
echo [5/5] Creating storage directories...
if not exist "storage\invoices" mkdir storage\invoices
call php artisan storage:link

echo.
echo ========================================
echo  Installation Complete! ✓
echo ========================================
echo.
echo Next steps:
echo 1. Add Paystack API keys to .env file
echo 2. Configure company information in .env
echo 3. Review PHASE1_INSTALLATION.md for details
echo 4. Test invoice generation
echo 5. Start development server: php artisan serve
echo.
echo Run 'npm install' and 'npm run dev' for frontend assets
echo.
echo Note: Paystack integration uses HTTP client (no separate package needed)
echo.
pause
goto end

:error
echo.
echo ========================================
echo  Installation Failed! ✗
echo ========================================
echo Please check the error message above
echo.
pause

:end
