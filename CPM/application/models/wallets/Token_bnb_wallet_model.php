<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'json-rpc.php';

class Token_bnb_wallet_model extends CI_Model {  
	/*
	* Mainly Source From : https://github.com/ethereum/wiki/wiki/JSON-RPC
	*/
	protected $id = 0;
	public function __construct() 
	{
		parent::__construct();	
		$tables = wallet_table();

		$this->output 		= array();
		$this->return_var 	= -1;
		
		$wallet_whr 	   = array('type'=>'bnb','con_type'=>'node','status'=>'active');
		$wallet_row        = $this->db->where($wallet_whr)->get($tables)->row();// fetch ethereum wallet credentials
		if(!empty($wallet_row)){

			$wallet_username   = decryptIt($wallet_row->username);
			$wallet_password   = decryptIt($wallet_row->password); 
			$this->wallet_port = $wallet_portnumber = decryptIt($wallet_row->portnumber);
			$this->wallet_ip   = $wallet_allow_ip   = decryptIt($wallet_row->allow_ip); 
			$this->version	   = "2.0";

			
		}
		else
		die(json_encode(array('status'=>'error','message'=>'Security Error')));
	} 

	public function index()
	{
		die(json_encode(array('status'=>'error','message'=>'Security Error')));
	}

	public function decode_hex($input)
	{
		if(substr($input, 0, 2) == '0x')
			$input = substr($input, 2);
		
		if(preg_match('/[a-f0-9]+/', $input))
			return hexdec($input);
			
		return $input;
	}
	
	function web3_clientVersion()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function web3_sha3($input)
	{
		return $this->ether_request(__FUNCTION__, array('0x'.$this->strhex($input)));
	}

	  function strhex($string)
    {
        $hexstr = unpack('H*', $string);
       
        return array_shift($hexstr);
    }
	
	function net_version()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function net_listening()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function net_peerCount()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function eth_protocolVersion()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function eth_coinbase()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function eth_mining()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function eth_hashrate()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function eth_gasPrice()
	{
		return $this->decode_hex($this->ether_request(__FUNCTION__));
	}
	
	function eth_accounts()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function eth_blockNumber($decode_hex=FALSE)
	{
		$block = $this->ether_request(__FUNCTION__);
		
		if($decode_hex)
			$block = $this->decode_hex($block);
		
		return $block;
	}

	function eth_pendingTransactions()
	{
		$block = $this->ether_request(__FUNCTION__);
		return $block;
	}
	
	function eth_getBalance($address,$coin_symbol,$contract_address, $block='latest', $decode_hex=FALSE)
	{

		
	// echo " BNB tok Balance ";
	// echo "<br>";   
	// echo $address;
	// echo "<br>";
	// echo $contract_address;
	// echo "<br>";

		 //    $tables = wallet_table();
			// $wallet_whr 	   = array('type'=>'ethereum','con_type'=>'node','status'=>'active');
			// $wallet_row        = $this->db->where($wallet_whr)->get($tables)->row();// fetch ethereum wallet
			// //echo $this->db->last_query(); 
	        
			// $address  = $address;
		$get_trans_url='https://api.bscscan.com/api?module=account&action=tokenbalance&contractaddress='.$contract_address.'&address='.$address.'&apikey=915FYH435ABT5VABJUNFC68UWW6VYM3Q24&tag=latest';

	

		$coin_detail = getcoindetail($coin_symbol);
		$coin_decimal = $coin_detail->currency_decimal;

		$decimal_places = coin_decimal($coin_decimal);


$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$get_trans_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$trans = curl_exec($ch);
	curl_close($ch);
	//$trans = json_decode(file_get_contents($get_trans_url)); 
	$trans = json_decode($trans); 
	//echo '<pre>';print_r($trans);
				//$trans = json_decode(file_get_contents($get_trans_url),TRUE);
				$balance = $trans->result / $decimal_places;
		
		// echo "<br>";
		// echo " Bal -->  ".$trans->result;
		// echo "<br>";	

		return $balance;

		// Blockchain 

		// 1000000000000000000
		// $coin_detail = getcoindetail($coin_symbol);
		// $coin_decimal = $coin_detail->currency_decimal;

		// $decimal_places = coin_decimal($coin_decimal);
		// $decimal_places = 1000000000000000000;

		// //print_r($decimal_places); exit;
		// // echo "BNB Contrct Address".$contract_address;
		// // echo "<br/>";
		// // echo "Address".$address;
		// // echo "<br/>";
		// // exit;

	 //    $ch = curl_init();
	 //          $params = array(
	 //                "method" => "bnbbalance",
	 //                "contract_address" => $contract_address,
	 //                "address" => $address
	 //            );

	 //          //print_r($params);
	        
	 //        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:7003");
	 //        //curl_setopt($ch, CURLOPT_URL, "http://138.201.56.226:6545");
	 //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 //        curl_setopt($ch, CURLOPT_POST, 1);
	 //        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	 //        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	 //        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	 //        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

	 //        $headers = array();
	 //        $headers[] = "Content-Type : application/json";
	 //        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	 //        $result = curl_exec($ch);
	 //        //print_r($result);
	 //        $resp = json_decode($result,true);
	 //        // echo "<br>"; 
	 //        // print_r($resp['result']['_hex'])."<br/>";
	 //        // exit();

	 //        if (curl_errno($ch)) {
	 //        echo 'Error:' . curl_error($ch);
	 //        }
	 //        curl_close($ch);
  //             //exit;     
	 //        return hexdec($resp['result']['_hex']) / $decimal_places;
	        //return $resp['result'];
	}

	/*function listalltransactions($user_trans_res, $contract_address='') {
		
		$address_list  = $user_trans_res['address_list'];   
		$transactionIds = $user_trans_res['transactionIds'];

        $coin_decimal = $user_trans_res['currency_decimal'];

        $decimal_places = coin_decimal($coin_decimal);

        $Arr_Unique = array_unique(array_map(function ($i) { return $i['currency_symbol']; }, $address_list));

        $Currency_Sym = current($Arr_Unique).'-BEP20';

        $Var = array_keys($address_list);

		$return_trans = array();

$Blocks_search = $this->common_model->getTableData('blocks',array('currency'=>$Currency_Sym), 'blocks','','','','','1',array('id','DESC'))->row();

       if(isset($Blocks_search) && !empty($Blocks_search)){
        
        $old_block = $Blocks_search->blocks+1;
       }
       else{
        $old_block = $this->eth_blockNumber(TRUE);
        //$old_block = 14700217;
       }

       $current_block = $this->eth_blockNumber(TRUE);
       //$current_block=14700218;

		$signature = $this->web3_sha3('transfer(address,uint256)');
		
		$signature = substr($signature, 0, 10);
		
		for($a=$old_block;$a<=$current_block;$a++){
			;
		$blocknum = '0x'.dechex($a);
		$block = $this->eth_getBlockByNumber($blocknum);
		$block = json_encode($block);
		$block = json_decode($block,TRUE);
		$Block_Count =  count($block['transactions']) -1;

		
    $time =  str_replace('0x', '', $block['timestamp']);
    $time =  hexdec($time);
        for($j=0;$j<=$Block_Count;$j++){
        	
if($block['transactions'][$j]['to']==$contract_address){
	$Input = explode($signature, $block['transactions'][$j]['input']);
	$From_Val = $Input[1];
	//echo "<pre>";
	//print_r($block['transactions'][$j]);
	$output = str_split($From_Val, 64);
	$To_Address_1 = str_split($output[0], 24);
	$Toaddress = '0x'.$To_Address_1[1].$To_Address_1[2];
	$Val = '0x'.ltrim($output[1], '0');
            if(in_array($Toaddress, $Var)){

        $acc_address = $Toaddress;
        $rec_amount = str_replace('0x', '', $Val);
        $rec_amount = hexdec($rec_amount);

        $Contract = $block['transactions'][$j]['to'];

        $txid          = str_replace('0x', '', $block['transactions'][$j]['transactionIndex']);
        $hash          = $block['transactions'][$j]['blockHash'];
        $from          = $block['transactions'][$j]['from'];
        $this->email_column = 'user_email';
        $acc_owner     = $address_list[$Toaddress][$this->email_column];
        $confirmations = 4;

        $cat_sat = 'receive';

        $trans_arr = array(
                                        'account'         => $acc_owner,
                                        'address'         => $acc_address,
                                        'category'         => $cat_sat,
                                        'amount'         => $rec_amount/$decimal_places,
                                        'blockhash'         => $hash,
                                        'confirmations'     => $confirmations,
                                        'txid'              => $txid,
                                        'time'              => $time,
                                        'contract'			=> $Contract

                                    );

        array_push($return_trans,$trans_arr);
    }
}

		$Blocks_Count = $this->common_model->getTableData('blocks',array('currency'=>$Currency_Sym,'blocks'=>$a))->num_rows();
            if($Blocks_Count==0){
                    $Insert_Blocks = array(
                                            'currency'=>$Currency_Sym,
                                            'blocks' => $a
                                        );
                   $ins = $this->common_model->insertTableData('blocks',$Insert_Blocks);
               }

}
    
    } 

    return $return_trans;

}*/

function listalltransactions($user_trans_res, $contract_address='') 
	{
		// echo "LISSS"; 

		// echo "<pre>";
		// print_r($user_trans_res);
		// echo "<pre>";
		// exit(); 

	    $address_list  =  $user_trans_res['address_list'];
        
        $coin_decimal = $user_trans_res['currency_decimal'];

        $decimal_places = coin_decimal($coin_decimal);
        
		$return_trans = array();
		foreach ($address_list as $key => $value) {

			$tables = wallet_table();
			$wallet_whr 	   = array('type'=>'ethereum','con_type'=>'node','status'=>'active');
			$wallet_row        = $this->db->where($wallet_whr)->get($tables)->row();// fetch ethereum wallet 
	
			$address  = $value['address'];

			$get_trans_url = 'https://api.bscscan.com/api?module=account&action=tokentx&contractaddress='.$contract_address.'&address='.$address.'&apikey=915FYH435ABT5VABJUNFC68UWW6VYM3Q24&page=1&offset=100&sort=asc';

			//$trans = json_decode(file_get_contents($get_trans_url));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$get_trans_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$trans = curl_exec($ch);
			curl_close($ch);


			//$trans = json_decode(file_get_contents($get_trans_url)); 
			$trans = json_decode($trans); 
			

				
				if($trans->status == 1){
					foreach ($trans->result as $trans_key => $trans_value) {
						//echo "AMT".$trans_value->value;
						$acc_address   = $trans_value->to;
						$rec_amount    = $trans_value->value/$decimal_places;
						//$rec_amount    = $trans_value->value;
						$confirmations = $trans_value->confirmations;
						$time          = $trans_value->timeStamp;
						$txid          = $trans_value->transactionIndex;
						$blockHash     = $trans_value->blockHash;
						$hash          = $trans_value->hash;
						$blockNumber   = $trans_value->blockNumber;
						$from   	   = $trans_value->from;
						$this->email_column = 'user_email';
						$acc_owner     = $address_list[$key][$this->email_column];
						if(strtolower($value['address'])==strtolower($trans_value->to))
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
								            'from'           => $from
								        );
						array_push($return_trans,$trans_arr);
					}
				}
		
		}

		
		return $return_trans;
	}
	function eth_getStorageAt($address, $at, $block='latest')
	{
		return $this->ether_request(__FUNCTION__, array($address, $at, $block));
	}
	
	function eth_getTransactionCount($address, $block='latest', $decode_hex=FALSE)
	{
		$count = $this->ether_request(__FUNCTION__, array($address, $block));
        
        if($decode_hex)
            $count = $this->decode_hex($count);
            
        return hexdec($count);  
	}
	
	function eth_getBlockTransactionCountByHash($tx_hash)
	{
		return $this->ether_request(__FUNCTION__, array($tx_hash));
	}
	
	function eth_getBlockTransactionCountByNumber($tx='latest')
	{
		return $this->ether_request(__FUNCTION__, array($tx));
	}
	
	function eth_getUncleCountByBlockHash($block_hash)
	{
		return $this->ether_request(__FUNCTION__, array($block_hash));
	}
	
	function eth_getUncleCountByBlockNumber($block='latest')
	{
		return $this->ether_request(__FUNCTION__, array($block));
	}
	
	function eth_getCode($address, $block='latest')
	{
		return $this->ether_request(__FUNCTION__, array($address, $block));
	}
	
	function eth_sign($address, $input)
	{
		return $this->ether_request(__FUNCTION__, array($address, $input));
	}

	

 function hexstr($string)
    {
        return pack('H*', $string);
    }

	function eth_sendTransaction($transaction,$contract_address)
	{
		/*echo "Pila";exit();*/
		$data1 = array();
		$data1['params'] = array($transaction['from'],'password');
		$data1['jsonrpc'] = $this->version;
		$data1['id'] = $this->id++;
		$data1['method'] = 'personal_unlockAccount';
		// echo "<pre>";
		// print_r($transaction);
		// echo "<pre>";
		// echo "------------";
		// echo "<br/>";
		// // print_r($contract_address);
	 // exit;

		$ch1 = curl_init();
		curl_setopt($ch1, CURLOPT_URL, $this->wallet_ip);
		curl_setopt($ch1, CURLOPT_PORT, $this->wallet_port);
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		curl_setopt($ch1, CURLOPT_POST, count($data1['params']));
		curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode($data1));
		$ret1 = curl_exec($ch1);
		curl_close($ch1);



if($ret1 !== FALSE)
		{
			$formatted1 = $this->format_response($ret1);

			if(isset($formatted1->error))
			{
				throw new RPCException($formatted1->error->message, $formatted1->error->code);
				$res1 = FALSE;
			}
			else
			{
				$res1 = $formatted1->result;
			}
		}
		else{
			$res1 = FALSE;
		}

		
		if($res1){
		$data = array();
		$data['jsonrpc'] = $this->version;
		$data['id'] = $this->id++;
		$data['method'] = 'eth_sendTransaction';

		$signature = $this->web3_sha3('transfer(address,uint256)');
		$to = str_pad(substr($transaction['to'], 2), 64, '0', STR_PAD_LEFT);
		//$value = str_pad($this->bcdechex($transaction['value']), 64, '0', STR_PAD_LEFT);
		$value = str_pad($this->bcdechex(rtrim(sprintf("%.40f", $transaction['value']), ".")), 64, '0', STR_PAD_LEFT);
			/*echo $transaction['value']."<br/>";	 
			echo rtrim(sprintf("%.0f", $transaction['value']));
			exit();*/
		$transaction['value'] =  '0x0';
		$signature = substr($signature, 0, 10);
		$transaction['data'] = $signature.$to.$value;
		
		//$transaction['to'] = $transaction['contract'];
        if($contract_address!='')
		$transaction['to'] = $contract_address;
	    else
	    $transaction['to'] = $transaction['contract'];

		
		$gas = $transaction['gas'];
		$gasPrice = $transaction['gasPrice'];
		$transaction['gas'] = '0x'.dechex($gas);
		$transaction['gasPrice'] = '0x'.dechex($gasPrice);
		
		//unset($transaction['contract']);
		
		$data['params'] = array($transaction);
		// echo "<pre>";
		// print_r($data['params']);
		// echo "<pre>";
		// exit(); 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->wallet_ip);
		curl_setopt($ch, CURLOPT_PORT, $this->wallet_port);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		curl_setopt($ch, CURLOPT_POST, count($transaction));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$ret = curl_exec($ch);
		curl_close($ch);
		
		

		if($ret !== FALSE)
		{
			$formatted = $this->format_response($ret);
			
			if(isset($formatted->error))
			{
				throw new RPCException($formatted->error->message, $formatted->error->code);
			}
			else
			{
				return $formatted->result;
			}
		}
		else
		{
			throw new RPCException("Server did not respond");
		}

        }

	}

	 function bcdechex($dec)
    {
        $hex = '';
        do {
            $last = bcmod($dec, 16);
            $hex = dechex($last).$hex;
            $dec = bcdiv(bcsub($dec, $last), 16);
        } while($dec > 0);
        return $hex; 
    }

   /* function toWei(float $value, int $decimals = 18)
    {
        $brokenNumber = explode('.', $value);
        return number_format($brokenNumber[0]).''.str_pad($brokenNumber[1] ?? '0', $decimals, '0');
    }*/
	
	function eth_call($message, $block)
	{
		if(!is_a($message, 'Ethereum_Message'))
		{
			throw new ErrorException('Message object expected');
		}
		else
		{
			return $this->ether_request(__FUNCTION__, $message->toArray());
		}
	}
	
	function eth_estimateGas($message, $block)
	{
		if(!is_a($message, 'Ethereum_Message'))
		{
			throw new ErrorException('Message object expected');
		}
		else
		{
			return $this->ether_request(__FUNCTION__, $message->toArray());
		}
	}
	
	function eth_getBlockByHash($hash, $full_tx=TRUE)
	{
		return $this->ether_request(__FUNCTION__, array($hash, $full_tx));
	}
	
	function eth_getBlockByNumber($block='latest', $full_tx=TRUE)
	{
		return $this->ether_request(__FUNCTION__, array($block, $full_tx));
	}
	
	function eth_getTransactionByHash($hash)
	{
		return $this->ether_request(__FUNCTION__, array($hash));
	}
	
	function eth_getTransactionByBlockHashAndIndex($hash, $index)
	{
		return $this->ether_request(__FUNCTION__, array($hash, $index));
	}
	
	function eth_getTransactionByBlockNumberAndIndex($block, $index)
	{
		return $this->ether_request(__FUNCTION__, array($block, $index));
	}
	
	function eth_getTransactionReceipt($tx_hash)
	{
		return $this->ether_request(__FUNCTION__, array($tx_hash));
	}
	
	function eth_getUncleByBlockHashAndIndex($hash, $index)
	{
		return $this->ether_request(__FUNCTION__, array($hash, $index));
	}
	
	function eth_getUncleByBlockNumberAndIndex($block, $index)
	{
		return $this->ether_request(__FUNCTION__, array($block, $index));
	}
	
	function eth_getCompilers()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function eth_compileSolidity($code)
	{
		return $this->ether_request(__FUNCTION__, array($code));
	}
	
	function eth_compileLLL($code)
	{
		return $this->ether_request(__FUNCTION__, array($code));
	}
	
	function eth_compileSerpent($code)
	{
		return $this->ether_request(__FUNCTION__, array($code));
	}
	
	function eth_newFilter($filter, $decode_hex=FALSE)
	{
		if(!is_a($filter, 'Ethereum_Filter'))
		{
			throw new ErrorException('Expected a Filter object');
		}
		else
		{
			$id = $this->ether_request(__FUNCTION__, $filter->toArray());
			
			if($decode_hex)
				$id = $this->decode_hex($id);
			
			return $id;
		}
	}
	
	function eth_newBlockFilter($decode_hex=FALSE)
	{
		$id = $this->ether_request(__FUNCTION__);
		
		if($decode_hex)
			$id = $this->decode_hex($id);
		
		return $id;
	}
	
	function eth_newPendingTransactionFilter($decode_hex=FALSE)
	{
		$id = $this->ether_request(__FUNCTION__);
		
		if($decode_hex)
			$id = $this->decode_hex($id);
		
		return $id;
	}
	
	function eth_uninstallFilter($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}
	
	function eth_getFilterChanges($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}
	
	function eth_getFilterLogs($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}
	
	function eth_getLogs($filter)
	{
		if(!is_a($filter, 'Ethereum_Filter'))
		{
			throw new ErrorException('Expected a Filter object');
		}
		else
		{
			return $this->ether_request(__FUNCTION__, $filter->toArray());
		}
	}
	
	function eth_getWork()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function eth_submitWork($nonce, $pow_hash, $mix_digest)
	{
		return $this->ether_request(__FUNCTION__, array($nonce, $pow_hash, $mix_digest));
	}
	
	function db_putString($db, $key, $value)
	{
		return $this->ether_request(__FUNCTION__, array($db, $key, $value));
	}
	
	function db_getString($db, $key)
	{
		return $this->ether_request(__FUNCTION__, array($db, $key));
	}
	
	function db_putHex($db, $key, $value)
	{
		return $this->ether_request(__FUNCTION__, array($db, $key, $value));
	}
	
	function db_getHex($db, $key)
	{
		return $this->ether_request(__FUNCTION__, array($db, $key));
	}
	
	function shh_version()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function shh_post($post)
	{
		if(!is_a($post, 'Whisper_Post'))
		{
			throw new ErrorException('Expected a Whisper post');
		}
		else
		{
			return $this->ether_request(__FUNCTION__, $post->toArray());
		}
	}
	
	function shh_newIdentinty()
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function shh_hasIdentity($id)
	{
		return $this->ether_request(__FUNCTION__);
	}
	
	function shh_newFilter($to=NULL, $topics=array())
	{
		return $this->ether_request(__FUNCTION__, array(array('to'=>$to, 'topics'=>$topics)));
	}
	
	function shh_uninstallFilter($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}
	
	function shh_getFilterChanges($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}
	
	function shh_getMessages($id)
	{
		return $this->ether_request(__FUNCTION__, array($id));
	}

	/*public function isAddress($address)
	{
		$result = exec('cd '.$this->wallet_dir.'/eth_ethc; '.$this->node_dir.' isAddress.js '.$this->wallet_ip.' '.$this->wallet_port.' '.$address, $this->output, $this->return_var);
		return $this->output;
		return array('isvalid'=>1,'data'=>$address);	
	}*/

	public function isAddress($address)
	{
		/*$result = exec('cd '.$this->wallet_dir.'/eth_ethc; '.$this->node_dir.' isAddress.js '.$this->wallet_ip.' '.$this->wallet_port.' '.$address, $this->output, $this->return_var);
		return $this->output;*/

		if (!preg_match('/^(0x)?[0-9a-f]{40}$/i',$address)) {
		// check if it has the basic requirements of an address
			$isvalid = 0;
		//return false;
		} elseif (!preg_match('/^(0x)?[0-9a-f]{40}$/',$address) || preg_match('/^(0x)?[0-9A-F]{40}$/',$address)) {
		// If it's all small caps or all all caps, return true
		//return true;
			$isvalid = 1;
		} else {
		// Otherwise check each case
		$isvalid = $this->isChecksumAddress($address);
		}

		return array('isvalid'=>$isvalid,'data'=>$address);	
	}

	function isChecksumAddress($address) {
	// Check each case
	$address = str_replace('0x','',$address);
	$addressHash = hash('sha3',strtolower($address));
	$addressArray=str_split($address);
	$addressHashArray=str_split($addressHash);

	for($i = 0; $i < 40; $i++ ) {
	    // the nth letter should be uppercase if the nth digit of casemap is 1
	    if ((intval($addressHashArray[$i], 16) > 7 && strtoupper($addressArray[$i]) !== $addressArray[$i]) || (intval($addressHashArray[$i], 16) <= 7 && strtolower($addressArray[$i]) !== $addressArray[$i])) {
	        return false;
	    }
	}
	return true;
 }

	



	/**
	 * Remote procedure call handler
	 *
	 * @param string $cmd
	 * @param string $request
	 * @param string $postfield
	 * @return stdClass Returned fields
	 */
	private function ether_request($cmd, $postfields=null) {

		$data = array();
		$data['jsonrpc'] = $this->version;
		$data['id'] = $this->id++;
		$data['method'] = $cmd;
		$data['params'] = $postfields;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->wallet_ip);
		curl_setopt($ch, CURLOPT_PORT, $this->wallet_port);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		curl_setopt($ch, CURLOPT_POST, count($postfields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$ret = curl_exec($ch);
		curl_close($ch);
		//echo "<pre>";print_r($ret);exit;

		if($ret !== FALSE)
		{
			$formatted = $this->format_response($ret);
			
			if(isset($formatted->error))
			{
				//return "error";
				throw new RPCException($formatted->error->message, $formatted->error->code);
			}
			else
			{
				return $formatted->result;
			}
		}
		else
		{
			//throw new RPCException("Server did not respond");
			return "error";
		}
	}

	function format_response($response)
	{
		return @json_decode($response);
	}

	public function create_eth_account($user_email)
	{
	
	$result = $this->ether_request('personal_newAccount', array("password")); //personal_unlockAccount
		return $result;

	}

	public function transfer_to_admin($user_email)
	{
		// print_R($user_email); die;
		$result = exec('cd '.$this->wallet_dir.'/eth_ethc; '.$this->node_dir.' eth_transferadmin.js '.$this->wallet_ip.' '.$this->wallet_port.' '.$this->admin_address.' '.$user_email, $this->output, $this->return_var);
		print_R($this->output); die;
		return $this->output;
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
                //echo "<pre>";print_r($cu); echo "<br>";
                if($cu->currency_name == "Tether")
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

}
// end of class