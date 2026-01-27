function Check-BladeBalance($filePath) {
    if (-not (Test-Path $filePath)) { return }
    $content = Get-Content $filePath -Raw
    $ifs = [regex]::Matches($content, "@if\b").Count
    $elseifs = [regex]::Matches($content, "@elseif\b").Count
    $elses = [regex]::Matches($content, "@else\b").Count
    $endifs = [regex]::Matches($content, "@endif\b").Count
    
    $foreachs = [regex]::Matches($content, "@foreach\b").Count
    $endforeachs = [regex]::Matches($content, "@endforeach\b").Count

    $sections = [regex]::Matches($content, "@section\b").Count
    $stops = [regex]::Matches($content, "@stop\b").Count
    $endsections = [regex]::Matches($content, "@endsection\b").Count
    
    $report = "File: $filePath`r`n"
    $report += "  @if: $ifs, @elseif: $elseifs, @else: $elses, @endif: $endifs`r`n"
    if ($ifs + $elseifs -lt $endifs) { $report += "  WARNING: Possible extra @endif`r`n" }
    elseif ($ifs -gt $endifs) { $report += "  WARNING: Possible missing @endif`r`n" }

    $report += "  @foreach: $foreachs, @endforeach: $endforeachs`r`n"
    if ($foreachs -ne $endforeachs) { $report += "  WARNING: @foreach mismatch`r`n" }

    $report += "  @section: $sections, @stop: $stops, @endsection: $endsections`r`n"
    $totalSectionClosers = $stops + $endsections
    $report += "  Total Section Closers: $totalSectionClosers`r`n"
    $report += "----------------------------------`r`n"
    return $report
}

$files = @(
    "d:\workspace\mks\resources\views\frontend\comparison.blade.php",
    "d:\workspace\mks\resources\views\layouts\frontend.blade.php",
    "d:\workspace\mks\resources\views\layouts\frontend_country.blade.php",
    "d:\workspace\mks\resources\views\includes\sidebar_mobile-phones.blade.php",
    "d:\workspace\mks\resources\views\includes\compare-details.blade.php",
    "d:\workspace\mks\resources\views\includes\footer.blade.php",
    "d:\workspace\mks\resources\views\layouts\footer.blade.php"
)

$finalReport = ""
foreach ($f in $files) {
    $finalReport += Check-BladeBalance $f
}

$finalReport | Set-Content "d:\workspace\mks\balance_report.txt" -Encoding UTF8
