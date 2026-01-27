$file = "d:\workspace\mks\resources\views\layouts\footer.blade.php"
$lines = Get-Content $file
$i = 0
foreach ($line in $lines) {
    $i++
    if ($line.Contains("@if")) {
        Write-Output ($i.ToString() + ": " + $line.Trim())
    }
    if ($line.Contains("@endif")) {
        Write-Output ($i.ToString() + ": " + $line.Trim())
    }
    if ($line.Contains("@foreach")) {
        Write-Output ($i.ToString() + ": " + $line.Trim())
    }
    if ($line.Contains("@endforeach")) {
        Write-Output ($i.ToString() + ": " + $line.Trim())
    }
}
