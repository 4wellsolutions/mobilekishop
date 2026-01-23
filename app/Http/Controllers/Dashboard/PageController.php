<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Page;
use Auth;

class PageController extends Controller
{
    public function index()
    {
        $pages = new Page();
        if(\Request::get('search')){
            $pages = $pages->where("slug",\Request::get('search'));
        }

        $pages = $pages->orderBy("id","DESC")->paginate(20);
        return view("dashboard.pages.index",compact("pages"));
    }

    public function create()
    {
        return view("dashboard.pages.create");
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title'       => 'required|unique:pages',
            'description' => 'required|unique:pages',
            'canonical'   => 'required|unique:pages',
            'slug'        => 'required|unique:pages',
            'h1'          => 'required|unique:pages',
            'body'        => 'nullable|unique:pages',
        ]);

        // Create a new page instance
        $page = new Page();
        $page->title        = $request->title;
        $page->description  = $request->description;
        $page->canonical    = $request->canonical;
        $page->slug         = $request->slug;
        $page->h1           = $request->h1;
        $page->body         = $request->body;
        $page->user_id      = Auth::User()->id;
        $page->save();

        // Redirect the user to a success page or wherever you prefer
        return redirect()->route('dashboard.pages.index')->with('success', 'Page created successfully');
    }

    public function show(Page $id)
    {
        
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('dashboard.pages.edit', compact("page"));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title'         => 'required|unique:pages,title,' . $id,
            'description'   => 'required|unique:pages,description,' . $id,
            'canonical'     => 'required|unique:pages,canonical,' . $id,
            'slug'          => 'required|unique:pages,slug,' . $id,
            'h1'            => 'required|unique:pages,h1,' . $id,
            'body'          => 'nullable|unique:pages,body,' . $id,
        ]);

        // Retrieve the page to update
        $page = Page::findOrFail($id);

        // Update the page's properties with the new values
        $page->title        = $request->title;
        $page->description  = $request->description;
        $page->canonical    = $request->canonical;
        $page->h1           = $request->h1;
        $page->slug         = $request->slug;
        $page->body         = $request->body;

        // Save the updated page
        $page->save();

        // Redirect the user to a success page or wherever you prefer
        return redirect()->route('dashboard.pages.index')->with('success', 'Page updated successfully');
    }

    public function destroy($id)
    {
        //
    }
}
