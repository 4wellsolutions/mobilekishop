<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Validator;
use Image;
use URL;
use Response;
use Str;

class AppController extends Controller
{
    public function index()
    {
        return view("api.app");
    }
    public function postData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'android' => 'required_without:ios',
            'ios' => 'required_without:android',
        ]);
        $ios = [];
        $android = [];
        $fileName = "";
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if ($request->android && !$request->ios) {
            // android only
            $android = $this->getAndroidAppData($request->android);
            $android = json_decode($android);
            $fileName = Str::slug($android->product_info->title);
            $thumbnail = $android->product_info->thumbnail;
            $screenshots = $this->generateImage($android->media->images, $fileName);
        } elseif ($request->ios && !$request->android) {
            // ios only
            if (!$ios = $this->getIosAppData($request->ios)) {
                return response::make(["success" => false, "message" => "IOS app error"]);
            }
            $ios = json_decode($ios);
            if (isset($ios->error)) {
                return response::make(["success" => false, "message" => $ios->error]);
            }
            $images = null;
            $fileName = Str::slug($ios->title);
            $thumbnail = $ios->logo;
            if (isset($ios->iphone_screenshots) && !empty($ios->iphone_screenshots)) {
                $images = $ios->iphone_screenshots;
            } elseif (isset($ios->ipad_screenshots) && !empty($ios->ipad_screenshots)) {
                $images = $ios->ipad_screenshots;
            } elseif (isset($ios->_screenshots) && !empty($ios->_screenshots)) {
                $images = $ios->_screenshots;
            }

            $screenshots = $this->generateImage($request->input('images')[0] != null ? $request->images : $images, $fileName);
        } elseif ($request->ios && $request->android) {
            // android and ios
            if (!$android = $this->getAndroidAppData($request->android)) {
                return response::make(["success" => false, "message" => "Android app error"]);
            }
            $android = json_decode($android);
            $fileName = Str::slug($android->product_info->title);
            $thumbnail = $android->product_info->thumbnail;
            $screenshots = $this->generateImage($android->media->images, Str::slug($android->product_info->authors[0]->name));
            if (!$ios = $this->getIosAppData($request->ios)) {
                return response::make(["success" => false, "message" => "IOS app error"]);
            }
            $ios = json_decode($ios);
        }
        $thumbnail = $this->saveImageFromUrl($thumbnail, $fileName);

        $thumbnail = $this->uploadImageToWordpress($thumbnail, $fileName);
        $screenshots = $this->uploadImageToWordpress($screenshots, $fileName . "-screenshots");

        return view("api.app_data", compact("android", "ios", "thumbnail", "screenshots"));
    }
    public function getIosAppData($url)
    {
        $apiKey = $this->getApiKey();
        $parts = parse_url($url);

        $pathSegments = explode('/', $parts['path']);

        $idSegment = collect($pathSegments)->first(function ($segment) {
            return str_starts_with($segment, 'id');
        });

        $appId = $idSegment ? substr($idSegment, 2) : null; // This will be '377951542'


        $queryParameters = http_build_query([
            'api_key' => $apiKey,
            'engine' => 'apple_product',
            'product_id' => 'mobile',
            'store' => "apps",
            "product_id" => $appId
        ]);

        $url = "https://serpapi.com/search";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $queryParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($content === false || $httpCode != 200) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => "Failed to fetch data: $error"]);
        }

        curl_close($ch);
        return $content;
    }
    public function getAndroidAppData($url)
    {
        $apiKey = $this->getApiKey();
        $parts = parse_url($url);

        parse_str($parts['query'], $query);

        $appId = $query['id'] ?? null; // This will be 'com.gotv.crackle.handset'

        $queryParameters = http_build_query([
            'api_key' => $apiKey,
            'engine' => 'google_play_product',
            'product_id' => 'mobile',
            'store' => "apps",
            "product_id" => $appId
        ]);

        $url = "https://serpapi.com/search";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $queryParameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($content === false || $httpCode != 200) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => "Failed to fetch data: $error"]);
        }

        curl_close($ch);
        return $content;
    }
    public function getApiKey()
    {
        $url = 'https://optimizo.io/api/api-key?secret_key=' . env('OPTIMIZO_SECRET_KEY');

        // Making a GET request to the URL
        $response = Http::get($url);

        // Check if the request was successful
        if ($response->successful()) {
            // Get the response as a JSON object
            $data = $response->json();

            return $data["api_key"];
        } else {
            // Handle the error appropriately
            return response()->json(['error' => 'Request failed'], $response->status());
        }
    }
    public function generateImage($images, $name)
    {
        $filename = $name . "-screenshots.jpg";
        $images = array_map(function ($imagePath) {
            return str_replace('=w526-h296-rw', '=w2560-h1440-rw', $imagePath);
        }, $images);
        $canvasWidth = 1280;
        $canvasHeight = 720;

        // Create a blank canvas
        $canvas = Image::canvas($canvasWidth, $canvasHeight);

        $images = array_slice($images, 0, 4);

        // Determine the width available for each image on the canvas
        $imageWidthOnCanvas = $canvasWidth / count($images);
        $margin = 10;

        foreach ($images as $index => $imgPath) {
            $image = Image::make($imgPath);

            // Resize the image to fit within the allocated width on the canvas while maintaining aspect ratio
            $image->resize($imageWidthOnCanvas - $margin * 2, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // Prevent upscaling smaller images
            });

            // Calculate X position to center the image in its allocated space on the canvas
            $x = $index * $imageWidthOnCanvas + ($imageWidthOnCanvas - $image->width()) / 2;

            // Center the image vertically on the canvas
            $y = ($canvasHeight - $image->height()) / 2;

            // Round $x and $y to ensure they are integers
            $x = round($x);
            $y = round($y);

            // Insert image into canvas
            $canvas->insert($image, 'top-left', $x, $y);
        }
        // $canvas->save($_SERVER['DOCUMENT_ROOT']."/thumbnail.jpg", 100);
        // Save the image with high quality
        $path = public_path("/apps/" . $filename);
        $canvas->save($path, 100); // 100 is the quality

        return $path;
    }
    public function saveImageFromUrl($imageUrl, $fileName)
    {
        try {
            $fileName = $fileName . ".jpg";
            // Define the destination path where you want to save the image
            $path = public_path("/apps/" . $fileName);

            // Create an instance of Image from the URL
            $image = Image::make($imageUrl);

            // Save the image to the destination path
            $image->save($path);

            return $path;

        } catch (\Exception $e) {
            // Handle exception if something goes wrong
            return 'Error: ' . $e->getMessage();
        }
    }

    public function uploadImageToWordpress($image, $altText)
    {

        $url = 'https://mobilekishop.net/blog/wp-json/wp/v2/media';
        $site_url = 'https://mobilekishop.net/blog/';
        $filename = $image;
        $token = $this->get_jwt_token($site_url, env('WP_USERNAME'), env('WP_PASSWORD'));

        // Initialize cURL for image upload
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Disposition: form-data; filename="' . $altText . '.jpg"',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => new \CURLFile($filename)]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return "cURL Error #:" . $err;
        }

        $responseData = json_decode($response, true);
        $uploadedMediaId = $responseData['id'] ?? null;
        // dd($responseData);
        // Check if the media was uploaded successfully
        if (!$uploadedMediaId) {
            return 'Failed to upload media.';
        }

        // Initialize cURL for updating media metadata
        $metadataUrl = $url . '/' . $uploadedMediaId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $metadataUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['alt_text' => $altText, 'title' => $altText]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $metadataResponse = curl_exec($ch);
        curl_close($ch);

        $metadataResponseData = json_decode($metadataResponse, true);
        if (isset($metadataResponseData['alt_text']) && $metadataResponseData['alt_text'] == $altText) {
            return $responseData['source_url'] ?? null; // Return the image URL
        }

        return 'Failed to update image metadata.';
    }

    public function get_jwt_token($site_url, $username, $password)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $site_url . '/wp-json/jwt-auth/v1/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('username' => $username, 'password' => $password)));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response)->token;
    }

}
