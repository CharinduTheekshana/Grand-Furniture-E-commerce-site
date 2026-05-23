Set-Location 'C:\Sillicon Radon Projects\PROJECTS\furniture2'

$exclusions = @('assets','build','hot','storage')

Get-ChildItem -Path public -Directory -Recurse | ForEach-Object {
    if($exclusions -contains $_.Name) { return }
    if((Get-ChildItem -Path $_.FullName -Force -Recurse | Measure-Object).Count -eq 0) {
        Remove-Item -LiteralPath $_.FullName -Force -Recurse
        Write-Output "Removed empty dir: $($_.FullName)"
    }
}
