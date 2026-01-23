<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;

class RobotsController extends Controller
{
    public function index(){
        $countries = Country::all();
        return view("dashboard.robots.index",compact("countries"));
    }
    public function edit($countryCode)
    {
        $robotsPath = public_path("robots/{$countryCode}.txt");

        // Check if the robots.txt file exists
        if (file_exists($robotsPath)) {
            // Return the content of the robots.txt file
            $content = file_get_contents($robotsPath);
            return response()->json([
                'success' => true,
                'content' => $content
            ]);
        }

        // If the file does not exist, create an empty file
        file_put_contents($robotsPath, '');

        // Return a response with the empty content
        return response()->json([
            'success' => true,
            'content' => ''  // Empty content as the file was just created
        ]);
    }


    // Update the robots.txt content for the country
    public function update(Request $request, $countryCode)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $robotsPath = public_path("robots/{$countryCode}.txt");

        // Save the new content to the robots.txt file
        file_put_contents($robotsPath, $request->input('content'));

        return response()->json([
            'success' => true,
            'message' => 'Robots.txt file updated successfully.'
        ]);
    }
}
