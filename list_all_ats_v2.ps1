$file = "d:\workspace\mks\resources\views\frontend\comparison.blade.php"
$lines = Get-Content $file
$results = @()
for ($i = 0; $i -lt $lines.Count; $i++) {
    $line = $lines[$i]
    if ($line -match "@[a-zA-Z]+") {
        $matches = [regex]::Matches($line, "@[a-zA-Z]+")
        foreach ($m in $matches) {
            $name = $m.Value
            $results += "$($i + 1): $name -> $( $line.Trim() )"
        }
    }
}
$results | Set-Content "d:\workspace\mks\all_ats_report.txt" -Encoding UTF8
