<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'json-rpc.php';

require_once 'jsonRPCClient.php'; 
class Ethereum_wallet_model extends CI_Model {  
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
		$wallet_whr 	   = array('type'=>'ethereum','con_type'=>'node','status'=>'Active');
		$wallet_row        = $this->db->where($wallet_whr)->get($tables)->row();// fetch ethereum wallet credentials

		
		if(!empty($wallet_row)){

			$wallet_username   	= decryptIt($wallet_row->username);
			$wallet_password   	= decryptIt($wallet_row->password); 
			$this->wallet_port = $wallet_portnumber = decryptIt($wallet_row->portnumber);
			$this->wallet_ip   = $wallet_allow_ip   = decryptIt($wallet_row->allow_ip); 
			$this->version	   	=  "2.0";
			  
			// echo  $this->url = 'http://'.$wallet_username.':'.$wallet_password.'@'.$wallet_allow_ip.':'.$wallet_portnumber;
			// exit();
			 


			//$this->url 			= 'https://'.$wallet_username.':'.$wallet_password.'@'.$this->wallet_ip;
			// $this->wallet 	   	= new jsonRPCClient($this->url);
			//print_r($this->wallet);
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
		return $this->ether_request(__FUNCTION__, array($input));
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
	
	/*function eth_getBalance($address,$contract_address='',$block='latest', $decode_hex=FALSE)
	{
		
		$balance = $this->ether_request(__FUNCTION__, array($address, $block));

		if($decode_hex)
			$balance = $this->decode_hex($balance);
		
		return $balance;
		
	}
   */

	function eth_getBalance($address,$contract_address='',$block='latest', $decode_hex=TRUE)
	{		
	    echo "Address".$address;
		echo "<br/>";
		echo "Block".$block;
		echo "<br/>";
	    $balance1 = $this->ether_request(__FUNCTION__, array($address, $block)); 		
		$bal = $this->decode_hex($balance1);
		$balance = $bal /1000000000000000000;
		echo $balance;
		echo "<br/>";	
		return $balance;		
	}


	// function listalltransactions($user_trans_res, $contract_address='') {
    //     $address_list = $user_trans_res['address_list'];
    //     $transactionIds = $user_trans_res['transactionIds'];
    //     $return_trans = array();

    //     $Var = array_keys($address_list);

    //    $Blocks_search = $this->common_model->getTableData('blocks',array('currency'=>'ETH'), 'blocks','','','','','1',array('id','DESC'))->row();

    //    if(isset($Blocks_search) && !empty($Blocks_search)){
        
    //     $old_block = $Blocks_search->blocks+1;
    //    }
    //    else{
    //     $old_block = $this->eth_blockNumber(TRUE);
    //     //$old_block = 14075989;
        
    //    }
      
    //    $current_block = $this->eth_blockNumber(TRUE);
    //    //$current_block=14075990;

    //    for($a=$old_block;$a<=$current_block;$a++){
    //         $blocknum = '0x'.dechex($a);
    //         $block = $this->eth_getBlockByNumber($blocknum);
    //         $block = json_encode($block);
    //         $block = json_decode($block,TRUE);
            
    //         if(isset($block) && !empty($block)){
            
    //         $Block_Count =  count($block['transactions']) -1;

    //         for($j=0;$j<=$Block_Count;$j++){
                
            
    //     if (in_array($block['transactions'][$j]['to'], $Var)){
                       
    //                     $time =  str_replace('0x', '', $block['timestamp']);
    //                     $time =  hexdec($time);
    //                     $acc_address = $block['transactions'][$j]['to'];
    //                     $rec_amount = str_replace('0x', '', $block['transactions'][$j]['value']);
    //                     $rec_amount = hexdec($rec_amount)/1000000000000000000;

    //                     $txid          = str_replace('0x', '', $block['transactions'][$j]['transactionIndex']);
    //                     //$txid          = $block['transactions'][$j]['transactionIndex'];
    //                     $hash          = $block['transactions'][$j]['hash'];
    //                     $from          = $block['transactions'][$j]['from'];
    //                     $this->email_column = 'user_email';
    //                     $acc_owner     = $address_list[$block['transactions'][$j]['to']][$this->email_column];
    //                     $confirmations = 4;

    //                     $cat_sat = 'receive';

    //                     $trans_arr = array(
    //                         'account'        => $acc_owner,
    //                         'address'        => $acc_address,
    //                         'category'       => $cat_sat,
    //                         'amount'         => $rec_amount,
    //                         'blockhash'      => $hash,
    //                         'confirmations'  => $confirmations,
    //                         'txid'           => $txid,
    //                         'time'           => $time,
    //                         'from'           => $from
    //                     );

    //                     array_push($return_trans,$trans_arr);
    //                 }
    //             }
    //         $Blocks_Count = $this->common_model->getTableData('blocks',array('currency'=>'ETH','blocks'=>$a))->num_rows();
    //         if($Blocks_Count==0){
    //                 $Insert_Blocks = array(
    //                                         'currency'=>'ETH',
    //                                         'blocks' => $a
    //                                     );
    //                $ins = $this->common_model->insertTableData('blocks',$Insert_Blocks);
    //            }
    //             }
    //         }
                
            
        

    //     return $return_trans;
    // }

	function listalltransactions($user_trans_res, $contract_address='') 
	{
		$address_list     = $user_trans_res['address_list'];
		$transactionIds   = $user_trans_res['transactionIds'];

		$return_trans = array();
	
		foreach ($address_list as $key => $value) 
		{

			if(str_replace(" ","",$value['currency_name'])=='Ethereum')
			{
				 $get_trans_url = 'https://api.etherscan.io/api?module=account&action=txlist&address='.$value['address'].'&endblock=latest&sort=asc&apikey=ICGUFQYWKJAFICH9XZQXURQRHAWD3K99BI';
			
				$trans = json_decode(file_get_contents($get_trans_url));
				if($trans->status == 1){
					foreach ($trans->result as $trans_key => $trans_value) {
						$acc_address   = $trans_value->to;
						$rec_amount    = $trans_value->value/1000000000000000000;
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
						$trans_arr = array(
				            'account'		 => $acc_owner,
				            'address'		 => $acc_address,
				            'category'		 => $cat_sat,
				            'amount'		 => $rec_amount,
				            'blockhash'		 => $hash,
				            'confirmations'	 => $confirmations,
				            'txid'		 	 => $txid,
				            'time'		 	 => $time,
				            'from'			 =>$from

						);
						array_push($return_trans,$trans_arr);
					}
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
            
        return $count;   
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

	function eth_sendTransaction($transaction)
	{		

		//error_reporting(E_ALL);
		//ini_set("display_errors",1);
		$data1 = array();
		$data1['params'] = array($transaction['from'],'password');
		$data1['jsonrpc'] = $this->version;
		$data1['id'] = $this->id++;
		$data1['method'] = 'personal_unlockAccount';

	
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
				/*print_r($formatted1);
				exit();*/
				throw new RPCException($formatted1->error->message, $formatted1->error->code);
				$res1 = FALSE;
			}
			else
			{
				$res1 = $formatted1->result;
			}
		}
		
		// if($res1){
		$data = array();
		$data['jsonrpc'] = $this->version;
		$data['id'] = $this->id++;
		$data['method'] = 'eth_sendTransaction';
		/*echo sprintf("%.40f", $transaction['value']);
		exit();*/
		//$transaction['value'] =  '0x'.dechex(rtrim(sprintf("%.40f", $transaction['value']), "."));
		$transaction['value'] =  '0x'.dechex($transaction['value']);
		$gas = $transaction['gas'];
		$gasPrice = $transaction['gasPrice'];
		$transaction['gas'] = '0x'.dechex($gas);
		$transaction['gasPrice'] = '0x'.dechex($gasPrice);
		if($transaction['nonce'] !="")
		{
		$transaction['nonce'] = '0x'.dechex($transaction['nonce']);
		}

		$data['params'] = array($transaction);

		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->wallet_ip);
		curl_setopt($ch, CURLOPT_PORT, $this->wallet_port);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		curl_setopt($ch, CURLOPT_POST, count($transaction));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$ret = curl_exec($ch);

		//echo '<pre>';print_r($ret); exit;
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

        //}

	}
	
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

	public function isAddress($address)
	{
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
		//return array('isvalid'=>1,'data'=>$address);	
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
		//echo "<br>****<br>";
		//print_r($ch);
		//echo "<br>****<br>";
		if (curl_errno($ch)) {
		    $error_msg = curl_error($ch);
		    print_r($error_msg);
		}

		curl_close($ch);


		// echo "<br>";
		// echo $this->wallet_ip;
		// echo "<br>";
		// echo $this->wallet_port;

		// echo "<pre>";print_r($ret);
		// echo "<pre>";
		// print_r($data);
		// echo "sds</pre>";
		// echo "<pre>";print_r($ret); echo "sds</pre>"; //exit; 

		if($ret !== FALSE)
		{
			$formatted = $this->format_response($ret);
			
			if(isset($formatted->error))
			{
				//return "failed";
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
			//return "failed";
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
		// print_R($this->output); die;
		return $this->output;
	}

}
// end of class
