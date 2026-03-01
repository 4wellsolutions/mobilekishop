<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Redirection;
use Illuminate\Support\Facades\Cache;
use Auth;

class RedirectionController extends Controller
{
    public function index()
    {
        $redirections = Redirection::orderBy("id", "DESC");
        if (\Request::get("search")) {
            $name = (\Request::get("url_type") == "from") ? "from_url" : "to_url";
            $redirections = $redirections->where($name, "like", "%" . \Request::get("search") . "%");
        }
        $redirections = $redirections->paginate(100);
        return view("dashboard.redirections.index", compact("redirections"));
    }
    public function create()
    {
        return view("dashboard.redirections.create");
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            "to_url" => "required",
            "from_url" => "required"
        ]);

        $from_url = $this->removeSlash($request->from_url);
        $redirection = new Redirection();
        $redirection->to_url = $request->to_url;
        $redirection->from_url = $from_url;
        $redirection->user_id = Auth::User()->id;
        $redirection->save();
        Cache::forget('url_redirections');

        return redirect()->route('dashboard.redirections.index')->with("success", "Redirection saved.");
    }
    public function removeSlash($url)
    {
        if (substr($url, -1) == '/') {
            return rtrim($url, "/");
        }
        return $url;
    }
    public function show($id)
    {
        return redirect()->route('dashboard.redirections.edit', $id);
    }
    public function edit($id)
    {
        $redirection = Redirection::find($id);
        return view("dashboard.redirections.edit", compact("redirection"));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "to_url" => "required",
            "from_url" => "required"
        ]);
        $from_url = $this->removeSlash($request->from_url);
        $redirection = Redirection::find($id);
        $redirection->to_url = $request->to_url;
        $redirection->from_url = $from_url;
        $redirection->user_id = Auth::User()->id;
        $redirection->save();
        Cache::forget('url_redirections');

        return redirect()->route('dashboard.redirections.index')->with("success", "Redirection updated.");
    }
    public function destroy($id)
    {
        $redirection = Redirection::find($id);
        $redirection->delete();
        Cache::forget('url_redirections');
        return redirect()->route('dashboard.redirections.index')->with("success", "Redirection deleted.");
    }
}
