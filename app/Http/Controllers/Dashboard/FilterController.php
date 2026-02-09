<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Filter;
use Auth;

class FilterController extends Controller
{
    public function index(Request $request){
    	$filters = new Filter();
        if($request->input('search')){
            $filters = $filters->where($request->search_by, "like","%".$request->search."%");
        }
        $filters = $filters->get();
    	return view("dashboard.filters.index",compact("filters"));
    }
    public function create(){
    	return view("dashboard.filters.create");
    }
    public function store(Request $request){
        $this->validate($request, [
            "page_url"    => "required|url",
            "title"       => "required",
            "url"         => "required|url",
        ]);

        // Custom validation to check if a filter with the same url and page_url already exists
        $existingFilter = Filter::where("url", $request->url)
                               ->where("page_url", $request->page_url)
                               ->first();

        if ($existingFilter) {
            return redirect()->route("dashboard.filters.create")->with("fail", "Filter already exists");
        }

        $filter = new Filter();
        $filter->page_url = $request->page_url;
        $filter->title = $request->title;
        $filter->url = $request->url;
        $filter->save();

        return redirect()->route("dashboard.filters.create")->with("success", "Filter saved");
    }

    public function edit(Filter $filter){
        return view("dashboard.filters.edit",compact("filter"));
    }
    public function update(Request $request, $id){
        $filter = Filter::findOrFail($id);

        // Validate request, excluding the current filter from the unique checks
        $this->validate($request, [
            "page_url"    => "required|url",
            "title"       => "required",
            "url"         => "required|url",
        ]);

        if (Filter::where("url", $request->url)
                  ->where("page_url", $request->page_url)
                  ->where("id", "<>", $filter->id) // Exclude current filter
                  ->first()) {
            return redirect()->back()->withInput()->with("fail", "Filter already exists.");
        }

        // Update the filter with new values
        $filter->page_url = $request->page_url;
        $filter->title    = $request->title;
        $filter->url      = $request->url;
        $filter->save();

        // Redirect back with success message
        return redirect()->route("dashboard.filters.index")->with("success", "Filter updated successfully.");
    }
}
