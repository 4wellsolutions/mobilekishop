<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use Response;

class FinanceController extends Controller
{
	public function index(){
		$metas = (object)[
            "title" => "Mobile Phone Installment Calculator - Mobile Ki Shop",
            "description" => "Looking to calculate the mobile phone installments (EMI) across different banks in Pakistan? Try out Mobile Ki Shop's installment calculator tool.",
            "canonical" => "https://mobilekishop.net/mobile-installment-calculator",
            "h1" => "Mobile Phones Specifications, Price in Pakistan",
            "name" => "installments"
        ];
		return view("frontend.tools.installments",compact("metas"));
	}
	public function postInstallments(Request $request){
		$validator = Validator::make($request->all(), [
            "product_id" 	=> "required",
        ],[
   			"product_id.required" => "Please select product"
        ]);
        if ($validator->fails()) {
        	return Response::make(['success' => false, "errors" =>  $validator->errors()]);
   		} 
   		$product = Product::find($request->product_id);
   		
   		$price = $product->price_in_pkr;
		$banks = ['askari','alfalah','faysal','mcb','scb','silk','ubl'];
		$data = []; // Ensure $data is initialized as an empty array.
		foreach($banks as $bank){
		    // Correctly append each new associative array to $data.
		    $data[] = [
		        "bank" => $bank,
		        "installments" => $this->calculateInstallment($bank, $price)
		    ];

		}

		return view("frontend.tools.installments_results",compact("data"));
	}
	public function getProductsByBrand(Request $request){
	    if(!$request->brand_id){
	    	return Response::make(["success" => false, ["error" => "Select Brand"]]);
	    }
	    // $products = Product::where("brand_id",$request->brand_id)->get();
	    $products = Product::where('brand_id', $request->brand_id)
                ->orderByRaw('LENGTH(name) ASC')
                ->get();
		
        if(!$products->isNotEmpty()){
        	return Response::make(["success" => false, ["error" => "No mobile found"]]);	
        }
	    return Response::make(["success" => true, "products" => $products]);
    }
    public static function calculateAllBanksInstallments($amount) {
	    $banks = ['askari', 'alfalah', 'faysal', 'mcb', 'scb', 'silk', 'ubl'];
	    $allInstallments = [];
	    foreach ($banks as $bank) {
	        $allInstallments[$bank] = self::calculateInstallment($bank, $amount);
	    }
	    
	    return $allInstallments;
	}

    public static function calculateInstallment($bankName, $amount) {
	    $finance = new self(); // Instantiate the finance object once per bank.
	    $amount = (int) $amount; // Ensure the amount is an integer.

	    $tenures = [];
	    $percentFee = "";
	    $fixedFee = "";
	    $processFeeList = "";
	    
	    switch ($bankName) {
	        case 'askari':
	            $tenures = [3, 6, 12, 18, 24];
	            $percentFee = "3.5";
	            $fixedFee = "1400";
	            $processFeeList = '<li class="list-group-item fs-14">One time upfront payment</li><li class="list-group-item fs-14">Higher of 3.5% or Rs. 1,400/- + FED</li><li class="list-group-item fs-14">Please ensure Name and CNIC of the cardholder.</li>';
	            break;
	        case 'alfalah':
	            $tenures = [3, 6, 12, 18, 24, 36];
	            $percentFee = "5";
	            $fixedFee = "1000";
	            $processFeeList = '<li class="list-group-item fs-14">One time upfront payment</li><li class="list-group-item fs-14">3 months: A processing fee of 5% or Rs 1000 ( whichever is higher) will be applicable per transaction amount plus FED.</li><li class="list-group-item fs-14">6 months and onwards: A processing fee of 2.5% or Rs 1000 ( whichever is higher) will be applicable per transaction amount plus FED.</li><li class="list-group-item fs-14">Please ensure Name and CNIC of the cardholder.</li>';
	            break;
	        case 'faysal':
	            $tenures = [3, 6, 12, 18, 24, 36];
	            $percentFee = "5";
	            $fixedFee = "1000";
	            $processFeeList = '<li class="list-group-item fs-14">One time upfront payment</li><li class="list-group-item fs-14">All tenures of Rs.800 + FED</li><li class="list-group-item fs-14">Please ensure Name and CNIC of the cardholder.</li>';
	            break;
	        case 'mcb':
	            $tenures = [3, 6, 12, 18, 24, 36];
	            $percentFee = "5";
	            $fixedFee = "1000";
	            $processFeeList = '<li class="list-group-item fs-14">One time upfront payment</li><li class="list-group-item fs-14">3.0% or PKR 700, whichever is higher</li><li class="list-group-item fs-14">Please ensure Name and CNIC of the cardholder.</li>';
	            break;
	        case 'scb':
	            $tenures = [3, 6, 12];
	            $percentFee = "5";
	            $fixedFee = "1000";
	            $processFeeList = '<li class="list-group-item fs-14">One time upfront payment</li><li class="list-group-item fs-14">No processing fee for 3 months. For 6 & 12 months tenures: Conventional Cards: 2.5% 6 Months, 7% 12 Months. Saadiq Cards: 6 & 12 month tenures Rs.9,500/- fixed</li><li class="list-group-item fs-14">Please ensure Name and CNIC of the cardholder.</li>';
	            break;
	        case 'silk':
	            $tenures = [3, 6, 12, 18, 24, 36];
	            $percentFee = "5";
	            $fixedFee = "1000";
	            $processFeeList = '<li class="list-group-item fs-14">One time upfront payment</li><li class="list-group-item fs-14">All tenures higher of 1.5% or Rs.1,500 + FED</li><li class="list-group-item fs-14">Please ensure Name and CNIC of the cardholder.</li>';
	            break;
	        case 'ubl':
	            $tenures = [3, 6, 12];
	            $percentFee = "5";
	            $fixedFee = "1000";
	            $processFeeList = '<li class="list-group-item fs-14">One time upfront payment</li><li class="list-group-item fs-14">All tenures higher of 1.55% or Rs.600 + FED</li><li class="list-group-item fs-14">Please ensure Name and CNIC of the cardholder.</li>';
	            break;
	        default:
	            $tenures = [3,6,9,12]; 
	            break;
	    }
	    
	    $installments = [];

	    foreach ($tenures as $tenure) {
	        // Calculate the monthly markup based on the bank and tenure.
	        switch ($bankName) {
	            case 'askari':
	                $monthlyMarkup = $finance->getAskariMarkup($tenure);
	                $upfrontFee 	= $finance->calculateAskariUpfrontFee($amount,"0.16");
	                break;
	            case 'alfalah':
	                $monthlyMarkup 	= $finance->getAlfalahMarkup($tenure);
	                $upfrontFee 	= $finance->calculateAlfalahUpfrontFee($amount,$tenure, "0.05");
	                break;
	            case 'faysal':
	                $monthlyMarkup 	= $finance->getFaysalMarkup($tenure);
	                $upfrontFee 	= $finance->calculateFaysalBankUpfrontFee($amount,"0.05");
	                break;
	            case 'mcb':
	                $monthlyMarkup 	= $finance->getMCBMarkup($tenure);
	                $upfrontFee 	= $finance->calculateMCBUpfrontFee($amount);
	                break;
	            case 'scb':
	                $monthlyMarkup 	= 0;
	                $upfrontFee 	= $finance->calculateSCBUpfrontFee($amount,$tenure,"conventional");
	                break;
	            case 'silk':
	                $monthlyMarkup 	= $finance->getSilkMarkup($tenure);
	                $upfrontFee 	= $finance->calculateSilkBankUpfrontFee($amount,"0.05");
	                break;
	            case 'ubl':
	                $monthlyMarkup 	= $finance->getUBLMarkup($tenure);
	                $upfrontFee 	= $finance->calculateUBLUpfrontFee($amount,"0.16");
	                break;
	        }
	        
	        $monthlyInstallment = $finance->calculateInstallmentAmount($amount, $tenure, $monthlyMarkup);

	        // Append the calculated values for each tenure to the installments array.
	        $installments[] = (object)[
	            'tenure' => $tenure,
	            'monthly_markup' => $monthlyMarkup,
	            'monthly_installment' => number_format($monthlyInstallment),
	            'upfront_fee' => number_format($upfrontFee,2),
	            'process_fee_list' => $processFeeList,
	            'total' => number_format(($monthlyInstallment*$tenure) + $upfrontFee),
	        ];
	    }
	    return $installments;
    }
     private function calculateInstallmentAmount($amount, $tenure, $monthlyMarkup)
    {
        return round($amount * (1 + ($monthlyMarkup / 100) * $tenure) / $tenure,0);
    }
    public function calculateAlfalahUpfrontFee($amount, $tenure, $fedRate) {
	    // Determine the processing fee rate based on the tenure
	    $processingFeeRate = $tenure == 3 ? 0.05 : 0.025; // 5% for 3 months, 2.5% for 6 months and onwards
	    
	    // Calculate the processing fee as the higher of the percentage of the amount or Rs. 1,000
	    $processingFee = max($processingFeeRate * $amount, 1000);
	    
	    // Calculate FED on the amount
	    $fed = $amount * $fedRate;
	    
	    // Total upfront fee is the sum of the processing fee and FED
	    $totalUpfrontFee = $processingFee + $fed;
	    
	    return round($totalUpfrontFee, 2); // Rounding for currency formatting
	}
	public function calculateFaysalBankUpfrontFee() {
	    $fixedFee = 800; // Fixed fee of Rs.800
	    $gstRate = 0.16; // 16% GST

	    // Calculate GST on the fixed fee
	    $gst = $fixedFee * $gstRate;
	    
	    // Total upfront fee is the sum of the fixed fee and GST
	    $totalUpfrontFee = $fixedFee + $gst;
	    
	    return round($totalUpfrontFee, 2); // Rounding for currency formatting
	}
	public function calculateAskariUpfrontFee($amount, $fedRate) {
	    $percentageFee = 0.035 * $amount; // 3.5% of the transaction amount
	    $fixedFee = 1400; // Rs. 1,400 fixed fee
	    
	    // Choose the higher of the percentage fee or the fixed fee
	    $higherFee = max($percentageFee, $fixedFee);
	    
	    // Calculate FED on the higher fee
	    $fed = $higherFee * $fedRate;
	    
	    // Total upfront fee is the sum of the higher fee and FED
	    $totalUpfrontFee = $higherFee + $fed;
	    
	    return round($totalUpfrontFee, 2); // Rounding for currency formatting
	}
	public function calculateMCBUpfrontFee($amount) {
	    $percentageFee = 0.03 * $amount; // 3.0% of the transaction amount
	    $fixedFee = 700; // PKR 700 fixed fee
	    
	    // Determine the higher of the percentage fee or the fixed fee
	    $higherFee = max($percentageFee, $fixedFee);
	    
	    return round($higherFee, 2); // Rounding for currency formatting
	}
    public function calculateSilkBankUpfrontFee($amount, $fedRate) {
	    // Calculate the basic fee as the higher of 1.5% of the amount or Rs.1,500
	    $basicFee = max(0.015 * $amount, 1500);
	    
	    // Calculate FED on the basic fee
	    $fed = $basicFee * $fedRate;
	    
	    // Total upfront fee is the sum of the basic fee and FED
	    $totalUpfrontFee = $basicFee + $fed;
	    
	    return round($totalUpfrontFee, 2); // Rounding to 2 decimal places for currency formatting
	}
    // Calculate the upfront fee based on the provided percentage or fixed amount
    public function calculateSCBUpfrontFee($amount, $tenure, $cardType) {
	    // No processing fee for 3 months tenure
	    if ($tenure == 3) {
	        return 0;
	    }
	    
	    // Processing fees for Conventional Cards
	    if ($cardType == 'conventional') {
	        if ($tenure == 6) {
	            return 0.025 * $amount; // 2.5% of the amount
	        } elseif ($tenure == 12) {
	            return 0.07 * $amount; // 7% of the amount
	        }
	    }
	    
	    // Fixed processing fee for Saadiq Cards for 6 & 12 months tenures
	    if ($cardType == 'saadiq' && ($tenure == 6 || $tenure == 12)) {
	        return 9500; // Fixed amount
	    }
	    
	    // Default to 0 if none of the conditions are met
	    return 0;
	}
	public function calculateUBLUpfrontFee($amount, $fedRate) {
	    $percentageFee = 0.0155 * $amount; // 1.55% of the transaction amount
	    $fixedFee = 600; // Rs. 600 fixed fee
	    
	    // Determine the higher of the percentage fee or the fixed fee
	    $baseFee = max($percentageFee, $fixedFee);
	    
	    // Calculate FED on the base fee
	    $fed = $baseFee * $fedRate;
	    
	    // Total upfront fee is the sum of the base fee and FED
	    $totalUpfrontFee = $baseFee + $fed;
	    
	    return round($totalUpfrontFee, 2); // Rounding for currency formatting
	}
    // Get the markup percentage for Askari Bank
    private function getAskariMarkup($tenure){
        switch ($tenure) {
            case 3: return 2.00;
            case 6: return 1.75;
            case 12: return 1.63;
            case 18: return 1.58;
            case 24: return 1.57;
            default: return 0;
        }
    }
    private function getAlfalahMarkup($tenure){
        if ($tenure <= 3) return 0.00;
	    if ($tenure <= 24) return 1.85; // Up to 12 months
	    return 1.86; // Beyond 12 months
    }
    private function getFaysalMarkup($tenure) {
	    return $tenure > 3 ? 2.00 : 0.00; // 2% markup for tenures beyond 3 months
	}
	private function getMCBMarkup($tenure) {
	    switch ($tenure) {
            case 3: return 0.00;
            case 6: return 1.95;
            case 12: return 1.86;
            case 18: return 1.87;
            case 24: return 1.90;
            case 36: return 1.93;
            default: return 0;
        }
	    return 1.93; // Beyond 24 months
	}
	private function getUBLMarkup($tenure) {
	    if ($tenure == 3) return 1.62;
	    if ($tenure == 6) return 1.44;
	    return 1.37; // For 12 months, assuming UBL doesn't offer beyond 12 months in this example
	}
	private function getSilkMarkup($tenure) {
	    return $tenure > 3 ? 2.00 : 0.00;
	}
}
