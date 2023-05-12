<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'json-rpc.php';

class Coinpayments_wallet_model extends CI_Model {  
	/*
	* Mainly Source From : https://github.com/ethereum/wiki/wiki/JSON-RPC
	*/
	protected $id = 0;
	public function __construct() 
	{
		
	} 

	public function index()
	{
		die(json_encode(array('status'=>'error','message'=>'Security Error')));
	}

	

function listalltransactions($user_trans_res, $coin='') 
	{

		if($coin=='doge') {
		  
		 echo " List All Transactions ";    

		$address_list  =  $user_trans_res['address_list'];
        $coin_decimal = $user_trans_res['currency_decimal'];
		$decimal_places = coin_decimal($coin_decimal);
        $return_trans = array();
		foreach ($address_list as $key => $value) {




	
		$address  = $value['address'];
		   	$url = 'http://api.blockcypher.com/v1/'.$coin.'/main/addrs/'.$address.'?token=61f23adf95c8403881215ed767943344';	
	    $curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false),
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "postman-token: edd91264-2b65-b493-9f4d-ff42784e3e66"
		  ),
		));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl); 
	if ($err) { 
	  echo "cURL Error #:" . $err; 
	} else {
	  // echo $response;
	}

	

	$res= json_decode($response, true);
  	// $hash = $res['txrefs'][0]['tx_hash'];

  	foreach ($res['txrefs'] as $hash_det) {	

  

  	$hash = $hash_det['tx_hash'];	
	if(isset($hash))
	{ 



			$url = 'https://api.blockcypher.com/v1/'.$coin.'/main/txs/'.$hash.'?token=61f23adf95c8403881215ed767943344';	
			$curls = curl_init();
			curl_setopt_array($curls, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			curl_setopt($curls, CURLOPT_SSL_VERIFYPEER, false),
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache",
			    "postman-token: edd91264-2b65-b493-9f4d-ff42784e3e66"
			  ),
			));	

			$depositresponse = curl_exec($curls);
			$err = curl_error($curls);
			curl_close($curls); 
			$depositres= json_decode($depositresponse, true);

			$blockhash = $depositres['hash'];
			$value=$depositres['outputs'][1]['value']/100000000;
		  	$amount=number_format($value, 8, '.', '');
			
			$recv_address = $depositres['outputs'][1]['addresses'][0];


			
			// echo " USER ADDR ".$address;
			// echo "<br>";

			// echo " RECV ADDR ".$recv_address;
			// echo "<br>";


					// foreach ($trans->result as $trans_key => $trans_value) {
						//echo "AMT".$trans_value->value;
						$acc_address   = $recv_address;
						$rec_amount    = $amount;
						//$rec_amount    = $trans_value->value;
						$confirmations = $hash_det['confirmations'];
						$time          = $hash_det['confirmed'];
						$txid          = $blockhash;
						$blockHash     = $blockhash;
						$hash          = $blockhash;

						$this->email_column = 'user_email';
						$acc_owner     = $address_list[$key][$this->email_column];

						if(strtolower($address)==strtolower($recv_address))
						{
							$cat_sat = 'receive';
						}
						else
						{
							$cat_sat = 'send';
						}

						$recx_amount = $rec_amount;
						$trans_arr = array(
								            'account'		 => $acc_owner,
								            'address'		 => $acc_address,
								            'category'		 => $cat_sat,
								            'amount'		 => $recx_amount,
								            'blockhash'		 => $hash,
								            'confirmations'	 => $confirmations,
								            'txid'		 	 => $txid,
								            'time'		 	 => $time,
								        );
						array_push($return_trans,$trans_arr);
					// }
				

			}

		 }		
		
		}

			// echo '<pre>';
			// print_r($return_trans); 
			// echo '<pre>'; 	

		
		return $return_trans;
	} 
	

 }


}
// end of class