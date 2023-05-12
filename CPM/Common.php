<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods", "GET, POST, DELETE, PUT");


defined('BASEPATH') OR exit('No direct script access allowed');
class Common extends CI_Controller {
	public function __construct()
	{	
		parent::__construct();		
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation'));
		$this->load->library('session');
    $this->load->database();
		$this->site_api = new Tradelib();
		$this->load->helper(array('url', 'language'));
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
		
		$data['site_common'] = site_common();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'home'))->row();
		$data['news'] = $this->common_model->getTableData('news',array('status'=>1))->result();

		$data['about_content'] = $this->common_model->getTableData('static_content',array('slug'=>'secure_coin'))->row();

		$data['trading_platform'] = $this->common_model->getTableData('static_content',array('slug'=>'trading_platform'))->row();

		$data['portfolio'] = $this->common_model->getTableData('static_content',array('slug'=>'portfolio'))->row();

    $data['manage_portfolio1'] = $this->common_model->getTableData('static_content',array('slug'=>'manage_portfolio1'))->row();

    $data['manage_portfolio2'] = $this->common_model->getTableData('static_content',array('slug'=>'manage_portfolio2'))->row();

    $data['manage_portfolio3'] = $this->common_model->getTableData('static_content',array('slug'=>'manage_portfolio3'))->row();

    $data['manage_portfolio4'] = $this->common_model->getTableData('static_content',array('slug'=>'manage_portfolio4'))->row();

		
		$data['mobile_app'] = $this->common_model->getTableData('static_content',array('slug'=>'mobile_app'))->row();
    $data['one_platform'] = $this->common_model->getTableData('static_content',array('slug'=>'one_platform'))->row();
		$data['banners'] = $this->common_model->getTableData('banners',array('status'=>1))->result();		
		$data['testimonials'] = $this->common_model->getTableData('testimonials',array('status'=>'1'))->result();
		
		
		$data['currencyn'] = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'),'','','','','','', array('id', 'ASC'))->result();

    $data['pairs'] = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
//New design
    $data['latest_listings'] = $this->common_model->getTableData('listings',array('position'=>'latest','status'=>'1'))->result();
    $data['coming_soon'] = $this->common_model->getTableData('listings',array('position'=>'coming','status'=>'1'))->result();
    $data['user_experience'] = $this->common_model->getTableData('static_content',array('slug'=>'user_experience'))->row();
    $data['high_liquidity'] = $this->common_model->getTableData('static_content',array('slug'=>'high_liquidity'))->row();
    $data['secure_stable'] = $this->common_model->getTableData('static_content',array('slug'=>'secure_stable'))->row();
    $data['token_types'] = $this->common_model->getTableData('static_content',array('slug'=>'token_types'))->row();
    $data['electric_matching'] = $this->common_model->getTableData('static_content',array('slug'=>'electric_matching'))->row();
    $data['speed'] = $this->common_model->getTableData('static_content',array('slug'=>'speed'))->row();
    $data['inr_payment'] = $this->common_model->getTableData('static_content',array('slug'=>'inr_payment'))->row();
    $data['crypto_wallet'] = $this->common_model->getTableData('static_content',array('slug'=>'crypto_wallet'))->row();
    $data['secure_assets'] = $this->common_model->getTableData('static_content',array('slug'=>'secure_assets'))->row();
    $data['buy_sell'] = $this->common_model->getTableData('static_content',array('slug'=>'buy_sell'))->row();
    $data['send_funds'] = $this->common_model->getTableData('static_content',array('slug'=>'send_funds'))->row();
    $data['security'] = $this->common_model->getTableData('static_content',array('slug'=>'security'))->row();
    $data['bug_bounty'] = $this->common_model->getTableData('static_content',array('slug'=>'bug_bounty'))->row();
    $data['penetration_tests'] = $this->common_model->getTableData('static_content',array('slug'=>'penetration_tests'))->row();
    $data['account_shield'] = $this->common_model->getTableData('static_content',array('slug'=>'account_shield'))->row();
    $data['two_step'] = $this->common_model->getTableData('static_content',array('slug'=>'two_step'))->row();
    $data['two_step_ver'] = $this->common_model->getTableData('static_content',array('slug'=>'two_step_ver'))->row();
    $data['ssl_encrypt'] = $this->common_model->getTableData('static_content',array('slug'=>'ssl_encrypt'))->row();
    $data['support'] = $this->common_model->getTableData('static_content',array('slug'=>'support'))->row();
    $data['community'] = $this->common_model->getTableData('static_content',array('slug'=>'community'))->row();
    $data['blog'] = $this->common_model->getTableData('static_content',array('slug'=>'blog'))->row();


    $this->load->view('front/common/home_new',$data);
	}

	public function block()
	{
		$cip = get_client_ip();
		$match_ip = $this->common_model->getTableData('page_handling',array('ip'=>$cip))->row();
		if($match_ip > 0)
		{
		return 1;
		}
		else
		{
		return 0;
		}
	}

	public function block_ip()
    {
        $this->load->view('front/common/blockips');
    }
    public function blockips()
    {
        $this->load->view('front/common/blockips');  
    }

  function cms($link)
	{
    
		if($this->block() == 1)
		{
		front_redirect('block_ip');
		}
		$data['cms'] = $this->common_model->getTableData('cms', array('status' => 1, 'link'=>$link))->row();
		$data['meta_content'] = $this->common_model->getTableData('cms', array('link'=>$link))->row();
		$data['home_footer'] = $this->common_model->getTableData('static_content',array('slug'=>'home_footer'))->row();
//print_r($data['meta_content']);
		if(empty($data['cms']))
		{
			front_redirect('', 'refresh');
		}

		
		 
		$data['js_link'] = '';
		$data['site_common'] = site_common();
		
		$data['user_id'] = $this->session->userdata('user_id');
		$static_content  = $this->common_model->getTableData('static_content',array('english_page'=>'home'))->result();
		$Static_Content = array();
		foreach ($static_content as $static) {
			$Static_Content[$static->english_title]['title'] = $static->english_name;
			$Static_Content[$static->english_title]['description'] = $static->english_content;

		}
		$data['static_content'] = $Static_Content;
		$this->load->view('front/common/cms', $data);
	}

	function faq()
	{
		if($this->block() == 1)
		{
		front_redirect('block_ip');
		}
		$data['faq'] = $this->common_model->getTableData('faq', array('status' => 1))->result();
        $data['site_common'] = site_common();
        $data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'faq'))->row();
     /*   echo"<pre>";
        print_r($data['faq']);
        echo "</pre>"*/;
        $this->load->view('front/common/faq', $data);
	}
  function fee()
  {
    if($this->block() == 1)
    {
      front_redirect('block_ip');
    }
    $data['currency'] = $this->common_model->getTableData('currency',array('show_home'=>'1'),'','','','','','', array('currency_name', 'ASC'))->result();
        $data['site_common'] = site_common();
        $data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'fee'))->row();
        
        $this->load->view('front/common/fee', $data);
  }  

  function execute_order($amount,$price,$limit_price,$total,$fee,$ordertype,$pair,$type,$loan_rate,$pagetype,$user_id)
	{		
		$response = array('status'=>'','msg'=>'');
		if($user_id !="")
		{			
			$response 	= $this->site_api->createOrder($user_id,$amount,$price,$limit_price,$total,$fee,$pair,$ordertype,$type,$loan_rate,$pagetype);
		}
		else
		{
			$response['status'] = "login";
		}
		$result=json_encode($response);
		return  $result;
	}

  function api_click_function($type,$user_id,$price,$amount)
  {
    $type   = $type;//$this->input->post('type');
    $price  = $price;//$this->input->post('price');
    $amount = $amount;//$this->input->post('amount');
    
    $query = $this->common_model->getTableData('coin_order',array('status'=>'pending'));
    if($query->num_rows() > 0)
    {
      $result = $this->common_model->updateTableData('coin_order',array('Type'=>$type,'Price'=>$price,'Amount'=>$amount),array('click_status'=>1));
      $result_id = $this->common_model->getTableData('coin_order',array('Type'=>$type,'Price'=>$price,'Amount'=>$amount,'Amount'=>$amount))->row();
      $res_id     = $result_id->trade_id;
      $table_name     = 'coin_order';
    }
    else
    {
      $result = $this->common_model->updateTableData('trade_paircoins',array('type'=>$type,'price'=>$price,'quantity'=>$amount),array('click_status'=>1));
      $result_id = $this->common_model->getTableData('trade_paircoins',array('type'=>$type,'price'=>$price,'quantity'=>$amount))->row();
      $res_id     = $result_id->id;
      $table_name     = 'api_coin_order';
    } 
    
    $data['res_id']   = $res_id;
    $data['table_name'] = $table_name;
    $result     = json_encode($data);
    return $result;
    
  }
function trade_integration($pair_id,$user_id,$type='',$pair)
  {
    /*$start_memory = memory_get_usage();
    $data['memory1'] = memory_get_usage() - $start_memory;*/
    
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);  
    
   if(isset($user_id) && !empty($user_id)){
    $user_id = $user_id;
    $data['transactionhistory'] = $this->transactionhistory($pair_id,$user_id);
}
else{
    $user_id = 0;
    $data['transactionhistory'] = 0;
}

    //$data['pairs'] = trade_pairs($type);
    $this->newtrade_prices($pair_id,$type,$user_id);
    $checkapi = checkapi($pair_id);
    
    if($checkapi==0){
    $data['sellResult'] = $this->gettradeopenOrders('sell',$pair_id);
    $data['buyResult'] = $this->gettradeopenOrders('buy',$pair_id);
    $data['market_trades'] = $this->market_trades($pair_id);  
  }
  else{
    $data['api_sellResult'] = $this->gettradeapisellOrders($pair_id);
    $data['api_buyResult'] = $this->gettradeapibuyOrders($pair_id);
    $data['market_api_trades'] = $this->market_api_trades($pair);
  }


    
    //$data['current_trade'] = $this->current_trade_pair($pair_id);

    //$data['trade_records']  = trade_records($pair_id);
    

    $pair_details = $this->common_model->getTableData('trade_pairs',array('id'=>$pair_id),'from_symbol_id,to_symbol_id')->row();

    // echo ' To Sym '.$pair_details->from_symbol_id;
    // echo "<br>";

    $fromID = $pair_details->from_symbol_id;
        $toID = $pair_details->to_symbol_id;
        $getfrom_symbols= $this->common_model->getTableData('currency',array('id'=>$fromID),'','',array())->row();
        $getto_symbols= $this->common_model->getTableData('currency',array('id'=>$toID),'','',array())->row();
        if($getfrom_symbols->currency_symbol =='INR')
         $format = 2;
        else if($getfrom_symbols->currency_symbol =='USDT')  
         $format = 6; 
        else
          $format = 8; 
        if($getto_symbols->currency_symbol =='INR')
         $format1 = 2;
        else if($getto_symbols->currency_symbol =='USDT')  
         $format1 = 6; 
        else
         $format1 = 8;  
    if($type!='home')
    {
      if(isset($user_id)&&$user_id!=0)
      {
        $data['open_orders']=$this->get_active_order($user_id);
      }
      else
      {
        $data['open_orders']=0;
      }
    }
    
    if($this->user_balance!=0 && isset($user_id))
    {
      $balance=$this->user_balance;

      if (array_key_exists($pair_details->from_symbol_id, $balance)) {
        $data['from_currency'] = to_decimal($balance[$pair_details->from_symbol_id], $format);
      }
      else
      {
       $data['from_currency'] = '';
      }    

      if (array_key_exists($pair_details->to_symbol_id, $balance)) {
         $data['to_currency'] = to_decimal($balance[$pair_details->to_symbol_id], $format1);
      }
      else
      {
          $data['to_currency'] = '';
      } 
  
      
      

      // echo "<pre>";
      // echo $balance[$pair_details->from_symbol_id]; 
      // exit();


    
      $data['from_symbol'] = $getfrom_symbols->currency_symbol;
            $data['to_symbol'] = $getto_symbols->currency_symbol;     
    }
    else
    {
      $data['from_currency']='';
      $data['to_currency']=''; 
      $data['from_symbol'] = '';
      $data['to_symbol'] = '';
    }
    //$data['arthbit_userid']=$this->user_id;
    $data['current_buy_price']=to_decimal($this->lowestaskprice,8);
    $data['current_sell_price']=to_decimal($this->highestbidprice,8);
    $data['lastmarketprice']=to_decimal($this->lastmarketprice,8);
    $data['web_trade'] = '1';

    //$data['memory'] = memory_get_usage() - $start_memory;

    $result = json_encode($data);
    return  $result;
  }

  function gettradeapiactiveOrders($pair){
    $tradehistory_via_api = $this->common_model->getTableData('site_settings',array('tradehistory_via_api'=>1))->row('tradehistory_via_api');
    if($tradehistory_via_api ==1){
    $pair_value=explode('_',$pair);
    $this->db->order_by("id", "desc");
    $this->db->limit('50'); 
      $this->db->where('type = "buy" or type = "sell"');
    
      $this->db->where('first_currency',$pair_value[0]);
    $this->db->where('second_currency',$pair_value[1]);
    $activeorder_trade_result_value = $this->db->get('trade_paircoins')->result_array();
    return $activeorder_trade_result_value;
    }
  }
  function gettradeapicalcelOrders($pair){
    $tradehistory_via_api = $this->common_model->getTableData('site_settings',array('tradehistory_via_api'=>1))->row('tradehistory_via_api');
    if($tradehistory_via_api ==1){
    $pair_value=explode('_',$pair);
    $this->db->order_by("id", "asc");
    $this->db->limit('50');
    $this->db->where('type = "buy" or type = "sell"');
    $this->db->where('first_currency',$pair_value[0]);
    $this->db->where('second_currency',$pair_value[1]);
    $cancelorder_trade_result_value = $this->db->get('trade_paircoins')->result_array();
    return $cancelorder_trade_result_value;
    }
  }
  function gettradeapihistoryOrders($pair){
    $tradehistory_via_api = $this->common_model->getTableData('site_settings',array('tradehistory_via_api'=>1))->row('tradehistory_via_api');
    if($tradehistory_via_api ==1){
    $pair_value=explode('_',$pair);
    $this->db->order_by("id", "desc");
    $this->db->limit('100');
    $this->db->where('first_currency',$pair_value[0]);
    $this->db->where('second_currency',$pair_value[1]);
    $historyorder_trade_result_value = $this->db->get('trade_recent_api')->result_array();
    return $historyorder_trade_result_value;
    }
  }
  function gettradeapistopOrders($pair){
    $pair_value=explode('_',$pair);
    $this->db->order_by("id", "asc");
    $this->db->limit('14');
    $this->db->where('first_currency',$pair_value[0]);
    $this->db->where('second_currency',$pair_value[1]);
    
    $stoporder_trade_result_value = $this->db->get('trade_paircoins')->result_array();
    return $stoporder_trade_result_value;
  }

  function trade_pairs($type='')
  {
    $ci =& get_instance();
    $joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
    $where = array('a.status'=>1,'b.status'=>1,'c.status'=>1);
    
    $orderprice = $ci->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.*,b.currency_name as from_currency,b.currency_symbol as from_currency_symbol,c.currency_name as to_currency,c.currency_symbol as to_currency_symbol')->result();
    $i=0;
    foreach($orderprice as $pair)
    {
      $volume=getTradeVolume($pair->id);
      if($volume->price!=0)
      {
        $orderprice[$i]->price = to_decimal($volume->price,8);
      }
      else
      {
        $orderprice[$i]->price = to_decimal($pair->buy_rate_value,8);
      }
      $orderprice[$i]->change = $volume->change;
      $orderprice[$i]->volume = to_decimal($volume->volume,2);
      $i++;
    }
    return $orderprice;
  }

	public function market_trade()
	{
		$pair_currency = $this->common_model->customQuery("select * from arthbit_trade_pairs where status='1' order by id ASC")->result();
        $result = array();
           if(count($pair_currency)>0) 
          { 
            foreach($pair_currency as $pair) 
            {  
             $from_currency_det = getcryptocurrencydetail($pair->from_symbol_id);
             $to_currency_det = getcryptocurrencydetail($pair->to_symbol_id);
             $curr_pair = $First_Currency.$Second_Currency;
             $pairname = $from_currency_det->currency_symbol.$to_currency_det->currency_symbol;
             $firstcur_image =  $from_currency_det->image;
             $pair_id = $pair->id;
             $trading_price = tradeprices($pair_id);
             $high_price = highprices($pair_id);
            $low_price = lowprices($pair_id);
            $to_curr_det = getcryptocurrencydetail($to_currency_id);
            $trading_usdprice = $trading_price * $to_curr_det->online_usdprice;
            $price_24hr = pricechanges($pair_id);
            if($price_24hr>=0)
            {
                $price_sym = "+";
            }
            $price_24hrusd = $price_24hr * $to_curr_det->online_usdprice;

            $volume = volumes($pair_id);
            $usermal = $this->db->where('id', $user_id)->get($prefix.'users')->result();
            $First_Currency = $First_Currency;
            if ($First_Currency == 'USD') {
              $num_format1 = 2;
            } else {
              $num_format1 = 6;
            }
            $Second_Currency = $Second_Currency;
            if ($Second_Currency == 'USD') {
              $num_format2 = 2;
            } else {
              $num_format2 = 6;
            }
            if ($this->user_id != 0) {  
              if(isset($this->user_balance[$pair_details->from_symbol_id]) && !empty($this->user_balance[$pair_details->from_symbol_id])){
              $from_curs = to_decimal($this->user_balance[$pair_details->from_symbol_id], 8);
            }
            else{
              $from_curs = 0;
            }
            if(isset($this->user_balance[$pair_details->to_symbol_id]) && !empty($this->user_balance[$pair_details->to_symbol_id])){
              $to_curs = to_decimal($this->user_balance[$pair_details->to_symbol_id], 8);
            }
            else{
              $to_curs = 0;
            }
              $from_cur = ($from_curs>0)?$from_curs:"0.000";
              $to_cur = ($to_curs>0)?$to_curs:"0.000";
            } else {
              $from_cur = 0;
              $to_cur = 0;
            }

            $price_24hr = pricechanges($pair_id);
            $change_24hrs = pricechangepercents($pair_id);
            if($price_24hr>0)
            {
                $price_sym = "+";
                $percent_class = 'text-green';
                $price_24hr = $price_24hr;
            }
            else if($price_24hr==0)
            {
                $price_sym = "";
                $percent_class ="";
                $price_24hr = '0.00';
            }
            else
            {
                $price_sym = "";
                $percent_class = 'text-red';
                $price_24hr = $price_24hr;
            }
            $price_24hrs = $price_sym.$price_24hr;
            $trading_price = tradeprices($pair_id);
            $data['from_symbol'] = $from_currency_det->currency_symbol;
            $data['to_symbol'] = $to_currency_det->currency_symbol;
            $data['trading_price'] = trailingZeroes(numberFormatPrecision($trading_price));
            $data['percent_class'] = $percent_class;
            //$data['price_24hrs'] = trailingZeroes(numberFormatPrecision($price_24hrs,4));
             $data['price_24hrs'] = trailingZeroes(numberFormatPrecision($price_24hrs));
            $data['change_24hrs'] = $change_24hrs;
            $data['volume'] = trailingZeroes(numberFormatPrecision($volume));
            array_push($result, $data);
         }
        }

		if(count($result)>0)
		{
		    $response = $result;
		}
		else
		{
		    $response=0;
		}
		return $response;
	}

	public function market_trade_tab()
	{
		$currencies = $this->common_model->customQuery("select * from arthbit_currency where status='1' and currency_symbol in ('BTC','ETH','USDT','CBC','USD') ")->result();
        if(count($currencies)>0) { foreach($currencies as $cur) {

		$pair_currency =  $this->common_model->customQuery("select * from arthbit_trade_pairs where status='1' and from_symbol_id = ".$cur->id." or to_symbol_id = ".$cur->id." order by id DESC")->result();
        $result = array();
           if(count($pair_currency)>0) 
          { 
            foreach($pair_currency as $pair) 
            {  
             $from_currency_det = getcryptocurrencydetail($pair->from_symbol_id);
             $to_currency_det = getcryptocurrencydetail($pair->to_symbol_id);
             $curr_pair = $First_Currency.$Second_Currency;
             $pairname = $from_currency_det->currency_symbol.$to_currency_det->currency_symbol;
             $firstcur_image =  $from_currency_det->image;
             $pair_id = $pair->id;
             $trading_price = tradeprices($pair_id);
             $high_price = highprices($pair_id);
            $low_price = lowprices($pair_id);
            $to_curr_det = getcryptocurrencydetail($to_currency_id);
            $trading_usdprice = $trading_price * $to_curr_det->online_usdprice;
            $price_24hr = pricechanges($pair_id);
            if($price_24hr>=0)
            {
                $price_sym = "+";
            }
            $price_24hrusd = $price_24hr * $to_curr_det->online_usdprice;

            $volume = volumes($pair_id);
            $usermal = $this->db->where('id', $user_id)->get($prefix.'users')->result();
            $First_Currency = $First_Currency;
            if ($First_Currency == 'USD') {
              $num_format1 = 2;
            } else {
              $num_format1 = 6;
            }
            $Second_Currency = $Second_Currency;
            if ($Second_Currency == 'USD') {
              $num_format2 = 2;
            } else {
              $num_format2 = 6;
            }
            if ($this->user_id != 0) {  
              $from_curs = to_decimal($this->user_balance[$pair_details->from_symbol_id], 8);
              $to_curs = to_decimal($this->user_balance[$pair_details->to_symbol_id], 8);
              $from_cur = ($from_curs>0)?$from_curs:"0.000";
              $to_cur = ($to_curs>0)?$to_curs:"0.000";
            } else {
              $from_cur = 0;
              $to_cur = 0;
            }
            //print_r($to_cur);
            $coin_name = strtolower($fromcurrency_details->currency_name);
            $to_curr_symbol = strtolower($Second_Currency);
            
            $price_24hr = pricechanges($pair_id);
            $change_24hrs = pricechangepercents($pair_id);
            if($price_24hr>0)
            {
                $price_sym = "+";
                $percent_class = 'text-green';
                $price_24hr = $price_24hr;
            }
            else if($price_24hr==0)
            {
                $price_sym = "";
                $percent_class ="";
                $price_24hr = '0.00';
            }
            else
            {
                $price_sym = "";
                $percent_class = 'text-red';
                $price_24hr = $price_24hr;
            }
            $price_24hrs = $price_sym.$price_24hr;
            $trading_price = tradeprices($pair_id);
            $data['from_symbol'] = $from_currency_det->currency_symbol;
            $data['to_symbol'] = $to_currency_det->currency_symbol;
            $data['trading_price'] = $trading_price;
            $data['percent_class'] = $percent_class;
            $data['price_24hrs'] = $price_24hrs;
            $data['change_24hrs'] = $change_24hrs;
            $data['volume'] = $volume;
            array_push($result, $data);
         }
        }
        }
        } 

		if(count($result)>0)
		{
		    $response = $result;
		}
		else
		{
		    $response=0;
		}
		return $response;
	}

	function gettradeapisellOrders($pair)
{
	$orderBy=array('price','asc'); 
  $sellresult = $this->common_model->getTableData("api_orders",array("pair_id"=>$pair,'type'=>'sell'),'price,quantity','','','','',20,$orderBy)->result();
        
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
  $buyresult = $this->common_model->getTableData("api_orders",array("pair_id"=>$pair,'type'=>'buy'),'price,quantity','','','','',20)->result();
        
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

	function newtrade_prices($pair,$pagetype='',$user_id)
  {
    //$this->marketprice = marketprice($pair);
    $this->marketprice = tradeprice($pair);
    $this->lowestaskprice = lowestaskprice($pair);
    $this->highestbidprice = highestbidprice($pair);
    $this->lastmarketprice = tradeprice($pair);
    $this->minimum_trade_amount = get_min_trade_amt($pair);
    $this->maker=getfeedetails_buy($pair);
    $this->taker=getfeedetails_sell($pair);
    $this->myfrmt = get_decimalpairs($pair);
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

   function get_active_order($user_id)
  {
    $user_id = $user_id;
    $selectFields='CO.*,date_format(CO.datetime,"%d-%b-%Y %h:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
    $names = array('active', 'partially', 'margin','stoporder');
    $where=array('CO.userId'=>$user_id);
    $orderBy=array('CO.trade_id','desc');
    $groupBy=array('CO.trade_id');
    $where_in=array('CO.status', $names);
    $joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId','trade_pairs as TP'=>'CO.pair = TP.id');
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

  function get_active_limitorder($user_id)
  {
    $user_id = $user_id;
    $selectFields='CO.*,date_format(CO.datetime,"%d-%b-%Y %h:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
    $names = array('active', 'partially', 'margin');
    $where=array('CO.userId'=>$user_id,'CO.ordertype'=>'limit');
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

  function get_active_marketorder($user_id)
  {
    $user_id = $user_id;
    $selectFields='CO.*,date_format(CO.datetime,"%d-%b-%Y %h:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
    $names = array('active', 'partially', 'margin');
    $where=array('CO.userId'=>$user_id,'CO.ordertype'=>'instant');
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
  function get_active_stoporder($user_id)
  {
    $user_id = $user_id;
    $selectFields='CO.*,date_format(CO.datetime,"%d-%b-%Y %h:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
    $names = array('active', 'partially', 'margin');
    $where=array('CO.userId'=>$user_id,'CO.ordertype'=>'stop');
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
  function get_active_userorder($pair_id,$user_id)
  {
    $user_id = $user_id;
    $selectFields='CO.*,date_format(CO.datetime,"%d-%b-%Y %h:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
    $names = array('active', 'partially', 'margin');
    $where=array('CO.pair'=>$pair_id,'CO.userId'=>$user_id);
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
  function get_cancel_order($pair_id,$user_id)
  {
    $user_id = $user_id;
    $selectFields='CO.*,OT.filledAmount as totalamount';
    $where=array('CO.pair'=>$pair_id,'CO.userId'=>$user_id,'CO.status'=>'cancelled');
    $orderBy=array('CO.trade_id','desc');
    $joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
    $query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy);
    if($query->num_rows() >= 1)
    {
      $cancel_orders = $query->result();
    }
    else
    {
      $cancel_orders = '';
    }
    if($cancel_orders&&$cancel_orders[0]->trade_id!='')
    {
      $cancel_orders_text=$cancel_orders;
    }
    else
    {
      $cancel_orders_text=0;
    }
    return $cancel_orders_text;
  }
  function get_stop_order($pair_id,$user_id)
  {
    $user_id = $user_id;
    $query = $this->common_model->customQuery('select trade_id, Type, Price, Amount, Fee, Total, status, date_format(datetime,"%d-%m-%Y %H:%i %a") as tradetime from arthbit_coin_order where userId = '.$user_id.' and status = "stoporder" and pair = '.$pair_id.'');
    if($query->num_rows() >= 1)
    {
      $stop_orders = $query->result();
    }
    else
    {
      $stop_orders='';
    }
    if($stop_orders)
    {
      $stoporder=$stop_orders;
    }
    else
    {
      $stoporder=0;
    }
    return $stoporder;
  }
  function close_active_order()
  {
    $tradeid = $this->input->post('tradeid');
    $pair_id = $this->input->post('pair_id');
    $user_id = $this->session->userdata('user_id');
    $response=$this->site_api->close_active_order($tradeid,$pair_id,$user_id);
    echo json_encode($response);
  }

    public function coinprice($coin_symbol)
    {
      $api_key = getSiteSettings('cryptocompare_apikey');
       $url = "https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=USD&api_key=".$api_key;
		$curres = $coin_symbol;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$res = json_decode($result);
    /*echo "<pre>";
    print_r($res);
    exit();*/
		return $res->USD;
    }

     function update_onlineUSD()
    {
      $all_currency = $this->common_model->getTableData('currency', array('type'=>'digital','status'=>1), '', '', '', '', '', '', array('id', 'DESC'))->result();
      if(count($all_currency)>0)
      {
        foreach($all_currency as $getcoin)
        {
          $coin_symbol = $getcoin->currency_symbol;
          $usd_pricer = $getcoin->usd_price;
          if($coin_symbol=='CBC')
          {
            $coin_symbol = 'CBC';
          }
          $api_key = getSiteSettings('cryptocompare_apikey');

          $url = "https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=USD&api_key=".$api_key;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $res = json_decode($result);
        //echo '<pre>'; print_r($res);
        if($usd_pricer !=0)
        {
                    //$usd_val = $usd_price;
                    $usd_val = $res->USD;
          $condition = array('currency_symbol'=>$coin_symbol);
          $update = array('online_usdprice_currency'=>$usd_val);
          $this->common_model->updateTableData('currency', $condition, $update);
          echo $coin_symbol . " => " . $usd_val. '<br/>';
        }
        else
        {
          if($res->Response=='Error')
          {
            echo $coin_symbol . " =>  No Datas <br/>";
            $usd_val = 1;
            $condition = array('currency_symbol'=>$coin_symbol);
            $update = array('online_usdprice_currency'=>$usd_val);
            $this->common_model->updateTableData('currency', $condition, $update);
          }
          else
          {
            $usd_val = $res->USD;
            $condition = array('currency_symbol'=>$coin_symbol);
            $update = array('online_usdprice_currency'=>$usd_val);
            $this->common_model->updateTableData('currency', $condition, $update);
            echo $coin_symbol . " => " . $usd_val. '<br/>';
          }

        }
                
        }
      }
    }
    public function update_usd_price()
    {
        $currency_results=$this->common_model->update_usd_price();
        foreach($currency_results as $cvalue){
            echo $currency_symbol=$cvalue->currency_symbol;
            $equal_usd=0;
            $equal_usd = $this->coinprice($currency_symbol);
            if(isset($equal_usd) && !empty($equal_usd)){
            	$currency_arr[$currency_symbol]=$equal_usd;
            }
            else{
            	$currency_arr[$currency_symbol]=0;
            }
            

            //echo $currency_arr[$currency_symbol];
            //$usd_price = $cvalue->online_usdprice;
            $usd_price = $currency_arr[$currency_symbol];
            
            if($usd_price !=0){
              $equal_usd = $usd_price;
            }

            if($currency_symbol=="CBC")
            {
              $equal_usd = '0.75';
            }
            echo $equal_usd;
            $updateData = array(
                'online_usdprice' => $equal_usd,
            );
            $this->common_model->updateTableData('currency', array('id' => $cvalue->id), $updateData);
            echo $this->db->last_query(); echo '<br/>';
        }
        print_r($currency_arr);
    }

    public function newget_chart_record($pairs)
    {
        // EDITED BY MANIMEGS
        $segment_array = $this->uri->segment_array();
        $do_add = array_search("newget_chart_record", $segment_array);
        $symbol_vals = str_replace("_", "", $this->uri->segment($do_add + 1));
        $symbol_val = $this->uri->segment($do_add + 2);
        if ($symbol_val != 'config') {
            if (strpos($symbol_val, 'symbols') !== false) {
                if ($symbol_val != '') {
                    //$fin_symbol     = $again_split[1];
                    $fin_symbol = strtoupper($symbol_vals);
                    $fin_symbol = str_replace('Arthbit%3A', '', $fin_symbol);
                    $symbol_details = $this->common_model->getTableData('coins_symbols', array('name' => $fin_symbol))->result_array();

                    $chart = '{"name":"' . $symbol_details[0]['name'] . '","exchange-traded":"Arthbit","exchange-listed":"Arthbit","timezone":"' . $symbol_details[0]['timezone'] . '","minmov2":0,"pointvalue":1,"has_intraday":true,"has_no_volume":false,"description":"' . $symbol_details[0]['description'] . '","type":"' . $symbol_details[0]['type'] . '","supported_resolutions":["1","3", "5", "60", "D", "2D","W","3W","M","6M"],"pricescale":1000000,"ticker":"' . $symbol_details[0]['name'] . '","session":"0000-2400|0000-2400:17","intraday_multipliers": ["1","60"]}';
                    echo $chart;exit;
                    $this->newtradechart_check($pairs);
                }
            } else {
                $this->newtradechart_check($pairs);
            }
        }
    }
    public function newtradechart_check($pair_val)
    {
        $pair_val_file     = strtolower($pair_val);
      $json_pair         = $pair_val_file.'.json';
      /*if($pair_val_file=="coco_eth" || $pair_val_file=="coco_usdt" || $pair_val_file=="coco_btc" || $pair_val_file=="coco_usd" || $pair_val_file=="coco_eur" || $pair_val_file=="coco_sek" )
      {
        $str = file_get_contents(FCPATH."chart/eth_btc.json");
      }
      else
      {*/
        $str = file_get_contents(FCPATH."chart/".$json_pair);
     // }
      echo $str; exit;
    }
    public function get_chart_record()
    {
        $order = $this->common_model->getTableData('trade_pairs', array('status' => 1))->result_array();
        foreach ($order as $order_value) {
            //echo $order_value['id'];
            $first_symbol_id = $this->common_model->getTableData('trade_pairs', array('id' => $order_value['id']), 'from_symbol_id')->row('from_symbol_id');
            $second_symbol_id = $this->common_model->getTableData('trade_pairs', array('id' => $order_value['id']), 'to_symbol_id')->row('to_symbol_id');
            $first_coin = $this->common_model->getTableData('currency', array('id' => $first_symbol_id), 'currency_symbol')->row('currency_symbol');
            $second_coin = $this->common_model->getTableData('currency', array('id' => $second_symbol_id), 'currency_symbol')->row('currency_symbol');
            $coin_pair = $first_coin . "_" . $second_coin;
            //echo "<br>".$order_value['id'].$coin_pair;
            $this->tradechart_check($order_value['id'], $coin_pair);
        }
    }
    public function tradechart_check($pair, $pair_val)
    {
        $timestamp = strtotime('today midnight');
        $end_date = date("Y-m-d H:i:s", $timestamp);
        $start_date = date('Y-m-d H:i:s', strtotime($end_date . '- 90 days'));
        $start = strtotime($start_date);
        $end = time();
        $enddate = date('Y-m-d H:i:s', $end);
        $interval = 1 / 2;
        $int = 1 * 60 * 60 * $interval;
        $chart = "";
        $chart1 = "";
        $chart2 = "";
        $chart3 = "";
        $chart4 = "";
        $chart5 = "";
        $chartdata = "";
        $pair_value = explode('_', $pair_val);
        $first_pair = $pair_value[0];
        $second_pair = $pair_value[1];
        $names = array('filled');
        $where_in = array('status', $names);
    //$coinorder_data = $this->common_model->getTableData('coin_order', array('pair' => $pair), '', '', '', '', '', '', '', '', '', $where_in)->result();
        $coinorder_data = $this->common_model->getTableData('trade_pairs', array('id'=>$pair,'api_status' =>'0'))->num_rows();

        //if (count($coinorder_data) > 20) 
        if ($coinorder_data) 
        {
            $sec_pair = $second_pair;
            if ($sec_pair == "USD") {
                $sec_pair = "USDC";
            }
            $pairss = $first_pair . $sec_pair;
            $datetimes = "";
            $opens = "";
            $closes = "";
            $highs = "";
            $lows = "";
            $volumes = "";
            $datetimes1 = "";
            $open1 = "";
            $close1 = "";
            $high1 = "";
            $low1 = "";
            $volume1 = "";
            $newchart = "";
            $Close="";
            for ($i = $start; $i <= $end; $i += $int) 
            {
                $taken = date('Y-m-d H:i:s', $i);
                $exp = explode(' ', $taken);
                $curdate = $exp[0];
                $time = $exp[1];
                $datetime = strtotime($taken);
                $date_time = strtotime($taken);
                $destination = date('Y-m-d H:i:s', strtotime($taken . ' +30 minutes'));
                $api_chartResult = $this->common_model->getTableData('coin_order', array('datetime >= ' => $taken, 'datetime <= ' => $destination, 'pair' => $pair), 'SUM(Amount) as volume,MIN(Price) as low,MAX(Price) as high,datetime', '', '', '', '', '', '', '', '', $where_in)->row();
                $api_OpenchartResult = $this->common_model->getTableData('coin_order', array('datetime >= ' => $taken, 'datetime <= ' => $destination, 'pair' => $pair), 'Price as open,datetime', '', '', '', '', '', array('trade_id', 'ASC'), '', '', $where_in)->row();
                $api_ClosechartResult = $this->common_model->getTableData('coin_order', array('datetime >= ' => $taken, 'datetime <= ' => $destination, 'pair' => $pair), 'Price as close,datetime', '', '', '', '', '', array('trade_id', 'DESC'), '', '', $where_in)->row();
                if (isset($api_chartResult)) {
                    $time = strtotime($api_chartResult->datetime);
                    $volume = $api_chartResult->volume;
                    $low = $api_chartResult->low;
                    $high = $api_chartResult->high;
                    $volume1 = $api_chartResult->volume;
                    $low1 = $api_chartResult->low;
                    $high1 = $api_chartResult->high;
                    if ($time != '') {
                        $time = $time . ',';
                    }
                    if ($high != '') {
                        $high = $high . ',';
                    }
                    if ($low != '') {
                        $low = $low . ',';
                    }
                    if ($volume != '') {$volume = $volume . ',';}
                    $chart .= $time;
                    $chart3 .= $high;
                    $chart4 .= $low;
                    $chart5 .= $volume;
                }
                if (isset($api_OpenchartResult)) {
                    $Open = $api_OpenchartResult->open;
                    $open1 = $api_OpenchartResult->open;
                    if ($Open != '') {$open = $Open . ',';;}
                    $chart2 .= $open;
                }
                if (isset($api_ClosechartResult)) {
                    $Close = $api_ClosechartResult->close;
                    $close1 = $api_ClosechartResult->close;
                    if ($Close != '') {$close = $Close . ',';;}
                    $chart1 .= $close;
                }
                if ($date_time != '' && $open1 != '' && $high1 != '' && $close1 != '' && $low1 != '') {
                    $chartdata .= '[' . $date_time . '000' . ',' . $open1 . ',' . $high1 . ',' . $low1 . ',' . $close1 . '],';
                }
                $chart_new = $chartdata;
            }
            $update['new_lastprice'] = $Close;
      $this->common_model->updateTableData("trade_pairs",array('id' => $pair),$update);

            $pair_val_file = strtolower($pair_val);
            $json_pair = $pair_val_file . '.json';

            $newchart = '{"t":[' . trim($chart, ',') . '],"o":[' . trim($chart2, ',') . '],"h":[' . trim($chart3, ',') . '],"l":[' . trim($chart4, ',') . '],"c":[' . trim($chart1, ',') . '],"v":[' . trim($chart5, ',') . '],"s":"ok"}';

            $fp = fopen(FCPATH . 'chart/' . $json_pair, 'w');
            fwrite($fp, $newchart);
            fclose($fp);
      echo $json_pair . " -- Coin Order success <br>";
      
        } 
        else 
        { //CALL API BINANCE
            $sec_pair = $second_pair;
            if ($sec_pair == "USD") {
                $sec_pair = "USDC";
            }
            $pairss = $first_pair . $sec_pair;
            $datetime = "";
            $open = "";
            $close = "";
            $high = "";
            $low = "";
            $volume = "";
            $datetime1 = "";
            $open1 = "";
            $close1 = "";
            $high1 = "";
            $low1 = "";
            $volume1 = "";
            $url = "https://api.binance.com/api/v1/klines?symbol=" . $pairss . "&interval=1m";
            //echo $url;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $res = json_decode($result, true);
            if (isset($res['code']) && !empty($res['code']) && $res['code'] == '-1003') {
                $pair_val_file = strtolower($pair_val);
                $json_pair = $pair_val_file . '.json';
                $json_pair . '-- IP banned from BInance <br>';
            } 
            else if (isset($res['code']) && !empty($res['code']) && $res['code'] == '-1121') 
            {
                $pairss = $sec_pair . $first_pair;
                $url = "https://api.binance.com/api/v1/klines?symbol=" . $pairss . "&interval=1m";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                $res = json_decode($result, true);
                if($res['code'] != '-1121')
                {
                  foreach ($res as $row) {
                     $Close = $row['4'];
                      $datetime .= substr($row['0'], 0, -3) . ',';
                      $datetime1 = $datetime;
                      $open .= $row['1'] . ',';
                      $open1 = $open;
                      $high .= $row['2'] . ',';
                      $high1 = $high;
                      $low .= $row['3'] . ',';
                      $low1 = $low;
                      $close .= $row['4'] . ',';
                      $close1 = $close;
                      $volume .= ($row['5'] *(0.03/100)) . ',';
                      $volume1 = $volume;
                  }
                  $update['new_lastprice'] = $Close;
              $this->common_model->updateTableData("trade_pairs",array('id' => $pair),$update);
              echo $this->db->last_query()."<br/>";
                  $pair_value = explode('_', $pair_val);
                  $first_pair = $first_pair;
                  $second_pair = $sec_pair;
                  $pairss_name = $first_pair . '_' . $second_pair;
                  $pair_val_file = strtolower($pairss_name);
                  $json_pair = $pair_val_file . '.json';
                  $newchart = '{"t":[' . trim($datetime1, ',') . '],"o":[' . trim($open1, ',') . '],"h":[' . trim($high1, ',') . '],"l":[' . trim($low1, ',') . '],"c":[' . trim($close1, ',') . '],"v":[' . trim($volume1, ',') . '],"s":"ok"}';
                  $fp = fopen(FCPATH . 'chart/' . $json_pair, 'w');
                  fwrite($fp, $newchart);
                  fclose($fp);
                  echo $pairss_name . " -- Binance success for reverse pair <br>";
              }
              else
              {
                $datetime = "";
                  $open = "";
                  $close = "";
                  $high = "";
                  $low = "";
                  $volume = "";
                  $datetime1 = "";
                  $open1 = "";
                  $close1 = "";
                  $high1 = "";
                  $low1 = "";
                  $volume1 = "";
                  $url = "https://www.binance.com/api/v1/klines?symbol=" . $pairss . "&interval=1m";
                  //echo $url;
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $url);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                  $result = curl_exec($ch);
                  $res = json_decode($result, true);
                      foreach ($res as $row) {
                        $Close = $row['4'];
                          $datetime .= substr($row['0'], 0, -3) . ',';
                          $datetime1 = $datetime;
                          $open .= $row['1'] . ',';
                          $open1 = $open;
                          $high .= $row['2'] . ',';
                          $high1 = $high;
                          $low .= $row['3'] . ',';
                          $low1 = $low;
                          $close .= $row['4'] . ',';
                          $close1 = $close;
                          $volume .= ($row['5'] * (0.03/100)) . ',';
                          $volume1 = $volume;
                      }
                      $update['new_lastprice'] = $Close;
              $this->common_model->updateTableData("trade_pairs",array('id' => $pair),$update);
                      $first_pair = $first_pair;
                      $second_pair = $sec_pair;
                      $pairss_name = $first_pair . '_' . $second_pair;
                      $pair_val_file = strtolower($pairss_name);
                      $json_pair = $pair_val_file . '.json';
                      $newchart = '{"t":[' . trim($datetime1, ',') . '],"o":[' . trim($open1, ',') . '],"h":[' . trim($high1, ',') . '],"l":[' . trim($low1, ',') . '],"c":[' . trim($close1, ',') . '],"v":[' . trim($volume1, ',') . '],"s":"ok"}';
                      //$fp = fopen(FCPATH . 'chart/test.json', 'w');
                      $fp = fopen(FCPATH . 'chart/'.$json_pair, 'w');
                      fwrite($fp, $newchart);
                      fclose($fp);
                      echo $pairss_name . " -- Dummyt success  <br>";
                     // $this->common_model->customQuery("UPDATE arthbit_trade_pairs SET chart_load_status=1 WHERE id='".$pair."'");
                      
              }
            } 
            else if (isset($res['code']) && !empty($res['code']) && $res['code'] != '-1121') 
            {
                foreach ($res as $row) {
                  $Close = $row['4'];
                    $datetime .= substr($row['0'], 0, -3) . ',';
                    $datetime1 = $datetime;
                    $open .= $row['1'] . ',';
                    $open1 = $open;
                    $high .= $row['2'] . ',';
                    $high1 = $high;
                    $low .= $row['3'] . ',';
                    $low1 = $low;
                    $close .= $row['4'] . ',';
                    $close1 = $close;
                    $volume .= ($row['5'] * (0.03/100)) . ',';
                    $volume1 = $volume;
                }
               $update['new_lastprice'] = $Close;
              $this->common_model->updateTableData("trade_pairs",array('id' => $pair),$update);
                $first_pair = $first_pair;
                $second_pair = $sec_pair;
                if ($second_pair == "USDC") {
                    $second_pair = "USD";
                }
                $pairss_name = $first_pair . '_' . $second_pair;
                $pair_val_file = strtolower($pairss_name);
                $json_pair = $pair_val_file . '.json';
                $newchart = '{"t":[' . trim($datetime1, ',') . '],"o":[' . trim($open1, ',') . '],"h":[' . trim($high1, ',') . '],"l":[' . trim($low1, ',') . '],"c":[' . trim($close1, ',') . '],"v":[' . trim($volume1, ',') . '],"s":"ok"}';
                $fp = fopen(FCPATH . 'chart/' . $json_pair, 'w');
                fwrite($fp, $newchart);
                fclose($fp);
                echo $pairss_name . " -- Binance success <br>";
            } 
            else 
            {
                $datetime = "";
                $open = "";
                $close = "";
                $high = "";
                $low = "";
                $volume = "";
                $datetime1 = "";
                $open1 = "";
                $close1 = "";
                $high1 = "";
                $low1 = "";
                $volume1 = "";
                $url = "https://www.binance.com/api/v1/klines?symbol=" . $pairss . "&interval=1m";
                //echo $url;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                $res = json_decode($result, true);
                if (isset($res['code']) && !empty($res['code']) && $res['code'] == '-1003') {
                    $pair_val_file = strtolower($pair_val);
                    $json_pair = $pair_val_file . '.json';
                    $pair_val_file . '-- IP banned from BInance <br>';
                } 
               // else if (isset($res['code']) && !empty($res['code']) && $res['code'] != '-1121') 
                else
                {
                    foreach ($res as $row) {
                      $Close = $row['4'];
                        $datetime .= substr($row['0'], 0, -3) . ',';
                        $datetime1 = $datetime;
                        $open .= $row['1'] . ',';
                        $open1 = $open;
                        $high .= $row['2'] . ',';
                        $high1 = $high;
                        $low .= $row['3'] . ',';
                        $low1 = $low;
                        $close .= $row['4'] . ',';
                        $close1 = $close;
                        $volume .= ($row['5'] * (0.03/100)) . ',';
                        $volume1 = $volume;
                    }
                    $update['new_lastprice'] = $Close;
              $this->common_model->updateTableData("trade_pairs",array('id' => $pair),$update);
                    $first_pair = $first_pair;
                    $second_pair = $sec_pair;
                    $pairss_name = $first_pair . '_' . $second_pair;
                    $pair_val_file = strtolower($pairss_name);
                    $json_pair = $pair_val_file . '.json';
                    $newchart = '{"t":[' . trim($datetime1, ',') . '],"o":[' . trim($open1, ',') . '],"h":[' . trim($high1, ',') . '],"l":[' . trim($low1, ',') . '],"c":[' . trim($close1, ',') . '],"v":[' . trim($volume1, ',') . '],"s":"ok"}';
                    //$fp = fopen(FCPATH . 'chart/test.json', 'w');
                    $fp = fopen(FCPATH . 'chart/'.$json_pair, 'w');
                    fwrite($fp, $newchart);
                    fclose($fp);
                    echo $pairss_name . " -- Dummy success  <br>";
                   // $this->common_model->customQuery("UPDATE arthbit_trade_pairs SET chart_load_status=1 WHERE id='".$pair."'");
                }
            }
        }
        //}
  }

    function update_adminaddress()
{

    $Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'))->result();

    $whers_con = "id='1'";

    // $get_admin  =   $this->common_model->getrow("arthbit_admin_wallet", $whers_con);
    // print_r($get_admin); exit();

    $admin_id = "1";

    $enc_email = getAdminDetails($admin_id, 'email_id');

    $email = decryptIt($enc_email);         

    $get_admin = $this->common_model->getrow("arthbit_admin_wallet", $whers_con);
    $trx_id = $get_admin->TRX_id;

    if(!empty($get_admin)) 
    {
        $get_admin_det = json_decode($get_admin->addresses, true);

        foreach($Fetch_coin_list as $coin_address)
        {           
            //$currency_exit =  array_key_exists($coin_address->currency_symbol, $get_admin_det)?true:false;

            if(array_key_exists($coin_address->currency_symbol, $get_admin_det))
            {
                //$currency_address_checker = (!empty($get_admin_det[$coin_address->currency_symbol]))?true:false;

                if(empty($get_admin_det[$coin_address->currency_symbol]))
                {
                    $parameter = '';

                    switch ($coin_address->coin_type) {
                        case 'coin':

                            switch ($coin_address->currency_symbol) {
                                case 'ETH':
                                    $parameter='create_eth_account';

                                    $Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_eth_account', $email);
                                    //$Get_First_address = "0xa3695021fc052cf8e81ae6834421dcac2f1e8ae2";


                                    $get_admin_det[$coin_address->currency_symbol] = $Get_First_address;

                                    $update['addresses'] = json_encode($get_admin_det);

                                    $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update);

                                    break;

                                case 'BNB':
                                    $parameter='create_eth_account';

                                    $Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_eth_account', $email);
                                    //$Get_First_address = "0xa3695021fc052cf8e81ae6834421dcac2f1e8ae2";


                                    $get_admin_det[$coin_address->currency_symbol] = $Get_First_address;

                                    $update['addresses'] = json_encode($get_admin_det);

                                    $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update);

                                    break;

                                case 'TRX':
                                    $parameter='create_tron_account';

                                    //$Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_tron_account', $trx_id);
                                    //$Get_First_address = "0xa3695021fc052cf8e81ae6834421dcac2f1e8ae2";

                                    $Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_tron_account',$trx_id);

                                    $tron_private_key = $Get_First_address['privateKey'];
                                    $tron_public_key = $Get_First_address['publicKey'];
                                    $tron_address = $Get_First_address['address']['base58'];
                                    $tron_hex = $Get_First_address['address']['hex'];

                                    $get_admin_det[$coin_address->currency_symbol] = $tron_address;

                                    $update['addresses'] = json_encode($get_admin_det);
                                    $update['TRX_hexaddress'] = $tron_hex;
                                    $update['TRX_skey'] = $tron_private_key;
                                    $update['TRX_pkey'] = $tron_public_key;

                                    $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update);

                                    break;

                                default:
                                    $parameter='getnewaddress';

                                    $Get_First_address = $this->local_model->access_wallet($coin_address->id,'getnewaddress', $email);

                                    //$Get_First_address = "tb1qs472eaffl0z5qpqg9ytsdm355eg8afzz449sr0";


                                    $get_admin_det[$coin_address->currency_symbol] = $Get_First_address;

                                    $update['addresses'] = json_encode($get_admin_det);

                                    $this->common_model->updateTableData("admin_wallet",array('user_id'=>$admin_id),$update);

                                    break;
                            }

                            break;
                        case 'token':

                            if($coin_address->crypto_type=="eth")
                            {
                                $get_admin_det[$coin_address->currency_symbol] = $get_admin_det['ETH'];

                                $update['addresses'] = json_encode($get_admin_det);

                            }
                            elseif($coin_address->crypto_type=="tron")
                            {
                                $get_admin_det[$coin_address->currency_symbol] = $get_admin_det['TRX'];

                                $update['addresses'] = json_encode($get_admin_det);

                            }
                            else
                            {
                                 $get_admin_det[$coin_address->currency_symbol] = $get_admin_det['BNB'];

                                  $update['addresses'] = json_encode($get_admin_det);
                            }


                            $this->common_model->updateTableData("admin_wallet",array('user_id'=>$admin_id),$update);

                            break;
                        default:
                            break;
                    }                  
                }
            }
        }
    }
}

function update_adminbalance()
{

    $Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'))->result();

    $whers_con = "id='1'";

    $admin_id = "1";

    $enc_email = getAdminDetails($admin_id, 'email_id');

    $email = decryptIt($enc_email);         

    $get_admin = $this->common_model->getrow("arthbit_admin_wallet", $whers_con);

    if(!empty($get_admin)) 
    {
        $get_admin_det = json_decode($get_admin->wallet_balance, true);
        $get_admin_dets = json_decode($get_admin->addresses, true);

        foreach($Fetch_coin_list as $coin_address)
        {           

            $admin_address   =   $get_admin_dets[$coin_address->currency_symbol];

            if(array_key_exists($coin_address->currency_symbol, $get_admin_det))
            {   

                $Crypto_type = getcoindetail($coin_address->currency_symbol)->crypto_type;
                if($Crypto_type=='tron')
              {
                    $private_key = getadmintronPrivate(1);
                    $wallet_balance = $this->local_model->wallet_balance($coin_address->currency_name, $admin_address,$private_key);
                    //echo "JST token";

              }
                else
                { echo " CURRENCY => ".$coin_address->currency_name;echo '<br/>';
                    $wallet_balance = $this->local_model->wallet_balance($coin_address->currency_name, $admin_address);
                }

                echo $coin_address->currency_name.'=>'.$wallet_balance;
                    echo "<br>";

                $old_balance = $get_admin_det[$coin_address->currency_symbol];
                if($old_balance != $wallet_balance && $wallet_balance !=0)
                {
                    $get_admin_det[$coin_address->currency_symbol] = number_format($wallet_balance,8,'.', '');
                    //print_r($get_admin_det);
                    $update['wallet_balance'] = json_encode($get_admin_det);

                  // print_r($update);

                    $check_launchpad = $this->common_model->getTableData("launchpad",array("token_coin_symbol"=>$coin_address->currency_symbol))->row();
                    if(count($check_launchpad)>0)
                    {

                    $update_token = $this->common_model->updateTableData('launchpad', array("token_coin_symbol"=>$coin_address->currency_symbol), array("total_token"=>number_format($wallet_balance,8,'.', '')));
                    }

                   $update_qry = $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update); 
                }

            }
        }
        if($update_qry)
        {
            echo "updated success";
        }
        else
        {
            echo "updated failed";
        }
    }
}
    
    public function admin_wallet_balance($coin_symbol)
    {
      $whers_con = "id='1'";
        $get_admin = $this->common_model->getrow("admin_wallet", $whers_con);
        if(!empty($get_admin)) 
        {
            $get_admin_det          =   json_decode($get_admin->addresses, TRUE);
            $Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1','currency_symbol'=>$coin_symbol))->row();
            $coin_symbol   =  $Fetch_coin_list->currency_symbol;  
            $coinname    =  $Fetch_coin_list->currency_name;      
            if(array_key_exists($coin_symbol, $get_admin_det))
            {         
                if(!empty($get_admin_det[$coin_symbol]))
                {
                  $admin_address            =   $get_admin_det[$coin_symbol];
                  if(!empty($admin_address))
                  {
                        $wallet_bal         =   $this->local_model->wallet_balance($coinname, $admin_address);
                                 // echo $wallet_bal.'eth';
                        $en_code_bal                =   json_decode($get_admin->wallet_balance, TRUE);
                    
                        $en_code_bal[$coin_symbol]  =   number_format($wallet_bal,8);
                        //$update['wallet_balance']   =   json_encode($en_code_bal);
                        $update['wallet_balance']   =   json_encode($en_code_bal);
                        $condition          =   array('id' => '1');
                        $exc                =   $this->common_model->updateTableData('admin_wallet', $condition, $update);
                        if($exc){
                            echo "success";
                        }
                        else
                        {
                            echo "Fail";
                        }
                  }                
                }
            }    
        }
    }



  ///
    
	
  public function transactionhistory($pair_id,$user_id)
  {
    $user_id = $user_id;
    $joins = array('coin_order as b'=>'a.sellorderId = b.trade_id','coin_order as c'=>'a.buyorderId = c.trade_id');
    $where = array('c.pair'=>$pair_id,'c.userId'=>$user_id);
    //$where_or = array('c.userId'=>$user_id);
    $where_or = '';
    $transactionhistory = $this->common_model->getJoinedTableData('ordertemp as a',$joins,$where,'a.*,
       date_format(b.datetime,"%H:%i:%s") as sellertime,b.trade_id as seller_trade_id,date_format(c.datetime,"%H:%i:%s") as buyertime,c.trade_id as buyer_trade_id,a.askPrice as sellaskPrice,a.ac_price,c.Price as buyaskPrice,b.Fee as sellerfee,c.Fee as buyerfee,b.Total as sellertotal,c.Total as buyertotal,c.pair_symbol as pair_symbol, c.status as status','',$where_or,'','','20',array('a.tempId','desc'))->result();
    
        $newquery = $this->common_model->customQuery('select trade_id, Type, Price, Amount, Fee, Total, status, date_format(datetime,"%d-%b-%Y %h:%i %p") as tradetime, pair_symbol from arthbit_coin_order where userId = '.$user_id.' and pair = '.$pair_id.' and status IN ("cancelled","filled")')->result();
        //echo $this->db->last_query();
        //exit();
        //$newquery = array();
 

    if((isset($transactionhistory) && !empty($transactionhistory)) || (isset($newquery) && !empty($newquery)))
    {
        $transactionhistory_1 = array_merge($transactionhistory,$newquery);
        $historys = $transactionhistory_1;
    }
    else
    {
        $historys='0';
    }
    return $historys;
  }
  public function markettrendings($pair_id)
  {
    $joins = array('coin_order as b'=>'a.sellorderId = b.trade_id','coin_order as c'=>'a.buyorderId = c.trade_id');
    $where = array('a.pair'=>$pair_id);
    $transactionhistory = $this->common_model->getJoinedTableData('ordertemp as a',$joins,$where,'a.*,b.datetime as sellertime,b.trade_id as seller_trade_id,c.datetime as buyertime,c.trade_id as buyer_trade_id,b.Price as sellaskPrice,c.Price as buyaskPrice,MAX(b.Price) as sellaskMaxPrice,MAX(c.Price) as buyaskMaxPrice,b.Fee as sellerfee,c.Fee as buyerfee,b.Total as sellertotal,c.Total as buyertotal','','','','','',array('a.tempId','desc'))->result();
    if ($transactionhistory)
    {
      $historys=$transactionhistory;
    }
    else
    {
      $historys=0;
    }
    return $historys;
  }
  public function liquiditydata($pair_id)
  {
    $liquidity = $this->common_model->getTableData('site_settings',array('id'=>1),'liquidity_concept')->row('liquidity_concept');
    if($liquidity==1)
    {
      $joins      =   array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
      $where      =   array('a.id'=>$pair_id);
      $pair_details   =   $this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'b.currency_symbol as from_currency_symbol,c.currency_symbol as to_currency_symbol,a.to_symbol_id')->row();
      $pair_symbol  = $pair_details->from_currency_symbol.'_'.$pair_details->to_currency_symbol;
      $datass=$this->api->get_order_book($pair_symbol);
      $data1=$datass;
      //print_r($data1);die;
      if(isset($data1->asks))
      {
        $asks=$data1->asks;
      }
      else if(isset($data1['asks']))
      {
        $asks=$data1['asks'];
      }
      else
      {
        $asks='';
      }
      if(isset($data1->bids))
      {
        $bids=$data1->bids;
      }
      else if(isset($data1['bids']))
      {
        $bids=$data1['bids'];
      }
      else
      {
        $bids='';
      }
      if($asks!='')
      {
        $ask_orders=array();
        foreach($asks as $ask)
        {
          $ask_orders["'".$ask[0]."'"]=$ask[1];
        }
      }
      else
      {
        $ask_orders=0;
      }
      if($bids!='')
      {
        $bids_orders=array();
        foreach($bids as $bid)
        {
          $bids_orders["'".$bid[0]."'"]=$bid[1];
        }
      }
      else
      {
        $bids_orders=0;
      }
      $orders=array();
      $orders['asks']=$ask_orders;
      $orders['bids']=$bids_orders;
    }
    else
    {
      $orders=0;
    }
    return $orders;
  }
    public function gettradeopenOrders($type,$pair_id)
  {
    $selectFields='CO.Price,CO.Amount,sum(OT.filledAmount) as filledAmount';
            $names = array('active', 'partially');
            $where=array('CO.Type'=>$type,'CO.pair'=>$pair_id);
            if($type=="sell")
            {
                $order_id='sellorderId';
                $orderBy=array('CO.Price','asc'); 
            }
            else
            {
                $order_id='buyorderId';
                $orderBy=array('CO.Price','desc');
            }
            $where_in=array('CO.status', $names);
            $groupBy=array("CO.trade_id");
            $joins = array('ordertemp as OT'=>'CO.trade_id = OT.'.$order_id);
            $q = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
            $result = $q->result_array();
           // echo $this->db->last_query();
            return $result;
  }
  public function current_trade_pair($pair_id)
  {
    $joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
    $where = array('a.status'=>1);
    $orderprice = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.*,b.currency_name as from_currency,b.currency_symbol as from_currency_symbol,c.currency_name as to_currency,c.currency_symbol as to_currency_symbol')->result();
    $pair=$this->common_model->getTableData('trade_pairs', array('id' => $pair_id))->row();
    $trade_prices=array();
    $volume=getTradeVolume($pair->id);
    if($volume->price!=0)
    {
      $trade_prices['price'] = to_decimal($volume->price,8);
    }
    else
    {
      $trade_prices['price'] = to_decimal($pair->buy_rate_value,8);
    }
    $trade_prices['volume'] = $volume->volume;
    $trade_prices['high'] = $volume->high;
    $trade_prices['low'] = $volume->low;
    return $trade_prices;
  }


    

  

  function cancel_order($tradeid,$pair_id,$userid)
  {
    $tradeid = $tradeid;
    $pair_id = $pair_id;
    $user_id = $userid;
    $response = array('status'=>'','msg'=>'');
    $response=$this->site_api->close_active_order($tradeid,$pair_id,$user_id);
    $result=json_encode($response);
    return  $result;
  }

  function make_chart_points($obj_response){
    if(count($obj_response) > 0){
      foreach ($obj_response as $cpkey => $cpvalue) {
        $t_arr[]=strtotime($cpvalue->startsAt);
        $o_arr[]=$cpvalue->open;
        $h_arr[]=$cpvalue->high;
        $l_arr[]=$cpvalue->low;
        $c_arr[]=$cpvalue->close;
        $v_arr[]=$cpvalue->volume;
      }
      $res_arr=array(
        't'=>$t_arr,
        'o'=>$o_arr,
        'h'=>$h_arr,
        'l'=>$l_arr,
        'c'=>$c_arr,
        'v'=>$v_arr,
        's'=>"ok",
      );
      return json_encode($res_arr);
    }
  }

  function localpair_details()
    {
      error_reporting(0);
      $pair_details = $this->common_model->getTableData('trade_pairs',array('status' => 1))->result();
      if(count($pair_details)>0)
      {
        foreach($pair_details as $pair_detail)
        { 
          if($pair_detail->api_status==1){
          $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
        $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();

        if($from_currency->currency_symbol=="CBC")
          $from_currency_symbol1 = "CBC";
        elseif($from_currency->currency_symbol=='USD')
          $from_currency_symbol1 ='USDC';
        else
          $from_currency_symbol1 = $from_currency->currency_symbol;
        
        if($to_currency->currency_symbol=="CBC")
          $to_currency_symbol1 = "CBC";
        elseif($to_currency->currency_symbol=='USD')
          $to_currency_symbol1 ='USDC';
        else
          $to_currency_symbol1 = $to_currency->currency_symbol;

        

        $pair_symbol = $from_currency_symbol1.$to_currency_symbol1;
        $url = file_get_contents("https://api.binance.com/api/v1/ticker/24hr?symbol=".$pair_symbol);
              $res = json_decode($url,true); 
          if ($res['symbol'] == '' || $res['code']=='-1121') 
          {
            $pair_symbols = $to_currency_symbol1.$from_currency_symbol1;
            $urls = file_get_contents("https://api.binance.com/api/v1/ticker/24hr?symbol=".$pair_symbols);
                  $ress = json_decode($urls,true);
                  if ($ress['symbol'] != '') 
              {
                $priceChangePercent = $ress['priceChangePercent'];
                $lastPrice =  $ress['lastPrice'];
                $volume =  ($ress['volume'] * (0.03/100));
                $change_highs = $ress['highPrice'];
                $change_lows = $ress['lowPrice'];
                $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows
                );
              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
              echo $pair_symbol." reverse Updated <br/>";
              }
              else
              {
                $dbrsymbol = $to_currency_symbol1."/".$from_currency_symbol1;
                $dbsymbol = $from_currency_symbol1."/".$to_currency_symbol1;
                $dbrQuery = $this->db->query("SELECT * FROM `arthbit_coin_order` WHERE `pair_symbol`='".$dbrsymbol."' AND (status='active' || status='partially') ORDER BY `trade_id` DESC LIMIT 1")->row();
                $dbQuery = $this->db->query("SELECT * FROM `arthbit_coin_order` WHERE `pair_symbol`='".$dbsymbol."' AND (status='active' || status='partially') ORDER BY `trade_id` DESC LIMIT 1")->row();
                if(count($dbrQuery)>0)
                { 
                  $priceChangePercent = pricechangepercent($pair_detail->id);
                  $url = "https://api.binance.com/api/v1/ticker/24hr?symbol=ETHBTC";
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              $ress = json_decode($result,true);
                      $priceChangePercent_new = $ress['priceChangePercent'];

                  $priceChangePercent = ($priceChangePercent=='')?$priceChangePercent_new:$priceChangePercent;

                  $lastPrice =  $dbrQuery->Price;
                  $volume = volume($pair_detail->id);
                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume);
                $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
                echo $pair_symbol." DB REV Updated <br/>";
                }
                elseif(count($dbQuery)>0)
                {
                  
                  $priceChangePercent = pricechangepercent($pair_detail->id);
                  $url = "https://api.binance.com/api/v1/ticker/24hr?symbol=ETHBTC";
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              $ress = json_decode($result,true);
                      $priceChangePercent_new = $ress['priceChangePercent'];

                  $priceChangePercent = ($priceChangePercent=='')?$priceChangePercent_new:$priceChangePercent;
                  $lastPrice =  $dbQuery->Price;
                  $volume =  volume($pair_detail->id);
                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume);
                $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
                echo $pair_symbol." DB Updated <br/>";
                }
                else
                {
                  $url = "https://api.binance.com/api/v1/ticker/24hr?symbol=ETHBTC";
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              $ress = json_decode($result,true);
                      $priceChangePercent = $ress['priceChangePercent'];
                  $lastPrice =  $ress['lastPrice'];
                  $volume =  ($ress['volume'] * (0.03/100));
                  $change_highs = $ress['highPrice'];
                $change_lows = $ress['lowPrice'];
                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows
                );
                $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
                  echo $pair_symbol." DUMMY <br/>";
                }
                //echo $pair_symbol." Not Updated <br/>";
              }
          }
          else
          {
            $priceChangePercent = $res['priceChangePercent'];
              $lastPrice =  $res['lastPrice'];
              $volume =  ($res['volume'] * (0.03/100));
              $change_highs = $res['highPrice'];
                $change_lows = $res['lowPrice'];
              $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                'change_high'=>$change_highs,
                  'change_low'=>$change_lows
                );
            $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
            echo $pair_symbol." Updated <br/>";
          }
        }
        else{
          //database
          $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
        $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();

        $pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;

       /* if($from_currency->currency_symbol!='CBC' && $to_currency->currency_symbol=='INR'){
          $Pair_lower = strtolower($from_currency->currency_symbol).strtolower($to_currency->currency_symbol);
          $url = "https://api.wazirx.com/api/v2/tickers/".$Pair_lower;
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              $res = json_decode($result,true);
              

              $lastPrice =  $res['ticker']['last'];
              $volume =  $res['ticker']['vol'];
              $change_highs = $res['ticker']['high'];
              $change_lows = $res['ticker']['low'];

              $Price_change = $lastPrice - $change_lows;
              $Per = $change_lows/100 ;
              $priceChangePercent = $Price_change/$Per;

              $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                'change_high'=>$change_highs,
                  'change_low'=>$change_lows
                );

              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
              echo $pair_symbol." WazirX<br/>";

        }*/
       // else{
          $coin_order = $this->db->query("SELECT * FROM `arthbit_coin_order` WHERE `pair_symbol`='".$pair_symbol."' AND (status='active' || status='partially') ORDER BY `trade_id` DESC")->result();
          $lastPrice = lastmarketprice($pair_detail->id);
          $before_price = before_price($pair_detail->id);
          $cur_price = tradeprice($pair_detail->id);



          $volume = volume($pair_detail->id);
          $change_highs = change_high($pair_detail->id);
          $change_lows = change_low($pair_detail->id);
           
           // $Price_change = $lastPrice - $change_lows;
           //    $Per = $change_lows/100 ;
           //    $priceChangePercent = $Price_change/$Per;

          $pric = $cur_price - $before_price;
          $pric_per = $pric / $before_price;
          $priceChangePercent = $pric_per * 100;
 

              if($volume!=0){
              $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                'change_high'=>$change_highs,
                  'change_low'=>$change_lows
                );
              }
              else{
                $updateTableData = array('priceChangePercent'=>'0',
                  'lastPrice'=>$lastPrice,
                  'volume'=>'0',
                'change_high'=>$lastPrice,
                  'change_low'=>$lastPrice
                );
              }
              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);

              echo $pair_symbol." Database<br/>";
          
       // }
        }
      }
      }
    }

     public function market_trades($pair_id)
{

  // if($pair_id=='47' || $pair_id=='52')  
  // {

     $sevendays    = strtotime("-10 day");
     $date_con = date("Y-m-d h:i:s",$sevendays);
     $where=array('CO.pair'=>$pair_id,'CO.datetime >=' => $date_con);
    
  // }
  // else
  // {
  //    $where=array('CO.pair'=>$pair_id);
  // }

  /*$tradehistory_via_api = $this->common_model->getTableData('site_settings',array('tradehistory_via_api'=>1))->row('tradehistory_via_api');
  if($tradehistory_via_api ==0){*/
  //$selectFields='CO.*,date_format(CO.datetime,"%H:%i:%s") as trade_time,sum(OT.filledAmount) as totalamount,CO.Type as ordertype,CO.Price as price';
    $selectFields='CO.Amount,date_format(CO.datetime,"%H:%i:%s") as trade_time,sum(OT.filledAmount) as totalamount,CO.Type as ordertype,CO.Price as price,CO.datetime as date';
  //$names = array('active', 'partially', 'margin');
  $names = array('filled');
  //$where=array('CO.pair'=>$pair_id);
  $orderBy=array('CO.trade_id','desc');
  $groupBy=array('CO.trade_id');
  $where_in=array('CO.status', $names);
  $joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
  $query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','20',$orderBy,$groupBy,$where_in); 


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

function market_api_trades($pair){

  //$sellresult = $this->common_model->getTableData("api_orders",array("pair_symbol"=>$pair),'price,quantity,type','','','','',40,array('id','DESC'))->result();

  $sellresult = $this->db->query("SELECT price,quantity,type FROM arthbit_api_orders WHERE pair_symbol='".$pair."' ORDER BY rand() LIMIT 20")->result();
        
        if(count($sellresult)>0 && !empty($sellresult))
        { 
          $res_data = array();
          $i=1;
          foreach($sellresult as $sell)
          {
            $sellData['id'] = $i;
            $sellData['price'] = $sell->price;
            $sellData['quantity'] = $sell->quantity;
            $sellData['ordertype'] = ucfirst($sell->type);            
              $res_data[] = $sellData;
              $i++;
          }
          if($res_data&&$res_data!=0)
        {
          //$res_data = shuffle_assoc($res_data);
          $res_data = $res_data;
        }
        else
        {
             $res_data = 0;
        }
        
      }
      else
      {
        $res_data = 0;
      }
      return $res_data;

  
}

    

    

    public function contact_us()
    {
        if($this->block() == 1)
		{ 
		front_redirect('block_ip');
		}
        $this->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('comments', 'Comments', 'trim|required|xss_clean');
        if ($this->input->post('cc_frm')) 
        {
            if ($this->form_validation->run()) 
            {
                $name = $this->db->escape_str($this->input->post('name'));
                $email = $this->db->escape_str($this->input->post('email'));
                $subject = $this->db->escape_str($this->input->post('subject'));
                $comments = $this->db->escape_str($this->input->post('comments'));
                $phone = $this->db->escape_str($this->input->post('phone'));


                $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, 'https://emailvalidation.abstractapi.com/v1/?api_key=d08f03fe70554086a6d4cdcf21d3736a&email='.$email);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                      $data = curl_exec($ch);
                      curl_close($ch);
                      $res = json_decode($data);
                      $mail_check = $res->deliverability;
                      if($mail_check!='DELIVERABLE'){
                        $this->session->set_flashdata('error', 'Please enter a valid Email');
                                  front_redirect('contact_us', 'refresh');
                } 




                $status = 0;
                $contact_data = array(
                    'username' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $comments,
                    'phone' => $phone,
                    'status' => $status,
                    'created_on' => date("Y-m-d h:i:s")
                );
                $id = $this->common_model->insertTableData('contact_us', $contact_data);
                $email_template = 'Contact_user';
                $email_template1 = 'Contact_admin';
				$username=$this->input->post('name');
				$message = $this->input->post('comments');
				$link = base_url().'arthbit_admin/contact';
				$site_common      =   site_common();
				$data['site'] = $this->common_model->getTableData('site_settings',array('id'=>1))->row();
				$admin_admin = $data['site']->site_email;
				$special_vars = array(					
				'###USERNAME###' => $username,
				'###MESSAGE###' => $message
				);

				$special_vars1 = array(					
				'###USERNAME###' => $username,
				'###MESSAGE###' => $message,
				'###LINK###' => $link
				);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
				$this->email_model->sendMail($admin_admin, '', '', $email_template1, $special_vars1);
                if ($id) 
                {
                    $this->session->set_flashdata('success', $this->lang->line('Your message successfully sent to our team'));
                    front_redirect('contact_us', 'refresh');
                } 
                else 
                {
                    $this->session->set_flashdata('error', $this->lang->line('Error occur!! Please try again'));
                    front_redirect('contact_us', 'refresh');
                }
            } 
            else 
            {
                $this->session->set_flashdata('error', validation_errors());
                front_redirect('contact_us', 'refresh');
            }
        }
        $data['site_common'] = site_common();
        $data['action'] = front_url() . 'contact_us';
        $data['site_details'] = $this->common_model->getTableData('site_settings', array('id' => '1'))->row();
        $data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'contact_us'))->row();
        /*$data['heading'] = $meta->heading;
        $data['title'] = $meta->title;
        $data['meta_keywords'] = $meta->meta_keywords;
        $data['meta_description'] = $meta->meta_description;*/
        $data['js_link'] = 'contact_us';
        $this->load->view('front/common/contact', $data);
    }

      function stripe_success()
	{
		$site_settings = site_common();

		$stripe_private_key = $site_settings['site_settings']->stripeapi_private_key;


		try {
				$userid = $_POST['userId'];
				$orderid = $_POST['orderID'];
				$crypto_amnt = $_POST['crypto_amnt'];
				$send_currency = $_POST['send_currency'];
				$receive_currency = $_POST['receive_currency'];
				$fiat_amount = $_POST['fiat_amount'];
				$description = $_POST['prod_details'];

				/*print_r($_POST);
				exit;*/
			/*echo "send_currency".$send_currency."    ======fiat_amount ".$fiat_amount;
			echo "<br>";*/
				
        	require APPPATH .'third_party/stripe/Stripe.php';
        	// Stripe::setApiKey('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'); //Replace with your Secret Key
        	Stripe::setApiKey($stripe_private_key); //Replace with your Secret Key
	        $charge = Stripe_Charge::create(array(
		        "amount" => $fiat_amount * 100,
		        "currency" => $send_currency,
		        "card" => $_POST['stripeToken'],
		        "description" => $description,
		        "metadata" => ["order_id" => $orderid]
	        ));

	        $response = $charge->__toArray(TRUE);

	       /* echo "<pre>";
	        print_r($response);
	        exit;*/

	    	$txn_status = $charge['status'];    	
	    	
	    	if($txn_status=="succeeded")
	    	{

		        $currency_id 	= $charge['item_number']; 
		        $userId 		= $_POST['userId'];
		        $txn_id 		= $charge["id"];
		        $payment_amt 	= $charge["amount"];
		        $transaction_id 	= $charge["balance_transaction"];
		        $currency_code 	= $charge["mc_currency"];
		        // $currency_name 	= $charge["currency"];
		        $status 		= $txn_status;
		        $payment_date 	= $charge["payment_date"];
		        $payer_email 	= $charge["payer_email"];
		        $payment_mode 	= $charge['calculated_statement_descriptor'];
		        $txn_name 		= $charge['item_name'];
		        $payment_type   = $charge['description'];

		        $currency_name   = strtoupper($charge["currency"]);

		       // echo "jhjfhdj".$_POST['receive_currency'];die;

		        $ddddd = getcoindetail($receive_currency);

		        $currencyId = $ddddd->id;

		        $update_amnt = $crypto_amnt;

		        $userbalance = getBalance($userid,$currencyId);

		        $finalbalance = $update_amnt+$userbalance;


		        $updatebalance = updateBalance($userid,$currencyId,$finalbalance,'crypto'); // Update balance		        

		        $curny = $this->common_model->getTableData('fiat_currency',array('currency_symbol'=>$currency_name))->row();


		        $userbalance = getBalance($userId,$usd_id);

		        
				 $dataInsert = array(
				'user_id' => $userId,
				'currency_id' => $currencyId,
				'currency_name' => $ddddd->currency_symbol,
				'amount' => $fiat_amount,
				'type' => 'instant_buy',
				'transfer_amount' => $crypto_amnt,
				'instant_tot_amount' => $crypto_amnt,
				'transfer_currency'=>$receive_currency,
				'transaction_id'=>$transaction_id,
				'status' => "completed",
				'datetime' => date("Y-m-d h:i:s")
			    );
				 
				$ins_id = $this->common_model->insertTableData('transactions', $dataInsert);

				if ($ins_id) {
				// Mail Function
				$prefix = get_prefix();
				$user = getUserDetails($userId);
				$usernames = $prefix.'username';
				$username = $user->$usernames;
				$email = getUserEmail($userId);
				//$currency_name = $curny->currency_symbol;
				$link_ids = base64_encode($ins_id);
				$sitename = getSiteSettings('site_name');
				$site_common      =   site_common();
				$email_template = 'InstantBuy_Complete';		
					$special_vars = array(
					'###SITENAME###' => $sitename,			
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $crypto_amnt,
					'###CURRENCY###' => $receive_currency
				
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
				/* echo $this->email->print_debugger();
				 exit;*/
				$this->session->set_flashdata('success','Your Crypto Amount successfully sent to your crypto wallet');
				front_redirect('instant_buy', 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Unable to submit your withdraw request. Please try again');
				front_redirect('instant_buy', 'refresh');
			}

				
				
    		}
     	}
    	catch(Stripe_CardError $e) {
    		/*echo "err1";
    		print_r($e);die;*/
    	}
	    catch (Stripe_InvalidRequestError $e) {
	    	/*echo "err2";
	    	echo "<pre>";
    		print_r($e);die;*/
	    } catch (Stripe_AuthenticationError $e) {
	    	/*echo "err3";
    		print_r($e);die;*/
	    } catch (Stripe_ApiConnectionError $e) {
	    	/*echo "err4";
    		print_r($e);die;*/
	    } catch (Stripe_Error $e) {
	    	/*echo "err5";
    		print_r($e);die;*/
	    } catch (Exception $e) {
	    	/*echo "err6";
    		print_r($e);die;*/
	    }
	}

	function test()
	{

    echo getUserEmail(19);
	}

  function testing()
  {



  }

	function market_api()
  {
    $this->db->truncate('market_api');
    $trade_pairs = $this->common_model->getTableData("trade_pairs", array("status"=>1))->result();
    if(count($trade_pairs) > 0)
    {
      foreach($trade_pairs as $pair)
      {
               $pair_symbol = getcryptocurrency($pair->from_symbol_id).'_'.getcryptocurrency($pair->to_symbol_id);
               echo $pair_symbol;
               echo "<br>";
               $market_data = $this->common_model->getTableData("market_api",array("symbol"=>$pair_symbol))->row();
               if(count($market_data)>0)
               {
                
               $data['symbol'] = getcryptocurrency($pair->from_symbol_id).'_'.getcryptocurrency($pair->to_symbol_id);
               $data['last_price'] = tradeprice($pair->id);
               $data['high_price'] = highprice($pair->id);
               $data['low_price'] = lowprice($pair->id);
               $data['volume'] = volume($pair->id);
               $data['quoteVolume'] = quotevolume($pair->id);
               $data['price_change'] = pricechange($pair->id);
               $data['price_change_percent'] = pricechangepercent($pair->id);
               $data['bid_price'] = highestbidprice($pair->id);
               $data['ask_price'] = lowestaskprice($pair->id);
               $data['market_price'] = marketprice($pair->id);
               $data['updated_date'] = date("Y-m-d H:i:s");
               
               $update = $this->common_model->updateTableData("market_api",array("symbol"=>$pair_symbol),$data); 
              }
              else
              {
               $pair_symbol = getcryptocurrency($pair->from_symbol_id).'_'.getcryptocurrency($pair->to_symbol_id);
               $data['symbol'] = getcryptocurrency($pair->from_symbol_id).'_'.getcryptocurrency($pair->to_symbol_id);
               $data['last_price'] = tradeprice($pair->id);
               $data['high_price'] = highprice($pair->id);
               $data['low_price'] = lowprice($pair->id);
               $data['volume'] = volume($pair->id);
               $data['quoteVolume'] = quotevolume($pair->id);
               $data['price_change'] = pricechange($pair->id);
               $data['price_change_percent'] = pricechangepercent($pair->id);
               $data['bid_price'] = highestbidprice($pair->id);
               $data['ask_price'] = lowestaskprice($pair->id);
               $data['market_price'] = marketprice($pair->id);
               $data['updated_date'] = date("Y-m-d H:i:s");

               $update = $this->common_model->insertTableData("market_api",$data);
              }
        if($update)
        {
          echo "api updated success";
        }
        else
        {
          echo "api updated failed";
        }
      }
    }

  }

  function showApiList()
{
  $data['js_link'] = '';
  $data['site_common'] = site_common();
  $data['action'] = front_url() . 'api';
  $meta = $this->common_model->getTableData('meta_content', array('link' => 'news'))->row();
  $data['heading'] = $meta->heading;
  $data['title'] = $meta->title;
  $data['meta_keywords'] = $meta->meta_keywords;
  $data['meta_description'] = $meta->meta_description;
  $this->load->view('front/common/show_api', $data);
}

	function market_api_orders()
{
  $this->db->truncate('api_orders');
  $trade_pairs = $this->common_model->getTableData("trade_pairs", array("status"=>1))->result();
  if(count($trade_pairs) > 0)
  {
    foreach($trade_pairs as $pair)
    {
        
        $from_symbol = getcryptocurrency($pair->from_symbol_id);
        $to_symbol = getcryptocurrency($pair->to_symbol_id);

    $pair_value=$from_symbol.$to_symbol;
    /*echo "pair";
    echo $from_symbol.'_'.$to_symbol;
    echo "<br>";*/
        if($pair_value != "") 
        {
          $first_pair  = strtoupper($from_symbol);
        $second_pair = strtoupper($to_symbol);
          $coin_pair = $first_pair.$second_pair;
          $coin_pair_rev = $second_pair.$first_pair;
      
          $json= file_get_contents('https://api.binance.com/api/v1/depth?symbol='.$coin_pair.'&limit=20');
          $newresult = json_decode($json,true);echo $newresult['code'];
          //if(!empty($newresult) 
            if (empty($newresult))
            { 
              $json= file_get_contents('https://api.binance.com/api/v1/depth?symbol='.$coin_pair_rev.'&limit=20');
              $newresult = json_decode($json,true);
              if (empty($newresult))
                {
                  $json= file_get_contents('https://api.binance.com/api/v1/depth?symbol=ETHBTC&limit=20');
                  $newresult = json_decode($json,true);
                  $buy_orders = $newresult['asks'];
                $sell_orders = $newresult['bids'];
                $buy_res = array();
                $i=1;
                foreach($buy_orders as $buy)
                {
                  $buyData['trade_id'] = $i;
                  $buyData['price'] = $buy[0];
                  $buyData['quantity'] = $buy[1];
                  $buyData['pair_id'] = $pair->id;
                  $buyData['pair_symbol'] = $first_pair.'_'.$second_pair;
                  $buyData['type'] = 'buy';
                  $buyData['updated_at'] = date("Y-m-d H:i:s");
                    $insert = $this->common_model->insertTableData("api_orders",$buyData);
              if($insert)
              {
                /*echo $coin_pair_rev." api buy orders updated success";
                echo "<br>";*/
              }
              else
              {
                /*echo $coin_pair_rev." api buy orders updated failed";
                echo "<br>";*/
              }
                    $i++;
                }

                  $j=1;
                foreach($sell_orders as $sell)
                {
                  $sellData['trade_id'] = $j;
                  $sellData['price'] = $sell[0];
                  $sellData['quantity'] = $sell[1];
                  $sellData['pair_id'] = $pair->id;
                    $sellData['pair_symbol'] = $first_pair.'_'.$second_pair;
                    $sellData['type'] = 'sell';
                  $sellData['updated_at'] = date("Y-m-d H:i:s");
                    $insert = $this->common_model->insertTableData("api_orders",$sellData);
              if($insert)
              {
                /*echo "api sell orders updated success";
                echo "<br>";*/
              }
              else
              {
                /*echo "api sell orders updated failed";
                echo "<br>";*/
              }
              $j++;
                }
                echo $coin_pair_rev."DUMMY api buy orders updated success<br/>";
                }
                else
                {
                $buy_orders = $newresult['asks'];
                $sell_orders = $newresult['bids'];
                $buy_res = array();
                $i=1;
                foreach($buy_orders as $buy)
                {
                  $buyData['trade_id'] = $i;
                  $buyData['price'] = $buy[0];
                  $buyData['quantity'] = $buy[1];
                  $buyData['pair_id'] = $pair->id;
                  $buyData['pair_symbol'] = $first_pair.'_'.$second_pair;
                  $buyData['type'] = 'buy';
                  $buyData['updated_at'] = date("Y-m-d H:i:s");
                    $insert = $this->common_model->insertTableData("api_orders",$buyData);
              if($insert)
              {
                /*echo $coin_pair_rev." api buy orders updated success";
                echo "<br>";*/
              }
              else
              {
                /*echo $coin_pair_rev." api buy orders updated failed";
                echo "<br>";*/
              }
                    $i++;
                }

                  $j=1;
                foreach($sell_orders as $sell)
                {
                  $sellData['trade_id'] = $j;
                  $sellData['price'] = $sell[0];
                  $sellData['quantity'] = $sell[1];
                  $sellData['pair_id'] = $pair->id;
                    $sellData['pair_symbol'] = $first_pair.'_'.$second_pair;
                    $sellData['type'] = 'sell';
                  $sellData['updated_at'] = date("Y-m-d H:i:s");
                    $insert = $this->common_model->insertTableData("api_orders",$sellData);
              if($insert)
              {
                /*echo "api sell orders updated success";
                echo "<br>";*/
              }
              else
              {
                /*echo "api sell orders updated failed";
                echo "<br>";*/
              }
              $j++;
                }
                echo $coin_pair_rev." REV api buy orders updated success<br/>";
            }
            }
            else
            {
              $buy_orders = $newresult['bids'];
              $sell_orders = $newresult['asks'];
              $buy_res = array();
              $i=1;
              foreach($buy_orders as $buy)
              {
                $buyData['trade_id'] = $i;
                $buyData['price'] = $buy[0];
                $buyData['quantity'] = $buy[1];
                $buyData['pair_id'] = $pair->id;
                $buyData['pair_symbol'] = $first_pair.'_'.$second_pair;
                $buyData['type'] = 'buy';
                $buyData['updated_at'] = date("Y-m-d H:i:s");
                  $insert = $this->common_model->insertTableData("api_orders",$buyData);
            if($insert)
            {
              /*echo "api buy orders updated success";
              echo "<br>";*/
            }
            else
            {
              /*echo "api buy orders updated failed";
              echo "<br>";*/
            }
                  $i++;
              }

                $j=1;
              foreach($sell_orders as $sell)
              {
                $sellData['trade_id'] = $j;
                $sellData['price'] = $sell[0];
                $sellData['quantity'] = $sell[1];
                $sellData['pair_id'] = $pair->id;
                  $sellData['pair_symbol'] = $first_pair.'_'.$second_pair;
                  $sellData['type'] = 'sell';
                $sellData['updated_at'] = date("Y-m-d H:i:s");
                  $insert = $this->common_model->insertTableData("api_orders",$sellData);
            if($insert)
            {
              /*echo "api sell orders updated success";
              echo "<br>";*/
            }
            else
            {
              /*echo "api sell orders updated failed";
              echo "<br>";*/
            }
            $j++;
              }
              echo $coin_pair." api buy orders updated success<br/>";
          }
            
        }
        }
      }
    
}

function market_api_list(){
    header('Content-Type: application/json');

        $symbol = $_GET['pair'];
    if(isset($symbol) && !empty($symbol)){

        $Api_List = $this->common_model->getTableData('market_api',array('symbol'=>$symbol))->result();
    }
    else{
        $Api_List = $this->common_model->getTableData('market_api')->result();
    }

    

          foreach($Api_List as $Api){
            $symbol = explode("_",$Api->symbol);
            $usd_price = getcoindetail($symbol[0])->online_usdprice;
            $volume_usd = $Api->volume * $usd_price;
            $Insert_data['trading_pairs']     = $Api->symbol;
            $Insert_data['last_price']    = trailingZeroes(numberFormatPrecision($Api->last_price));
            $Insert_data['lowest_ask']    = trailingZeroes(numberFormatPrecision($Api->ask_price));
            $Insert_data['highest_bid']     = trailingZeroes(numberFormatPrecision($Api->bid_price));
            $Insert_data['base_volume']     = trailingZeroes(numberFormatPrecision($Api->volume));
            $Insert_data['quote_volume']    = trailingZeroes(numberFormatPrecision($Api->last_price)) * trailingZeroes(numberFormatPrecision($Api->volume));

            $Insert_data['price_change_percent_24h']  = trailingZeroes(numberFormatPrecision($Api->price_change_percent));
            $Insert_data['highest_price_24h']   = trailingZeroes(numberFormatPrecision($Api->high_price));
            $Insert_data['lowest_price_24h']  = trailingZeroes(numberFormatPrecision($Api->low_price));

            $newDataArray[] = $Insert_data;
  $post_data_allticker = array('code' => '200', 'msg'=>'success', 'data' => ($newDataArray));

          }
    
  echo json_encode($post_data_allticker,true);


  }


function market_api_data($pair_symbol){
    header('Content-Type: application/json');

  $Api = $this->common_model->getTableData('market_api',array("symbol"=>$pair_symbol))->row();
    $symbol = explode("_",$pair_symbol);
    $usd_price = getcoindetail($symbol[0])->online_usdprice;
    $volume_usd = $Api->volume * $usd_price;
    $Insert_data['symbol']    = $Api->symbol;
    $Insert_data['last']    = $Api->last_price;
    $Insert_data['high']    = $Api->high_price;
    $Insert_data['low']     = $Api->low_price;
    $Insert_data['volume']    = $Api->volume;
        $Insert_data['volume_usd']    = $volume_usd;
    $Insert_data['bidPrice']    = $Api->bid_price;
    $Insert_data['askPrice']  = $Api->ask_price;
    $Insert_data['price_change']  = $Api->price_change;
    $Insert_data['price_change_percent']  = $Api->price_change_percent;
    
  if (count($Api)>0) 
      
  {
  $post_data_ticker_pair = array('code' => '200', 'msg'=>'success', 'data' => ($Insert_data));
  }

  else
    
  {
    $post_data_ticker_pair = array ('status' => false,'error' => 'Incorrect pair',);

  }
  echo json_encode($post_data_ticker_pair,true);
    
  }

   function market_api_depth($pair_symbol){
        $pair_id = getpair($pair_symbol)->id;
        $checkapi = checkapi($pair_id);
        $limit = ($_GET['limit']!='')?$_GET['limit']:0;
        header('Content-Type: application/json');
        $data = array();
        if($checkapi==1)
        {
            if($limit==0)
            {
              $Api = $this->common_model->getTableData('api_orders',array("pair_symbol"=>$pair_symbol,'type'=>'buy'),'','','','','','',array('price','desc'))->result();
            }
            else
            {
              $Api = $this->common_model->getTableData('api_orders',array("pair_symbol"=>$pair_symbol,'type'=>'buy'),'','','','','',$limit,array('price','desc'))->result();
            }

            if(count($Api)>0)
            {            
                $bids = array();
                foreach($Api as $row)
                {
                    array_push($bids, array(trailingZeroes(numberFormatPrecision($row->price)),trailingZeroes(numberFormatPrecision($row->quantity))));
                }
                $data['bids'] = $bids;           
            }
            if($limit==0)
            {
               $Apis = $this->common_model->getTableData('api_orders',array("pair_symbol"=>$pair_symbol,'type'=>'sell'),'','','','','','',array('price','asc'))->result(); 
            }
            else
            {
              $Apis = $this->common_model->getTableData('api_orders',array("pair_symbol"=>$pair_symbol,'type'=>'sell'),'','','','','',$limit,array('price','asc'))->result(); 
            }
            
             if(count($Apis)>0)
             {
                $sells = array();
                foreach($Apis as $row)
                {
                    array_push($sells, array(trailingZeroes(numberFormatPrecision($row->price)),trailingZeroes(numberFormatPrecision($row->quantity))));
                }
                
                $data['asks'] = $sells;           
            }
       }
        else
        {
            if($limit==0)
            {
              $Api = $this->common_model->getTableData('coin_order',array("pair"=>$pair_id,'Type'=>'buy',"status"=>'active'),'','','','','','',array('price','desc'))->result();
            }
            else
            {
              $Api = $this->common_model->getTableData('coin_order',array("pair"=>$pair_id,'Type'=>'buy',"status"=>'active'),'','','','','',$limit,array('price','desc'))->result();
            }

            if(count($Api)>0)
            {            
                $bids = array();
                foreach($Api as $row)
                {
                    array_push($bids, array(trailingZeroes(numberFormatPrecision($row->Price)),trailingZeroes(numberFormatPrecision($row->Amount))));
                }
                $data['bids'] = $bids;           
            }
            if($limit==0)
            {
               $Apis = $this->common_model->getTableData('coin_order',array("pair"=>$pair_id,'Type'=>'sell',"status"=>'active'),'','','','','','',array('price','asc'))->result(); 
            }
            else
            {
              $Apis = $this->common_model->getTableData('coin_order',array("pair"=>$pair_id,'Type'=>'sell',"status"=>'active'),'','','','','',$limit,array('price','asc'))->result(); 
            }
            
             if(count($Apis)>0)
             {
                $sells = array();
                foreach($Apis as $row)
                {
                    array_push($sells, array(trailingZeroes(numberFormatPrecision($row->Price)),trailingZeroes(numberFormatPrecision($row->Amount))));
                }
                
                $data['asks'] = $sells;           
            }
        }
        
        
        
        if(count($Api)>0 || count($Apis)>0)
        {
        $lastUpdateId = strtotime(date("Y-m-d H:i:s"));
        $data['timestamp'] = $lastUpdateId;
      
    $post_data_orderbook = array('code' => '200', 'msg'=>'success', 'data' => ($data));

        echo json_encode($post_data_orderbook,true);
        }
        else
        {
        $data['response'] = "No orders found";

        echo json_encode($data,true);
        }

        
    }

function assets(){
        
     header('Content-Type: application/json');

     $symbol = $_GET['symbol'];

       if(isset($symbol) && !empty($symbol)){
       $currency_list = $this->common_model->getTableData('currency',array("currency_symbol"=>$symbol,"status"=>1))->result();
       }
       else{
       $currency_list = $this->common_model->getTableData('currency',array("status"=>1))->result();
       }

          if(count($currency_list) >0 )
          {
            foreach($currency_list as $curr){
              $check_currency = $this->common_model->customQuery("SELECT * from arthbit_trade_pairs where status = 1 AND from_symbol_id = ".$curr->id." OR to_symbol_id = ".$curr->id."")->row();
              if(count($check_currency)>0)
              {
                if($curr->type=="digital")
                {
                  $deposit_status = ($curr->deposit_status==1)?"true":"false";
                  $withdraw_status = ($curr->withdraw_status==1)?"true":"false";
                }
                else
                {
                  $deposit_status = ($curr->fiatdeposit_status==1)?"true":"false";
                  $withdraw_status = ($curr->fiatwithdraw_status==1)?"true":"false";
                }
                $Insert_data['name']    = strtolower($curr->currency_name);
                $Insert_data['unified_cryptoasset_id'] = $curr->id;
                $Insert_data['can_withdraw']    = $withdraw_status;
                $Insert_data['can_deposit']     = $deposit_status;
                $Insert_data['min_withdraw']    = trailingZeroes(numberFormatPrecision($curr->min_withdraw_limit));
                $Insert_data['max_withdraw']    = trailingZeroes(numberFormatPrecision($curr->max_withdraw_limit));
                $Insert_data['maker_fee']    = trailingZeroes(numberFormatPrecision($curr->maker_fee));
                $Insert_data['taker_fee']    = trailingZeroes(numberFormatPrecision($curr->taker_fee));
                $newDataArray[$curr->currency_symbol] = $Insert_data;
              
              }
            
          }
      $post_data_assets = array('code' => '200', 'msg'=>'success', 'data' => ($newDataArray));
          }
   else
   {
    $post_data_assets = array ('status' => false,'error' => 'Incorrect symbol',);

   }
   
  echo json_encode($post_data_assets,true);

  }

  function ticker(){
    header('Content-Type: application/json');

    $symbol = $_GET['pair'];
    if(isset($symbol) && !empty($symbol)){
        $Exp = explode('_', $symbol);
        $from_symbol = getcoindetail($Exp[0])->id;
        $to_symbol = getcoindetail($Exp[1])->id;

        $pair_list = $this->common_model->getTableData('trade_pairs',array("status"=>1,'from_symbol_id'=>$from_symbol,'to_symbol_id'=>$to_symbol))->result();
    }
    else{
        $pair_list = $this->common_model->getTableData('trade_pairs',array("status"=>1))->result();
    }
   

   if(count($pair_list)>0)
   {
    foreach($pair_list as $pair){
      $from_symbol = getcryptocurrency($pair->from_symbol_id);
      $to_symbol = getcryptocurrency($pair->to_symbol_id);

      $market_data = $this->common_model->getTableData("market_api",array("symbol"=>$from_symbol."_".$to_symbol))->row();
            
            $Insert_data['base_id']    = $pair->from_symbol_id;
            $Insert_data['quote_id']    = $pair->to_symbol_id;
            $Insert_data['last_price']    =  trailingZeroes(numberFormatPrecision($market_data->last_price));
            $Insert_data['base_volume']    = trailingZeroes(numberFormatPrecision($market_data->volume));
            $Insert_data['quote_volume']     = trailingZeroes(numberFormatPrecision($market_data->volume)) * trailingZeroes(numberFormatPrecision($market_data->last_price));

            if($pair->status=='1'){
                $Status = 0;
            }
            else{
                $Status = 1;
            }
            $Insert_data['isFrozen']    = $Status;
            $newDataArray[$market_data->symbol] = $Insert_data;
      }
      $post_data_ticker = array('code' => '200', 'msg'=>'success', 'data' => ($newDataArray));
   }
      else
   {
    $post_data_ticker = array ('status' => false,'error' => 'Incorrect pair',);

   }
   
  echo json_encode($post_data_ticker,true);

  }

  function trades($pair_symbol){
  header('Content-Type: application/json');
  $sym_array = explode("_",$pair_symbol);
  $symbol = $sym_array[0]."/".$sym_array[1];  
  $Ispair_exists=$this->common_model->check_pair_exists($symbol);
  if(count($Ispair_exists)>0)
  {
      $order_list = $this->common_model->gettrades($symbol);
      if(count($order_list)>0)
      {
        foreach($order_list as $list){
               $quote_volume = $list['Amount'] * $list['Price'];
               $Insert_data['trade_id']    = $list['trade_id'];
               $Insert_data['price']    = trailingZeroes(numberFormatPrecision($list['Price']));
               $Insert_data['base_volume']    =  trailingZeroes(numberFormatPrecision($list['Amount']));
               $Insert_data['quote_volume']     = trailingZeroes(numberFormatPrecision($quote_volume));
               $Insert_data['timestamp']    = strtotime($list['datetime']);
               $Insert_data['type']     = $list['Type'];
               $newDataArray[] = $Insert_data;
        }
        $post_data_trades = array('code' => '200', 'msg'=>'success', 'data' => ($newDataArray));
      }
      else
      {
       $post_data_trades = array('code' => '200', 'msg'=>'success', 'data' => ($newDataArray));
      }
  }
  else
  {
       $post_data_trades = array ('status' => false,'error' => 'Incorrect pair',);
  }
  echo json_encode($post_data_trades,true);

  }

	function api()
	{
		if($this->block() == 1)
		{
		front_redirect('block_ip');
		}
        $data['site_common'] = site_common();
        $data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'api'))->row();
        $this->load->view('front/common/market_api', $data);
	}

    /*function common_test_details()
    {
       
        echo getUserEmail(2);
        echo "<br>";
        echo decryptIt("ODVNVDJvbmVxaTRXY0kzME1KMjM2Zz09");
        exit;
         $activation_code = time().rand();
          echo $usermail = getUserEmail(5);
          exit;
        
         $uname = getUserDetails(5,"arthbit_username");

         $this->common_model->updateTableData('users',array('id'=>5),array('activation_code'=>$activation_code));
          $email_template = 'Registration';
          $site_common      =   site_common();
                    $fb_link = $site_common['site_settings']->facebooklink;
                    $tw_link = $site_common['site_settings']->twitterlink;
                    $tg_link = $site_common['site_settings']->telegramlink;
                    $md_link = $site_common['site_settings']->youtube_link;
                    $ld_link = $site_common['site_settings']->linkedin_link;
          $special_vars = array(
          '###USERNAME###' => $uname,
          '###LINK###' => front_url().'verify_user/'.$activation_code
          );
          //-----------------
          $this->email_model->sendMail($usermail, '', '', $email_template, $special_vars);
          exit;

        $coin_name = 'BNB';
        $model_name = strtolower($coin_name).'_wallet_model';
        $model_location = 'wallets/'.strtolower($coin_name).'_wallet_model';
        $this->load->model($model_location,$model_name);
        $block_no = $this->$model_name->eth_blockNumber();
        echo $block_no;
        exit;
        $user_id = 1;
        $result = $this->$model_name->createaddress($user_id);
        $resp = json_decode($result,true);
        echo "<pre>";
       echo $resp['privateKey'];
       echo $resp['address']['base58'];
       exit;
        
    }*/

    public function common_test_details(){

           
          $data = array();
          $data['jsonrpc'] = "2.0"; 
          $data['id'] = 5;
          $data['method'] = 'personal_newAccount';
          $data['params'] =  array("password"); 
          $postfields = array("password"); 

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://rpc01.bdltscan.io');
          curl_setopt($ch, CURLOPT_PORT, '53556');
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
          curl_setopt($ch, CURLOPT_POST, count($postfields));
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          $ret = curl_exec($ch);
          curl_close($ch); 


          echo "Result.."; 
          echo "<pre>";  
          print_r($ret);
          echo "<pre>";   
         
          // if($ret !== FALSE)
          // {
          //   $formatted = $this->format_response($ret);
            
          //   if(isset($formatted->error))
          //   {
          //     throw new RPCException($formatted->error->message, $formatted->error->code);
          //   }
          //   else
          //   {
          //     return $formatted;
          //   }
          // }
          // else
          // {
          //   throw new RPCException("Server did not respond");
          // }

    }

    public function admin_profit_balance($coin_symbol)
    {
        $whers_con = "id='1'";

        $get_admin = $this->common_model->getrow("admin_wallet", $whers_con);

        if(!empty($get_admin)) 
        {
                $get_admin_det              =   json_decode($get_admin->addresses, TRUE);

                $Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1','currency_symbol'=>$coin_symbol))->row();

                $coin_symbol   =  $Fetch_coin_list->currency_symbol;    
                $coinname      =  $Fetch_coin_list->currency_name;
                $currency_id = $Fetch_coin_list->id;
                $coin_type = $Fetch_coin_list->coin_type;    
                if(array_key_exists($coin_symbol, $get_admin_det))
                {                   

                    if(!empty($get_admin_det[$coin_symbol]))
                    {
                        $admin_address                  =   $get_admin_det[$coin_symbol];

                        if(!empty($admin_address))
                        {
                            //$wallet_bal                 =   $this->local_model->wallet_balance($coinname, $admin_address);
                        if($coin_type=="token")
                        {
                         $profit = $this->common_model->customQuery("SELECT SUM(profit_amount) as profit_total, SUM(bonus_amount) as bonus_total from arthbit_transaction_history where currency = ".$currency_id." and type != 'Withdraw'")->row();
                        }
                        else
                        {
                        $profit = $this->common_model->customQuery("SELECT SUM(profit_amount) as profit_total, SUM(bonus_amount) as bonus_total from arthbit_transaction_history where currency = ".$currency_id."")->row();
                        }
                        

                        /*echo $profit->profit_total;
                        echo "<br>";
                        echo $profit->bonus_total;
                        echo "<br>";*/
                        
                        if($coin_symbol=="ETH")
                        {
                            $ethprofit = $this->common_model->customQuery("SELECT SUM(profit_amount) as profit_total, SUM(bonus_amount) as bonus_total from arthbit_transaction_history where currency in(3,5) and type = 'Withdraw'")->row();
                            $ethprofit_total = $ethprofit->profit_total;
                            $ethbonus_total = $ethprofit->bonus_total;
                            $eth_total = $ethprofit_total + $ethbonus_total;
                            /*echo $eth_total;
                            echo "<br>";*/
                            $total_profit = $profit->profit_total + $profit->bonus_total;
                            $total = $total_profit + $eth_total;
                        }
                        else
                        {
                            $total_profit = $profit->profit_total + $profit->bonus_total;
                            $total = $total_profit;
                        }
                        

                           /*echo number_format($total,8);
                           exit;*/
                            $en_code_bal                =   json_decode($get_admin->balance, TRUE);
                        
                            $en_code_bal[$coin_symbol]  =   number_format($total,8);

                            //$update['balance']   =   json_encode($en_code_bal);
                            $update['balance']   =   json_encode($en_code_bal);

                            $condition                  =   array('id' => '1');

                           // print_r($update); exit;

                            $exc                        =   $this->common_model->updateTableData('admin_wallet', $condition, $update);


                            if($exc){
                                echo "success";
                            }
                            else
                            {
                                echo "Fail";
                            }
                        }                  
                    }
                }
        
        }
    }

    function api_call_list(){

  $joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
  $coin_pairs = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,'','a.*,b.currency_name as from_currency,b.currency_symbol as from_currency_symbol,c.currency_name as to_currency,c.currency_symbol as to_currency_symbol,b.image as image')->result();


    $end_date = date("Y-m-d H:i:s");
    $start_date = date('Y-m-d H:i:s', strtotime($end_date . '- 1 day'));

    $names  = array('filled', 'partially');
    $where_in=array('status',$names);
  
    $newDataArray = [];
    $this->db->truncate('api');
    foreach($coin_pairs as $pairs){
      $volume = 0;
        $low = 0;
        $high = 0;

  if($pairs->data_fetch==0){

    $chartResult = $this->common_model->getTableData('coin_order',array('datetime >= '=>$start_date,'datetime <= '=>$end_date,'pair'=>$pairs->id),'SUM(Amount) as volume,MIN(Price) as low,MAX(Price) as high','','','','','','','','',$where_in)->row();

    if(isset($chartResult) && !empty($chartResult)){
        $volume   = $chartResult->volume;
        $low    = $chartResult->low; 
        $high   = $chartResult->high;
      }
      
        
      

    $lowestaskprice = lowestaskprice($pairs->id);
    $highestbidprice = highestbidprice($pairs->id);
    $lastmarketprice = lastmarketprice($pairs->id);

      $Insert_data['cur']     = $pairs->from_currency_symbol;
      $Insert_data['symbol']    = $pairs->from_currency_symbol.'_'.$pairs->to_currency_symbol;
      $Insert_data['last']    = (float)number_format((float)$lastmarketprice, 8, '.', '');
      $Insert_data['high']    = (float)number_format((float)$high, 8, '.', '');
      $Insert_data['low']     = (float)number_format((float)$low, 8, '.', '');
      $Insert_data['volume']    = (float)number_format((float)$volume, 8, '.', '');
      $Insert_data['vwap']    = $thisDataArray['last'];
      $Insert_data['max_bid']   = (float)number_format((float)$highestbidprice, 8, '.', '');
      $Insert_data['min_ask']   = (float)number_format((float)$lowestaskprice, 8, '.', '');
      $Insert_data['best_bid']  = (float)number_format((float)$highestbidprice, 8, '.', '');
      $Insert_data['best_ask']  = (float)number_format((float)$lowestaskprice, 8, '.', '');
      $Insert_data['updated_on']  = date('Y-m-d H:i:s');
      $Insert_data['db']      = 'db';

      $id=$this->common_model->insertTableData('api', $Insert_data);
    }
    else{
    $Trade_Pairs = $pairs->from_currency_symbol.'/'.$pairs->to_currency_symbol;
    

    if($Trade_Pairs=='ETC/BTC'){
        $thisData     = file_get_contents('https://poloniex.com/public?command=returnTicker');
          
          
          if(isset($thisData) && !empty($thisData)){
          $thisDataArray = json_decode($thisData,true);
          unset($thisDataArray['BTC_ETC']['id']);
          $last = $thisDataArray['BTC_ETC']['last'];
          unset($thisDataArray['BTC_ETC']['last']);
          $high = $thisDataArray['BTC_ETC']['high24hr'];
          unset($thisDataArray['BTC_ETC']['high24hr']);
          $low = $thisDataArray['BTC_ETC']['low24hr'];
          unset($thisDataArray['BTC_ETC']['low24hr']);
          $volume1 = $thisDataArray['BTC_ETC']['quoteVolume'];
          unset($thisDataArray['BTC_ETC']['quoteVolume']);
          unset($thisDataArray['BTC_ETC']['baseVolume']);
          $max_bid = $thisDataArray['BTC_ETC']['highestBid'];
          unset($thisDataArray['BTC_ETC']['highestBid']);
          $min_ask = $thisDataArray['BTC_ETC']['lowestAsk'];
          unset($thisDataArray['BTC_ETC']['lowestAsk']);
          unset($thisDataArray['BTC_ETC']['percentChange']);
          unset($thisDataArray['BTC_ETC']['isFrozen']);

          $Insert_data['cur']     = $thisDataArray['BTC_ETC']['cur'] = 'ETC';
          $Insert_data['symbol']    = $thisDataArray['BTC_ETC']['symbol'] = 'ETC_BTC';
          $Insert_data['last']    = $thisDataArray['BTC_ETC']['last'] = $last;
          $Insert_data['high']    = $thisDataArray['BTC_ETC']['high'] = $high;
          $Insert_data['low']     = $thisDataArray['BTC_ETC']['low'] = $low;
          $Insert_data['volume']    = $thisDataArray['BTC_ETC']['volume'] = $volume1 + $volume;
          $Insert_data['vwap']    = $thisDataArray['BTC_ETC']['last'] = $last;
          $Insert_data['max_bid']   = $thisDataArray['BTC_ETC']['max_bid'] = $max_bid;
          $Insert_data['min_ask']   = $thisDataArray['BTC_ETC']['min_ask'] = $min_ask;
          $Insert_data['best_ask']  = $thisDataArray['BTC_ETC']['best_ask'] = $best_ask;
          $Insert_data['best_bid']  = $thisDataArray['BTC_ETC']['best_bid'] = $max_bid;
          $Insert_data['updated_on']  = date('Y-m-d H:i:s');
          $Insert_data['db']      = 'poloniex';

          $id=$this->common_model->insertTableData('api', $Insert_data);
        }
          
      }
      else{

  
    
    $thisData= file_get_contents('https://api.livecoin.net/exchange/ticker?currencyPair='.$Trade_Pairs);


    if(isset($thisData) && !empty($thisData)){
    $thisDataArray = json_decode($thisData,true);
    

        
        if(!isset($thisDataArray['success'])){
          
          /*echo "<pre>";
    print_r($thisDataArray);*/

          $Insert_data['cur']     = $thisDataArray['cur'];
          $Insert_data['symbol']    = $thisDataArray['symbol'];
          $Insert_data['last']    = $thisDataArray['last'];
          $Insert_data['high']    = $thisDataArray['high'];
          $Insert_data['low']     = $thisDataArray['low'];
            if(isset($thisDataArray['volume']) && !empty($thisDataArray['volume'])){
              $Insert_data['volume'] = $thisDataArray['volume'] + $volume;
                }
          $Insert_data['vwap']    = $thisDataArray['vwap'];
          $Insert_data['max_bid']   = $thisDataArray['max_bid'];
          $Insert_data['min_ask']   = $thisDataArray['min_ask'];
          $Insert_data['best_bid']  = $thisDataArray['best_bid'];
          $Insert_data['best_ask']  = $thisDataArray['best_ask'];
          $Insert_data['updated_on']  = date('Y-m-d H:i:s');
          $Insert_data['db']      = 'livecoin';

          $id=$this->common_model->insertTableData('api', $Insert_data);
        }
  }
    

}
  } 
  }

}

function apicall(){
  header('Content-Type: application/json');

  $Api_List = $this->common_model->getTableData('api')->result();

        foreach($Api_List as $Api){
          $Insert_data['cur']     = $Api->cur;
          $Insert_data['symbol']    = $Api->symbol;
          $Insert_data['last']    = $Api->last;
          $Insert_data['high']    = $Api->high;
          $Insert_data['low']     = $Api->low;
          $Insert_data['volume']    = $Api->volume;
          $Insert_data['vwap']    = $Api->last;
          $Insert_data['max_bid']   = $Api->max_bid;
          $Insert_data['min_ask']   = $Api->min_ask;
          $Insert_data['best_bid']  = $Api->best_bid;
          $Insert_data['best_ask']  = $Api->best_ask;

          $newDataArray[] = $Insert_data;
        }  
        echo json_encode($newDataArray,true);
}

    function getcurrency_localdetails()
    {
        $currency_details = $this->common_model->getTableData('currency',array('type'=>'digital','status' => 1),"id, currency_name, currency_symbol,contract_address")->result();
        if(count($currency_details)>0)
        {
          $market_cap = '';
          foreach($currency_details as $row)
          {         
            $currency_name = strtolower($row->currency_name); 
            $currency_name = ($currency_name=='bnb')?'binancecoin':$currency_name;
            $url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=".$currency_name;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $res = json_decode($result,true);
            echo $currency_name . " => coingecko ". $market_cap =  $res[0]['market_cap_change_percentage_24h']; echo '<br/>';
            echo "V1=>".$volume = $res[0]['total_volume']; echo '<br/>';
            if($market_cap =='' && empty($res))
            {              
              $contract = $row->contract_address; 
              $urls = 'https://api.coingecko.com/api/v3/coins/ethereum/contract/'.$contract;
              $chs = curl_init();
              curl_setopt($chs, CURLOPT_URL, $urls);
              curl_setopt($chs, CURLOPT_RETURNTRANSFER, 1);
              $result1 = curl_exec($chs);
              $errorMessage = curl_error($chs);
              $res1 = json_decode($result1); 
              $market_caps = $res1->market_data->market_cap_change_percentage_24h; 
              echo $currency_name . " => Contract " . $market_cap =($market_caps=='')?'0':$market_caps; echo '<br/>';
              echo "V2=>".$volume = '';echo '<br/>';
            }
            else
            {
              $coin_symbol = $row->currency_symbol;  

              $api_key = getSiteSettings('cryptocompare_apikey');

              $urls = "https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=USD&api_key=".$api_key;
              $chs = curl_init();
              curl_setopt($chs, CURLOPT_URL, $urls);
              curl_setopt($chs, CURLOPT_RETURNTRANSFER, 1);
              $result1 = curl_exec($chs);
              $errorMessage = curl_error($chs);
              $res1 = json_decode($result1);
              echo $coin_symbol.$usd_cap = $res1->USD; echo '<br/>';
              //echo "V3=>".$volume ='';echo '<br/>';
              //$market_cap = 0;
            }
            $market_cap = ($market_cap==''||$market_cap=='0')?'0':$market_cap;
            $updateTableData = array(
              'market_cap_change_percentage_24h' =>$market_cap,
              'usd_cap' =>$usd_cap,
              'volume' =>$volume
            );
            $this->common_model->updateTableData('currency', array('id' => $row->id), $updateTableData);
          }
        }
    }

    function fees()
{
if($this->block() == 1)
{
front_redirect('block_ip');
}
$data['currency'] = $this->common_model->getTableData('currency',array('show_home'=>'1'),'','','','','','', array('currency_name', 'ASC'))->result();
$data['site_common'] = site_common();
$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'fee'))->row();

    $this->load->view('front/common/fee', $data);
}


function binance_order_status(){

           $Orders = $this->common_model->getTableData('binance_order',array('order_type'=>'limit','status'=>'Pending'),'','','','','','', array('id', 'ASC'))->result();

           if(isset($Orders) && !empty($Orders)){
            foreach($Orders as $Binance_orders){
              $Arth_Trade_Id = $Binance_orders->arth_trade_id;
              $orderId = $Binance_orders->orderId;
              $Pair = $Binance_orders->pair_id;
              $Symbol = $Binance_orders->symbol;

              $Binance_Response = binance_order_status($Symbol,$orderId);

              echo "<pre>";
              print_r($Binance_Response);
              echo "</pre>";

              if(isset($Binance_Response['status']) && !empty($Binance_Response['status']) && $Binance_Response['status']=='FILLED'){
                $updateTableData = array(
              'status' =>'Completed'
            );
            $this->common_model->updateTableData('binance_order', array('id' => $Binance_orders->id), $updateTableData);
            echo $this->db->last_query()."<br/>";


            $coin_order = $this->common_model->getTableData('coin_order',array('trade_id'=>$Arth_Trade_Id))->row();

            $date               =   date('Y-m-d');
            $time               =   date("H:i:s");
            $datetime           =   date("Y-m-d H:i:s");
            if($coin_order->Type=='buy'){
              $Buyer_Status = 'inactive';
              $Seller_Status = 'active';
            }
            else{
              $Buyer_Status = 'active';
              $Seller_Status = 'inactive';
            }
            $data               =   array(
                        'sellorderId'       =>  $Arth_Trade_Id,
                        'sellerUserid'      =>  $coin_order->userId,
                        'askAmount'         =>  $coin_order->Amount,
                        'askPrice'          =>  $coin_order->Price,
                        'filledAmount'      =>  $coin_order->Amount,
                        'buyorderId'        =>  $Arth_Trade_Id,
                        'buyerUserid'       =>  $coin_order->userId,
                        'sellerStatus'      =>  $Seller_Status,
                        'buyerStatus'       =>  $Buyer_Status,
                        "pair"              =>  $coin_order->pair,
                        "datetime"          =>  $datetime
                        );
            $inserted=$this->common_model->insertTableData('ordertemp', $data);

            if($inserted){
              $this->site_api->ordercompletetype($Arth_Trade_Id,$coin_order->Type,$inserted);
              if($coin_order->Type=='buy'){
                $Or_Type = 'Buy';
              }
              else{
                $Or_Type = 'Sell';
              }
              $pair_details = $this->common_model->getTableData('trade_pairs',array('id'=>$Pair),'from_symbol_id,to_symbol_id')->row();
                $trans_data = array(
                'userId'=>$coin_order->userId,
                'type'=>$Or_Type,
                'currency'=>$pair_details->to_symbol_id,
                'amount'=>$coin_order->Total,
                'profit_amount'=>$coin_order->Fee,
                'comment'=>'Trade Buy order #'.$Arth_Trade_Id,
                'datetime'=>date('Y-m-d h:i:s'),
                'currency_type'=>'crypto',
                'bonus_amount'=>0
                );
                $update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);
                echo $Arth_Trade_Id." -Success<br/>";
              }
            }
            }
           }
           else{
            echo "Order Empty";
           }
}

function bot_order()
    {
      
        $pairs = $this->common_model->getTableData("trade_pairs",array("bot_status"=>'1'))->result();
        echo "Pila<br/>";
        echo count($pairs)."<br/>";
        if(count($pairs)>0)
        {
            foreach($pairs as $pair)
            {
                $pair_symbol = getcryptocurrency($pair->from_symbol_id).'/'.getcryptocurrency($pair->to_symbol_id);
                $bot_min_amount = $pair->bot_min_amount*100;
                $bot_max_amount = $pair->bot_max_amount*100;
                $bot_minprice_per = $pair->bot_minprice_per*10;
                $bot_maxprice_per = $pair->bot_maxprice_per*10;
                $bot_time_min = $pair->bot_time_min;
                $bot_time_max = $pair->bot_time_max;

                $amount = rand($bot_min_amount,$bot_max_amount);
        $amount = $amount / 100;
        
                $price_percent = rand($bot_minprice_per,$bot_maxprice_per); 
        $price_percent = $price_percent / 10;
        
                $time_interval = rand($bot_time_min,$bot_time_max); 

                $op_array = array('0'=>'+','1'=>'-');
                $op_key = array_rand($op_array);
                $op = $op_array[$op_key];

                $type_array = array('0'=>'buy','1'=>'sell');
                $type_key = array_rand($type_array);
                $type_val = $type_array[$type_key];

                echo "amount => ".$amount."<br>";
                echo "price per => ".$price_percent."<br>";
                echo "time interval => ".$time_interval."<br>";

                echo "op => ".$op."<br>";

                $check_coin_order = $this->common_model->customQuery("SELECT * from arthbit_coin_order where pair = ".$pair->id." and status in('active','partially')")->result();
               // echo count($check_coin_order)."<br>";

                if(count($check_coin_order)>0)
                {
                    //echo "here <br>";
                   /*$buy_price = $this->common_model->customQuery("SELECT Price from arthbit_coin_order where pair = ".$pair->id." and status in('active','partially') and Type = 'buy' order by Price DESC limit 0,1 ")->row();
                   $sell_price = $this->common_model->customQuery("SELECT Price from arthbit_coin_order where pair = ".$pair->id." and status in('active','partially') and Type = 'sell' order by Price ASC limit 0,1 ")->row();*/

                   /*$buy_price = $this->common_model->customQuery("SELECT Price from arthbit_coin_order where pair = ".$pair->id." and status in('active','partially') and Type = 'buy' order by RAND() limit 0,1 ")->row();
                   $sell_price = $this->common_model->customQuery("SELECT Price from arthbit_coin_order where pair = ".$pair->id." and status in('active','partially') and Type = 'sell' order by RAND() limit 0,1 ")->row();*/

                   $buy_price = $this->common_model->customQuery("SELECT Price from arthbit_coin_order where pair = ".$pair->id." and status in('active','partially') and Type = 'buy' order by Price DESC limit 0,1 ")->row();
                   $sell_price = $this->common_model->customQuery("SELECT Price from arthbit_coin_order where pair = ".$pair->id." and status in('active','partially') and Type = 'sell' order by Price ASC limit 0,1 ")->row();

                  /* echo $buy_price->Price."<br>";
                   echo $sell_price->Price."<br>";*/
                    
                 $buyPrice = $buy_price->Price;
                 $sellPrice = $sell_price->Price;
                   if($buyPrice !="" && $buyPrice != NULL)
                   {
                    $buyPrice = $buyPrice;
                   }
                   else
                   {
                    $buyPrice = 0;
                   }

                   if($sellPrice !="" && $sellPrice != NULL)
                   {
                    $sellPrice = $sellPrice;
                   }
                   else
                   {
                    $sellPrice = 0;
                   }
                   
                   $avg_price = ($buyPrice + $sellPrice) / 2;
                   

                   $percent_calc = $price_percent / 100;

                   $price_calc = $avg_price * $percent_calc;
                   
                   if($op=='+')
                   {
                    $price = floatval($avg_price) + floatval($price_calc);
                   }
                   else
                   {
                    $price = floatval($avg_price) - floatval($price_calc);
                   }
                   
                   /*echo "avg price ".$avg_price."<br>";
                   echo "price_calc ".$price_calc."<br>";
                   echo "price ".floatval($avg_price) + floatval($price_calc)."<br>";*/
 

                   
                }
                else
                {
                  echo "api";

                   /*$buy_price = $this->common_model->customQuery("SELECT price from arthbit_api_orders where pair_id = ".$pair->id." and type = 'buy' order by id ASC limit 0,1 ")->row();
                   $sell_price = $this->common_model->customQuery("SELECT price from arthbit_api_orders where pair_id = ".$pair->id." and type = 'sell' order by id ASC limit 0,1 ")->row();*/
                  
                  /*$buy_price = $this->common_model->customQuery("SELECT price from arthbit_api_orders where pair_id = ".$pair->id." and type = 'buy' order by RAND() limit 0,1 ")->row();
                   $sell_price = $this->common_model->customQuery("SELECT price from arthbit_api_orders where pair_id = ".$pair->id." and type = 'sell' order by RAND() limit 0,1 ")->row();*/

                   $buy_price = $this->common_model->customQuery("SELECT price from arthbit_api_orders where pair_id = ".$pair->id." and type = 'buy' order by id DESC limit 0,1 ")->row();
                   $sell_price = $this->common_model->customQuery("SELECT price from arthbit_api_orders where pair_id = ".$pair->id." and type = 'sell' order by id DESC limit 0,1 ")->row();

                   $buyPrice = $buy_price->price;
                   $sellPrice = $sell_price->price;
                  /* echo $buyPrice."<br>";
                   echo $sellPrice."<br>";*/

                   if($buyPrice !="" && $buyPrice != NULL)
                   {
                    $buyPrice = $buyPrice;
                   }
                   else
                   {
                    $buyPrice = 0;
                   }

                   if($sellPrice !="" && $sellPrice != NULL)
                   {
                    $sellPrice = $sellPrice;
                   }
                   else
                   {
                    $sellPrice = 0;
                   }
                   
                   $avg_price = ($buyPrice + $sellPrice) / 2;
                   

                   $percent_calc = $price_percent / 100;
                  
                   $price_calc = $avg_price * $percent_calc;
                   
                   if($op=='+')
                   {
                    $price = floatval($avg_price) + floatval($price_calc);
                   }
                   else
                   {
                    $price = floatval($avg_price) - floatval($price_calc);
                   }


                  /* echo "avg price ".$avg_price."<br>";
                   echo "price_calc ".$price_calc."<br>";*/
                   

                }

               /* echo "price ".$price;
                exit;*/
                if($amount>0 && $price>0)
                {
                  // insert_order
                $total = $amount * $price;
                $Fee = 0;
                $fee_per = 0;
                $user_id = 47;
                $current_date           =   date('Y-m-d');
                $current_time           =   date('H:i A');
                $datetime   =date("Y-m-d H:i:s");
                $updated_on = strtotime(date('Y-m-d H:i:s'));
                $wallet = 'Exchange AND Trading';

                $sell_data   =   array(
                                    'userId'    =>$user_id,
                                    'Amount'    =>$amount,
                                    'ordertype' =>'limit',
                                    'Fee'       =>$Fee,
                                    'Total'     =>$total,
                                    'Price'     =>$price,
                                    'Type'      =>'sell',
                                    'orderDate' =>$current_date,
                                    'orderTime' =>$current_time,
                                    'datetime'  =>$datetime,
                                    'tradetime' =>$datetime,
                                    'pair'      =>$pair->id,
                                    'pair_symbol' =>$pair_symbol,
                                    'status'    =>'filled',
                                    'fee_per'   =>$fee_per,
                                    'wallet'    =>$wallet,
                                    'updated_on'    =>$updated_on,
                                    "bot_order" => 1
                                    ); 

                $buy_data   =   array(
                                    'userId'    =>$user_id,
                                    'Amount'    =>$amount,
                                    'ordertype' =>'limit',
                                    'Fee'       =>$Fee,
                                    'Total'     =>$total,
                                    'Price'     =>$price,
                                    'Type'      =>'buy',
                                    'orderDate' =>$current_date,
                                    'orderTime' =>$current_time,
                                    'datetime'  =>$datetime,
                                    'tradetime' =>$datetime,
                                    'pair'      =>$pair->id,
                                    'pair_symbol' =>$pair_symbol,
                                    'status'    =>'filled',
                                    'fee_per'   =>$fee_per,
                                    'wallet'    =>$wallet,
                                    'updated_on'    =>$updated_on,
                                    "bot_order" => 1
                                    ); 
                $last_order = $this->common_model->customQuery("SELECT * from arthbit_coin_order where pair = ".$pair->id." and bot_order = 1 order by trade_id DESC limit 0,1 ")->row();
                $last_order_time = strtotime($last_order->datetime);
                $current_time = strtotime(date("Y-m-d H:i:s"));
                $diff_time = $current_time - $last_order_time;

                echo "last order time ".$last_order_time."<br>";
                echo "current time ".$current_time."<br>";
                echo "diff time ".$diff_time."<br>";

                if($diff_time>$time_interval)
                {
                    if($type_val=='buy')
                    {
                      $order_ins = $this->common_model->insertTableData('coin_order', $buy_data);
                    }
                    else
                    {
                      $order_ins = $this->common_model->insertTableData('coin_order', $sell_data);
                    }
                    
                    

                    if($order_ins)
                    {
                        $datetime           =   date("Y-m-d H:i:s");
                        $tempdata           =   array(
                                                'sellorderId'       =>  $order_ins,
                                                'sellerUserid'      =>  $user_id,
                                                'askAmount'         =>  $amount,
                                                'askPrice'          =>  $price,
                                                'filledAmount'      =>  $amount,
                                                'buyorderId'        =>  $order_ins,
                                                'buyerUserid'       =>  $user_id,
                                                'sellerStatus'      =>  "active",
                                                'buyerStatus'       =>  "active",
                                                "pair"              =>  $pair->id,
                                                "datetime"          =>  $datetime
                                                );
                        $inserted=$this->common_model->insertTableData('ordertemp', $tempdata);
                        if($inserted)
                        {
                          $order_type = $this->common_model->getTableData("coin_order",array("trade_id"=>$order_ins))->row('Type');
                            if($order_type=='buy')
                           {
                               $trans_data_buy = array(
                                'userId'=>$user_id,
                                'type'=>'Buy',
                                'currency'=>$pair->to_symbol_id,
                                'amount'=>$total+$Fee,
                                'profit_amount'=>$Fee,
                                'comment'=>'Trade Buy order #'.$buy_ins,
                                'datetime'=>date('Y-m-d h:i:s'),
                                'currency_type'=>'crypto'
                                );
                                $update_trans_buy = $this->common_model->insertTableData('transaction_history',$trans_data_buy);
                           }
                           else
                           {
                               $trans_data_sell = array(
                                'userId'=>$user_id,
                                'type'=>'Sell',
                                'currency'=>$pair->from_symbol_id,
                                'amount'=>$total+$Fee,
                                'profit_amount'=>$Fee,
                                'comment'=>'Trade Sell order #'.$sell_ins,
                                'datetime'=>date('Y-m-d h:i:s'),
                                'currency_type'=>'crypto'
                                );
                                $update_trans_sell = $this->common_model->insertTableData('transaction_history',$trans_data_sell);
                           }
                            

                                

                            echo "success";
                        }
                    }
                }
                else
                {
                   echo "failed"; 
                }

                }
                
                

            }
        }
        else{
          echo "No records";
        }

    }

    function market_pair($link)
  {
    if($this->block() == 1)
    {
    front_redirect('block_ip');
    }

    $data['site_common'] = site_common();
    $this->load->view('front/common/market_pair', $data);
  }

  function mcoin_blocks()
  {
     $this->load->view('front/common/mcoin_blocks');
  }


  function test_commission(){
    exit();
   $commission_details = $this->common_model->sentLevelCommission('6290','5339','2.5','1','5');

      echo "<pre>";
      print_r($commission_details); 
      echo "<pre>";


  }
  function coin_list($coin=null){
      $data['site_common'] = site_common();
      $data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'market_info'))->row(); 
// print_r($coin);exit;
    if($coin!='') {
        $data['coin_info'] = getcurrencydetails($coin);
    } else {
        $data['coin_info'] = getcurrencydetails('BTC');
    }
    $data['market_info'] = $this->common_model->getTableData('market_info',array('currency'=>$data['coin_info']->id))->row();
    $data['currency'] = $this->common_model->getTableData('market_info')->result();
    // print_r($data['currency']);exit;
    $this->load->view('front/common/coin_list',$data);
    }

    
}