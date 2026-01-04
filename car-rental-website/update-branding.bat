@echo off
echo ============================================
echo    UPDATING ALL FILES TO SoCar BRANDING
echo ============================================
echo.

REM Change to the car-rental directory
cd /d "C:\xampp\htdocs\car-rental"

echo 1. Updating HTML files...
echo -------------------------

REM Update all HTML files
for %%f in (*.html) do (
    echo Processing: %%f
    powershell -Command "(Get-Content '%%f') -replace 'SpeedRent', 'SoCar' | Set-Content '%%f'"
    powershell -Command "(Get-Content '%%f') -replace 'Speed<span', 'So<span' | Set-Content '%%f'"
    powershell -Command "(Get-Content '%%f') -replace 'speedrent.com', 'socar.com' | Set-Content '%%f'"
    powershell -Command "(Get-Content '%%f') -replace 'Speed Rent', 'SoCar' | Set-Content '%%f'"
    powershell -Command "(Get-Content '%%f') -replace 'speed rent', 'SoCar' | Set-Content '%%f'"
)

echo.
echo 2. Updating CSS files...
echo ------------------------

REM Update CSS files
for %%f in (css\*.css) do (
    echo Processing: %%f
    powershell -Command "(Get-Content '%%f') -replace 'SpeedRent', 'SoCar' | Set-Content '%%f'"
)

echo.
echo 3. Updating JavaScript files...
echo -------------------------------

REM Update JS files
for %%f in (js\*.js) do (
    echo Processing: %%f
    powershell -Command "(Get-Content '%%f') -replace 'SpeedRent', 'SoCar' | Set-Content '%%f'"
    powershell -Command "(Get-Content '%%f') -replace 'speedrent', 'socar' | Set-Content '%%f'"
)

echo.
echo 4. Creating backup of original files...
echo ---------------------------------------
xcopy *.html backup\ /Y >nul 2>&1
mkdir backup 2>nul
copy *.html backup\ >nul 2>&1
echo Backup saved to: backup\

echo.
echo ============================================
echo    UPDATE COMPLETE!
echo ============================================
echo.
echo All files have been updated to SoCar branding.
echo.
echo Please refresh your browser with Ctrl+F5
echo to see the changes.
echo.
pause