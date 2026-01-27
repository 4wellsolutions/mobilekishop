$file = "d:\workspace\mks\resources\views\frontend\comparison.blade.php"
$lines = Get-Content $file
$i = 0
foreach ($line in $lines) {
    $i++
    $trimmed = $line.Trim()
    if ($trimmed.StartsWith("@")) {
        Write-Output "$i: $trimmed"
    }
}
