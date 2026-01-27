$file = "d:\workspace\mks\resources\views\frontend\comparison.blade.php"
$content = Get-Content $file -Raw

$directives = @(
    "if", "elseif", "else", "endif",
    "foreach", "endforeach",
    "forelse", "empty", "endforelse",
    "for", "endfor",
    "while", "endwhile",
    "section", "endsection", "stop", "show", "append", "overwrite",
    "hasSection",
    "auth", "endauth",
    "guest", "endguest",
    "switch", "case", "break", "default", "endswitch",
    "isset", "endisset",
    "empty", "endempty",
    "can", "cannot", "endcan", "endcannot",
    "php", "endphp"
)

$results = @()
foreach ($d in $directives) {
    $pattern = "@" + $d
    $matches = [regex]::Matches($content, $pattern)
    if ($matches.Count -gt 0) {
        $results += [PSCustomObject]@{
            Directive = "@" + $d
            Count     = $matches.Count
        }
    }
}

$results | Sort-Object Count -Descending | Format-Table -AutoSize
