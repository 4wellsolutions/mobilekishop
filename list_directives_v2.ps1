$file = "d:\workspace\mks\resources\views\frontend\comparison.blade.php"
$lines = Get-Content $file
$known = @("if", "elseif", "else", "endif", "foreach", "endforeach", "for", "endfor", "while", "endwhile", "section", "endsection", "stop", "show", "append", "overwrite", "include", "php", "endphp", "extends", "yield", "stack", "push", "prepend", "auth", "endauth", "guest", "endguest", "isset", "endisset", "empty", "endempty")

$results = @()
for ($i = 0; $i -lt $lines.Count; $i++) {
    $line = $lines[$i]
    if ($line -match "@([a-zA-Z]+)") {
        $matches = [regex]::Matches($line, "@([a-zA-Z]+)")
        foreach ($m in $matches) {
            $name = $m.Groups[1].Value
            if ($known -contains $name) {
                $results += [PSCustomObject]@{
                    Line      = $i + 1
                    Directive = "@" + $name
                    Content   = $line.Trim()
                }
            }
        }
    }
}

$results | Format-List
