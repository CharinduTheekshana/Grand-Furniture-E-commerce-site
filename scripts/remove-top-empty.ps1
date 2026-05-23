Set-Location 'C:\Sillicon Radon Projects\PROJECTS\furniture2'

foreach($d in @('css','js','img')){
    $p = Join-Path (Get-Location) ('public\\' + $d)
    if(Test-Path $p){
        $count = (Get-ChildItem -Path $p -Force -Recurse | Measure-Object).Count
        if($count -eq 0){
            Remove-Item -LiteralPath $p -Force -Recurse
            Write-Output "Removed empty: public\\$d"
        } else {
            Write-Output "Not empty: public\\$d - $count items"
        }
    } else {
        Write-Output "Not found: public\\$d"
    }
}
