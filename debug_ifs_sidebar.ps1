$file = "d:\workspace\mks\resources\views\includes\sidebar_mobile-phones.blade.php"
$lines = Get-Content $file
for ($i = 0; $i -lt $lines.Count; $i++) {
    if ($lines[$i] -match "@if") {
        Write-Output "$($i + 1): $($lines[$i].Trim())"
    }
    if ($lines[$i] -match "@endif") {
        Write-Output "$($i + 1): $($lines[$i].Trim())"
    }
}
