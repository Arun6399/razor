<?php defined('BASEPATH') OR exit('No direct script access allowed');

class token_tron_wallet_model extends CI_Model { 
	/*
	* Mainly Source From : https://en.bitcoin.it/wiki/Original_Bitcoin_client/API_calls_list
	* Rpc test site : https://chainquery.com/bitcoin-api/
	* Bolg about Bitcoin : http://bitzuma.com/posts/bitcoin-think-of-it-as-electronic-cash/
	*/
	public function __construct() 
	{
		parent::__construct();	
		$tables = wallet_table();		
		$wallet_whr 	   = array('type'=>'tron','con_type'=>'node','status'=>'Active');
		$wallet_row        = $this->db->where($wallet_whr)->get($tables)->row();// fetch bitcoin wallet credentials

		if(!empty($wallet_row)){
		
			$this->wallet_port = $wallet_portnumber = decryptIt($wallet_row->portnumber);
			
			$this->wallet_ip   = $wallet_allow_ip   = decryptIt($wallet_row->allow_ip);

		}
		else
		die(json_encode(array('status'=>'error','message'=>'Security Error')));
	} 

	public function index()
	{
		die(json_encode(array('status'=>'error','message'=>'Security Error')));
	}

	public function isAddress($address)
	{
		$ch = curl_init();
		$postdata = json_encode(array("address"=>$address,"visible"=>true));
		curl_setopt($ch, CURLOPT_URL, 'http://'.$this->wallet_ip.":".$this->wallet_port.'/wallet/validateaddress');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch); 
		$resp = json_decode($result,true);
		if($resp['result']==1)
		{
			$isvalid = 1;
		}
		else
		{
			$isvalid = 0;
		}
		return array('isvalid'=>$isvalid,'data'=>$address);	
	}

	public function getBalance($transaction)
	{
		$ch = curl_init();
		// echo "TRANSACTION";
		// print_r($transaction); 
          $params = array(
                "method" => "tokenbalance",
                "privateKey" => $transaction['privateKey'],
                "contract_address" => trim($transaction['contract_address']),
                "address" => $transaction['address']
            );
        //echo "PARAMSSSSSS";
			// print_r($params);
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:7003");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $headers = array();
        $headers[] = "Content-Type : application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $resp = json_decode($result,true);
        if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
		// echo "BALANCEEEEE";
		// print_r($resp['result']); 
		//exit;
        return $resp['result'] / $transaction['decimal_place'];
	}
	
	public function listtransactions($user_trans_res,$contract_address='')
	{
		// echo "USer Trans";
		// print_r($user_trans_res);
		// echo "Contract Add".$contract_address;
		// echo "<br>";
		$address_list     = $user_trans_res['address_list'];
		// print_r($address_list); exit;
        $coin_decimal = $user_trans_res['currency_decimal'];
        $decimal_places = coin_decimal($coin_decimal);
      

		$return_trans = array();
		foreach ($address_list as $key => $value) 
		{ 
            $get_trans_url = 'https://api.trongrid.io/v1/accounts/'.$key.'/transactions/trc20?contract_address='.$contract_address;
           //echo $get_trans_url;
            // echo "<br>"; exit;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$get_trans_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$trans = curl_exec($ch);
			curl_close($ch);
			$transactions = json_decode($trans,true);
	        $data = $transactions['data'];
	        //echo "<pre>";
	        /*print_r($data);*/
	        foreach($data as $trans)
	        {
	            
	            $tx_id = $trans['transaction_id'];
	    
	            $amount = $trans['value'];

	            $to = $trans['to'];
	         
	            $from = $trans['from'];
	            
	            $time = $trans['block_timestamp'];

	            $this->email_column = 'user_email';
				$acc_owner     = $address_list[$key][$this->email_column];
				
				if(strtolower($value['address'])==strtolower($to))
				{
					$cat_sat = 'receive';
				}
				else
				{
					$cat_sat = 'send';
				}
	            $trans_arr = array(
	                                'account'        => $acc_owner,
	                                'address'        => $to,
	                                'category'       => $cat_sat,
	                                'amount'         => $amount / $decimal_places,
	                                'blockhash'      => $tx_id,
	                                'confirmations'  => 1,
	                                'txid'           => $tx_id,
	                                'time'           => $time,
	                                'from'           => $from
	                            );
	           // print_r($trans_arr);
	              
	              array_push($return_trans,$trans_arr);
	
	                             
	        }

		}
		//print_r($return_trans);

		return $return_trans;
	}

	
	public function createtransaction($transaction,$contract_address)
	{
		/*$ch = curl_init();
        $to_address = $transaction['to_address'];
        $amount = $transaction['amount'];
		$parameter = $this->encode_params($to_address,$amount);
		$owner_address = $transaction['owner_address'];
		$ch = curl_init();
	    $postdata = json_encode(array("contract_address"=>$contract_address,"function_selector"=>'transfer(address,uint256)',"parameter"=>$parameter,"owner_address"=>$owner_address,"fee_limit" => 1000000,"call_value"=>10));
	    //print_r($postdata); exit;
		curl_setopt($ch, CURLOPT_URL, 'http://'.$this->wallet_ip.":".$this->wallet_port.'/wallet/triggersmartcontract');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

		$headers = array();
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		echo '<pre>';print_r(json_decode($result));
		exit;
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);*/

		$ch = curl_init();
          $params = array(
                "method" => "tokentransfer",
                "privateKey" => $transaction['privateKey'],
                "amount" => $transaction['amount'],
                "to" => $transaction['to_address'],
                "contract_address" => $contract_address,
                "owner_address" => $transaction['owner_address']
            );
        //print_r($params); exit;
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:7003");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $headers = array();
        $headers[] = "Content-Type : application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);

        $resp = json_decode($result,true);
        /*echo "<pre>";
        print_r($resp);*/
        if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return $resp['result'];
	}


	public function encode_params($address,$amount)
	{
		$ch = curl_init();
          $params = array(
                "method" => "encode",
                "address" => $address,
                "amount" => $amount
            );
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:7003");
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
        $result_get = json_decode($result,true);
        if($result_get['status'])
        {
        	return $result_get['result'];
        }
        
	}

} // end of class
