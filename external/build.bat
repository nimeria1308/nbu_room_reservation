@echo off

for /D %%f in (*) do (
    start /b /w /d "%%f" cmd.exe /c "npm install" && start /b /w /d "%%f" cmd.exe /c "npm run build"
)
