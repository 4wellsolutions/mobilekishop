<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PriceController extends Controller
{
    public function extractPrices(Request $request, $slug)
    {
        // Define your base URLs for all countries
        $urls = [
            'https://mobileinto.com/ar/',
            'https://au.mobileinto.com/',
            'https://mobileinto.com/at/',
            'https://mobileinto.com/bh/',
            'https://mobileinto.com/bd/',
            'https://mobileinto.com/be/',
            'https://mobileinto.com/br/',
            'https://ca.mobileinto.com/',
            'https://mobileinto.com/hr/',
            'https://mobileinto.com/cy/',
            'https://mobileinto.com/dk/',
            'https://mobileinto.com/ee/',
            'https://mobileinto.com/fi/',
            'https://fr.mobileinto.com/',
            'https://de.mobileinto.com/',
            'https://mobileinto.com/gh/',
            'https://mobileinto.com/gr/',
            'https://mobileinto.com/hk/',
            'https://mobileinto.com/ie/',
            'https://mobileinto.com/jp/',
            'https://mobileinto.com/jo/',
            'https://mobileinto.com/ke/',
            'https://mobileinto.com/kw/',
            'https://mobileinto.com/ly/',
            'https://my.mobileinto.com/',
            'https://mobileinto.com/mx/',
            'https://mobileinto.com/md/',
            'https://mobileinto.com/ma/',
            'https://mobileinto.com/np/',
            'https://mobileinto.com/nl/',
            'https://mobileinto.com/nz/',
            'https://mobileinto.com/ng/',
            'https://mobileinto.com/no/',
            'https://mobileinto.com/om/',
            'https://mobileinto.com/ph/',
            'https://mobileinto.com/pl/',
            'https://mobileinto.com/pt/',
            'https://qa.mobileinto.com/',
            'https://mobileinto.com/ro/',
            'https://sa.mobileinto.com/',
            'https://mobileinto.com/rs/',
            'https://sg.mobileinto.com/',
            'https://mobileinto.com/za/',
            'https://mobileinto.com/kr/',
            'https://es.mobileinto.com/',
            'https://mobileinto.com/lk/',
            'https://mobileinto.com/se/',
            'https://mobileinto.com/ch/',
            'https://mobileinto.com/tw/',
            'https://mobileinto.com/tz/',
            'https://mobileinto.com/tr/',
            'https://th.mobileinto.com/',
            'https://mobileinto.com/tn/',
            'https://ae.mobileinto.com/',
            'https://gb.mobileinto.com/',
            'https://us.mobileinto.com/'
        ];

        $results = [];
        $i = 0;

        // Iterate through each URL, append the slug, and extract the first price
        foreach ($urls as $url) {
            // Append the slug to the URL
            $finalUrl = $url . $slug . "/";

            // Make an HTTP request using Laravel's HTTP Client
            $response = Http::get($finalUrl);

            // Check if the request was successful
            if ($response->successful()) {
                // Extract the HTML content
                $html = $response->body();

                // Load the HTML content into DOMDocument
                $doc = new \DOMDocument();
                libxml_use_internal_errors(true); // Disable warnings about invalid HTML
                $doc->loadHTML($html);
                libxml_clear_errors();

                // Query for the first <td class="tbl_pr"> element
                $xpath = new \DOMXPath($doc);
                $nodes = $xpath->query('//td[@class="tbl_pr"]');

                // Extract the first numeric price from the nodes (if available)
                $tblPrText = [];
                if ($nodes->length > 0) {
                    $firstNode = $nodes->item(0); // Get the first node
                    $numericValue = preg_replace('/[^0-9]/', '', trim($firstNode->nodeValue)); // Clean the value
                    $tblPrText[] = $numericValue; // Store the cleaned numeric value
                } else {
                    $tblPrText[] = 'No price found'; // If no price found
                }

                // Store the result for this URL
                $results[] = [
                    'url' => $finalUrl,
                    'tbl_pr_text' => $tblPrText,
                    'error' => null // No error, successful request
                ];
            } else {
                // If the request failed, store the error
                $results[] = [
                    'url' => $finalUrl,
                    'tbl_pr_text' => [],
                    'error' => 'Failed to fetch the URL'
                ];
            }

            // Limit the number of requests (break after 10 URLs)
            if ($i == 10) {
                break;
            }
            $i++;
        }

        // Start generating the HTML table
        $tableHtml = '<table class="table table-bordered">';
        $tableHtml .= '<thead><tr><th>URL</th><th>Price</th><th>Error Message</th></tr></thead>';
        $tableHtml .= '<tbody>';

        foreach ($results as $result) {
            $tableHtml .= '<tr>';
            $tableHtml .= '<td>' . e($result['url']) . '</td>';
            $tableHtml .= '<td>' . implode(', ', $result['tbl_pr_text']) . '</td>';
            $tableHtml .= '<td>' . e($result['error'] ?? '') . '</td>';
            $tableHtml .= '</tr>';
        }

        $tableHtml .= '</tbody></table>';

        // Return the HTML table directly to the response
        return response($tableHtml)->header('Content-Type', 'text/html');
    }


}
