Set-Location 'C:\Sillicon Radon Projects\PROJECTS\furniture2'
Get-ChildItem -Path public\js -Force -Recurse | ForEach-Object { Write-Output $_.FullName }
