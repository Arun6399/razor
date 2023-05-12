<?php
/**
 * Common Model
 * @package Spiegel Technologies
 * @subpackage Preemex
 * @category Models
 * @author Charles
 * @version 2.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Local_model extends CI_Model {
 	// Constructor 
 	function __construct()
	{
		parent::__construct();
	}

	public function get_valid_server(){
		$exp_arr = array('');
		return (in_array($_SERVER['SERVER_ADDR'],$exp_arr))?false:true;
	}

	// Access into the selected wallet
	public function access_wallet($coin_name, $method, $parameter = '')
	{			
		if(is_numeric($coin_name))
		{
			$coin_name = $this->db->select('currency_name')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('currency_name');
		}
		// Check token:
		$ctype = $this->db->select('*')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row();
        $coin_symbol = $ctype->currency_symbol;    
		if(isset($parameter['contract']) && $parameter['contract'] != '')
		{
			$contract = $parameter['contract'];
			$parameter['contract_address'] = $parameter['contract'];
			$coin_name = "token";
			if($parameter['coin_type'] == 'tron')
			{
				$c_name = "token_tron";
			}
			else if($parameter['coin_type'] == 'bsc')
			{
				if(isset($parameter['address_list']))
				{
					$c_name = "token_bnb";
				} else {
					$c_name = "token_bnb";
					$contract = $parameter['contract_address'];
					$parameter = $parameter['address'];
				}
			}
			else {
				$c_name = "token";
			}
			$coin_name = str_replace(" ","",$coin_name);
		} else {
			if($ctype->coin_type=='token')
			{
				$contract = $this->db->select('contract_address')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('contract_address');
				$coin_name = "token";
				if($ctype->crypto_type == 'tron')
					$c_name = "token_tron";
				else if($ctype->crypto_type == 'bsc')
					$c_name = "token_bnb";
				else 
					$c_name = "token";
				$coin_name = str_replace(" ","",$coin_name);
			}
			else
			{
				$coin_name = strtolower($coin_name);
				$coin_name = str_replace(" ","",$coin_name);
				$c_name = $coin_name;
				$contract='';
			}
		}
        
		if($coin_name!='')
		{
			$model_name = strtolower($c_name).'_wallet_model';
			$model_location = 'wallets/'.strtolower($c_name).'_wallet_model';           
			$this->load->model($model_location,$model_name);
            /*echo $model_name.'<br>';
            echo $model_location.'<br>';
            echo $method.'<br>';
            echo $coin_name;
            exit;*/
           
			switch ($method) {
				case 'validateaddress':
					$result = $this->$model_name->validateaddress($parameter);
					break;
				case 'getaccountaddress':
					$result = $this->$model_name->getaccountaddress($parameter);
					break;
				case 'getnewaddress':
					$result = $this->$model_name->getnewaddress($parameter);
					break;
				case 'listalltransactions_xrp':
					$result = $this->$model_name->listalltransactions($parameter);
					break;	
				case 'create_eth_account':
					$result = $this->$model_name->create_eth_account($parameter);
					break;
				case 'create_tron_account':
					$result = $this->$model_name->createaddress($parameter);
					break;
				case 'get_eth_accounts':
					$result = $this->$model_name->get_eth_accounts($parameter);
					break;
				case 'create_etc_account':
					$result = $this->$model_name->create_etc_account($parameter);
					break;
				case 'get_etc_accounts':
					$result = $this->$model_name->get_etc_accounts($parameter);
					break;
				case 'wallet_address':
					$result = $this->$model_name->wallet_address($parameter);
					break;
				case 'isAddress':
					$result = $this->$model_name->isAddress($parameter);
					break;
				case 'istokenAddress':
					$result = $this->$model_name->isAddress($parameter);
					break;
				case 'checkAddress':
					$result = $this->$model_name->isAddress($parameter);
					break;
				case 'is_valid_address':
					$result = $this->$model_name->is_valid_address($parameter);
					break;
				case 'make_integrated_address':
					$result = $this->$model_name->make_integrated_address();
					break;
				case 'split_integrated_address':
					$result = $this->$model_name->split_integrated_address($parameter);
					break;
				case 'listalltransactions':
					$result = $this->$model_name->listalltransactions();
					break;
				case 'listalltransactions_sia':
					$result = $this->$model_name->listalltransactions($parameter);
					break;
				case 'listalltransactions_eth':
					$result = $this->$model_name->listalltransactions($parameter,$contract);
					break;
				case 'listalltransactions_token':
					$result = $this->$model_name->listalltransactions($parameter,$contract);
					//print_r($result); exit;
					break;
				case 'listalltransactions_trx':
					$result = $this->$model_name->listtransactions($parameter);
					break;
				case 'tokentransactions_tron':
				    $result = $this->$model_name->listtransactions($parameter,$contract);
					break;
				case 'listalltransactions_ethc':
					$result = $this->$model_name->listalltransactions($parameter);
					break;
				case 'help':
					$result = $this->$model_name->help();
					break;
				case 'get_balance':
					$result = $this->$model_name->get_balance($parameter);
					break;
				case 'get_xrp_balance':
					$result = $this->$model_name->get_xrp_balance($parameter);
					break;	
				case 'get_wallet_balance':
					$result = $this->$model_name->get_wallet_balance();
					break;
				case 'sendtoaddress':
					$result = $this->$model_name->sendtoaddress($parameter['address'],$parameter['amount'],$parameter['comment']);
					break;

				case 'sendfrom':
				if($coin_name=="ripple")
				{ 					
					$result = $this->$model_name->sendfrom($parameter['fromacc'],$parameter['toaddress'],$parameter['amount'],$parameter['xrp_tag_det'],$parameter['des_tag'],$parameter['secret'],$parameter['comment'],$parameter['comment_to']);
				}
				else
				{
					$result = $this->$model_name->sendfrom($parameter['fromacc'],$parameter['toaddress'],$parameter['amount'],$parameter['comment'],$parameter['comment_to']);
				}
				break;
				case 'eth_sendTransaction':
					$result = $this->$model_name->eth_sendTransaction($parameter);
					break;
				case 'token_sendTransaction':
					$result = $this->$model_name->eth_sendTransaction($parameter,$contract);
					break;
				case 'trx_sendTransaction':
				     $result = $this->$model_name->createtransaction($parameter);
					 break;
				case 'tron_tokenTransaction':
				     $result = $this->$model_name->createtransaction($parameter,$contract);
					 break;
				case 'eth_getBalance':
					$result = $this->$model_name->eth_getBalance($parameter,$contract);
					break;
				case 'token_getBalance':
					$result = $this->$model_name->eth_getBalance($parameter,$coin_symbol,$contract);
					break;
				case 'token_tronBalance':
				     $result = $this->$model_name->getBalance($parameter);
					break;
				case 'trx_getBalance':
				    $result = $this->$model_name->getBalance($parameter);
					break;
				case 'transfer':
					$result = $this->$model_name->transfer($parameter['amount'], $parameter['address']);
					break;
				case 'send_sc':
					$result = $this->$model_name->send_sc($parameter['amount'], $parameter['address']);
					break;
				case 'incoming_transfer':
					$result = $this->$model_name->incoming_transfer();
					break;
				case 'transfer_to_admin':
					$result = $this->$model_name->transfer_to_admin($parameter);
					break;
				case 'transfer_ripple_xrp':
					$result = $this->$model_name->transfer_ripple_xrp($parameter);
					break;
				case 'eth_gasPrice':
					$result = $this->$model_name->eth_gasPrice();
					break;
					
			}
			//echo "<pre>";print_r($result);echo "</pre>";exit;
			return $result;
		}else
		return false;
	}

	public function get_coin_address($coin_name,$user_id)
	{
		$coin_id = $coin_name;
		$query = getAddress($user_id, $coin_id);
		if(is_numeric($coin_name)){
		$coin_name = $this->db->select('currency_name')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('currency_name');
		}
		$coin_name = strtolower($coin_name);
		$coin_name = str_replace(" ","",$coin_name);
		if($query!='0' && $query!='')  
		return $query;  
		else {
			if($this->get_valid_server()){
				$user_email  = getUserEmail($user_id); 

				switch ($coin_name) {
					case 'ethereum':
						$new_address = $this->access_wallet($coin_name, 'create_eth_account', $user_email);
						break;
					
					default:
						$new_address = $this->access_wallet($coin_name, 'getaccountaddress', $user_email);
						break;
				}
				// print_r($new_address); die;
				if($new_address){
					switch ($coin_name) {
						case 'ripple':
							$rippleaddress = json_decode($new_address);
							$new_address = $rippleaddress->address;
							$secret = $this->encryption->encrypt($rippleaddress->secret);
							$upsecret = updaterippleSecret($user_id, $coin_id, $secret);
							$updaddress = updateAddress($user_id, $coin_id, $new_address);
							break;
						
						default:
							$updaddress = updateAddress($user_id, $coin_id, $new_address);
							break;
					}
					return $new_address;
				}
				else
				return mt_rand(0,99).'eJEjMBhBNKD1enzjWxHTq3djv'.mt_rand(0,99).'abNg5'.'Pila';
			}
			else
			return mt_rand(0,99).'eJEjMBhBNKD1enzjWxHTq3djv'.mt_rand(0,99).'abNg6'.'Rajan';
		}
	}

	public function get_new_coin_address($coin_name,$user_id)
	{
		$user_email = getUserEmail($user_id); 
		$coin_id = $coin_name;
		if(is_numeric($coin_name)){
		$coin_name = $this->db->select('currency_name')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('currency_name');
		}
		$coin_name = strtolower($coin_name);
		$coin_name = str_replace(" ","",$coin_name);
		switch ($coin_name) {
			case 'zcash':
				$new_address = $this->access_wallet($coin_name, 'getnewaddress');
				break;
			case 'ethereum':
				$new_address = $this->access_wallet($coin_name, 'create_eth_account', $user_email);
				break;
			case 'ethereumclassic':
				$new_address = $this->access_wallet($coin_name, 'create_etc_account', $user_email);
				break;
			case 'siacoin':
				$new_address = $this->access_wallet($coin_name, 'wallet_address', $user_email);
				break;
			
			default:
				$new_address = $this->access_wallet($coin_name, 'getaccountaddress', $user_email);
				break;
		}
		//return $new_address;
		if($new_address){
			$updaddress = updateAddress($user_id, $coin_id, $new_address);
			return $new_address;

		}else
		return mt_rand(0,99).'eJEjMBhBNKD1enzjWxHTq3djv'.mt_rand(0,99).'abNg4';
	}

	public function get_transactions($coin_name,$parameter = '',$other_crypto='')
	{
		if(is_numeric($coin_name))
		{
			$coin_name = $this->db->select('currency_name')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('currency_name');
			$coin_type = $this->db->select('coin_type')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('coin_type');

			$crypto_type = $this->db->select('crypto_type')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('crypto_type');

		}
		else
		{
			$coin_type = $this->db->select('coin_type')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('coin_type');

			$crypto_type = $this->db->select('crypto_type')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('crypto_type');
		}

		if($coin_type=='coin'){

			switch ($coin_name) {
				case 'Ethereum':
					$return = $this->access_wallet($coin_name, 'listalltransactions_eth', $parameter);
					break;

					case 'BNB':
					$return = $this->access_wallet($coin_name, 'listalltransactions_eth', $parameter);
					break;

					case 'Tron':
					$return = $this->access_wallet($coin_name, 'listalltransactions_trx', $parameter);
					break;

					case 'Ripple':
						$return = $this->access_wallet($coin_name, 'listalltransactions_xrp', $parameter);
						break;

				default:
					$return = $this->access_wallet($coin_name, 'listalltransactions');
					break;
			}
		}
		else{
			
			$crypto_type_other = $this->db->select('crypto_type_other')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('crypto_type_other');
			if($crypto_type_other != '')
			{
				$crypto_type_other_arr = explode('|',$crypto_type_other);
				foreach($crypto_type_other_arr as $val)
				{
					if($val=='eth' && $val==$other_crypto){
						$contract_address = $this->db->select('contract_address')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('contract_address');
						$parameter['contract'] = $contract_address;
						$parameter['coin_type'] = 'eth';
						$return = $this->access_wallet($coin_name, 'listalltransactions_token', $parameter);
					}
					elseif($val=='tron' && $val==$other_crypto){
						//print_r($parameter); exit;
						$contract_address = $this->db->select('trx_contract_address')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('trx_contract_address');
						$parameter['contract'] = $contract_address;
						$parameter['coin_type'] = 'tron';
						$return = $this->access_wallet($coin_name, 'tokentransactions_tron', $parameter);
					}
					elseif($val=='bsc' && $val==$other_crypto){
						$contract_address = $this->db->select('bsc_contract_address')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('bsc_contract_address');
						$parameter['contract'] = $contract_address;
						$parameter['coin_type'] = 'bsc';
						$return = $this->access_wallet($coin_name, 'listalltransactions_token', $parameter);
					}
				}
			} else {
				if($crypto_type=='eth'){
					$return = $this->access_wallet($coin_name, 'listalltransactions_token', $parameter);
				}
				elseif($crypto_type=='tron'){
					$return = $this->access_wallet($coin_name, 'tokentransactions_tron', $parameter);
				}
				else{
					$return = $this->access_wallet($coin_name, 'listalltransactions_token', $parameter);
				}
			}

			
		}
		return $return;
	}

	public function get_xrp_balance($coin_name,$parameter='')
	{
		if(is_numeric($coin_name)){
		$coin_name = $this->db->select('currency_name')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('currency_name');
		}
		$coin_name = strtolower($coin_name);
		$coin_name = str_replace(" ","",$coin_name);

		return $this->access_wallet($coin_name, 'get_xrp_balance', $parameter);
	}

	public function transfer_ripple_xrp($coin_name, $parameter='')
	{
		if(is_numeric($coin_name)){
		$coin_name = $this->db->select('currency_name')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('currency_name');
		}
		$coin_name = strtolower($coin_name);
		$coin_name = str_replace(" ","",$coin_name);
		// print_R($parameter); die;
		return $this->access_wallet($coin_name, 'transfer_ripple_xrp', $parameter);
	}

	public function validatate_address($coin_name,$address)
	{
		if(is_numeric($coin_name)){
		$coin_name = $this->db->select('currency_name')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('currency_name');
		$coin_type = $this->db->select('coin_type')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('coin_type');
		$crypto_type = $this->db->select('crypto_type')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('crypto_type');
		}
		else
		{
			$coin_type = $this->db->select('coin_type')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('coin_type');
			 $crypto_type = $this->db->select('crypto_type')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('crypto_type');
		}
		/*$coin_name = strtolower($coin_name);
		$coin_name = str_replace(" ","",$coin_name);*/

		if($coin_type=='coin'){
			
		switch ($coin_name) {
			case 'Ethereum':
				$return = $this->access_wallet($coin_name, 'isAddress', $address);
				break;
			case 'BNB':
				$return = $this->access_wallet($coin_name, 'isAddress', $address);
				break;
			case 'Tron':
				$return = $this->access_wallet($coin_name, 'checkAddress', $address);
				break;
			default:

				$return = $this->access_wallet($coin_name, 'validateaddress', $address);
				break;
		}
	   }
	   else{
	   	if($crypto_type=='eth'){
		$return = $this->access_wallet($coin_name, 'istokenAddress', $address);
	    }
	    elseif($crypto_type=='tron'){
		$return = $this->access_wallet($coin_name, 'checkAddress', $address);
	    }
	    else {
         $return = $this->access_wallet($coin_name, 'istokenAddress', $address);
	    }
	    }
		return $return['isvalid'];
	}

	public function make_transfer($coin_name,$parameter = '',$crypto_type_other='')
	{	
		if(is_numeric($coin_name)){
		$coin_name = $this->db->select('currency_name')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('currency_name');
		$coin_type = $this->db->select('coin_type')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('coin_type');
		$crypto_type = $this->db->select('crypto_type')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('crypto_type');
		}
		else
		{
		 $coin_type = $this->db->select('coin_type')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('coin_type');
		 $crypto_type = $this->db->select('crypto_type')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('crypto_type');
		}
		
	/*	$coin_name = strtolower($coin_name);
		$coin_name = str_replace(" ","",$coin_name);*/
	    //echo $coin_name.'coin_name';
	    if($coin_type=="coin")
	    {
		switch ($coin_name) {
			case 'Ethereum':
				return $this->access_wallet($coin_name, 'eth_sendTransaction', $parameter);
				break;

			case 'BNB':
				return $this->access_wallet($coin_name, 'eth_sendTransaction', $parameter);
				break;

			case 'Tron':
				return $this->access_wallet($coin_name, 'trx_sendTransaction', $parameter);
				break;

			case 'Ripple':
				return $this->access_wallet($coin_name, 'sendfrom', $parameter);
				break;
			
			default:
				//return $this->access_wallet($coin_name, 'sendfrom', $parameter);
				return $this->access_wallet($coin_name, 'sendtoaddress', $parameter);
				break;
		}
	   }
	   else
	   {
		    if($crypto_type_other != '')
			{
				$crypto_type = $crypto_type_other;
				if($crypto_type=='eth'){
					$contract_address_arr = $this->db->select('*')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row();
					$contract = $contract_address_arr->contract_address;
					$decimal_place = coin_decimal($contract_address_arr->currency_decimal); // Need to change it dynamic for each coin
					$parameter['coin_type'] = 'eth';
					$parameter['contract'] = $contract;
					return $this->access_wallet($coin_name, 'token_sendTransaction', $parameter);
				}
				elseif($crypto_type=='tron'){
					$contract_address_arr = $this->db->select('*')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row();
					$contract = $contract_address_arr->trx_contract_address;
					$decimal_place = coin_decimal($contract_address_arr->trx_currency_decimal); // Need to change it dynamic for each coin
					$parameter['coin_type'] = 'tron';
					$parameter['contract'] = $contract;
					return $this->access_wallet($coin_name, 'tron_tokenTransaction', $parameter);
				}
				else{
					$contract_address_arr = $this->db->select('*')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row();
					$contract = $contract_address_arr->bsc_contract_address;
					$decimal_place = coin_decimal($contract_address_arr->bsc_currency_decimal); // Need to change it dynamic for each coin
					$parameter['coin_type'] = 'bsc';
					$parameter['contract'] = $contract;
					return $this->access_wallet($coin_name, 'token_sendTransaction', $parameter);
				}
			} else {
				if($crypto_type=='eth'){
					return $this->access_wallet($coin_name, 'token_sendTransaction', $parameter);
				}
				elseif($crypto_type=='tron'){
					return $this->access_wallet($coin_name, 'tron_tokenTransaction', $parameter);
				}
				else{
					return $this->access_wallet($coin_name, 'token_sendTransaction', $parameter);
				}
			}
         
	   }
	}

	public function transfer_to_admin($coin_name, $email)
	{
		if(is_numeric($coin_name)){
		$coin_name = $this->db->select('currency_name')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('currency_name');
		}
		$coin_name = strtolower($coin_name);
		$coin_name = str_replace(" ","",$coin_name);
		
		switch ($coin_name) {
			case 'ethereumclassic':
				return $this->access_wallet($coin_name, 'transfer_to_admin', $email);
				break;

			case 'ethereum':
				return $this->access_wallet($coin_name, 'transfer_to_admin', $email);
				break;
		}

	}

	// Modified by Ram Nivas - Need to check get_transactions method too - crypto_type_other

	// Third paramter acts as both array and variable
	// For Array - to check whether its crypto_type_other, if so applies that other_crypto 
	// For Variable - directly assigns tron_private
	public function wallet_balance($coin_name, $coin_address = '',$crypto_type_other='')
	{
		$tron_private = $crypto_type_other;
		if(isset($crypto_type_other['tron_private']))
		{
			$tron_private = $crypto_type_other['tron_private'];
		}
		if(isset($crypto_type_other['crypto']))
		{
			$other_crypto = $crypto_type_other['crypto'];
		}

		if(is_numeric($coin_name))
		{
			$coin_name = $this->db->select('currency_name')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('currency_name');

			$coin_type = $this->db->select('coin_type')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('coin_type');
			$crypto_type = $this->db->select('crypto_type')->where(array('id'=>$coin_name,'status'=>'1'))->get('currency')->row('crypto_type');
		}
		else
		{
			$coin_type = $this->db->select('coin_type')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('coin_type');
			$crypto_type = $this->db->select('crypto_type')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('crypto_type');
			$currency_det = getcurrencydetail_cms($coin_name);
			
		}
		

		//$coin_name = strtolower($coin_name);

		//$coin_name = str_replace(" ", "", $coin_name);
		
		if($coin_type=='coin')
		{

			switch ($coin_name) 
			{
				case 'Ethereum':
					$return = $this->access_wallet($coin_name, 'eth_getBalance', $coin_address);
					break;
				case 'BNB':
					$return = $this->access_wallet($coin_name, 'eth_getBalance', $coin_address);
					break;
				case 'Tron':
					$return = $this->access_wallet($coin_name, 'trx_getBalance', $coin_address);
					break;

				case 'Ripple':
					$return = $this->access_wallet($coin_name, 'get_xrp_balance', $coin_address);
					break;
				default:
					$return = $this->access_wallet($coin_name, 'get_wallet_balance');
					break;
			}
		}	
		else
		{


			$crypto_type_other = $this->db->select('crypto_type_other')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('crypto_type_other');
			if($crypto_type_other != '')
			{
				$crypto_type_other_arr = explode('|',$crypto_type_other);
				foreach($crypto_type_other_arr as $val)
				{
					if($val=='eth' && $val == $other_crypto){
						$contract_address = $this->db->select('contract_address')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row('contract_address');
						// $coin_address['contract'] = $contract_address;
						// $coin_address['coin_type'] = 'eth';
						$return = $this->access_wallet($coin_name, 'token_getBalance', $coin_address);
						echo "RET1".$return."<br/>";

					}
					elseif($val=='tron' && $val == $other_crypto){

						// $contract_address = $currency_det->contract_address;
						// $decimal_place = coin_decimal($currency_det->currency_decimal);
						$contract_address_arr = $this->db->select('*')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row();
						$contract = $contract_address_arr->trx_contract_address;
						$decimal_place = coin_decimal($contract_address_arr->trx_currency_decimal); // Need to change it dynamic for each coin
						$parameter = array("address"=>$coin_address,"contract"=>$contract,"decimal_place"=>$decimal_place,"privateKey"=>$tron_private,"coin_type"=>'tron');
						$return = $this->access_wallet($coin_name, 'token_tronBalance', $parameter);
						echo "RET".$return."<br/>";


						
					}
					elseif($val=='bsc' && $val == $other_crypto){

						$contract_address_arr = $this->db->select('*')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row();
						// $coin_address['contract'] = $contract_address;
						// $coin_address['coin_type'] = 'bsc';
						$contract = $contract_address_arr->bsc_contract_address;
						$decimal_place = coin_decimal($contract_address_arr->bsc_currency_decimal); // Need to change it dynamic for each coin
						$parameter = array("address"=>$coin_address,"contract"=>$contract,"contract_address"=>$contract,"decimal_place"=>$decimal_place,"coin_type"=>'bsc');
						$return = $this->access_wallet($coin_name, 'token_getBalance', $parameter);
						echo "RET2".$return."<br/>";
						
					}
				}
			} else {
				if($crypto_type=='eth'){
					$return = $this->access_wallet($coin_name, 'token_getBalance', $coin_address);
				}
				else if($crypto_type=='tron'){
					$contract_address = $currency_det->contract_address;
					$decimal_place = coin_decimal($currency_det->trx_currency_decimal);
					$parameter = array("address"=>$coin_address,"contract_address"=>$contract_address,"decimal_place"=>$decimal_place,"privateKey"=>$tron_private);
					$return = $this->access_wallet($coin_name, 'token_tronBalance', $parameter);
				}
				else{
					$return = $this->access_wallet($coin_name, 'token_getBalance', $coin_address);
				}
			}











			// if($crypto_type=='eth'){
			// 	$return = $this->access_wallet($coin_name, 'token_getBalance', $coin_address);
			// }
			// else if($crypto_type=='tron'){
            //     $contract_address = $currency_det->contract_address;
            //     $decimal_place = coin_decimal($currency_det->currency_decimal);
			// 	$parameter = array("address"=>$coin_address,"contract_address"=>$contract_address,"decimal_place"=>$decimal_place,"privateKey"=>$tron_private);
			// 	$return = $this->access_wallet($coin_name, 'token_tronBalance', $parameter);
			// }
			// else{
			// 	$return = $this->access_wallet($coin_name, 'token_getBalance', $coin_address);
			// }
			
		}
		echo "FINALRET".$return."<br/>";
		return $return;
	}

}

/*
 * End of the file Local_model.php
 * Location: application/models/local_model.php
 */