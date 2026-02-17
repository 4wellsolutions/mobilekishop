<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Image as ProductImage;
use App\Models\Attribute;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Country;
use App\Specification;
use App\Models\Variant;
use App\Models\ProductColor;
use App\Models\ExpertRating;
use Carbon\Carbon;
use Str;
use Auth;
use DB;
use URL;
use File;
use Response;

class ProductController extends Controller
{
	public function index(Request $request)
	{
		$products = new Product();

		if ($request->get('search')) {
			$products = $products->where("name", "like", "%" . $request->get('search') . "%");
		}
		if ($request->get("date_filter")) {
			$products = $products->whereDate($request->get('date_filter'), ">=", $request->get("date1"))
				->whereDate($request->get('date_filter'), "<=", $request->get("date2"));
		}
		if ($request->get('filter_key')) {
			$products = $products->orderBy($request->get('filter_key'), $request->get('filter_value'));
		}
		if ($request->get('category_id')) {
			$products = $products->where("category_id", $request->get('category_id'));
		}
		if (!$request->get('filter_key')) {
			$products = $products->orderBy("id", "DESC");
		}

		$products = $products->paginate(50);
		return view("dashboard.products.index", compact("products"));
	}

	public function show()
	{
		dd("asdf");
	}
	public function create(Request $request)
	{
		$brands = Brand::all();
		$categories = Category::all();
		$variants = Variant::all();
		$countries = Country::all();
		$colors = Color::all();
		$category = Category::find($request->get("category_id"));
		return view("dashboard.products.create", compact("brands", "categories", 'variants', 'countries', 'colors', 'category'));
	}

	public function store(Request $request)
	{
		// Define validation rules and custom messages
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|unique:products,name',
			'category_id' => 'required|integer|exists:categories,id',
			'brand_id' => 'required|integer|exists:brands,id',
			'release_date' => 'required_if:category_id,1|date',
			'colors' => 'required|array|min:1',
			'colors.*' => 'exists:colors,id',
			'color_images' => 'required|array',
			'color_images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
			'variants' => 'required|array',
			'variants.*' => 'exists:variants,id',
			'prices' => 'required|array',
			'prices.*.*' => 'numeric|min:0',
			'body' => 'nullable|string',
			'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
			'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
			// Add other attribute validations if necessary
		], [
			'release_date.required_if' => 'Release date is required for this category.',
			'colors.required' => 'At least one color must be selected.',
			'color_images.required' => 'Images are required for each selected color.',
			'variants.required' => 'At least one variant must be provided.',
		]);

		// Handle validation failure
		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}

		// Start a database transaction to ensure data integrity
		DB::beginTransaction();

		try {
			// Create the Product using mass assignment
			$product = Product::create([
				'name' => $request->name,
				'slug' => Str::slug($request->name),
				'release_date' => $request->release_date ? Carbon::parse($request->release_date)->format('Y-m-d') : null,
				'category_id' => $request->category_id,
				'brand_id' => $request->brand_id,
				'title' => ucwords($request->name) . " Price, Specification and Review Pakistan",
				'body' => $request->body,
			]);

			// Set canonical URL
			$product->canonical = route('product.show', [$product->brand->slug, $product->slug]);

			// Handle thumbnail upload if provided
			if ($request->hasFile('thumbnail')) {
				$thumbnailPath = $this->uploadImage($request->file('thumbnail'), "{$product->slug}.jpg");
				$product->thumbnail = $thumbnailPath;
			}

			// Handle multiple image uploads
			if ($request->hasFile('images')) {
				$product->images()->createMany(
					collect($request->file('images'))->map(function ($image, $index) use ($product) {
						$imageName = "{$product->slug}-" . ($index + 1) . ".jpg";
						$imagePath = $this->uploadImage($image, $imageName, true);
						return [
							'name' => $imageName,
							'alt' => "{$product->slug}-" . ($index + 1),
						];
					})->toArray()
				);
			}

			// Attach variants with prices and country IDs
			foreach ($request->variants as $variantId) {
				foreach ($request->prices[$variantId] as $countryId => $price) {
					$product->variants()->attach($variantId, [
						'price' => $price,
						'country_id' => $countryId,
					]);
				}
			}

			// Sync product attributes
			$excludedKeys = [
				'name',
				'category_id',
				'brand_id',
				'release_date',
				'colors',
				'color_images',
				'variants',
				'prices',
				'body',
				'thumbnail',
				'images',
				'_token'
			];
			$attributes = $request->except($excludedKeys);

			$syncData = [];
			foreach ($attributes as $attributeId => $value) {
				if (!is_null($value)) {
					$syncData[$attributeId] = ['value' => $value];
				}
			}

			// Add default review attributes
			$syncData += [
				Attribute::where('name', 'reviewer')->where('category_id', $product->category_id)->value('id') => ['value' => $this->randomName()],
				Attribute::where('name', 'review_count')->where('category_id', $product->category_id)->value('id') => ['value' => rand(5, 50)],
				Attribute::where('name', 'rating')->where('category_id', $product->category_id)->value('id') => ['value' => rand(38, 50) / 10],
			];

			$product->attributes()->sync($syncData);

			// Handle color associations with images
			foreach ($request->colors as $colorId) {
				$color = Color::findOrFail($colorId);
				$colorImage = $request->file("color_images.{$colorId}");
				$imagePath = null;

				if ($colorImage) {
					$filename = Str::slug("{$product->name}-{$color->name}") . '.jpg';
					$path = $_SERVER['DOCUMENT_ROOT'] . "/products/{$filename}";

					// Resize and save the image using Intervention Image
					Image::make($colorImage)
						->resize(300, 300, function ($constraint) {
							$constraint->aspectRatio();
							$constraint->upsize();
						})
						->save($path);

					$imagePath = url("products/{$filename}");
				}

				// Attach color to product with image
				$product->colors()->attach($colorId, ['image' => $imagePath]);
			}

			// Update product thumbnail to the first color image if available
			if ($product->colors()->exists()) {
				$firstColorImage = $product->colors()->first()->pivot->image;
				$product->thumbnail = $firstColorImage;
				$product->save();
			}

			// Commit the transaction
			DB::commit();

			return response()->json([
				'success' => true,
				'url' => route('dashboard.products.index'),
				'message' => 'Product added successfully',
			]);
		} catch (\Exception $e) {
			// Rollback the transaction on error
			DB::rollBack();

			// Log the error for debugging (optional)
			\Log::error('Product creation failed: ' . $e->getMessage());

			return response()->json([
				'success' => false,
				'message' => 'An error occurred while creating the product.',
				'error' => $e->getMessage(),
			], 500);
		}
	}

	public function edit(Product $product)
	{
		$brands = Brand::all();
		$categories = Category::all();
		$variants = Variant::all();
		$countries = Country::all();
		$colors = Color::all();
		$category = $product->category;

		// Load expert rating
		$expertRating = $product->expertRating ?? new ExpertRating();

		// Fetch all pivot entries for product-variant-country combinations
		$productVariants = DB::table('product_variants')
			->where('product_id', $product->id)
			->get();

		// Organize variant prices as variant_id => country_id => price
		$variantPrices = [];
		foreach ($productVariants as $pv) {
			$variantPrices[$pv->variant_id][$pv->country_id] = $pv->price;
		}

		return view("dashboard.products.edit", compact(
			"brands",
			"categories",
			"variants",
			"countries",
			"colors",
			"category",
			"product",
			"variantPrices",
			"expertRating"
		));
	}


	public function update(Request $request, Product $product)
	{
		// Define validation rules and custom messages
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|unique:products,name,' . $product->id,
			'brand_id' => 'required|integer|exists:brands,id',
			'release_date' => 'required_if:category_id,1|date',
			'colors' => 'required|array|min:1',
			'colors.*' => 'exists:colors,id',
			'color_images' => 'sometimes|array',
			'color_images.*' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
			'variants' => 'required|array',
			'variants.*' => 'exists:variants,id',
			'prices' => 'required|array',
			'prices.*.*' => 'numeric|min:0',
			'body' => 'nullable|string',
			'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
			'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
			// Add other attribute validations if necessary
		], [
			'release_date.required_if' => 'Release date is required for this category.',
			'colors.required' => 'At least one color must be selected.',
			'color_images.required' => 'Images are required for each selected color.',
			'variants.required' => 'At least one variant must be provided.',
		]);

		// Handle validation failure
		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}

		// Start a database transaction to ensure data integrity
		DB::beginTransaction();

		try {
			// Update the Product using mass assignment
			$product->update([
				'name' => $request->name,
				'slug' => Str::slug($request->name),
				'release_date' => $request->release_date ? Carbon::parse($request->release_date)->format('Y-m-d') : null,
				'brand_id' => $request->brand_id,
				'title' => ucwords($request->name) . " Price, Specification and Review Pakistan",
				'body' => $request->body,
			]);

			// Set canonical URL
			$product->canonical = route('product.show', [$product->brand->slug, $product->slug]);

			// Handle thumbnail upload if provided
			if ($request->hasFile('thumbnail')) {
				$thumbnailPath = $this->uploadImage($request->file('thumbnail'), "{$product->slug}.jpg");
				$product->thumbnail = $thumbnailPath;
			}

			// Handle multiple image uploads
			if ($request->hasFile('images')) {
				$product->images()->createMany(
					collect($request->file('images'))->map(function ($image, $index) use ($product) {
						$imageName = "{$product->slug}-" . ($index + 1) . ".jpg";
						$imagePath = $this->uploadImage($image, $imageName, true);
						return [
							'name' => $imageName,
							'alt' => "{$product->slug}-" . ($index + 1),
						];
					})->toArray()
				);
			}

			// Detach existing variants and re-attach with new prices
			$product->variants()->detach();
			foreach ($request->variants as $variantId) {
				foreach ($request->prices[$variantId] as $countryId => $price) {
					$product->variants()->attach($variantId, [
						'price' => $price,
						'country_id' => $countryId,
					]);
				}
			}

			// Sync product attributes
			$excludedKeys = [
				'name',
				'category_id',
				'brand_id',
				'release_date',
				'colors',
				'color_images',
				'variants',
				'prices',
				'body',
				'thumbnail',
				'images',
				'_token',
				'_method',
				'expert_design',
				'expert_display',
				'expert_performance',
				'expert_camera',
				'expert_battery',
				'expert_value_for_money',
				'expert_verdict',
				'expert_rated_by',
			];
			$attributes = $request->except($excludedKeys);

			$syncData = [];
			foreach ($attributes as $attributeId => $value) {
				if (!is_null($value)) {
					$syncData[$attributeId] = ['value' => $value];
				}
			}

			// Add default review attributes
			$syncData += [
				Attribute::where('name', 'reviewer')->where('category_id', $product->category_id)->value('id') => ['value' => $this->randomName()],
				Attribute::where('name', 'review_count')->where('category_id', $product->category_id)->value('id') => ['value' => rand(5, 50)],
				Attribute::where('name', 'rating')->where('category_id', $product->category_id)->value('id') => ['value' => rand(38, 50) / 10],
			];

			$product->attributes()->sync($syncData);

			// Handle color associations with images
			foreach ($request->colors as $colorId) {
				$color = Color::findOrFail($colorId);
				$colorImage = $request->file("color_images.{$colorId}");
				$imagePath = null;

				if ($colorImage) {
					$filename = Str::slug("{$product->name}-{$color->name}") . '.jpg';
					$path = public_path("products/{$filename}");

					// Resize and save the image using Intervention Image
					// Image::make($colorImage)
					//     ->resize(300, 300, function ($constraint) {
					//         $constraint->aspectRatio();
					//         $constraint->upsize();
					//     })
					//     ->save($path);

					// $imagePath = url("products/{$filename}");
				}

				// Attach color to product with image
				// $product->colors()->syncWithoutDetaching([$colorId => ['image' => $imagePath ?? $product->colors()->find($colorId)->pivot->image]]);
			}

			// Update product thumbnail to the first color image if available
			// if ($product->colors()->exists()) {
			//     $firstColorImage = $product->colors()->first()->pivot->image;
			//     $product->thumbnail = $firstColorImage;
			//     $product->save();
			// }

			// Save Expert Rating if any criteria provided
			if ($request->filled('expert_design') || $request->filled('expert_display') || $request->filled('expert_performance') || $request->filled('expert_camera') || $request->filled('expert_battery') || $request->filled('expert_value_for_money')) {
				$rating = ExpertRating::updateOrCreate(
					['product_id' => $product->id],
					[
						'design' => $request->expert_design ?? 0,
						'display' => $request->expert_display ?? 0,
						'performance' => $request->expert_performance ?? 0,
						'camera' => $request->expert_camera ?? 0,
						'battery' => $request->expert_battery ?? 0,
						'value_for_money' => $request->expert_value_for_money ?? 0,
						'verdict' => $request->expert_verdict,
						'rated_by' => $request->expert_rated_by,
					]
				);
				$rating->overall = $rating->calculateOverall();
				$rating->save();
			}

			// Commit the transaction
			DB::commit();

			return response()->json([
				'success' => true,
				'url' => route('dashboard.products.index'),
				'message' => 'Product updated successfully',
			]);
		} catch (\Exception $e) {
			// Rollback the transaction on error
			DB::rollBack();

			// Log the error for debugging (optional)
			\Log::error('Product update failed: ' . $e->getMessage());

			return response()->json([
				'success' => false,
				'message' => 'An error occurred while updating the product.',
				'error' => $e->getMessage(),
			], 500);
		}
	}

	public function update1(Request $request, $id)
	{
		$product = Product::findOrFail($id);

		$validator = Validator::make($request->all(), [
			'name' => 'required|string|unique:products,name,' . $product->id,
			'category_id' => 'required|numeric',
			'brand_id' => 'required|numeric',
			'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
			'colors' => 'required|array|min:1',
			'colors.*' => 'exists:colors,id',
			'color_images' => 'nullable|array',
			'variants' => 'required|array',
			'prices' => 'required|array',
		], [
			'colors.required' => 'At least one color must be selected.',
			'colors.*.exists' => 'The selected color is invalid.',
			'color_images.required' => 'Images are required for each selected color.',
			'color_images.*.image' => 'Each color image must be a valid image file.',
			'variants.required' => 'At least one variant must be provided.',
		]);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}

		$product->name = $request->name;
		$product->slug = Str::slug($request->name);
		$product->category_id = $request->category_id;
		$product->brand_id = $request->brand_id;
		$product->release_date = Carbon::parse($request->release_date)->format('Y-m-d');
		$product->title = ucwords($request->name) . " Price, Specification and Review Pakistan";
		$product->body = $request->body;
		$product->save();

		// Update canonical
		$product->canonical = route('product.show', [$product->brand->slug, $product->slug]);
		$product->update();



		if ($request->hasFile('images')) {
			$images = $request->file('images');
			$i = 1;
			foreach ($images as $image) {
				$this->uploadImage($image, $product->slug . "-" . $i . ".jpg", true);
				$image = new ProductImage();
				$image->product_id = $product->id;
				$image->name = $product->slug . "-" . $i . ".jpg";
				$image->alt = $product->slug . "-" . $i;
				$image->save();
				$i++;
			}
		}

		// Detach existing variants
		$product->variants()->detach();
		// Loop through each variant in the request and attach it with the price and country_id
		foreach ($request->variants as $variantData) {
			$variant = Variant::find($variantData);
			if ($variant) {
				foreach ($request->prices[$variantData] as $country_id => $price) {

					$product->variants()->attach($variant->id, [
						'price' => $price,
						'country_id' => $country_id
					]);
				}
			}
		}


		// Define the attributes to exclude from processing
		$excludeKeys = [
			'name',
			'price_in_pkr',
			'price_in_inr',
			'price_in_eur',
			'price_in_tk',
			'price_in_dollkr',
			'price_in_dollar',
			'category_id',
			'brand_id',
			'_token',
			'body',
			'thumbnail',
			'release_date',
			'images',
			'colors',
			'color_images',
			'variants',
			'prices',
			'_method'
		];

		// Filter the request to get only the attributes to update
		$attributesToUpdate = $request->except($excludeKeys);

		// Prepare an array to sync attributes with their values
		$syncData = [];

		foreach ($attributesToUpdate["attributes"] as $key => $val) {
			if ($val != null) {
				$attribute = Attribute::find($key);
				if ($attribute) {
					$syncData[$key] = [
						'product_id' => $product->id,
						'value' => $val,
					];
				}
			}
		}
		// dd($syncData);
		// usort($syncData, function ($a, $b) {
		//     return $a['attribute_id'] <=> $b['attribute_id'];
		// });

		// Sync the attributes with their values
		$product->attributes()->sync($syncData);


		// Update colors and their images
		if ($request->has('colors')) {
			$colorData = [];

			foreach ($request->colors as $colorId) {
				$color = Color::find($colorId);

				if ($color) { // Check if the color exists
					$imagePath = null;
					if ($request->hasFile('color_images') && isset($request->file('color_images')[$colorId])) {
						$image = $request->file('color_images')[$colorId];
						$filename = Str::slug($product->name) . '-' . Str::slug($color->name) . '.jpg';

						// Use Intervention Image to resize and save the image
						$imagePath = '/products/' . $filename;
						$path = public_path($imagePath);
						// $path = $_SERVER['DOCUMENT_ROOT'].$imagePath;

						$resizedImage = Image::make($image)->resize(300, 300, function ($constraint) {
							$constraint->aspectRatio();
						})->save($path);

						$colorData[$colorId] = ['image' => $imagePath];
					} else {
						// Use existing image path if no new image is uploaded
						$existingImage = $product->colors()->where('color_id', $colorId)->first();
						$colorData[$colorId] = ['image' => $existingImage ? $existingImage->pivot->image : null];
					}
				}
			}
			$product->colors()->sync($colorData);
		}
		$firstColorImage = $product->colors->first()->pivot->image;
		// set first color image as thumbnail
		$product->thumbnail = $firstColorImage;
		$product->update();


		// Redirect or return a response
		return response()->json([
			'success' => true,
			'url' => route('dashboard.products.index'),
			'message' => 'Product updated successfully'
		]);
	}
	public function removeColor(Request $request, $productId, $colorId)
	{
		$product = Product::findOrFail($productId);
		$productColor = $product->colors()->where('color_id', $colorId)->first();

		if ($productColor) {
			// Delete the image file
			$imagePath = parse_url($productColor->pivot->image, PHP_URL_PATH);
			$fullImagePath = public_path($imagePath);

			// Delete the image file
			if (file_exists($fullImagePath)) {
				unlink($fullImagePath);
			}

			// Detach the color
			$product->colors()->detach($colorId);
		}

		return response()->json(['success' => true]);
	}

	public function byBrand($brand_id)
	{
		$products = Product::where('brand_id', $brand_id)->get(['id', 'name']);
		return response()->json($products);
	}

	public function priceCreate($id)
	{
		$product = Product::find($id);

		if (!$product) {
			return redirect()->route('dashboard.products.index')->with("fail", "Product not found");
		}

		// Exact URLs for each country
		$countryUrls = [
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

		$countries = Country::all();

		// Return the view with product, countries, and country URLs
		return view("dashboard.products.price_store", compact("product", "countries", "countryUrls"));
	}

	public function priceStore(Request $request, $id)
	{
		// dd($request->all()); // Uncomment for debugging if needed

		$product = Product::find($id); // Find the product by ID
		$country_ids = $request->input('country_id'); // Get the country IDs from the request
		$prices = $request->input('price'); // Get the prices from the request

		if ($product) {
			// Loop through the country IDs
			foreach ($country_ids as $index => $country_id) {
				$price_value = $prices[$index]; // Get the corresponding price for the country

				// Check if the price is neither null nor 0 before updating or creating
				if ($price_value !== null && $price_value != 0) {
					// Use DB to update or insert into the pivot table directly
					DB::table('product_variants') // Pivot table name (change if different)
						->updateOrInsert(
							[
								'product_id' => $product->id,  // Match product
								'variant_id' => 1,              // Hardcoding the variant ID to 1
								'country_id' => $country_id     // Match country
							],
							[
								'price' => $price_value        // Set the price for this combination
							]
						);
				}
			}

			return redirect()->route('dashboard.products.index')->with("success", "Prices updated for variants");
		}

		return redirect()->route('dashboard.products.index')->with("fail", "Product not found");
	}

	public function getPrices(Request $request)
	{
		$url = $request->url;
		$slug = $request->slug;
		$finalURL = $url . $slug . "/";
		// Extract the country code from the URL using a regular expression
		$countryCode = $this->extractCountryCode($url);

		// Check if the country code is valid
		if (!$countryCode) {
			return response()->json(['success' => false, 'message' => 'Invalid country code']);
		}
		if ($countryCode == "gb") {
			$countryCode = "uk";
		}
		// Fetch the country from the database using the extracted country code
		$country = Country::where('country_code', $countryCode)->first();

		// If the country is not found, return an error message
		if (!$country) {
			return response()->json(['success' => false, 'message' => 'Country not found']);
		}

		// Fetch the price for the product using the country URL (as you were doing)
		$price = $this->extractPriceFromUrl($finalURL);

		// Return the price as a response
		return response()->json(['success' => true, 'price' => $price, 'country' => $country->country_name, "country_code" => $countryCode, "countryId" => $country->id, "url" => $finalURL]);
	}

	public function extractCountryCode($url)
	{
		// $url = "https://au.mobileinto.com";
		$pattern = '/(?:https?:\/\/)?(?:([a-z]{2})\.)?mobileinto\.com(?:\/([a-z]{2}))?/';

		// Perform the match
		if (preg_match($pattern, $url, $matches)) {
			// Return the country code from either the subdomain or path (matches[1] or matches[2])
			return !empty($matches[1]) ? $matches[1] : (!empty($matches[2]) ? $matches[2] : null);
		}

		// If no match, return null or handle as needed
		return null;
	}


	public function extractPriceFromUrl($url)
	{
		$response = Http::get($url);

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

			if ($nodes->length > 0) {
				$firstNode = $nodes->item(0); // Get the first node
				$priceString = trim($firstNode->nodeValue);

				// Use a regular expression to extract the price (adjust as needed)
				preg_match('/\$?([\d,]+(?:\.\d+)?)/', $priceString, $matches);

				if (isset($matches[1])) {
					$price = str_replace(',', '', $matches[1]);
					return (float) $price;
				}
			}
		}

		return 0; // Return 0 if no price found or if the request failed
	}

	public function uploadImage($image, $name, $large_image = null)
	{
		dd(public_path("products/$name"));
		// $path = $_SERVER['DOCUMENT_ROOT']."/products/$name";
		$path = public_path("products/$name");
		$mainImage = Image::make($image);
		if ($large_image) {
			$mainImage->resize(1000, null, function ($const) {
				$const->aspectRatio();
			});
		} else {
			$mainImage->resize(160, 212);
		}
		$mainImage->save($path);
		return;
	}

	public function scrap()
	{
		return view("dashboard.products.scrap_amazon");
	}
	public function scrapAmazon(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'amazon_url' => 'required|url|regex:/^https:\/\/www\.amazon\.com\//',
		]);

		if ($validator->fails()) {
			// Return the validation error as JSON response
			return response()->json([
				'error' => $validator->errors()->first()  // Returning the first validation error
			], 400);
		}

		$amazonLink = $request->input('amazon_url');
		$apiKey = env('OUTSCRAPER_API_KEY'); // Your API Key
		$apiUrl = "https://api.app.outscraper.com/amazon/products"; // Outscraper API URL

		// Extract ASIN from Amazon URL
		$asin = $this->extractASIN($amazonLink);

		if (!$asin) {
			return response()->json(['error' => 'Unable to extract ASIN from the provided Amazon URL.'], 400);
		}

		// Construct the query parameters for the API request
		$queryParams = [
			'query' => $amazonLink,
			'limit' => 1,
			'async' => false,
		];

		// Initialize Guzzle Client
		$client = new \GuzzleHttp\Client();

		try {
			// Make the GET request to the API with query parameters
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
				$productData = $this->flattenArray($productData);

				// Ensure ASIN is present in the fetched data
				if (!isset($productData['asin']) || empty($productData['asin'])) {
					return response()->json(['error' => 'ASIN not found in the fetched product data.'], 400);
				}

				return response()->json([
					'success' => 'Product data fetched successfully.',
					'data' => $productData,
					'status' => 'Success'
				]);
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
	public function flattenArray($array, $prefix = '')
	{
		$result = [];

		foreach ($array as $key => $value) {
			// Remove the 'query' element
			if ($key === 'query') {
				continue;
			}

			$newKey = $prefix . $key;

			if (is_array($value)) {
				// Recursively flatten nested arrays
				$result = array_merge($result, $this->flattenArray($value, $newKey . '.'));
			} else {
				// Add the key-value pair to the result
				$result[$newKey] = $value;
			}
		}

		return $result;
	}
	private function extractASIN($url)
	{
		// Regular expression to extract ASIN from Amazon URL
		$pattern = '/\/dp\/([A-Z0-9]{10})/i';
		if (preg_match($pattern, $url, $matches)) {
			return $matches[1];
		}

		// Alternative pattern
		$pattern = '/\/gp\/product\/([A-Z0-9]{10})/i';
		if (preg_match($pattern, $url, $matches)) {
			return $matches[1];
		}

		return null;
	}
	public function deleteImage($id)
	{
		// Find the image
		$image = ProductImage::find($id);

		$path = $_SERVER['DOCUMENT_ROOT'] . '/products/' . $image->name;
		// Delete the image file from the storage
		if (File::exists($path)) {
			File::delete($path);
			// Delete the image record from the database
			$image->delete();

			return response()->json(['success' => true]);
		}
		return response()->json(['success' => false]);

	}
	// get random name
	public function randomName()
	{
		$input = array("Arham", "Ayan", "Ayaan", "Aryan", "Zayan", "Anas", "Rehan", "Huzaifa", "Zain", "Rayan", "Azlan", "Imran", "Shayan", "Ali", "Hamza", "Arish", "Aayan", "Saad", "Zeeshan", "Junaid", "Adnan", "Sufian", "Faizan", "Irfan", "Asif", "Sahil", "Rohaan", "Salman", "Taimoor", "Rizwan", "Hamdan", "Danish", "Umar", "Fahad", "Farhan", "Asad", "Abdullah", "Ahad", "Arabi", "Ahmed", "Faisal", "Ayaz", "Afan", "Kiyan", "Muslim", "Abrar", "Hasan", "Aaron", "Aman", "Sameer", "Ahmad", "Umair", "Arsalan", "Ahil", "Shoaib", "Noman", "Saif", "Kaif", "Usman", "Talha", "Owais", "Haris", "Bilal", "Maaz", "Zaid", "Husnain", "Sarim", "Taha", "Adyan", "Sohail", "Uzair", "Arif", "Hussain", "Abaan", "Azhar", "Hammad", "Azan", "Kashif", "Abu-Turab", "Zubair", "Azaan", "Rayyan", "Haider", "Murshad", "Shahzaib", "Izhaan", "Kabir", "Nadeem", "Arshan", "Daniyal", "Ammar", "Ibrahim", "Islam", "Aamir", "Sarfaraz", "Hadi", "Arshad", "Hashir", "Abir", "Zahid", "Atif", "Sajid", "Moiz", "Azhaan", "Mohsin", "Rahul", "Ahnaf", "Muhammad", "Safwan", "Mehar", "Saqib", "Hanzalah", "Rakib", "Samar", "Irtaza", "Ismail", "Ayman", "Abeer", "Shahid", "Arab", "Waqas", "Shahbaz", "Faiz", "Ahsan", "Usama", "Hassan", "Nihal", "Hunain", "Waqar", "Mustafa", "Ashar", "Tauseef", "Rashid", "Sayan", "Abbas", "Shahzain", "Arafat", "Adan", "Ameen", "Mahir", "Javed", "Ahtisham", "Hooman", "Arslan", "Raqeeb", "Shahin", "Rahman", "Shifa", "Tahir", "Mudassar");
		$key = array_rand($input, 1);
		$name = $input[$key];
		return $name;
	}
}
