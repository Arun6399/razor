<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods", "GET, POST, DELETE, PUT");

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
class Trade extends CI_Controller {

public function __construct()
{	
	parent::__construct();		
	$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
	$this->output->set_header("Pragma: no-cache");
	$this->load->library(array('form_validation'));
	$this->load->helper(array('url', 'language'));
	$this->load->library('session');
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
	// $this->load->library('session');
	$sitelan = $this->session->userdata('site_lang'); 
}

public function index()
{
	$this->common_model->sitevisits();
	$joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
	$where = array('a.status'=>1,'b.status'=>1,'c.status'=>1);
	$orderprice = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.id,b.currency_symbol as fromcurrency,c.currency_symbol as tocurrency','','','','','',array('a.id','asc'))->row();
	//$pair_url=$orderprice->fromcurrency.'_'.$orderprice->tocurrency;
	$pair_url = 'BTC_USDT';
	front_redirect('trade/'.$pair_url);
}

public function coinprice($coin_symbol)
{
    $url = "https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=USD&api_key=285b7e03605371a7125117efa8975b26a7452c9d3674c3c8a03b591bacd700a8";
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
		if(end($this->uri->segment_array())=='trade_advance'){
			front_redirect('trade_advance/'.$pair_url, 'refresh');
		}else{
			front_redirect('trade/'.$pair_url, 'refresh');

		}
	}

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
	
	$pair_currency = $this->common_model->customQuery("select id,from_symbol_id,to_symbol_id,lastPrice,priceChangePercent from cpm_trade_pairs where status='1' order by id DESC")->result();
	if(isset($pair_currency) && !empty($pair_currency))
	{
		$Pairs_List = array();
		foreach($pair_currency as $Pair_Currency)
		{
			$from_currency_det = getcryptocurrencydetail($Pair_Currency->from_symbol_id);
            $to_currency_det = getcryptocurrencydetail($Pair_Currency->to_symbol_id);
            $pairname = $from_currency_det->currency_symbol."/".$to_currency_det->currency_symbol;
            $pairurl = $from_currency_det->currency_symbol."_".$to_currency_det->currency_symbol;

            if($from_currency_det !='' && $to_currency_det!='')
            {

            $Site_Pairs[$Pair_Currency->id] = array(
         		"currency_pair"	=> $pairname,
         		"price"	=>	($Pair_Currency->lastPrice!='')?$Pair_Currency->lastPrice:'0.000',
         		"change"	=> ($Pair_Currency->priceChangePercent!='')?$Pair_Currency->priceChangePercent:'0.000',
         		"pairurl"	=> $pairurl
            );

        	}

		}
		$data['Site_Pairs'] = array_reverse($Site_Pairs);
	}
	$data['pairs_currency'] = $this->common_model->customQuery("select * from cpm_trade_pairs where status='1' group by to_symbol_id order by to_symbol_id ASC")->result();

	$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'trade'))->row();
	$data['currencies'] = $this->common_model->customQuery("select * from cpm_currency where status='1' and currency_symbol in ('BTC','ETH','USDT','INR','TRX')")->result();
	$data['allcurrencies'] = $this->common_model->customQuery("select * from cpm_currency where status='1' ")->result();
	$data['site_common'] = site_common();	
	$this->load->view('front/trade/trade', $data);
}





function pairdetails($pairSym='')
{
	$pairdetails = getPair($pairSym);
	$data['execution'] = $pairdetails->execution;
	die(json_encode($data));
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

     $newquery = $this->common_model->customQuery('select userId,trade_id, Type, Price, datetime, pair_symbol, Amount, Fee, Total, status, date_format(datetime,"%H:%i%s") as tradetime from cpm_coin_order where status = "cancelled" and pair = '.$pair_id.$wherenew)->result();

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



// Angular Trade
public function getRecentPrice($pairSym='')
{
	// To get Pair id
	$pair=explode('_',$pairSym);
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
	$data['current_buy_price'] = lowestaskprice($pair_id);
	$data['current_sell_price'] = highestbidprice($pair_id);
	$lastMarketPrice = lastmarketprice($pair_id);
	

	$pair_details1 = $this->common_model->getTableData('trade_pairs',array('id'=>$pair_id))->row();
	if($lastMarketPrice)
		$data['lastPrice'] = $lastMarketPrice;
	else 
	$data['firstCurrency'] =$pair[0];
	$data['secondCurrency'] =$pair[1];
	$data['secondCurrencyName'] = getcurrency_name($pair[1]);
	$data['pairSymbol'] =$pair[0].' / '.$pair[1];
	$data['lastPrice'] = to_decimal($pair_details1->lastPrice,8);
	$data['priceChangePercent']=to_decimal($pair_details1->priceChangePercent,2);
	$data['highPrice']=to_decimal($pair_details1->change_high,8);
	$data['lowPrice']=to_decimal($pair_details1->change_low,8);
	$data['volume']=to_decimal($pair_details1->volume,2);
	// $data['currencyImage'] = getcurrency_image($pair[1]);
	die(json_encode($data));
}


public function getPairs2($text='')
{
	$pair_currency = $this->common_model->customQuery("select id,from_symbol_id,to_symbol_id,lastPrice,priceChangePercent from cpm_trade_pairs where status='1' order by id DESC")->result();
	if(isset($pair_currency) && !empty($pair_currency))
	{
		$Pairs_List = array();
		foreach($pair_currency as $Pair_Currency)
		{
			$from_currency_det = getcryptocurrencydetail($Pair_Currency->from_symbol_id);
            $to_currency_det = getcryptocurrencydetail($Pair_Currency->to_symbol_id);
            $pairname = $from_currency_det->currency_symbol."/".$to_currency_det->currency_symbol;
            $pairurl = $from_currency_det->currency_symbol."_".$to_currency_det->currency_symbol;
			if($to_currency_det =='USD'){
				$decimal = 2;
			}else{
				$decimal = 6;
			}
            $Site_Pairs[$Pair_Currency->id] = array(
         		"currency_pair"	=> $pairname,
         		"price"	=>	($Pair_Currency->lastPrice!='')?number_format($Pair_Currency->lastPrice,$decimal):'0.00',
         		"change"	=> ($Pair_Currency->priceChangePercent!='')?number_format(rtrim($Pair_Currency->priceChangePercent,'.'),2):'0.00',
         		"pairurl"	=> $pairurl
            );
		}
		$sitepairs = array_reverse($Site_Pairs);
	}
	die(json_encode($sitepairs));
}
public function getCurrencies($pair_symbol= '', $user_id = '')
{
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


	// trade price
	$from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol_id))->row();
	$to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol_id))->row();

	$data['from_currdet'] = $from_currency;
	$data['to_currdet'] = $to_currency;
	$data['apicheck'] = checkapi($pair_id);
    $data['from_cur'] = number_format(getBalance($user_id,$pair_details->from_symbol_id), 8);
	$data['to_cur'] = number_format(getBalance($user_id,$pair_details->to_symbol_id), 8);

	$data['maker']=getfeedetails_buy($pair_id);
	$data['taker']=getfeedetails_sell($pair_id);
	$data['pair_id'] = $pair_id;
	if($user_id)
	{
		$data['user_id'] = $user_id;
		$data['user_balance'] = getBalance($user_id);
	}
	else
	{
		$data['user_id'] = 0;
		$data['user_balance'] = 0;
	}
	$token_chart = $this->common_model->customQuery("SELECT * FROM cpm_trade_pairs WHERE id='".$pair_id."'")->row();
    $data['marketprice'] = $token_chart->buy_rate_value;
	$data['lastmarketprice'] = $token_chart->lastPrice;
	$data['buy_rate_value'] = $token_chart->buy_rate_value;
	$data['sell_rate_value'] = $token_chart->sell_rate_value;
	$data['minimum_trade_amount'] = $token_chart->min_trade_amount;
	$data['lot_size'] = $token_chart->lot_size;


	// $data['currencies'] = $this->common_model->customQuery("select currency_symbol from cpm_currency where status='1' and currency_symbol in ('BTC','ETH','USD','USDT')")->result();
	$allcurrencies = $this->common_model->customQuery("select * from cpm_currency where status='1' ")->result();

	
	$pair_currency = array();

	if($allcurrencies):
	foreach($allcurrencies as $all_cur){
		if(checkpair_by_currency($all_cur->id)==1){
			$pair_currency[]= '"'.$all_cur->currency_symbol.'"';
		}
	}
	endif;	


	$popular_currency =  implode($pair_currency,',');



	$data['currencies'] = $this->common_model->customQuery("select currency_symbol,type from cpm_currency where status='1' and currency_symbol in (".$popular_currency.") order by id ASC")->result();
	$data['digitalcurrencies'] = $this->common_model->customQuery("select currency_symbol,type from cpm_currency where status='1' and type='digital' and currency_symbol in (".$popular_currency.")")->result();
	$data['fiatcurrencies'] = $this->common_model->customQuery("select currency_symbol,type from cpm_currency where status='1' and type='fiat' and currency_symbol in (".$popular_currency.")")->result();
	if(count($allcurrencies)>0) { 
		$j = 0;
		$res_cur = array();
		foreach($allcurrencies as $cur) {



            $pair_currencys = $this->common_model->customQuery("select * from cpm_trade_pairs where status='1' and  to_symbol_id = ".$cur->id." or  from_symbol_id = ".$cur->id." order by id DESC")->result();

            
   

			if(count($pair_currencys)>0) 
            { 
            	$i = 1;
                foreach($pair_currencys as $pair) 
                { 





                     $from_currency_det = getcryptocurrencydetail($pair->from_symbol_id);
                     $to_currency_det = getcryptocurrencydetail($pair->to_symbol_id);
                     $firstcur_image =  $from_currency_det->image;
                     $pair_id = $pair->id;


					 if($to_currency_det->currency_symbol =='USD'){
						$decimal = 2;
					}
					else if($from_currency_det->currency_symbol =='FN')
					{
						$decimal = 7;
					}
					else{
						$decimal = 6;
					}
                     $pairname = $from_currency_det->currency_symbol."/".$to_currency_det->currency_symbol;
                     $pairurl = $from_currency_det->currency_symbol."_".$to_currency_det->currency_symbol;

                     $price = ($pair->lastPrice!='')?number_format($pair->lastPrice,$decimal):'0.00';
                     $change = ($pair->priceChangePercent!='')?$pair->priceChangePercent:'0.00';

                     if($change>=0)
                     {
                        
                        $class= "text-success";
                        if($change==0){
                            $changePR = '+0'.'%';
                        }
                        else{
                        $b2 =  rtrim($change,'0');
                        $changePR = '+'.number_format(rtrim($b2,'.'),2).'%';
                    }
                    }
                    else
                    {
                        $class= "text-danger";
                        $b2 =  rtrim($change,'0');
                        $changePR = number_format(rtrim($b2,'.'),2).'%';
                       
                    }
                    $remCurData['id'] = $pairurl;
			        $remCurData['pair'] = $from_currency_det->currency_symbol.' / '.$to_currency_det->currency_symbol;
			        $remCurData['price'] = $price;
			        $remCurData['class'] = $class;
			        $remCurData['change'] = number_format($changePR,2).' %';
			        $remCurData['pairurl'] = $pairurl;
					$remCurData['favourites']=count($this->common_model->getTableData('favourites',array('pair_id' => $pair_id,'user_id'=> $user_id))->row());
					$remCurData['pairid'] = $pair_id;
					$remCurData['execution'] = $pair->execution;
			        if(!in_array($pairurl, $res_cur))
			        {	
			        	array_push($res_cur,$pairurl);
						$data['allcurrencies'][$from_currency_det->currency_symbol][] = $remCurData;
			        	$data['allcurrencies'][$to_currency_det->currency_symbol][] = $remCurData;
			        }
		            $i++;
          		}
            }
            $j++;
        }
    }



 //    echo '<pre>';
	echo json_encode($data);
	// echo '<pre>';

}


public function market_api_trades($pairSym='')
{

	// To get Pair id
	$pair=explode('_',$pairSym);
	$first_currency = $pair[0];
	$second_currency = $pair[1];

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



	if(checkapi($pair_id) == 0)
	{
		// DB Trade history

		$selectFields='CO.*,date_format(CO.datetime,"%H:%i:%s") as trade_time,sum(OT.filledAmount) as totalamount,CO.Type as ordertype,CO.Price as price';
		$names = array('active', 'partially', 'stoporder','filled'); // added filled condition
		$where=array('CO.pair'=>$pair_id);
		$orderBy=array('CO.trade_id','desc');
		$groupBy=array('CO.trade_id');
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
	        $orders = array();
	        $i=1;
		
	        foreach($result as $sb)
	        {
	        	$data['id'] = $i;
		        $data['price'] = $sb->Price;
		        $data['quantity'] = $sb->Amount;
		        $data['time']= date("h:i:s",$sb->orderTime);
		        $data['ordertype'] = ($sb->ordertype == 'buy')?'Buy':'Sell';
		        $data['class'] = ($sb->ordertype == 'buy')?'text-success':'text-danger';
		        $orders[] = $data;
	            $i++;
	        }
		}
		else
		{
			$orders=[];
		}
		die(json_encode($orders));
	}
	else 
	{	


		// Api Trade History
		$pair_value=explode('_',$pairSym);
		  if(count($pair_value) > 0) 
		  {
		    $first_pair  = strtoupper($pair_value[0]);
		    $second_pair1 = strtoupper($pair_value[1]);
		    if($second_pair1=='USD'){
		    	$second_pair = 'USDC';
				$decimal = 2;
		    }
		    else{
		    	$second_pair = $second_pair1;
				$decimal = 6;
		    }
		    $coin_pair = $first_pair.'-'.$second_pair;

		    $pairdetails = getPair($pairSym);
			$execution = $pairdetails->execution;
			// Coinbase
			if($execution=='coinbase') {
 
		    $newresult = coinbase_curl('trades',$coin_pair);


		  
			// $url = 'https://api.pro.coinbase.com/products/ETH-EUR/trades';
				
				
				foreach($newresult as $newres){

					$market_trades['id']= $newres['trade_id'];
					$market_trades['price']= $newres['price'];
					$market_trades['quantity']= $newres['size'];
					$market_trades['time']= date("h:i:s",$newres['time']);
					$market_trades['ordertype']= ($newres['side']=='sell')?'Sell':'Buy';
					$market_trades['class'] = ($sb->ordertype == 'buy')?'text-success':'text-danger';
					$res_data[] = $market_trades;
				}
			}
			else if($execution=='binance') // Binance
			{
				$binance_pair = $first_pair.$second_pair;
				$json  		= file_get_contents('http://api.binance.com/api/v1/trades?symbol='.$binance_pair.'&limit=20');
				$newresult = json_decode($json,true);
					if(count($newresult)>0 && !empty($newresult))
				    {
				    	$res_data = array();
						date_default_timezone_set('UTC');
						foreach($newresult as $newres){
							$market_trades['id']= $newres['id'];
							$market_trades['price']= number_format($newres['price'],$decimal);
							$market_trades['quantity']= $newres['qty'];
							$market_trades['time']= date("h:i:s",$newres['time']);
							$market_trades['ordertype']= ($newres['isBuyerMaker']==true)?'Sell':'Buy';
							$res_data[] = $market_trades;
						}

				    }
			}	
		         
		    
		    
			
		    if($res_data&&$res_data!=0)
		    {
		    	//$res_data = shuffle_assoc($res_data);
		    	$res_data = $res_data;
		    }
		    else
		    {
		         $res_data = [];
		    }
		    
			die(json_encode($res_data));
		}
	}

}





function gettradeapiOrders($pairSym='')
{


		$pairdetails = getPair($pairSym);
		$execution = $pairdetails->execution;



		if($execution=='coinbase') {


			// Api Trade History
			$pair_value=explode('_',$pairSym);
			if(count($pair_value) > 0) 
			{
			  $first_pair  = strtoupper($pair_value[0]);
			  $second_pair1 = strtoupper($pair_value[1]);
			  if($second_pair1=='USD'){
				  $second_pair = 'USDC';
				  $decimal =2;
				  $filter = ['2 Decimals','4 Decimals','6 Decimals','Float'];
				  
			  }
			  else{
				  $second_pair = $second_pair1;
				  $decimal =6;
				  $filter = ['6 Decimals','4 Decimals','2 Decimals'];
			  }
			  
			$coin_pair = $first_pair.'-'.$second_pair;
		    // $newresult = coinbase('getProductTrades',$coin_pair);

		  //    $json_rev  		= file_get_contents('https://api.pro.coinbase.com/products/'.$coin_pair.'/trades');
			 // $newresult = json_decode($json_rev,true);

			$newresult = coinbase_curl('trades',$coin_pair); 

			 // print_r($newresult); 

		      if(count($newresult)>0 && !empty($newresult))
			  {
				  
			  	$i=1;
			  	foreach ($newresult as $result) {
			  		
			  		if($result['side']=='buy')
			  		{
					
					  $buyData['id'] = $i; 
					  $buyData['price'] = preg_replace( '/,/', '',number_format($result['price'],$decimal));
					  $buyData['quantity'] = number_format($result['size'],6);
					  $buyData['total'] = number_format($result['size']*$result['price'],$decimal);
					  $buyData['ordertype'] = 'Buy';
					  $buy_res[] = $buyData;
					  $i++;
			  		
			  		}

			  		else if($result['side']=='sell'){

					  $sellData['id'] = $i;  
					  $sellData['price'] = preg_replace( '/,/', '',number_format($result['price'],$decimal));
					  $sellData['quantity'] = number_format($result['size'],6);
					  $sellData['total'] = number_format($result['size']*$result['price'],$decimal);
					  $sellData['ordertype'] = 'Sell';
					  $sell_res[] = $sellData;
					  $i++;

			  		}


			  	}


			  }
			  		$data['buy_res'] = $buy_res;
					$data['sell_res'] = $sell_res;
					die(json_encode($data));

				}		
					
			}
			else if($execution=='binance')
			{
					$pair_value=explode('_',$pairSym);
				 	if(count($pair_value) > 0) 
					{
						$pair_value=explode('_',$pairSym);
						$first_pair  = strtoupper($pair_value[0]);
				    	$second_pair1 = strtoupper($pair_value[1]);
					}

				  $coin_pairrev = $first_pair.$second_pair1;
				  $json_rev  		= file_get_contents('http://api.binance.com/api/v1/depth?symbol='.$coin_pairrev.'&limit=20');
				  $newresult_rev = json_decode($json_rev,true);



				  if(count($newresult_rev)>0 && !empty($newresult_rev))
				  {
					  $buy_orders = $newresult_rev['bids'];
					  $sell_orders = $newresult_rev['asks'];
					  $buy_res = array();
					  $sell_res = array();
					  $i=1;
				  foreach($sell_orders as $sell)
				  {
					  $sellData['id'] = $i;
					  $sellData['price'] = preg_replace( '/,/', '',number_format($sell[0],6));
					  $sellData['quantity'] = number_format($sell[1],6);
					  $sellData['total'] = number_format($sell[0]*$sell[1],6);
					  $sellData['ordertype'] = 'Sell';
					  $sell_res[] = $sellData;
					  $i++;
				  }
				  foreach($buy_orders as $key => $buy)
				  {
					  $ask_bar = ($buy[0] - $sell_orders[$key][0])/$buy[0] *100;
					  $buyData['id'] = $i;
					  $buyData['price'] = preg_replace( '/,/', '',number_format($buy[0],6));
					  $buyData['ask_bar'] = $ask_bar;
					  $buyData['quantity'] = number_format($buy[1],6);
					  $buyData['total'] = number_format($buy[0]*$buy[1],6);
					  $buyData['ordertype'] = 'Buy';
					  $buy_res[] = $buyData;
					  $i++;
				  }
				  }

				$data['buy_res'] = $buy_res;
				$data['sell_res'] = $sell_res;
				die(json_encode($data));
			}
			else // DataBase
			{
				 $buy_res = array();
				 $sell_res = array();
				 $i=1;
				 $coinorder =  $this->common_model->customQuery("select * from cpm_coin_order where pair=".$pairdetails->id." and status ='active' or status ='partially'")->result();
				 foreach ($coinorder as $orders) {
				 		
				 	
				 	if($orders->Type=='buy')
			  		{
					
					  $buyData['id'] = $i; 
					  $buyData['price'] = preg_replace( '/,/', '',$orders->Price);
					  $buyData['quantity'] = preg_replace( '/,/', '',$orders->Amount);
					  $buyData['total'] = number_format($orders->Amount * $orders->Price,6);
					  $buyData['ordertype'] = 'Buy';
					  $buy_res[] = $buyData;
					  $i++;
			  		
			  		}
			  		else if($orders->Type=='sell')
			  		{
			  		  $sellData['id'] = $i;  
					  $sellData['price'] = preg_replace( '/,/', '',$orders->Price);
					  $sellData['quantity'] = preg_replace( '/,/', '',$orders->Amount);
					  $sellData['total'] = number_format($orders->Amount * $orders->Price,6);
					  $sellData['ordertype'] = 'Sell';
					  $sell_res[] = $sellData;
					  $i++;
			  		}

				 	}
				 $data['buy_res'] = $buy_res;
				$data['sell_res'] = $sell_res;
				die(json_encode($data));		

			}  
			  
	
}



function getpairdetails($pairSym='')
{



			$pairdetails = getPair($pairSym);
			$execution = $pairdetails->execution;
			$lot_size  = $pairdetails->lot_size;
			if($execution=='coinbase') {
			// Api Trade History
			$pair_value=explode('_',$pairSym);
			if(count($pair_value) > 0) 
			{
			  $first_pair  = strtoupper($pair_value[0]);
			  $second_pair = strtoupper($pair_value[1]);
			  $coin_pair = $first_pair.'-'.$second_pair;
		      $newresult = coinbase_curl('stats',$coin_pair);

		      $getProductTicker = coinbase_curl('ticker',$coin_pair);

		      if(count($newresult)>0 && !empty($newresult))
			  {
				  	$price_cal = $newresult['last'] - $newresult['open']; 
 					$pricecals = $newresult['open'] / 100; 
					$priceChange = $price_cal / $pricecals;
					$priceChangePercent = sprintf("%.9f", $priceChange);  


			  	$data = array(
					"symbol" => $first_pair.$second_pair,
			  		"priceChange" => '',
			  		"priceChangePercent" => $priceChangePercent,
			  		"lastPrice" => $newresult['last'],
			  		"askPrice" => $getProductTicker['ask'],
			  		"bidPrice" => $getProductTicker['bid'],
			  		"open" => $newresult['open'],
			  		"highPrice" => $newresult['high'],
			  		"lowPrice" => $newresult['low'],
			  		"volume" => number_format($newresult['volume'],6),
			  		"lot_size" => $lot_size
				);

			  	$updateTableData = array('priceChangePercent'=> $priceChangePercent,
                  'lastPrice'=>$newresult['last'],
                  'volume'=>number_format($newresult['volume'],6),
                  'change_high'=>$newresult['high'],
                  'change_low'=>$newresult['low'],
                  'buy_rate_value'=>$newresult['last'],
                  'sell_rate_value'=>$newresult['last']
                );


			  	 $this->common_model->updateTableData('trade_pairs', array('id' => $pairdetails->id), $updateTableData);

			  }

				die(json_encode($data));
			  
		  }
		}
		else if($execution=='binance')
		{
			$pair_value=explode('_',$pairSym);
			if(count($pair_value) > 0) 
			{
			  $first_pair  = strtoupper($pair_value[0]);
			  $second_pair = strtoupper($pair_value[1]);
			$pair_symbols = $first_pair.$second_pair;
	        $urls = file_get_contents("https://api.binance.com/api/v1/ticker/24hr?symbol=".$pair_symbols);
	        $ress = json_decode($urls,true);



	        if ($ress['symbol'] != '') 
	          {
	            $data = array(
	          		"symbol" => $pair_symbols,
			  		"priceChange" => $ress['priceChange'],
			  		"priceChangePercent" => $ress['priceChangePercent'],
			  		"lastPrice" => $ress['lastPrice'],
			  		"askPrice" => $ress['askPrice'],
			  		"bidPrice" => $ress['bidPrice'],
			  		"open" => $ress['openPrice'],
			  		"highPrice" => $ress['highPrice'],
			  		"lowPrice" => $ress['lowPrice'],
			  		"volume" => number_format($ress['volume'],6),
			  		"lot_size" => $lot_size

			  		);

	            $updateTableData = array('priceChangePercent'=> $ress['priceChangePercent'],
                  'lastPrice'=>$ress['lastPrice'],
                  'volume'=> number_format($ress['volume'],6),
                  'change_high'=> $ress['highPrice'],
                  'change_low'=> $ress['lowPrice'],
                  'buy_rate_value'=>$ress['lastPrice'],
                  'sell_rate_value'=>$ress['lastPrice']
                );
				 $this->common_model->updateTableData('trade_pairs', array('id' => $pairdetails->id), $updateTableData);



	          }
	          die(json_encode($data));
	        }  
		}
		else // DataBase
		{


			$pair_value=explode('_',$pairSym);
			$first_pair  = strtoupper($pair_value[0]);
			$second_pair = strtoupper($pair_value[1]);
			$pair_symbols = $first_pair.$second_pair;

			$data = array(
	          		"symbol" => $pair_symbols,
			  		"priceChange" => '',
			  		"priceChangePercent" => $pairdetails->priceChangePercent,
			  		"lastPrice" => $pairdetails->lastPrice,
			  		"askPrice" => $pairdetails->lastPrice,
			  		"bidPrice" => $pairdetails->lastPrice,
			  		"open" => $pairdetails->lastPrice,
			  		"highPrice" => $pairdetails->change_high,
			  		"lowPrice" => $pairdetails->change_low,
			  		"volume" => $pairdetails->volume,
			  		"lot_size" => $lot_size
			);
			 die(json_encode($data));


		} 

}

 

function gettradefullapiOrders($pairSym='',$type='')
{
			// Api Trade History
			$pair_value=explode('_',$pairSym);
			if(count($pair_value) > 0) 
			{
			  $first_pair  = strtoupper($pair_value[0]);
			  $second_pair1 = strtoupper($pair_value[1]);
			  if($second_pair1=='USD'){
				  $second_pair = 'USDC';
				  $decimal = 2;
			  }
			  else{
				  $second_pair = $second_pair1;
				  $decimal = 6;
			  }
			  $coin_pair = $first_pair.$second_pair;
			  $json  		= file_get_contents('http://api.binance.com/api/v1/depth?symbol='.$coin_pair.'&limit=50');
			  $newresult = json_decode($json,true);
			  if(count($newresult)>0 && !empty($newresult))
			  {
				  $buy_orders = $newresult['bids'];
				  $sell_orders = $newresult['asks'];
				  $buy_res = array();
				  $sell_res = array();
				  $i=1;
					$max_sell_amount = array();
				  foreach($sell_orders as $key =>  $sell)
				  {
					$max_sell_amount[]= $sell[1];
				  }

				  $max_buy_amount = array();
				  foreach($buy_orders as $key =>  $buy)
				  {
					$max_buy_amount[]= $buy[1];
				  }
				//   (Ask Price - Bid Price)/Ask Price x 100 = BidAsk Spread Percentage
				  foreach($sell_orders as $key => $sell)
				  {
					$x = $sell[1]; 
					$total = max($max_sell_amount); 
					$ask_bar = ($x*100)/$total;
					  $sellData['id'] = $i;
					  $sellData['price'] =preg_replace( '/,/', '',number_format($sell[0],$decimal));;
					  $sellData['ask_bar'] = number_format($ask_bar,2);
					  $sellData['quantity'] = number_format($sell[1],6);
					  $sellData['total'] = number_format($sell[0]*$sell[1],$decimal);
					  $sellData['ordertype'] = 'Sell';
					  $sell_res[] = $sellData;
					  $i++;
				  }
				  foreach($buy_orders as $key => $buy)
				  {
						$x = $buy[1]; 
						$total = max($max_buy_amount); 
						$bids_bar = ($x*100)/$total;
					  $buyData['id'] = $i;
					  $buyData['price'] = preg_replace( '/,/', '',number_format($buy[0],$decimal));;
					  $buyData['bids_bar'] = $bids_bar;
					  $buyData['quantity'] = number_format($buy[1],$decimal);
					  $buyData['total'] = number_format($buy[0]*$buy[1],$decimal);
					  $buyData['ordertype'] = 'Buy';
					  $buy_res[] = $buyData;
					  $i++;
				  }
			  }
			  else
			  {
				  $coin_pairrev = $second_pair.$first_pair;
				  $json_rev  		= file_get_contents('http://api.binance.com/api/v1/depth?symbol='.$coin_pairrev.'&limit=50');
				  $newresult_rev = json_decode($json_rev,true);
				  if(count($newresult_rev)>0 && !empty($newresult_rev))
				  {
					  $buy_orders = $newresult_rev['bids'];
					  $sell_orders = $newresult_rev['asks'];
					  $buy_res = array();
					  $sell_res = array();
					  $i=1;
					  foreach($sell_orders as $key => $sell)
					  {
						$x = $sell[1]; 
						$total = max($max_sell_amount); 
						$ask_bar = ($x*100)/$total;
						  $sellData['id'] = $i;
						  $sellData['price'] = preg_replace( '/,/', '',number_format($sell[0],$decimal));;
						  $sellData['ask_bar'] = number_format($ask_bar,2);
						  $sellData['quantity'] = number_format($sell[1],6);
						  $sellData['total'] = number_format($sell[0]*$sell[1],$decimal);
						  $sellData['ordertype'] = 'Sell';
						  $sell_res[] = $sellData;
						  $i++;
					  }
					  foreach($buy_orders as $key => $buy)
					  {
							$x = $buy[1]; 
							$total = max($max_buy_amount); 
							$bids_bar = ($x*100)/$total;
						  $buyData['id'] = $i;
						  $buyData['price'] = preg_replace( '/,/', '',number_format($buy[0],$decimal));;
						  $buyData['bids_bar'] = $bids_bar;
						  $buyData['quantity'] = number_format($buy[1],$decimal);
						  $buyData['total'] = number_format($buy[0]*$buy[1],$decimal);
						  $buyData['ordertype'] = 'Buy';
						  $buy_res[] = $buyData;
						  $i++;
					  }
				  } 
			  }
			  
	if($type=='buy'){
		$data['buy_res'] = $buy_res;
	}
	if($type=='buy'){
		$data['sell_res'] = $sell_res;
	}else{
		$data['buy_res'] = $buy_res;
		$data['sell_res'] = $sell_res;
	}		  

	die(json_encode($data));
			  
			//   die(json_encode($res_data));
		  }
}


public function getSession($test='')
{
	// $user_id=$this->session->userdata('user_id');
	// echo ' User '.$user_id;
	// exit();
	// $user_id=$_SESSION["user_id"];
	$user_id= 1;

	if($user_id != '')
	{
		$results = $this->common_model->getTableData('users', array('id' => $user_id))->row();

		$result = array(
			"id"       => $results->id,
			"cpm_username" => $results->cpm_username,
			"useremail" => getUserEmail($results->id)

		);

    	echo json_encode($result);
	} else {
		$result = array("id"=> 0);
		echo json_encode($result);
	} 
	exit();  
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





public function login_check($email='',$password='',$tfa='')
{

$ip_address = get_client_ip();
$array = array('status' => 0, 'msg' => '', 'expiry' => '');

if ($email != '' && $password !='') {
    $email = lcfirst(urldecode($email));
    $password = urldecode($password);
    $prefix = get_prefix();
    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $check = checkSplitEmail($email, $password);
    }
    $array['email'] = $email;
    $array['password'] = $password;
    if (!$check) {
        //vv
        $array['msg'] = 'Enter Valid Login Details';
    } else {
        if ($check->verified != 1) {

            $array['msg'] = 'Please check your email to activate Moonex Crypto account';
          
        } else {
            $array['status'] = 1;
            $array['user_details'] = $check;
            $array['username'] = $check->cpm_username;
            $array['user_id'] = $check->id;
            if ($check->randcode == 'enable' && $check->secret != '') { 
                $array['tfa_status'] = 1;
                $login_tfa = ($tfa == '-')?'':$tfa;
                $check1 = $this->checktfa($check->id, $login_tfa);
                if ($check1) {
                    $session_data = array(
                        'user_id' => $check->id,
                    );
                    $this->session->set_userdata($session_data);
                    $this->common_model->last_activity('Login', $check->id);
                    $this->session->set_flashdata('success', $this->lang->line('Welcome back . Logged in Successfully'));
                    $array['msg'] = 'Welcome back . Logged in Successfully';
                    if ($check->verify_level2_status == 'Completed') {
                        $array['login_url'] = 'dashboard';
                    }
                    $array['tfa_status'] = 0;
                } else {
                    $array['msg'] = 'Enter Valid TFA Code';
                }
            } else { 
                $session_data = array(
                    'user_id' => $check->id,
                );
                $this->session->set_userdata($session_data);
                $this->common_model->last_activity('Login', $check->id, "", $ip_address);
                $array['tfa_status'] = 0;
                //if($check->verify_level2_status=='Completed')
                //{
                //$this->session->set_flashdata('success', 'Welcome back . Logged in Successfully');
                $array['msg'] = 'Welcome back . Logged in Successfully';
                $array['login_url'] = 'dashboard';
                //}
            }
        }
    }
} else {
    $array['msg'] = $this->lang->line('Login error. Please check username & password');
}
die(json_encode($array));
}
function get_favourite($user_id=''){
	if($user_id!=0){
		$result = $this->common_model->getTableData('favourites', array('user_id' => $user_id))->result();
	}else{
		$result = $this->common_model->getTableData('favourites', array('ipaddress' => $_SERVER['REMOTE_ADDR']))->result();
	}
	if($result):
		foreach($result as $res){
	$pair_currency = $this->common_model->customQuery("select id,from_symbol_id,to_symbol_id,lastPrice,priceChangePercent from cpm_trade_pairs where id=".$res->pair_id." order by id DESC")->result();
	if(isset($pair_currency) && !empty($pair_currency))
	{
		$Pairs_List = array();
		foreach($pair_currency as $Pair_Currency)
		{
			$from_currency_det = getcryptocurrencydetail($Pair_Currency->from_symbol_id);
            $to_currency_det = getcryptocurrencydetail($Pair_Currency->to_symbol_id);
            $pairname = $from_currency_det->currency_symbol."/".$to_currency_det->currency_symbol;
            $pairurl = $from_currency_det->currency_symbol."_".$to_currency_det->currency_symbol;
			$change = ($Pair_Currency->priceChangePercent!='')?$Pair_Currency->priceChangePercent:'0.00';
			if($change>=0)
			{
			   $class= "text-success";
		   }
		   else
		   {
			   $class= "text-danger";
		   }
            $Site_Pairs[$Pair_Currency->id] = array(
         		"currency_pair"	=> $pairname,
				 "pair" => $from_currency_det->currency_symbol.' / '.$to_currency_det->currency_symbol,
         		"price"	=>	($Pair_Currency->lastPrice!='')?$Pair_Currency->lastPrice:'0.00',
         		"change"	=> ($Pair_Currency->priceChangePercent!='')?number_format(rtrim($Pair_Currency->priceChangePercent,'.'),2):'0.00',
         		"pairurl"	=> $pairurl,
         		"pair_id" => $Pair_Currency->id,
				 "class" => $class,
				 "execution" => $Pair_Currency->execution
            );
		}
		$sitepairs = array_reverse($Site_Pairs);
	}
}
endif;
die(json_encode($sitepairs));
	// echo json_encode($result);
}

function check_favourite($user_id='',$pair_id=''){
	if($user_id!=0){
		$result = $this->common_model->getTableData('favourites', array('user_id' => $user_id,'pair_id'=>$pair_id))->row();
	}else{
		$result = $this->common_model->getTableData('favourites', array('ipaddress' => $_SERVER['REMOTE_ADDR'],'pair_id'=>$pair_id))->row();
	}
	if($result >0):
	$status = 1;	
	else:
	$status = 0;	
	endif;
die(json_encode($status));
	// echo json_encode($result);
}


public function execute_order($execute='')
{		

	if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
	    $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
	}


	if($this->input->post()) {
	$user_id = $this->input->post('user_id');
	$amount =$this->input->post('Amount');
	$price = $this->input->post('Price');
	$limit_price = $this->input->post('limit_price');
	$total = $this->input->post('Total');
	$fee = $this->input->post('Fee');
	$pair = $this->input->post('pairing_id');
	$ordertype = $this->input->post('ordertype');
	$type = $this->input->post('a');
	$loan_rate = $this->input->post('loan_rate');
	$pagetype = $this->input->post('pagetype');

	$response = array('status'=>'','msg'=>'');
	if($user_id !="" )
	{	
		
		$response 	= $this->site_api->createOrder($user_id,$amount,$price,$limit_price,$total,$fee,$pair,$ordertype,$type,$loan_rate,$pagetype);
			
	}
	else
	{
		$response['status'] = "login";
	}
 }
 else
 {
 	$response['status'] = "error";
 }	




	echo json_encode($response);
}



public function gettradeopenOrders2($type,$pair_symbol,$user_id)
{
	if($user_id){
		$user_id = $user_id;
	}else{
		$user_id = $this->session->userdata('user_id');
	}
	$pair=explode('_',$pair_symbol);
	$pair_id=0;

	// Coinbase Mapping Start
	$coinbase_pair = str_replace('_', '-', $pair_symbol);
	$pair_check = $this->common_model->getTableData('coinbase_order', array('product_id' => $coinbase_pair))->row();
	if(!empty($coinbase_pair) && !empty($pair_check)) {
		//$coinbase_mapping = $this->coinbase_mapping($coinbase_pair);
	}
	// Coinbase Mapping End 


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
	$selectFields='CO.*,SUM(CO.Amount) as TotAmount,date_format(CO.datetime,"%d-%m-%Y %H:%i") as trade_time,sum(OT.filledAmount) as totalamount';
	$names = array('active', 'partially', 'stoporder');
	$where=array('CO.pair'=>$pair_id,'CO.userId'=>$user_id);
	// $where=array('CO.userId'=>$user_id);
	$orderBy=array('CO.trade_id','desc');
	//$groupBy=array('CO.Price'); // commented due to live server group by issue
	$groupBy=array('CO.trade_id');
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
		$orders=[];
	}
	die(json_encode($orders));
}
public function getOrderHistory($type,$pair_symbol,$user_id="")
{


	// Coinbase Mapping call Start 
	$coinbase_pair = str_replace('_', '-', $pair_symbol);
	// print_r($coinbase_pair);
	// exit();
	$pair_check = $this->common_model->getTableData('coinbase_order', array('product_id' => $coinbase_pair))->row();


	if(!empty($coinbase_pair) && !empty($pair_check)) {
		$coinbase_mapping = $this->coinbase_mapping($coinbase_pair);
	}

	// Binance Order Mapping 
	$this->binance_mapping();




	// echo "<pre>";
	// print_r($coinbase_mapping);
	// echo "<pre>";
	// exit();
	// Coinbase Mapping call End


	if($user_id){
		$user_id = $user_id;
	}else{

		$user_id = $this->session->userdata('user_id');
	}

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
		$user_id = $user_id;
		$joins = array('coin_order as b'=>'a.sellorderId = b.trade_id','coin_order as c'=>'a.buyorderId = c.trade_id');

		$where = array('b.pair'=>$pair_id,'c.userId'=>$user_id);
		$where_or = array('b.userId'=>$user_id);
		$wherenew = ' and userId='.$user_id;
		$transactionhistory = $this->common_model->getJoinedTableData('ordertemp as a',$joins,$where,'a.*,
   date_format(b.datetime,"%H:%i:%s") as sellertime,b.trade_id as seller_trade_id,date_format(c.datetime,"%H:%i:%s") as buyertime,c.pair_symbol as pair_symbol,c.trade_id as buyer_trade_id,a.askPrice as sellaskPrice,c.Price as buyaskPrice,b.Fee as sellerfee,c.Fee as buyerfee,b.Total as sellertotal,c.Total as buyertotal, c.status as status, b.status as statuss, b.ordertype as ordertype','',$where_or,'',0,40,array('a.tempId','DESC'))->result();


		// $transactionhistory = $this->common_model->customQuery("SELECT * FROM cpm_coin_order WHERE pair = ".$pair_id." AND userId = ".$user_id." AND status IN ('filled','cancelled','partially')")->result();
		

		//  $newquery = $this->common_model->customQuery('select userId,trade_id,ordertype ,Type, pair_symbol, Price, Amount, Fee, Total, status, date_format(datetime,"%Y-%m-%d %H:%i:%s") as tradetime from cpm_coin_order where status = "cancelled" and pair = '.$pair_id.$wherenew.' order by trade_id DESC limit 0,10')->result();
		// echo $this->db->last_query();exit();
		// $newquery = $this->common_model->customQuery('select userId,trade_id, Type, pair_symbol, Price, Amount, Fee, Total, status, date_format(datetime,"%Y-%m-%d %H:%i:%s") as tradetime from cpm_coin_order where status = "cancelled" and pair = '.$pair_id.$wherenew.' order by trade_id DESC limit 0,10')->result();

		// echo "<pre>";
		// print_r(array_merge($transactionhistory,$newquery));
		// echo "<pre>";
		if(count($transactionhistory)>0 || count($newquery))
    {
        // $transactionhistory_1 = array_merge($transactionhistory,$newquery);
        $historys = $transactionhistory;
        // print_r($historys);exit;
		
    }
    else
    {
        $historys=0;
    }
	// print_r($historys);exit();
	// $transactionhistory = $historys
	if($historys != 0 && count($historys)> 0){
		$data = array();
		foreach($historys as $order_data){

				$askAmount = $order_data->askAmount;
				$buyer_trade_id = $order_data->buyer_trade_id;
				$buy_userId = $order_data->buyerUserid;
				$seller_trade_id = $order_data->seller_trade_id;
				$sell_userId = $order_data->sellerUserid;
				$filledAmount = $order_data->filledAmount;
				$pairy = $order_data->pair_symbol;
				if($order_data->ac_type=='buy')
					$clss = 'text-success';
				else
					$clss = 'text-danger';


				// echo $user_id;
				if($buy_userId == $sell_userId){
					if($buyer_trade_id < $seller_trade_id){
						$data []= array(
						'type1' =>  ucfirst($order_data->ac_type),
						'filledAmount' => $order_data->filledAmount,
						'askPrice' => $order_data->buyaskPrice,
						'sellaskPrice' => $order_data->sellaskPrice,
						'orderTime1' => $order_data->buyertime,
						'orderTime2' => $order_data->sellertime,
						'buyerfee' => $order_data->buyerfee,
						'sellerfee' => $order_data->sellerfee,
						'buyertotal' => $order_data->buyertotal,
						'sellertotal' => $order_data->sellertotal,
						'time1' => $order_data->buyertime,
						'datetime' => $order_data->datetime,
						'orderType' => $order_data->ordertype,
						'myuserID' => $order_data->buyerUserid,
						'b_tot' => ($order_data->buyaskPrice * $order_data->filledAmount),
						'status' => ucfirst($order_data->status),
						'class' => $clss,
						);
						// echo json_encode($data);
					}else{
						$data []= array(
							'type1' => ucfirst($order_data->ac_type),
							'filledAmount' => $order_data->filledAmount,
							'askPrice' => $order_data->sellaskPrice,
							'sellaskPrice' => $order_data->buyaskPrice,
							'orderTime1' => $order_data->buyertime,
							'orderTime2' => $order_data->sellertime,
							'pairy' => $order_data->pair_symbol,
							'buyerfee' => $order_data->buyerfee,
							'sellerfee' => $order_data->sellerfee,
							'buyertotal' => $order_data->buyertotal,
							'sellertotal' => $order_data->sellertotal,
							'time2' => $order_data->buyertime,
							'datetime' => $order_data->datetime,
							'orderType' => $order_data->ordertype,
							'myuserID' => $order_data->sellerUserid,
							'b_tot' => ($order_data->sellaskPrice * $order_data->filledAmount),
							'status' => ucfirst($order_data->status),
							'class' => $clss,
							);
							// echo json_encode($data);
					}
				}else{
					if($buy_userId == $user_id){
						$data []= array(
							'type1' => ucfirst($order_data->ac_type),
							'filledAmount' => $order_data->filledAmount,
							'askPrice' => $order_data->buyaskPrice,
							'sellaskPrice' => $order_data->sellaskPrice,
							'orderTime1' => $order_data->buyertime,
							'orderTime2' => $order_data->sellertime,
							'pairy' => $order_data->pair_symbol,
							'buyerfee' => $order_data->buyerfee,
							'sellerfee' => $order_data->sellerfee,
							'buyertotal' => $order_data->buyertotal,
							'sellertotal' => $order_data->sellertotal,
							'time2' => $order_data->buyertime,
							'datetime' => $order_data->datetime,
							'orderType' => $order_data->ordertype,
							'myuserID' => $order_data->buyerUserid,
							'b_tot' => ($order_data->buyaskPrice * $order_data->filledAmount),
							'status' => ucfirst($order_data->status),
							'class' => $clss,
							);
							// echo json_encode($data);
					}else if($sell_userId == $user_id){
						$data []= array(
							'type1' => ucfirst($order_data->ac_type),
							'filledAmount' => $order_data->filledAmount,
							'askPrice' => $order_data->sellaskPrice,
							'sellaskPrice' => $order_data->buyaskPrice,
							'orderTime1' => $order_data->buyertime,
							'orderTime2' => $order_data->sellertime,
							'pairy' => $order_data->pair_symbol,
							'buyerfee' => $order_data->buyerfee,
							'sellerfee' => $order_data->sellerfee,
							'buyertotal' => $order_data->buyertotal,
							'sellertotal' => $order_data->sellertotal,
							'time2' => $order_data->buyertime,
							'datetime' => $order_data->datetime,
							'orderType' => $order_data->ordertype,
							'myuserID' => $order_data->buyerUserid,
							'b_tot' => ($order_data->sellaskPrice * $order_data->filledAmount),
							'status' => ucfirst($order_data->statuss),
							'class' => $clss,
							);
							// echo json_encode($data);
					}
				}				
			// }
		}
		 
	}
	echo json_encode($data);
	// echo json_encode($data);
    // return $historys;
	// echo json_encode($historys);
}
public function cancel_active_order($tradeid,$pair_id,$user_id)
{
	$sessionId=$this->session->userdata('user_id'); 
	// $sessionId = '5';
	if($sessionId == $user_id)
	// if($user_id)
	{
		$response=$this->site_api->close_active_order($tradeid,$pair_id,$user_id);
		echo json_encode($response);
	} else {
		$response = array('status'=>'error','msg'=>"Can't cancel, since it was not posted by you.");
		echo json_encode();
	}
}

function getSiteSetting($type=''){
	$data = $this->common_model->getTableData('site_settings', array('id' => '1'))->row();
	echo json_encode($data);
}

function get_news($type=''){
    $news_data = $this->common_model->getTableData('news', array('status' => '1'))->result();
	if($news_data):
		$data = array();
		foreach($news_data as $news_da){
			$data []= array(
				'id'=>$news_da->id,
				'english_title' =>$news_da->english_title,
				'english_meta_keywords' =>$news_da->english_meta_keywords,
				'english_meta_description' =>$news_da->english_meta_description,
				'english_heading' =>$news_da->english_heading,
				'english_content' =>strip_tags($news_da->english_content),
				'link' =>$news_da->link,
				'created' => gmdate("Y-m-d h:i", $news_da->created)
				
			);
		}
	endif;	
    echo json_encode($data);
}


function angular_test($test=''){
	$all_currencies = $this->common_model->getTableData('currency',array('status' => 1))->result();
	// print_r($all_currencies);
	$pair_currency = '';

	if($all_currencies):
	foreach($all_currencies as $all_cur){
		if(checkpair_by_currency($all_cur->id)==1){
			$pair_currency[]= '"'.$all_cur->currency_symbol.'"';
		}
	}
	endif;	
	$popular_currency =  implode($pair_currency,',');
	// print_r($popular_currency);
	// print_r(json_decode($popular_currency));
	$data['currencies'] = $this->common_model->customQuery("select currency_symbol from cpm_currency where status='1' and currency_symbol in (".$popular_currency.")")->result();
	echo json_encode($data);
}


function close_active_order($user_id = '')
{		
	if($user_id){
		$user_id = $user_id;
	}else{
		$user_id = $this->session->userdata('user_id'); 
	}
	$response=$this->site_api->close_allactive_order($user_id);
	echo json_encode($response);
}
public function get_close_orders($pair_symbol,$user_id)
{
if($user_id){
$user_id = $user_id;
}else{
$user_id = $this->session->userdata('user_id');
}

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
$selectFields='CO.*,SUM(CO.Amount) as TotAmount,date_format(CO.datetime,"%d-%m-%Y %H:%i") as trade_time,sum(OT.filledAmount) as totalamount';

// $names = array('filled');
$names = array('cancelled');
$where=array('CO.pair'=>$pair_id,'CO.userId'=>$user_id);
// $where=array('CO.userId'=>$user_id);
$orderBy=array('CO.trade_id','desc');
//$groupBy=array('CO.Price'); // commented due to live server group by issue
$groupBy=array('CO.trade_id');
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
    $orders=[];
}
die(json_encode($orders));
}  


function add_favourite($pair='',$user_id=''){
		if($user_id){
		$user_id = $user_id;
		}else{
		$user_id = $this->session->userdata('user_id');
		}
		if($user_id){
		$insert_data['user_id'] = $user_id;
		$insert_data['pair_id'] = $pair;
		$insert_data['ipaddress'] = $_SERVER['REMOTE_ADDR'];
		$get_favourites = $this->common_model->getTableData('favourites', array('user_id' => $user_id,'pair_id'=>$pair))->row();
		if($get_favourites==0){
		$this->common_model->insertTableData('favourites',$insert_data);
		$data['status'] ='insert';
		}else{
		$this->common_model->deleteTableData('favourites',array('id'=>$get_favourites->id));
		$data['status'] ='delete';
		}
		$data['result']= $this->common_model->getTableData('favourites', array('user_id' => $user_id))->result();
		}else{
		$insert_data['user_id'] = 0;
		$insert_data['pair_id'] = $pair;
		$insert_data['ipaddress'] = $_SERVER['REMOTE_ADDR'];
		$get_favourites = $this->common_model->getTableData('favourites', array('ipaddress' => $_SERVER['REMOTE_ADDR'],'pair_id'=>$pair))->row();
		if($get_favourites==0){ 
		$this->common_model->insertTableData('favourites',$insert_data);
		$data['status'] ='insert';
		}else{
		$this->common_model->deleteTableData('favourites',array('id'=>$get_favourites->id));
		$data['status'] ='delete';
		}
		$data['result']= $this->common_model->getTableData('favourites', array('ipaddress' => $_SERVER['REMOTE_ADDR']))->result();
		}
		echo json_encode($data);
} 


function balance($user_id = ''){

	if($user_id){
		$user_id = $user_id;
	}else{
		$user_id = $this->session->userdata('user_id'); 
	}
	$data = array();
	
	// $data['wallet'] = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));
	// $data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
	$dig_currency = $this->common_model->getTableData('currency', array('status' => 1), '', '', '', '', '', '', array('sort_order', 'ASC'))->result();
	
	if($dig_currency):
		foreach($dig_currency as $curr){
		
			$curr_symbol = $curr->currency_symbol;
		
	$order_balance = $this->common_model->customQuery("SELECT SUM(Total) as Total FROM `cpm_coin_order` WHERE `pair_symbol` LIKE '%$curr_symbol%'")->row();    
		$order= $order_balance->Total;
			if($order==""){
				$order_bal="0";
			}
			else
			{
				$order_bal = $order_balance->Total;
			}

		$userbalance = getBalance($user_id,$curr->id);
		$USDT_Balance = $userbalance * $curr->online_usdprice;
		$data[] =array(
				'id'=>$curr->id,
				'currecny_name'=>$curr->currency_name,
				'currency_symbol'=>$curr->currency_symbol,
				'balance'  => $userbalance,
				'USDT_Balance'  => $USDT_Balance,
				'order_balance' => $order_bal,
				'available_balance' => $userbalance - $order_bal
			);
					
		} 
		
	endif;    
	
	echo json_encode($data);
	
	}


// Coinbase Order Mapping 

function coinbase_mapping($pair)
{ 
	// $pair = 'LTC-EUR';
	// exit();
	$getOrders = coinbase('getFills',$pair);
	// echo '<pre>';   
	// print_r($getOrders);
	// echo '<pre>';
	// exit();

	foreach ($getOrders as $opens) {
		$getuserorder = $this->common_model->getTableData('coinbase_order',array('coinbase_id'=>$opens['order_id'],'coinbase_status' =>'active'))->row();
			
		if($getuserorder) {
		$type               =   ucfirst($opens['side']);
		
		// print_r($opens); 
		// echo "<br>";
		$pair_details = $this->common_model->getTableData('trade_pairs',array('id'=>$getuserorder->pair_id),'from_symbol_id,to_symbol_id,api_status')->row();	

		
		$coinorder = $this->common_model->getTableData('coin_order',array('trade_id'=>$getuserorder->trade_id))->row();	
		$Total				=	$coinorder->Total;
		$Fee				=	$coinorder->Fee;
		$Price              =   $coinorder->Price;
		$Amount             =   $coinorder->Amount;


	if($opens['side']=='buy')
	{
		$second_type ='sell'; 
		$currency = $pair_details->to_symbol_id;
		$Getbalance = getBalance($getuserorder->user_id,$currency);

		if($Total <= $Getbalance)
		{
			$newbalance = $Getbalance - $Total;
		}

		// $newbalance = $Getbalance - $Total;

	}
	else if($opens['side']=='sell')
	{
		$second_type ='buy';
		$currency = $pair_details->from_symbol_id;
		$Getbalance = getBalance($getuserorder->user_id,$currency); 

		if($Amount <= $Getbalance)
		{
			$newbalance = $Getbalance - $Amount;
		}
	}  

	//echo "New Balance-- ".$Getbalance; 

	if($coinorder->status!='filled') {  


		$date               =   date('Y-m-d');
		$time               =   date("H:i:s");
		$datetime           =   date("Y-m-d H:i:s");
		$data    =   array(
			'sellorderId'       =>  $getuserorder->trade_id,
			'sellerUserid'      =>  $getuserorder->user_id,
			'askAmount'         =>  $getuserorder->amount,
			'askPrice'          =>  $Price,
			'filledAmount'      =>  $getuserorder->amount, 
			'buyorderId'        =>  $getuserorder->trade_id,
			'buyerUserid'       =>  $getuserorder->user_id,
			'sellerStatus'      =>  "inactive",
			'buyerStatus'       =>  "inactive",
			"pair"              =>  $getuserorder->pair_id,
			"ac_price"          =>  $Price,
			"wantPrice"         =>  $Price,
			"ac_type"         	=>  $opens['side'],
			"ac_amount"         =>  $getuserorder->amount,
			"datetime"          =>  $datetime
		);
		
	$inserted=$this->common_model->insertTableData('ordertemp', $data);


 
	// $updatequery = updateBalance($getuserorder->user_id,$currency,$newbalance); 

	// Trade Lib Call  

	//$response 		 =  $this->site_api->ordercompletetype($getuserorder->trade_id,$opens['side'],$inserted);
	$second_response =  $this->site_api->ordercompletetype($getuserorder->trade_id,$second_type,$inserted);
	$coinbase_status = 'filled'; 

	// if(trim($opens['size'])==trim($getuserorder->amount))
	// {
	// 	$response 		 =  $this->site_api->ordercompletetype($getuserorder->trade_id,$opens['side'],$inserted);
	// 	$second_response =  $this->site_api->ordercompletetype($getuserorder->trade_id,$second_type,$inserted);
	// 	$coinbase_status = 'filled';
	// }
	// else
	// {
	// 	$response 		 =  $this->site_api->orderpartialtype($getuserorder->trade_id,$opens['side'],$inserted);
	// 	$second_response =  $this->site_api->orderpartialtype($getuserorder->trade_id,$second_type,$inserted);
	// 	$coinbase_status = 'partially';

	// }

	// exit();

	$trans_data = array(
	'userId'=>$getuserorder->user_id,
	'type'=> $type,
	'currency'=>$pair_details->to_symbol_id,
	'amount'=>$Total+$Fee,
	'profit_amount'=>$Fee,
	'comment'=>'Trade '. $type .' order #'.$getuserorder->trade_id,
	'datetime'=>date('Y-m-d h:i:s'),
	'currency_type'=>'crypto',
	'bonus_amount'=>0
	);
	 $update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);
	if($inserted  && $second_response)
	{
		$updateData = array(
				'price'          => $opens['price'],
				'coinbase_status' => $coinbase_status,
                'update_date' => $datetime,
        );
       $this->common_model->updateTableData('coinbase_order', array('id' => $getuserorder->id), $updateData);

       $this->common_model->updateTableData('coin_order',array('trade_id'=>$getuserorder->trade_id),array('status'=>$coinbase_status,'tradetime'=>date('Y-m-d H:i:s')));
	  // echo " successfully Mapped.. ";
       // echo "<br>"; 
	}
	// echo "<pre>"; 
	// print_r($trans_data); 
	// echo "<pre>";      

	}

}

	}
} 

function binance_mapping()
{

	$Binance_List = $this->common_model->getTableData('binance_order', array('order_status'=>'Pending'))->result();
	if(isset($Binance_List) && !empty($Binance_List)){
	require_once(APPPATH.'third_party/binance/php-binance-api.php');

	$api = new Binance\API("","");
		foreach($Binance_List as $orders){

			$Symbol = $orders->symbol;
			$OrderId = $orders->orderId;



		
		$pair_details = $this->common_model->getTableData('trade_pairs',array('id'=>$orders->pair_id),'from_symbol_id,to_symbol_id,api_status')->row();	
		$binance = $this->common_model->getTableData('coin_order',array('trade_id'=>$orders->cpm_trade_id,'status'=>'active'))->row();
	
		

		if($binance->status=='active') {

		$Total				=	$binance->Total;
		$Fee				=	$binance->Fee;
		$Amount             =   $binance->Amount;
		$type               =   ucfirst($binance->Type);		
				if($binance->Type=='buy')
				{
					$second_type ='sell'; 
					$currency = $pair_details->to_symbol_id;
					$Getbalance = getBalance($binance->user_id,$currency);
							if($Total <= $Getbalance)
							{
								$newbalance = $Getbalance - $Total;
							}
					// $newbalance = $Getbalance - $Total;

				}
				else if($binance->Type=='sell')
				{
					$second_type ='buy';
					$currency = $pair_details->from_symbol_id;
					$Getbalance = getBalance($binance->user_id,$currency);
							if($Amount <= $Getbalance)
							{
								$newbalance = $Getbalance - $Amount;
							} 
					// $newbalance = $Getbalance - $Amount;
				}		

 

			$Binance_Response = $api->orderStatus($Symbol,$OrderId);

			if(isset($Binance_Response['status']) && !empty($Binance_Response['status']) && $Binance_Response['status']=='FILLED'){
	        $date               =   date('Y-m-d');
			$time               =   date("H:i:s");
			$datetime           =   date("Y-m-d H:i:s");
			$data    =   array(
				'sellorderId'       =>  $binance->trade_id,
				'sellerUserid'      =>  $binance->userId,
				'askAmount'         =>  $binance->Amount,
				'askPrice'          =>  $binance->Price,
				'filledAmount'      =>  $binance->Amount, 
				'buyorderId'        =>  $binance->trade_id,
				'buyerUserid'       =>  $binance->userId,
				'sellerStatus'      =>  "inactive",
				'buyerStatus'       =>  "inactive",
				"pair"              =>  $binance->pair,
				"ac_price"          =>  $binance->Price,
				"wantPrice"         =>  $binance->Price,
				"ac_type"         	=>  $binance->Type,
				"ac_amount"         =>  $Amount,
				"datetime"          =>  $datetime
			);
		$inserted=$this->common_model->insertTableData('ordertemp', $data);






	//$updatequery = updateBalance($binance->user_id,$currency,$newbalance); 



		// Trade Lib Call 
		// $response 	= $this->site_api->ordercompletetype($binance->trade_id,$binance->Type,$inserted);
		$second_response =  $this->site_api->ordercompletetype($binance->trade_id,$second_type,$inserted); 


		$trans_data = array(
		'userId'=>$binance->userId,
		'type'=> $type,
		'currency'=>$pair_details->to_symbol_id,
		'amount'=>$Total+$Fee,
		'profit_amount'=>$Fee,
		'comment'=>'Trade '. $type .' order #'.$binance->trade_id,
		'datetime'=>date('Y-m-d h:i:s'),
		'currency_type'=>'crypto',
		'bonus_amount'=>0
		);
	 	$update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);
		if($inserted && $second_response)
		{

			$current_time = date("Y-m-d H:i:s");
			$query  =   $this->common_model->updateTableData('coin_order',array('trade_id'=>$orders->cpm_trade_id),array('status'=>"filled",'datetime'=>$current_time));

			$executedQty = $Binance_Response['executedQty'];
			$cummulativeQuoteQty = $Binance_Response['cummulativeQuoteQty'];
			$price = $Binance_Response['price'];
			$tradeId = $Binance_Response['fills'][0]['tradeId'];

			$query1  =   $this->common_model->updateTableData('binance_order',array('cpm_trade_id'=>$orders->cpm_trade_id),array('status'=>"Completed",'executedQty'=>$executedQty,'cummulativeQuoteQty'=>$cummulativeQuoteQty,'price'=>$price,'tradeId'=>$tradeId,'order_status'=>'Completed')); 

	       //echo " successfully Mapped.. ";
	      // echo "<br>";
		}
   
	  }

	}
  }
}

} 






}

