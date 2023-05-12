<?php
/**
 * Common Class
 * @package Spiegel Technologies
 * @subpackage ixtokens
 * @trade Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods", "GET, POST, DELETE, PUT");
class Trade extends CI_Controller {

public function __construct()
{	
	parent::__construct();		
	$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
	$this->output->set_header("Pragma: no-cache");
	$this->load->library(array('form_validation'));
	$this->load->helper(array('url', 'language'));
	$this->site_api = new Tradelib();
	$lang_id = $this->session->userdata('site_lang');
	if($lang_id == '')
	{
		$this->lang->load('content','english');
		$this->session->set_userdata('site_lang','english');
	}
	else
	{
		$this->lang->load('content',$lang_id);	
		$this->session->set_userdata('site_lang',$lang_id);
	}
	$this->load->library('session');
	$sitelan = $this->session->userdata('site_lang'); 
}

public function index()
{
	$this->common_model->sitevisits();
	$joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
	$where = array('a.status'=>1,'b.status'=>1,'c.status'=>1);
	$orderprice = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.id,b.currency_symbol as fromcurrency,c.currency_symbol as tocurrency','','','','','',array('a.id','asc'))->row();
	//$pair_url=$orderprice->fromcurrency.'_'.$orderprice->tocurrency;
	$pair_url = 'ETH_BTC';
	front_redirect('trade/'.$pair_url);
}

public function coinprice($coin_symbol)
{
    $url = "https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=USD&api_key=a2ae4b9817a848ef5d2311a115856baa97c65d15a8b3e41cb4abf2295ed4d1aa";
	$curres = $coin_symbol;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	$res = json_decode($result);
	return $res->USD;
}

public function trade($pair_symbol='')
{
	$user_id = $this->session->userdata('user_id');
	if($user_id=="") {	
		front_redirect('', 'refresh');
	}
	$data['user_id'] = $user_id;
	$data['user'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
	$pair=explode('_',$pair_symbol);
	$pair_id=0;
	if(count($pair)==2)
	{
		$joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
		$where = array('a.status'=>1,'b.status!='=>0,'c.status!='=>0,'b.currency_symbol'=>$pair[0],'c.currency_symbol'=>$pair[1]);
		$orderprice = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.*');
		if($orderprice->num_rows()==1)
		{
			$pair_details=$orderprice->row();
			$pair_id=$pair_details->id;
		}
	}
	if($pair_id==0)
	{
		$joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
		$where = array('a.status'=>1,'b.status!='=>0,'c.status!='=>0);
		$orderprice = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.id,b.currency_symbol as fromcurrency,c.currency_symbol as tocurrency','','','','','',array('a.id','asc'))->row();
		$pair_url=$orderprice->fromcurrency.'_'.$orderprice->tocurrency;
		front_redirect('trade/'.$pair_url, 'refresh');
	}
	$data['tradeInfo'] = $this->common_model->getTableData('trade_pairs',array('id'=>$pair_details->id))->row();
	
	$data['pair']=$pair;
	$data['pair_id']=$pair_id;
	$data['pair_symbol']=$pair[0].'/'.$pair[1];
    $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol_id))->row();
	$to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol_id))->row();
	$data['from_currdet'] = $from_currency;
	$data['to_currdet'] = $to_currency;
	$data['apicheck'] = checkapi($pair_id);
	$data['pair_details'] = $pair_details;
	if ($user_id != 0) { 
	  $data['from_cur'] = number_format(getBalance($user_id,$pair_details->from_symbol_id), 8);
	  $data['to_cur'] = number_format(getBalance($user_id,$pair_details->to_symbol_id), 8);
	} else {
	  $data['from_cur'] = 0;
	  $data['to_cur'] = 0;
	}
    
	$this->trade_prices($pair_id,'trade');

	$data['pagetype'] = $this->uri->segment(1);
	$tradesym = $pair[0].'_'.$pair[1];
	
	$pair_currency = $this->common_model->customQuery("select id,from_symbol_id,to_symbol_id,lastPrice,priceChangePercent from bidex_trade_pairs where status='1' order by id DESC")->result();
	if(isset($pair_currency) && !empty($pair_currency))
	{
		$Pairs_List = array();
		foreach($pair_currency as $Pair_Currency)
		{
			$from_currency_det = getcryptocurrencydetail($Pair_Currency->from_symbol_id);
            $to_currency_det = getcryptocurrencydetail($Pair_Currency->to_symbol_id);
            $pairname = $from_currency_det->currency_symbol."/".$to_currency_det->currency_symbol;
            $pairurl = $from_currency_det->currency_symbol."_".$to_currency_det->currency_symbol;

            $Site_Pairs[$Pair_Currency->id] = array(
         		"currency_pair"	=> $pairname,
         		"price"	=>	($Pair_Currency->lastPrice!='')?$Pair_Currency->lastPrice:'0.000',
         		"change"	=> ($Pair_Currency->priceChangePercent!='')?$Pair_Currency->priceChangePercent:'0.000',
         		"pairurl"	=> $pairurl
            );
		}
		$data['Site_Pairs'] = array_reverse($Site_Pairs);
	}
	$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'trade'))->row();
	$data['currencies'] = $this->common_model->customQuery("select * from bidex_currency where status='1' and currency_symbol in ('BTC','ETH','BCH','XRP','BNB','EUR')")->result();
	$data['allcurrencies'] = $this->common_model->customQuery("select * from bidex_currency where status='1' ")->result();
	$data['site_common'] = site_common();	
	// echo "<pre>";print_r($data['currencies']);die;
	$this->load->view('front/trade/trade', $data);
}

public function gettradeopenOrders($type,$pair_id)
{
	$selectFields='CO.*,SUM(CO.Amount) as TotAmount,date_format(CO.datetime,"%d-%m-%Y %H:%i") as trade_time,sum(OT.filledAmount) as totalamount';
	$names = array('active', 'partially', 'margin');
	$where=array('CO.Type'=>$type,'CO.pair'=>$pair_id);
	$orderBy=array('CO.trade_id','desc');
	$groupBy=array('CO.Price');
	$where_in=array('CO.status', $names);
	$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
	$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
	if($query->num_rows() >= 1)
	{
		$result = $query->result();
	}
	else
	{
		$result = 0;
	}
	if($result&&$result!=0)
	{
		$orders=$result;
	}
	else
	{
		$orders=0;
	}
	return $orders;
}

function gettradeapisellOrders($pair)
{
	$sellresult = $this->common_model->getTableData("api_orders",array("pair_id"=>$pair,'type'=>'sell'))->result();
        
        if(count($sellresult)>0 && !empty($sellresult))
        {	
	        $sell_res = array();
	        $i=1;
	        foreach($sellresult as $sell)
	        {
	        	$sellData['id'] = $i;
		        $sellData['price'] = $sell->price;
		        $sellData['quantity'] = $sell->quantity;
	            $sell_res[] = $sellData;
	            $i++;
	        }
	       	return $sell_res;
	    }
	    else
	    {
	    	return $sell_res = [];
	    }
}

function gettradeapibuyOrders($pair)
{
	$buyresult = $this->common_model->getTableData("api_orders",array("pair_id"=>$pair,'type'=>'buy'))->result();
        
        if(count($buyresult)>0 && !empty($buyresult))
        {	
	        $buy_res = array();
	        $i=1;
	        foreach($buyresult as $buy)
	        {
	        	$buyData['id'] = $i;
		        $buyData['price'] = $buy->price;
		        $buyData['quantity'] = $buy->quantity;
	            $buy_res[] = $buyData;
	            $i++;
	        }
	       	return $buy_res;
	    }
	    else
	    {
	    	return $buy_res = [];
	    }
}

function get_active_order($pair_id='',$user_id)
{
	$user_id = $user_id;
	$selectFields='CO.*,date_format(CO.datetime,"%d/%m/%Y") as trade_time,sum(OT.filledAmount) as totalamount';
	$names = array('active', 'partially', 'margin','stoporder');
	$where=array('CO.userId'=>$user_id,'CO.pair' => $pair_id);
	$orderBy=array('CO.trade_id','desc');
	$groupBy=array('CO.trade_id');
	$where_in=array('CO.status', $names);
	$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
	$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
	if($query->num_rows() >= 1)
	{
		$open_orders = $query->result();
	}
	else
	{
		$open_orders = 0;
	}
	if($open_orders&&$open_orders!=0)
	{
		$open_orders_text=$open_orders;
	}
	else
	{
		$open_orders_text=0;
	}
	return $open_orders_text;
}

public function transactionhistory($pair_id,$user_id,$showme='')
{
	$user_id = $user_id;
	$joins = array('coin_order as b'=>'a.sellorderId = b.trade_id','coin_order as c'=>'a.buyorderId = c.trade_id');
	$where = array('b.pair'=>$pair_id);
	if($showme=='check')
	{
		$where_or = array('c.userId'=>$user_id);
		$wherenew = ' and userId='.$user_id;
	}		
    else
    {
    	$where_or = '';
    	$wherenew ='';
    }
	$transactionhistory = $this->common_model->getJoinedTableData('ordertemp as a',$joins,$where,'a.*,date_format(b.datetime,"%H:%i%s") as sellertime,b.datetime,b.trade_id as seller_trade_id,date_format(c.datetime,"%H:%i") as buyertime,c.trade_id as buyer_trade_id,a.askPrice as sellaskPrice,c.pair_symbol as pair_symbol,c.Price as buyaskPrice,b.Fee as sellerfee,c.Fee as buyerfee,b.Total as sellertotal,c.Total as buyertotal','',$where_or,'','','',array('a.tempId','desc'))->result();

     $newquery = $this->common_model->customQuery('select userId,trade_id, Type, Price, datetime, pair_symbol, Amount, Fee, Total, status, date_format(datetime,"%H:%i%s") as tradetime from bidex_coin_order where status = "cancelled" and pair = '.$pair_id.$wherenew)->result();

	if(count($transactionhistory)>0 || count($newquery))
	{
	    $transactionhistory_1 = array_merge($transactionhistory,$newquery);
	    $historys = $transactionhistory_1;
	}
	else
	{
	    $historys=0;
	}
	return $historys;
}

function trade_prices($pair,$pagetype='')
{
	$this->marketprice = marketprice($pair);
	$this->lastmarketprice = lastmarketprice($pair);
	$this->minimum_trade_amount = get_min_trade_amt($pair);
	$this->maker=getfeedetails_buy($pair);
	$this->taker=getfeedetails_sell($pair);
	$user_id=$this->session->userdata('user_id');
	if($user_id)
	{
		$this->user_id = $user_id;
		
			$this->user_balance = getBalance($user_id);
		
	}
	else
	{
		$this->user_id = 0;
		$this->user_balance = 0;
		
	}
}

function close_allactive_order()
{		
	$user_id = $this->session->userdata('user_id'); 
	$response=$this->site_api->close_allactive_order($user_id);
	echo json_encode($response);
}


// Angular Call
public function getSession($test='')
{
	$user_id=$_SESSION["user_id"];
	// $user_id=1;
	if($user_id != '')
	{
		$result = $this->common_model->getTableData('users', array('id' => $user_id))->row_array();
		
    	echo json_encode($result);
	} else {
		echo json_encode();
	}
}


function updateBalance($user_id,$currency,$balance=0)
{
$data = array();
$type='crypto';
$wallet_type='Exchange AND Trading';

$wallet = $this->db->where('user_id', $user_id)->get('wallet');
if($wallet->num_rows()==1)
{
	$upd=array();
	
		$wallets=unserialize($wallet->row('crypto_amount'));
		$wallets[$wallet_type][$currency]=to_decimal_point($balance,8);
		$upd['crypto_amount']=serialize($wallets);
	
	$this->db->where('user_id',$user_id);
	$this->db->update('wallet', $upd); 

	$data['msg'] = 1;  
}

echo json_encode($data);

//return 1;
}
function updateAdminBalance($currency,$balance)
	{
	$data = array();
	$adminbalance = getadminBalance(1,$currency);
	$finaladmin_balance = $adminbalance+$balance;
	$updateadmin_balance = updateadminBalance(1,$currency,$finaladmin_balance);
	$data['msg'] = 1;
	echo json_encode($data);
	}

public function getUserEmail($id='')
{
	if($id){
	$userEmail = getUserEmail($id);
	}
	echo json_encode($userEmail);
	// $user_id=$_SESSION["user_id"];

}
public function sitevisits($user_id=null)
	{
		// echo $user_id;exit;
		$browser_name = getBrowser();
		if (is_cli()) {
			$ip_address = '127.0.0.1';
		} else {
			$ip_address = get_client_ip();
		}
		$date = date('Y-m-d');
		$insertData = array('ip_address' => $ip_address, 'browser' => $browser_name, 'date_added' => $date);
		$already = $this->common_model->getTableData('site_visits', $insertData);
		if ($already->num_rows() == 0) {
			$ins=$this->common_model->insertTableData('site_visits', $insertData);
			if($ins){
				$data['msg'] = 'Successfully inserted';
				
			}else{
				$data['msg'] = 'Error in inserted';
			}
			}else{
				$data['msg'] = 'Already inserted';
			}
			echo json_encode($data);
			
	}

}
