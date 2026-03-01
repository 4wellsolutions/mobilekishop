<?php
$baseUrl = 'http://localhost:9000';
$pagesToScrape = [
    '/category/mobile-phones',
    '/category/chargers',
    '/category/tablets',
    '/category/power-banks',
];

$linksToCheck = [];

foreach ($pagesToScrape as $pageUrl) {
    $url = $baseUrl . $pageUrl;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$html || $httpCode !== 200) {
        echo "Failed to load $url (Status: $httpCode)\n";
        continue;
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $anchors = $dom->getElementsByTagName('a');
    foreach ($anchors as $a) {
        $href = $a->getAttribute('href');
        // Filter out javascript, mailto, tel, empty hrefs
        if (empty($href) || strpos($href, 'javascript:') === 0 || strpos($href, 'mailto:') === 0 || strpos($href, 'tel:') === 0 || $href === '#') {
            continue;
        }

        if (strpos($href, $baseUrl) === 0 || strpos($href, '/') === 0) {
            $fullUrl = strpos($href, $baseUrl) === 0 ? $href : rtrim($baseUrl, '/') . '/' . ltrim($href, '/');
            if (!in_array($fullUrl, $linksToCheck)) {
                $linksToCheck[] = $fullUrl;
            }
        }
    }
}

echo "Found " . count($linksToCheck) . " unique internal links. Checking status...\n";

$brokenLinks = [];
foreach ($linksToCheck as $i => $url) {
    if ($i % 20 === 0) {
        echo "Processing link " . ($i + 1) . " of " . count($linksToCheck) . "\n";
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Use GET because HEAD sometimes behaves weirdly or is unsupported in some setups
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status !== 200) {
        $brokenLinks[] = ['url' => $url, 'status' => $status];
        echo "Broken ($status): $url\n";
    }
}

echo "\nFinished checking.\n";
if (empty($brokenLinks)) {
    echo "All links are returning 200 OK!\n";
} else {
    echo "Found " . count($brokenLinks) . " broken links.\n";
    foreach ($brokenLinks as $bl) {
        echo "[" . $bl['status'] . "] " . $bl['url'] . "\n";
    }
}
