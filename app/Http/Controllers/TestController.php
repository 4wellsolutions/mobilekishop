<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function index(Request $request)
    {
        // Mobile model provided by the user or default
        $mobileModel = $request->input('mobile_model', 'Iphone 17 pro max');  // Default model if not provided

        // Fetch mobile specifications from API
        $mobileSpecs = $this->fetchMobileSpecsFromAPI($mobileModel);

        if ($mobileSpecs) {
            return response()->json($mobileSpecs, 200);  // Return JSON response with specs
        } else {
            return response()->json(['error' => 'Mobile model not found or API request failed'], 404);
        }
    }

    // Function to fetch mobile specs from an external API (e.g., Grok Mini3 or OpenAI search)
    protected function fetchMobileSpecsFromAPI($mobileModel)
    {
        $apiEndpoint = "https://api.openai.com/v1/chat/completions";  // Assuming you want to use GPT-5
        $apiKey = env('OPENAI_API_KEY');
        $model = "gpt-5";

        // Use the search operator to fetch real-time data about the mobile model
        $prompt = $this->buildUserPrompt($mobileModel);

        // Sending the API request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])
            ->timeout(120)  // Set timeout for the request
            ->retry(2, 1000)  // Retry logic
            ->post($apiEndpoint, [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

        // If the request is successful
        if ($response->successful()) {
            $apiResponse = $response->json();

            // Assuming reasoning content is in the 'choices' array as shown in your example
            $reasoningContent = $apiResponse['choices'][0]['message']['content'] ?? 'No reasoning content available';

            // Debug and dump the reasoning content (For logging or debugging)
            dd($reasoningContent);

            // Parse the JSON response here if required
            return json_decode($reasoningContent, true);

        } else {
            // Log if API request failed
            Log::error("Error fetching mobile specs from API: " . $response->status() . ' - ' . $response->body());
            return null;
        }
    }

    // Build the user prompt based on the mobile model
    protected function buildUserPrompt($mobileModel)
    {
        return "Search the latest specifications for the mobile model '$mobileModel' from real-time sources such as GSM Arena or other verified databases and provide it in a detailed, flat JSON array format.";
    }
}
