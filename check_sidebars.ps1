function Check-BladeBalance($filePath) {
    if (-not (Test-Path $filePath)) { return }
    $content = Get-Content $filePath -Raw
    $ifs = [regex]::Matches($content, "@if\b").Count
    $elseifs = [regex]::Matches($content, "@elseif\b").Count
    $elses = [regex]::Matches($content, "@else\b").Count
    $endifs = [regex]::Matches($content, "@endif\b").Count
    
    $foreachs = [regex]::Matches($content, "@foreach\b").Count
    $endforeachs = [regex]::Matches($content, "@endforeach\b").Count

    if ($ifs + $elseifs -ne $endifs -or $foreachs -ne $endforeachs) {
        Write-Output "BROKEN: $filePath"
        Write-Output "  @if: $ifs, @endif: $endifs"
        Write-Output "  @foreach: $foreachs, @endforeach: $endforeachs"
    }
}

$files = Get-ChildItem -Path "d:\workspace\mks\resources\views\includes" -Filter "sidebar_*.blade.php"
foreach ($f in $files) {
    Check-BladeBalance $f.FullName
}
