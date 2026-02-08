{{--
BreadcrumbList JSON-LD Structured Data
Usage: @include('includes.breadcrumb-schema', ['breadcrumbs' => [['name' => 'Home', 'url' => '/'], ['name' => 'Phones',
'url' => '/mobile-phones']]])
--}}
@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                @foreach($breadcrumbs as $index => $crumb)
                    {
                        "@type": "ListItem",
                        "position": {{ $index + 1 }},
                        "name": "{{ $crumb['name'] }}",
                        "item": "{{ $crumb['url'] }}"
                    }@if(!$loop->last),@endif
                @endforeach
            ]
        }
        </script>
@endif