<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'jsonRPCClient.php';
class Bitcoin_wallet_model extends CI_Model {  

	/*
	* Mainly Source From : https://en.bitcoin.it/wiki/Original_Bitcoin_client/API_calls_list
	* Rpc test site : https://chainquery.com/bitcoin-api/
	* Bolg about Bitcoin : http://bitzuma.com/posts/bitcoin-think-of-it-as-electronic-cash/
	*/
	public function __construct() 
	{
		//error_reporting(E_ALL);
		parent::__construct();	
		$tables = wallet_table();		
		$wallet_whr 	   = array('type'=>'bitcoin','con_type'=>'rpc','status'=>'Active');
		$wallet_row        = $this->db->where($wallet_whr)->get($tables)->row();// fetch bitcoin wallet credentials
		if(!empty($wallet_row)){
		

			$wallet_username   = trim(decryptIt($wallet_row->username));
			$wallet_password   = trim(decryptIt($wallet_row->password));
			$this->wallet_port = $wallet_portnumber = trim(decryptIt($wallet_row->portnumber));
			$this->wallet_ip   = $wallet_allow_ip   = trim(decryptIt($wallet_row->allow_ip));
			
			//$this->url = 'http://'.$wallet_username.':'.$wallet_password.'@'.$wallet_allow_ip.':'.$wallet_portnumber;

			$this->url = 'http://btcu3er:5QA9773XnjEBezjsKps8Kh7tYjuE93hP@168.119.89.118:8332';  
			 

			$this->wallet 	   = new jsonRPCClient($this->url);

			// echo "<pre>";
			// print_r($this->wallet);
			// exit(); 
			

		}
		else
		die(json_encode(array('status'=>'error','message'=>'Security Error')));
	} 

	public function index()
	{
		die(json_encode(array('status'=>'error','message'=>'Security Error')));
	}

	/*
	* Returns an object containing various state info. 
	*/
	public function getinfo()
	{
		return $this->wallet->getinfo();
	}

	/*
	* Returns an object containing mining-related information:
	* -> blocks
	* -> currentblocksize
	* -> currentblocktx
	* -> difficulty
	* -> errors
	* -> generate
	* -> genproclimit
	* -> hashespersec
	* -> pooledtx
	* -> testnet 
	*/
	public function getmininginfo()
	{
		return $this->wallet->getmininginfo();
	}
	public function getblockcount()
	{
		return $this->wallet->getblockcount();
	}
	/*
	* @parameter : account_email
	* If [account] is not specified, returns the server's total available balance.
	* If [account] is specified, returns the balance in the account. 
	*/
	public function get_balance($user_email = '')
	{
		return $this->wallet->getbalance($user_email);
	}

	public function get_wallet_balance()
	{
		return $this->wallet->getbalance();
	}

	/*
	* @parameter : bitcoinaddress
	* Returns the account associated with the given address. 
	*/
	public function getaccount($coin_address)
	{
		return $this->wallet->getaccount($coin_address);
	}

	/*
	* @parameter : account_email
	* Returns the current bitcoin address for receiving payments to this account. 
	* If <account> does not exist, it will be created along with an associated new address that will be returned. 
	*/
	public function getaccountaddress($user_email = '')
	{
		// echo $user_email;
		// exit();
		return $this->wallet->getnewaddress();
	}

	/*
	* @parameter : account_email
	* Returns the list of addresses for the given account. 
	*/
	public function getaddressesbyaccount($user_email = '')
	{
		return $this->wallet->getaddressesbyaccount($user_email);
	}

	/*
	* @parameter : account_email
	* Returns a new bitcoin address for receiving payments. 
	* If [account] is specified payments received with the address will be credited to [account]. 
	*/
	public function getnewaddress($user_email = '')
	{
		
		// echo $user_email;
		// exit(); 

		return $this->wallet->getnewaddress($user_email);  
	}

	/*
	* @parameter : account_email
	* Returns the total amount received by addresses with [account] in transactions with at least [minconf] confirmations. 
	* If [account] not provided return will include all transactions to all accounts. (version 0.3.24) 
	*/
	public function getreceivedbyaccount($user_email = '')
	{
		return $this->wallet->getreceivedbyaccount($user_email);
	}

	/*
	* @parameter : bitcoinaddress
	* Returns the amount received by <bitcoinaddress> in transactions with at least [minconf] confirmations. 
	* It correctly handles the case where someone has sent to the address in multiple transactions. 
	* Keep in mind that addresses are only ever used for receiving transactions. 
	* Works only for addresses in the local wallet, external addresses will always show 0. 
	*/
	public function getreceivedbyaddress($user_email)
	{
		return $this->wallet->getreceivedbyaddress($user_email);
	}

	/*
	* @parameter : transaction ID
	* Returns an object about the given transaction containing:
	*    "amount" : total amount of the transaction
	*    "confirmations" : number of confirmations of the transaction
	*    "txid" : the transaction ID
	*    "time" : time associated with the transaction[1].
	*    "details" - An array of objects containing:
	*        "account"
	*        "address"
	*        "category"
	*        "amount"
	*        "fee"
	*/
	public function gettransaction($txid)
	{
		return $this->wallet->gettransaction($txid);
	}

	/*
	* List commands, or get help for a command. 
	*/

	public function help()
	{
		//return $this->wallet->getheight();
		return $this->wallet->help();
	}

	/*
	* Returns Object that has account names as keys, account balances as values.
	*/
	public function listaccounts()
	{
		return $this->wallet->listaccounts(1,false);
	}

	/*
	* version 0.7 Returns all addresses in the wallet and info used for coincontrol. 
	*/
	public function listaddressgroupings()
	{
		return $this->wallet->listaddressgroupings(1,false);
	}

	/*
	* Returns an array of objects containing:
	    "account" : the account of the receiving addresses
	    "amount" : total amount received by addresses with this account
	    "confirmations" : number of confirmations of the most recent transaction included
	*/
	public function listreceivedbyaccount()
	{
		return $this->wallet->listreceivedbyaccount(1,true);
	}

	/*
	* Returns an array of objects containing:

		    "address" : receiving address
		    "account" : the account of the receiving address
		    "amount" : total amount received by the address
		    "confirmations" : number of confirmations of the most recent transaction included

		To get a list of accounts on the system, execute bitcoind listreceivedbyaddress 0 true  
	*/
	public function listreceivedbyaddress()
	{
		return $this->wallet->listreceivedbyaddress(1,true);
	}

	/*
	* Returns up to [count] most recent transactions skipping the first [from] transactions for account [account]. 
	* If [account] not provided it'll return recent transactions from all accounts. 
	*/
	public function listtransactions($user_email, $count ='', $from ='')
	{
		return $this->wallet->listtransactions($user_email,$count,$from);
	}

	public function listalltransactions()
	{
		return $this->wallet->listtransactions();
	}

	/*
	* Required arguments are denoted inside < and > Optional arguments are inside [ and ]. 
	*  Parameter  	<fromaccount> <toaccount> <amount> [minconf=1] [comment] 
	* <amount> is a real and is rounded to 8 decimal places. Will send the given amount to the given address, 
	* ensuring the account has a valid balance using [minconf] confirmations. Returns the transaction ID if successful (not in JSON object). 
	*/
	public function move($from_account, $to_account, $amount, $comment ='no comment')
	{
		return $this->wallet->move($from_account,$to_account,$amount,$comment);
	}

	/*
	* Required arguments are denoted inside < and > Optional arguments are inside [ and ]. 
	*  Parameter  	<fromaccount> <tobitcoinaddress> <amount> [minconf=1] [comment] [comment-to] 
	* <amount> is a real and is rounded to 8 decimal places. Will send the given amount to the given address, 
	* ensuring the account has a valid balance using [minconf] confirmations. Returns the transaction ID if successful (not in JSON object). 
	*/
	/*public function sendfrom($from_account, $to_coin_address, $amount, $comment ='no comment', $comment_to ='no to comment')
	{
		return $this->wallet->sendfrom($from_account,$to_coin_address,$amount,$comment,$comment_to);
	}*/

	public function sendfrom($from_account, $to_coin_address, $amount, $comment ='no comment', $comment_to ='no to comment')
	{

		/*echo $from_account;
		echo "<br>";
		echo $to_coin_address;
		echo "<br>";
		echo $amount;
		echo "<br>";
		echo $comment;
		echo "<br>";
		echo $comment_to;

		print_r($this->wallet->sendfrom($from_account,$to_coin_address,$amount));
		exit;
		*/
		
		return $this->wallet->sendfrom($from_account,$to_coin_address,$amount);
	}

	/*
	* Required arguments are denoted inside < and > Optional arguments are inside [ and ]. 
	*  Parameter  	 	<fromaccount> {address:amount,...} [minconf=1] [comment] 
	* amounts are double-precision floating point numbers 
	*/
	public function sendmany($from_account, $address_amt_arr, $comment ='no comment')
	{
		return $this->wallet->sendmany($from_account,$address_amt_arr,$comment);
	}

	/*
	* Required arguments are denoted inside < and > Optional arguments are inside [ and ]. 
	*  Parameter  	 	<bitcoinaddress> <amount> [comment] [comment-to] 
	* <amount> is a real and is rounded to 8 decimal places. Returns the transaction ID <txid> if successful. 
	*/
	public function sendtoaddress($coin_address, $amount, $comment ='no comment')
	{
		//echo $coin_address.', Amount : '.$amount.', comment : '.$comment;
		return $this->wallet->sendtoaddress($coin_address,$amount,$comment);
	}

	/*
	* Required arguments are denoted inside < and > Optional arguments are inside [ and ]. 
	*  Parameter  	 	<bitcoinaddress> <account> 
	* Sets the account associated with the given address. 
	* Assigning address that is already assigned to the same account will create a new address associated with that account. 
	*/
	public function setaccount($coin_address, $user_email)
	{
		return $this->wallet->setaccount($coin_address,$user_email);
	}

	/*
	* Required arguments are denoted inside < and > Optional arguments are inside [ and ]. 
	*  Parameter  	 	<amount> 
	* <amount> is a real and is rounded to the nearest 0.00000001  
	*/
	public function settxfee($amount)
	{
		return $this->wallet->settxfee($amount);
	}

	/*
	* Stop bitcoin server. 
	*/
	public function stop($amount)
	{
		return $this->wallet->stop($amount);
	}

	/*
	* Required arguments are denoted inside < and > Optional arguments are inside [ and ]. 
	*  Parameter  	 	<bitcoinaddress> 
	* Return information about <bitcoinaddress>.
	*/
	public function validateaddress($coin_address)
	{
		
		return $this->wallet->validateaddress($coin_address);
	}

	/*
	* Required arguments are denoted inside < and > Optional arguments are inside [ and ]. 
	*  Parameter  	 	<bitcoinaddress> <signature> <message> 
	* Verify a signed message. 
	*/
	public function verifymessage($coin_address,$signature,$message)
	{
		return $this->wallet->verifymessage($coin_address,$signature,$message);
	}

	/*
	* Required arguments are denoted inside < and > Optional arguments are inside [ and ]. 
	*  Parameter  	 	<passphrase> <timeout> 
	* Stores the wallet decryption key in memory for <timeout> seconds.
	*/
	public function walletpassphrase($pass_phrase,$time_out)
	{
		return $this->wallet->walletpassphrase($pass_phrase,$time_out);
	}

	/*Zcash coin*/

	/*
	* Required arguments are denoted inside < and > Optional arguments are inside [ and ]. 
	*  Parameter  	 	<oldpassphrase> <newpassphrase> 
	* Changes the wallet passphrase from <oldpassphrase> to <newpassphrase>. 
	*/
	public function walletpassphrasechange($old_passphrase,$new_passphrase)
	{
		return $this->wallet->walletpassphrasechange($old_passphrase,$new_passphrase);
	}

	/*
	* Returns an object containing various state info. 
	*/
	public function getwalletinfo()
	{
		return $this->wallet->getwalletinfo();
	}

} // end of class
