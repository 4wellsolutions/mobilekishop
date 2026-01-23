@php
    $countryCode = session('country_code', 'pk');
    $urlPrefix = $countryCode === 'pk' ? '' : '/' . $countryCode;
@endphp

<div style="background: yellow; padding: 10px; margin: 10px;">
    <strong>Debug Info:</strong><br>
    Country Code from Session: {{ $countryCode }}<br>
    URL Prefix: {{ $urlPrefix }}<br>
    Current Path: {{ request()->path() }}<br>
    First Segment: {{ explode('/', trim(request()->path(), '/'))[0] ?? 'none' }}
</div>