<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ripple_wallet_model extends CI_Model {  
	/*
	* Mainly Source From : https://xrpl.org/rippleapi-reference.html
	*/

	public function getnewaddress()
	{
		
		$ch = curl_init();
          $params = array(
                "method" => "getnewaddress"
            );
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:7001");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $headers = array();
        $headers[] = "Content-Type : application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
   

        return json_decode($result);
	}

	 public function get_user_list_coin()
    {
        $users = $this->common_model->getTableData('users', array('verified' => 1), 'id')->result();
        $rude = array();
        foreach ($users as $user) { //echo $user->id; echo "<br>";
            $wallet = unserialize($this->common_model->getTableData('crypto_address', array('user_id' => $user->id), 'address')->row('address'));
            //echo "hai"; echo "<br>";
            //echo "<pre>";print_r($wallet); exit;
            $email = getUserEmail($user->id);
            $currency = $this->common_model->getTableData('currency', array('status' => 1, 'type' => 'digital'))->result();
            //echo "<pre>";print_r($currency); exit;
            $i = 0;
            foreach ($currency as $cu) {
                // echo "<pre>";print_r($cu); echo "<br>";
                if($cu->currency_name == "Ripple")
                {
                	 if (($wallet[$cu->id] != '') || ($wallet[$cu->id] != 0)) {
	                    $balance[$user->id][$i] = array('currency_symbol' => $cu->currency_symbol,
	                        'currency_name' => $cu->currency_name,
	                        'currency_id' => $cu->id,
	                        'address' => $wallet[$cu->id],
	                        'user_id' => $user->id,
	                        'user_email' => $email);
	                    array_push($rude, $balance[$user->id][$i]);
	                }
                }
               
                $i++;
            }
        } //exit;
        return $rude;
    }

	public function listalltransactions($user_trans_res)
	{


		echo "<pre>";

		//print_r($user_trans_res);

		$address_list     = $user_trans_res['address_list'];
		$transactionIds   = $user_trans_res['transactionIds'];

		//$address_list  = $this->get_user_list_coin();
		$return_trans = array();

		$trans_det = "";

		$address = "";

		$ii = 1;

		//print_r($address_list); 
		foreach ($address_list as $key => $values) {
   
				$address .= $values['address'];
			

		}
	

				echo $address  = $address;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://data.ripple.com/v2/accounts/'.$address.'/transactions?type=Payment&result=tesSUCCESS');

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

				$result = curl_exec($ch);
			
				$trans = json_decode($result);	
			
				foreach ($trans->transactions as $key => $value) 
				{

					$tx_hash = $value->hash;
				
				    $transs = json_decode(json_encode($value->tx),true);
				 
					$acc_address   = $transs['Destination'];
					$rec_amount    = $transs['Amount']/1000000;
					$confirmations = "";
					$time          = "";
					$txid = $tx_hash;
					$DestinationTag  = $transs['DestinationTag'];

					$get_userid_det = $this->common_model->getTableData('crypto_address',array('payment_id'=>$DestinationTag))->row();

					if($acc_address == $address && $get_userid_det->payment_id == $DestinationTag)
					{
						$a = 1;
						$trans_arr = array(
						           
			            'address'		 => $acc_address,
			            'category'		 => "receive",
			            'amount'		 => $rec_amount,
			            'confirmations'	 => "1",
			            'txid'		 	 => $txid,
			            'destinationtag' => $DestinationTag,
			            'user_id' => $get_userid_det->user_id,
			            'time' => $DestinationTag,
			            'from_address'=> $transs['Account'],
			            'blockhash'=> $txid
			          
			        	);
			            array_push($return_trans,$trans_arr);
				  }
	   
						
					$a++;			
				}
				if (curl_errno($ch)) {
				    echo 'Error:' . curl_error($ch);
				}
				curl_close($ch);

	
		// print_r($return_trans);
		// exit;
		return $return_trans;
	}

	public function sendfrom($fromacc,$toaddress,$amount,$tagid,$destag,$secret)
	{

		$ch = curl_init();
          $params = array(
          	    "sendaddress" => $fromacc,
                "method" => "sendamount",
                "address" => $toaddress,
                "amount" => $amount,
                "tag_id" => $tagid,
                "destag" =>$destag,
                "secret"=> $secret
            );

         // print_r($params);
         //  exit();
         
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:7001");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $headers = array();
        $headers[] = "Content-Type : application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $result_get = json_decode($result);
      // echo "test";

        /*print_r($result_get);
        exit();*/
        $results = json_decode(json_encode($result_get->result),true);
        if($results['resultCode'] == "tesSUCCESS")
        {
        	$result  = $results['tx_json']['hash'];
        }
        else
        {
        	$result  = "error";
        }
        return $result;
	}

	public function validateaddress($address)
	{
		$ch = curl_init();
          $params = array(    
                "method" => "validateaddress",
                "address" => $address
                
            );

        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:7001");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $headers = array();
        $headers[] = "Content-Type : application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);    
        $result_get = json_decode($result);
        $result = $result_get->result;
 	
        return $result;
	}

	public function get_xrp_balance($xrp_address)
	{

		$url = "https://data.ripple.com/v2/accounts/" . $xrp_address . "/balances";
		$cObj = curl_init();
		curl_setopt($cObj, CURLOPT_URL, $url);
		curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
		$curlinfos = curl_getinfo($cObj);
		
		$result = curl_exec($cObj);
		$return_trans = json_decode($result);
		if (curl_errno($cObj)) {
			echo 'Error:' . curl_error($cObj);
		}
		curl_close($cObj);		
		if($return_trans->result == 'error')
		{
			return 0;
		}
			
		return $return_trans->balances[0]->value;
	}

	public function get_wallet_balance()
	{
		
		$xrp_address = $this->admin_address;
		
		$url = "https://data.ripple.com/v2/accounts/" . $xrp_address . "/balances?currency=XRP";
		$cObj = curl_init();
		curl_setopt($cObj, CURLOPT_URL, $url);
		curl_setopt($cObj, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($cObj, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($cObj, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($cObj);
		$curlinfos = curl_getinfo($cObj);

		$result = json_decode($output);
		$res = $result->result;
		$xrp_bal1 = 0;
		if ($res == 'success') 
		{
			$xrp_bal = $result->balances;
			$xrp_bal1 = $xrp_bal[0]->value;
		}
		//echo "<br> -----****--- <br>";
		//print_r($xrp_bal1);exit;
		return $xrp_bal1;
	}

	

} // end of class
