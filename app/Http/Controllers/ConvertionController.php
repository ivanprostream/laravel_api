<?php

namespace App\Http\Controllers;

use App\Models\Setting;

use App\Http\Requests\ConvertionStoreRequest;
use Illuminate\Http\Request;

class ConvertionController extends Controller
{
    /**
	 *
	 * Save BTN rate
	 *
	 */

	function saveBtcRate(ConvertionSaveBtsRequest $request)
	{

        $currency = $request->currency;

	    $arrContextOptions=array(
	        "ssl"=>array(
	            "verify_peer"=>false,
	            "verify_peer_name"=>false,
	        ),
	    );  

	    $url  = "https://bitpay.com/api/rates";
	    $data = file_get_contents($url, false, stream_context_create($arrContextOptions));
	    $json = json_decode($data, true);
	    foreach ($json as $value) {
	        if($value['code'] == $currency){

	        	$conversion = Setting::first();
		        $conversion->conversion = $value['rate'];
			    $conversion->save();
	          
	        }
	    }

	    return response()->json([
            'message' => 'Convertion rate updated'
        ], 200);
	}
}
