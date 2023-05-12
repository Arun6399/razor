<?php defined('BASEPATH') OR exit('No direct script access allowed');

class tron_wallet_model extends CI_Model { 
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
		// print_r($wallet_row);exit();
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
		curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:18099/wallet/validateaddress');
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



	public function getBalance($address)
{

	// echo $address;

		$ch = curl_init();
		$postdata = json_encode(array("address"=>$address,"visible"=>true));
		curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:18099/wallet/getaccount'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);  
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postdata);

		    $headers = array();
		    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		    $result = curl_exec($ch);

		    if (curl_errno($ch)) {
		    	echo "Error - Test...";
		    echo 'Error:' . curl_error($ch);
				exit();
			}

		    $resp = json_decode($result,true);

		    // echo "Tron Wallet Model File..";
		    // echo "<br>";
		    // print_r($resp);  
		    // exit(); 

    return $resp['balance'] / 1000000;
    
}

	public function createaddress($user_id)
	{
		
		/*$ch = curl_init();
		$postdata = json_encode(array("value"=>$user_id));
		curl_setopt($ch, CURLOPT_URL, 'http://'.$this->wallet_ip.":".$this->wallet_port.'/wallet/createaddress');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		$resp = json_decode($result,true);

		//$address = $resp['base58checkAddress'];

		return $resp;*/

		$ch = curl_init();
          $params = array(
                "method" => "createaddress"
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
        $resp = json_decode($result,true);
        if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $resp;
        /*$result_get = json_decode($result,true);
        if($result_get['status'])
        {
        	return $result_get['result'];
        }*/
	}

	
	public function listtransactions($user_trans_res)
	{

		$address_list     = $user_trans_res['address_list'];

		$return_trans = array();
		foreach ($address_list as $key => $value) 
		{
			$user_address = $value['address'];
            $get_trans_url = 'https://api.trongrid.io/v1/accounts/'.$key.'/transactions?only_confirmed=true&only_to=true';
            /*echo $get_trans_url;
            echo "<br>";*/
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$get_trans_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$trans = curl_exec($ch);
			curl_close($ch);
			$transactions = json_decode($trans,true);

			foreach($transactions as $transt)
	        {
	            
	            foreach($transt as $sub)
	            {
	                $raw_data = $sub['raw_data'];
	                $tx_id = $sub['txID'];
	                $time = $sub['block_timestamp'];
	                
	               foreach($raw_data as $ls)
	               {
	                
	                foreach($ls as $t)
	                {
	                    //print_r($t['parameter']);
	                    
	                    
	                    if($t['type']=='TransferContract')
	                    {
	                        
	                        $result = $t['parameter'];
	                        if(count($result) >0 && !empty($result))
	                        {
	                            foreach($result as $value)
	                            {
	                               if($value['amount'] !="" && $value['amount'] != 0)
	                               {
	                               	$this->email_column = 'user_email';
						            $acc_owner = $address_list[$key][$this->email_column];
	                                $trans_arr = array(
	                                                    'account'        => $acc_owner,
	                                                    'address'        => $user_address,
	                                                    'category'       => 'receive',
	                                                    'amount'         => $value['amount'] / 1000000,
	                                                    'blockhash'      => $tx_id,
	                                                    'confirmations'  => 1,
	                                                    'txid'           => $tx_id,
	                                                    'time'           => $time,
	                                                    'from'           => $value['owner_address']
	                                                );
	                                  
	                                  array_push($return_trans,$trans_arr);

	                               }
	                                
	                                

	                            }

	                        }
	                        
	                        
	                    }
	                }
	               }
	            }
	         }
		}

		return $return_trans;
	}

	
	public function createtransaction($transaction)
	{
		$ch = curl_init();
          $params = array(
                "method" => "createtransfer",
                "privateKey" => $transaction['privateKey'],
                "amount" => $transaction['amount'],
                "fromAddress" => $transaction['fromAddress'],
                "to" => $transaction['toAddress']
            );
        // print_r($params); exit;
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

        return $resp['txid'];
	}

} // end of class
