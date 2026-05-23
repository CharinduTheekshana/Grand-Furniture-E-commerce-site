Set-Location 'C:\Sillicon Radon Projects\PROJECTS\furniture2'

$map = @{
    'public\\css' = 'public\\assets\\css'
    'public\\js' = 'public\\assets\\js'
    'public\\fonts' = 'public\\assets\\fonts'
    'public\\img' = 'public\\assets\\images'
    'public\\images' = 'public\\assets\\images'
}

foreach($src in $map.Keys) {
    $srcPath = Join-Path (Get-Location) $src
    $destPath = Join-Path (Get-Location) $map[$src]
    if(-not (Test-Path $srcPath)) { Write-Output "Source not found: $srcPath"; continue }
    Get-ChildItem -Path $srcPath -File -Recurse | ForEach-Object {
        $rel = $_.FullName.Substring($srcPath.Length).TrimStart('\\')
        $target = Join-Path $destPath $rel
        if(Test-Path $target){
            Remove-Item -LiteralPath $_.FullName -Force
            Write-Output "Removed duplicate: $rel"
        } else {
            Write-Output "Keep (no counterpart): $rel"
        }
    }
    # remove src if empty
    if((Get-ChildItem -Path $srcPath -Recurse -Force | Measure-Object).Count -eq 0){ Remove-Item -LiteralPath $srcPath -Force -Recurse; Write-Output "Removed empty folder: $srcPath" }
}
