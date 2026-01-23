<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Package;
use Str;
use URL;

class PackageController extends Controller
{
    public function index($country_code){
        $canonical = url('/packages');

        $metas = (object)[
            "title" => Str::title("Mobile Packages - Call - SMS - Data - Hybrid"),
            "description" => "Mobile Sim/Network Packages in Pakistan - Call, SMS, Data, Hybrid, 4g, 5g, Internet, International, local packages",
            "canonical" => $canonical,
            "h1" => "Mobile Network Packages",
            "name" => "Mobile Packages"
        ];
        return view("frontend.packages.index", compact("metas"));
    }

    public function showNetwork($network){
        if ($network) {
            $packages = Package::where("filter_network", $network)->get();
        } else {
            $packages = new Collection();
        }

        // Construct the canonical URL without country code
        $baseUrl = url('/packages');
        $canonical = $network ? $baseUrl . '/' . $network : $baseUrl;

        $metas = (object)[
            "title" => Str::title("{$network} Mobile Packages - Call - SMS - Data - Hybrid"),
            "description" => "{$network} Mobile Packages in Pakistan - Call, SMS, Data, Hybrid, 4g, 5g, Internet, International, local packages",
            "canonical" => $canonical,
            "h1" => "$network Packages",
            "name" => "$network Packages"
        ];

        return view("frontend.packages.package", compact("metas", "packages", 'network'));
    }
    
    public function show($network,$slug)
    {
        if(!$network && !$slug){
            abort(404);
        } 
        if(!$package = Package::where("slug",$slug)->where("filter_network",$network)->first()){
            abort(404);
        }
        return view("frontend.packages.show",compact("package"));
    }

    public function showNetworkPackages($type){
        
        $network = \Request::segment(2);
        
        $network_type = ["prepaid","postpaid"];
        $package_type = ["hybrid","voice","data","sms"];
        $validity     = ["hourly","daily","weekly","monthly"];
        
        $packages = new Package();

        $packages = $packages->where("filter_network",$network);

        if (in_array($type, $network_type)) {
            $packages = $packages->where("type",($type == "prepaid") ? 0 : 1);
        }elseif (in_array($type, $package_type)) {
            $packages = $packages->where("filter_type",$type);
        }elseif (in_array($type, $validity)) {
            $packages = $packages->where("validity",$type);
        }else{
            return $this->show($network,$type);
        }

        $packages = $packages->get();
        if($packages->isEmpty()){
            return abort(404);
        }
        $metas = (object)[
            "title" => Str::title($network." $type Pacakges - Mobile Packages - MKS"),
            "description" => "Discover $network $type versatile mobile packages in Pakistan: Voice, Data, SMS, Hybrid options, plus Hourly to Monthly plans for all your communication needs",
            "canonical" => url()->current(),
            "h1" => $network." $type Packages"
        ];

        return view("frontend.packages.package",compact("packages","metas","network"));
    }
    public function showNetworkValidityPackages($country_code,$network,$type,$validity=null,$package=null){
        $packages = new Package();
        if($network){
            $packages = $packages->where("filter_network",$network);
        }
        if($type){
            $packages = $packages->where("type",($type == "prepaid") ? 0 : 1);
        }
        
        if($validity){
            $packages = $packages->where("filter_validity",$validity);
        }
        if($package){
            $packages = $packages->where("filter_type",$package);
        }
        $packages = $packages->get();
        
        if($packages->isEmpty()){
            return abort(404);
        }
        $metas = (object)[
            "title" => Str::title($network." $type, $validity, $package Pacakges - MKS"),
            "description" => $network." $type packages, $validity packages, $package packages. Find all network Packages in one place - Mobile ki shop",
            "canonical" => url()->current(),
            "h1" => $network." $type $validity $package Packages"
        ];

        return view("frontend.packages.index",compact("packages","metas","network"));   
    }
}
