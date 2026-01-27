$file = "d:\workspace\mks\resources\views\frontend\comparison.blade.php"
$lines = Get-Content $file
$directives = @()

for ($i = 0; $i -lt $lines.Count; $i++) {
    if ($lines[$i] -match "@[a-zA-Z]+") {
        $matches = [regex]::Matches($lines[$i], "@[a-zA-Z]+")
        foreach ($m in $matches) {
            $directives += [PSCustomObject]@{
                Line      = $i + 1
                Directive = $m.Value
                Content   = $lines[$i].Trim()
            }
        }
    }
}

$directives | Format-Table -AutoSize
