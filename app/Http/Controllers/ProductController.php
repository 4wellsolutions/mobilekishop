<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Models\Product;
use App\Models\Compare;
use App\Models\Brand;
use Response;

use DB;
class ProductController extends Controller
{


    public function showOld($brand, $slug)
    {
        $brand = Brand::firstWhere("slug", $brand);
        if (!$brand) {
            return abort(404);
        }
        $product = Product::where("brand_id", $brand->id)->firstWhere("slug", $slug);
        if (!$product) {
            return abort(404);
        }
        return redirect('/product/' . $slug)->setStatusCode(301);
    }

    public function show($slug)
    {
        $agent = new Agent();
        $country = DB::table("countries")->where("country_code", "pk")->first();
        $product = Product::firstWhere("slug", $slug);
        if (!$product) {
            return abort(404);
        }
        $product->load([
            'brand',
            'category',
            'attributes',
            'reviews' => function ($query) {
                $query->where('is_active', 1)->latest();
            },
            'images',
            'variants' => function ($query) use ($country) {
                $query->where('country_id', $country->id)
                    ->withPivot('price');
            }
        ]);

        $product->views++;
        $product->save();

        $products = $product->brand->products()
            ->whereHas('variants', function ($query) use ($country) {
                $query->where('price', '>', 0);
                $query->where('country_id', $country->id);
            })
            ->where("category_id", $product->category->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
        $category = $product->category;
        $compares = Compare::where('product1', $product->slug)
            ->orWhere('product2', $product->slug)
            ->orWhere('product3', $product->slug)
            ->inRandomOrder()
            ->limit(6)
            ->get();
        $amount = null;

        // Use the dedicated view for mobile-phones, generic show for others
        $view = $product->category && $product->category->slug === 'mobile-phones' ? 'mobile-phones' : 'show';
        return view("frontend.product.{$view}", compact('product', 'products', 'agent', 'category', 'compares', 'amount', 'country'));
    }
    public function getProducts1($countryId, $lowerAmount = null, $upperAmount = null)
    {
        $products = Product::select('products.*')->join('product_variants', 'products.id', '=', 'product_variants.product_id')->where('product_variants.country_id', $countryId);
        if ($lowerAmount) {
            $products = $products->where('product_variants.price', '>=', $lowerAmount);
        }
        if ($upperAmount) {
            $products = $products->where('product_variants.price', '<=', $upperAmount);
        }
        $products = $products->groupBy('products.id');
        return $products;
    }
    public function getProducts($countryId, $lowerAmount = null, $upperAmount = null)
    {
        $products = Product::select('products.*')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->where('product_variants.country_id', $countryId)
            ->where('product_variants.price', '>', 0)
            ->whereHas('variants', function ($query) use ($countryId) {
                $query->where('country_id', $countryId)
                    ->where('price', '>', 0);
            });

        if ($lowerAmount) {
            $products = $products->where('product_variants.price', '>=', $lowerAmount);
        }

        if ($upperAmount) {
            $products = $products->where('product_variants.price', '<=', $upperAmount);
        }

        $products = $products->groupBy('products.id');

        return $products;
    }
    public function autocompleteSearch(Request $request)
    {
        $query = $request->get("query");
        $products = new Product();
        if ($request->get("category_id")) {
            $products = $products->where("category_id", $request->get("category_id"));
        }
        $results = $products->limit(10)->where('name', 'LIKE', '%' . $query . '%');
        $result = json_decode($results->get());
        return Response::make($result);
    }

    public function scrape(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'amazon_link' => 'required|url',
        ]);

        $amazonLink = $request->amazon_link;
        $apiKey = env('OUTSCRAPER_API_KEY');
        // $apiKey = Setting::where('key', 'outscraper_api_key')->value('value');

        $apiUrl = "https://api.app.outscraper.com/amazon/products";

        // Extract ASIN from Amazon URL
        $asin = $this->extractASIN($amazonLink);

        if (!$asin) {
            return response()->json(['error' => 'Unable to extract ASIN from the provided Amazon URL.'], 400);
        }

        // Check if the product with this ASIN already exists
        $existingProduct = Product::where('asin', $asin);
        if ($request->product == "edit") {
            $existingProduct = $existingProduct->where("id", "!=", $request->product_id);
        }
        $existingProduct = $existingProduct->first();
        if ($existingProduct) {
            return response()->json([
                'errors' => [
                    'product' => ['Product already exists.'],
                ],
                'data' => $existingProduct,
                'status' => 'Exists',
            ], 422);
        }


        // Construct the full URL with query parameters for the API request
        $queryParams = [
            'query' => $amazonLink,
            'limit' => 1,
            'async' => false,
        ];

        // Initialize Guzzle Client
        $client = new \GuzzleHttp\Client();

        try {
            // Make the GET request with query parameters
            $response = $client->request('GET', $apiUrl, [
                'headers' => [
                    'X-API-KEY' => $apiKey,
                    'Accept' => 'application/json',
                ],
                'query' => $queryParams,
                'timeout' => 30, // Optional: Set a timeout for the request
            ]);

            $body = json_decode($response->getBody(), true);

            if (isset($body['data'][0][0])) {
                // Assuming the API returns data in $body['data'][0][0]
                $productData = $body['data'][0][0];

                // Ensure ASIN is present in the fetched data
                if (!isset($productData['asin']) || empty($productData['asin'])) {
                    return response()->json(['error' => 'ASIN not found in the fetched product data.'], 400);
                }

                return response()->json(
                    [
                        'success' => 'Product data fetched successfully.',
                        'data' => $productData,
                        'status' => 'Success'
                    ]
                );
            } else {
                return response()->json(['error' => 'Failed to fetch product data.'], 400);
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Handle 4xx errors
            return response()->json(['error' => 'Client error: ' . $e->getMessage()], $e->getCode());
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Handle 5xx errors
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], $e->getCode());
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // Handle connection errors
            return response()->json(['error' => 'Connection error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'Error fetching product data: ' . $e->getMessage()], 500);
        }
    }
}
