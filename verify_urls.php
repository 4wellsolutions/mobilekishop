<?php

$inputFile = 'urls.txt'; // Default, can be overridden by argument
if ($argc > 1) {
    $inputFile = $argv[1];
}

if (!file_exists($inputFile)) {
    echo "Error: Input file '$inputFile' not found.\n";
    echo "Usage: php verify_urls.php [path/to/urls.txt]\n";
    exit(1);
}

$urls = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$total = count($urls);
echo "Found $total URLs in $inputFile.\n";

$workingFile = 'working_urls.txt';
$failedFile = 'failed_urls.txt';
$errorFile = 'error_urls.txt';

file_put_contents($workingFile, "");
file_put_contents($failedFile, "");
file_put_contents($errorFile, "");

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true); // We only need headers
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Optional: checking dev environment might need this

$count = 0;
foreach ($urls as $url) {
    $count++;
    $url = trim($url);
    if (empty($url))
        continue;

    // Replace domain
    $testUrl = str_replace('mobilekishop.net', 'apps.mobilekishop.net', $url);

    // Ensure scheme exists
    if (!preg_match("~^(?:f|ht)tps?://~i", $testUrl)) {
        $testUrl = "https://" . $testUrl;
    }

    curl_setopt($ch, CURLOPT_URL, $testUrl);
    $start = microtime(true);
    $response = curl_exec($ch);
    $duration = round(microtime(true) - $start, 2);

    if ($response === false) {
        $error = curl_error($ch);
        echo "[$count/$total] ERROR: $testUrl ($error)\n";
        file_put_contents($errorFile, "$url | $testUrl | Error: $error\n", FILE_APPEND);
        continue;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode >= 200 && $httpCode < 300) {
        echo "[$count/$total] OK ($httpCode): $testUrl ({$duration}s)\n";
        file_put_contents($workingFile, "$url\n", FILE_APPEND);
    } else {
        echo "[$count/$total] FAIL ($httpCode): $testUrl ({$duration}s)\n";
        file_put_contents($failedFile, "$url | $testUrl | Status: $httpCode\n", FILE_APPEND);
    }
}

curl_close($ch);

echo "\nVerification complete.\n";
echo "Working URLs: $workingFile\n";
echo "Failed URLs: $failedFile\n";
