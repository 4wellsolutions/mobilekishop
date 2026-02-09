<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ExpertRating;
use App\Models\Product;
use Illuminate\Http\Request;

class ExpertRatingController extends Controller
{
    /**
     * List all products with their expert ratings.
     */
    public function index(Request $request)
    {
        $query = Product::with('expertRating')
            ->orderBy('id', 'DESC');

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(50);

        return view('dashboard.expert-ratings.index', compact('products'));
    }

    /**
     * Show the form for editing expert rating of a product.
     */
    public function edit($productId)
    {
        $product = Product::with('expertRating')->findOrFail($productId);
        $rating = $product->expertRating ?? new ExpertRating();

        return view('dashboard.expert-ratings.edit', compact('product', 'rating'));
    }

    /**
     * Store or update the expert rating for a product.
     */
    public function update(Request $request, $productId)
    {
        $request->validate([
            'design' => 'required|numeric|min:0|max:10',
            'display' => 'required|numeric|min:0|max:10',
            'performance' => 'required|numeric|min:0|max:10',
            'camera' => 'required|numeric|min:0|max:10',
            'battery' => 'required|numeric|min:0|max:10',
            'value_for_money' => 'required|numeric|min:0|max:10',
            'verdict' => 'nullable|string',
            'rated_by' => 'nullable|string|max:255',
        ]);

        $rating = ExpertRating::updateOrCreate(
            ['product_id' => $productId],
            [
                'design' => $request->design,
                'display' => $request->display,
                'performance' => $request->performance,
                'camera' => $request->camera,
                'battery' => $request->battery,
                'value_for_money' => $request->value_for_money,
                'verdict' => $request->verdict,
                'rated_by' => $request->rated_by,
            ]
        );

        // Auto-calculate overall
        $rating->overall = $rating->calculateOverall();
        $rating->save();

        return redirect()
            ->route('dashboard.expert-ratings.edit', $productId)
            ->with('success', 'Expert rating saved successfully!');
    }

    /**
     * Remove the expert rating for a product.
     */
    public function destroy($productId)
    {
        ExpertRating::where('product_id', $productId)->delete();

        return redirect()
            ->route('dashboard.expert-ratings.index')
            ->with('success', 'Expert rating removed.');
    }
}
