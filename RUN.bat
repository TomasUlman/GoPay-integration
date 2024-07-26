@echo off
start "" "C:\xampp\php\php.exe" -S localhost:8000

rem 
timeout /t 2 /nobreak >nul

rem Otevři projekt v Google Chrome
start chrome http://localhost:8000
