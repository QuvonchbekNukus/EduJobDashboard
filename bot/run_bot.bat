@echo off
REM Installation guide for Python and Bot
REM This script will help you set up Python and run the bot

echo ==========================================================
echo EduJobDashboard Bot - Installation Helper
echo ==========================================================
echo.

REM Check if Python is installed
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Python is NOT installed or not in PATH
    echo.
    echo SOLUTION: Install Python from https://www.python.org/downloads/
    echo           Make sure to check "Add Python to PATH" during installation
    echo.
    echo After installation:
    echo 1. Close this window
    echo 2. Open new Command Prompt or PowerShell
    echo 3. Run: python --version
    echo 4. Then run: cd bot ^&^& python app.py
    pause
    exit /b 1
) else (
    echo ✓ Python is installed
    python --version
    echo.
)

REM Check if aiogram is installed
python -c "import aiogram" >nul 2>&1
if %errorlevel% neq 0 (
    echo Installing Python packages...
    python -m pip install aiogram httpx python-dotenv aiohttp
) else (
    echo ✓ Python packages are installed
    echo.
)

REM Start the bot
echo Starting bot...
cd %~dp0
python app.py

pause
