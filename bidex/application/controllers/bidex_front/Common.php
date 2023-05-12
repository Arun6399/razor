<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Common extends CI_Controller {
	public function __construct()
	{	

		parent::__construct();		
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation'));
		$this->load->library('session');
		$this->site_api = new Tradelib();
		$this->load->helper(array('url', 'language'));


		$this->load->library('session');
	 
	}

	
	public function index()
	{

		$data['site_common'] = site_common();
		$data['main_banner'] = $this->common_model->getTableData('static_content',array('slug'=>'main_banner'))->row();
		$data['about'] = $this->common_model->getTableData('static_content',array('slug'=>'about'))->row();
		$data['about_payout'] = $this->common_model->getTableData('static_content',array('slug'=>'about_payout'))->row();
		$data['about_deposit'] = $this->common_model->getTableData('static_content',array('slug'=>'about_deposit'))->row();
		$data['about_withdraw'] = $this->common_model->getTableData('static_content',array('slug'=>'about_withdraw'))->row();
		$data['about_compounded'] = $this->common_model->getTableData('static_content',array('slug'=>'about_compounded'))->row();

		$data['resume'] = $this->common_model->getTableData('static_content',array('slug'=>'resume'))->row();
		$data['resume_crypto'] = $this->common_model->getTableData('static_content',array('slug'=>'resume_crypto'))->row();
		$data['resume_goal'] = $this->common_model->getTableData('static_content',array('slug'=>'resume_goal'))->row();
		$data['feature'] = $this->common_model->getTableData('static_content',array('slug'=>'feature'))->row();
		$data['feature_sass'] = $this->common_model->getTableData('static_content',array('slug'=>'feature_sass'))->row();
		$data['feature_1'] = $this->common_model->getTableData('static_content',array('slug'=>'feature_1'))->row();
		$data['feature_2'] = $this->common_model->getTableData('static_content',array('slug'=>'feature_2'))->row();
		$data['service'] = $this->common_model->getTableData('static_content',array('slug'=>'service'))->row();
		$data['service_blue'] = $this->common_model->getTableData('static_content',array('slug'=>'service_blue'))->row();
		$data['service_orange'] = $this->common_model->getTableData('static_content',array('slug'=>'service_orange'))->row();
		$data['service_pink'] = $this->common_model->getTableData('static_content',array('slug'=>'service_pink'))->row();
		$data['service_yellow'] = $this->common_model->getTableData('static_content',array('slug'=>'service_yellow'))->row();
		$data['service_red'] = $this->common_model->getTableData('static_content',array('slug'=>'service_red'))->row();
		$data['service_teal'] = $this->common_model->getTableData('static_content',array('slug'=>'service_teal'))->row();

		$data['testimonial'] = $this->common_model->getTableData('testimonials',array('status'=>1))->result();
		// $data['currencycontent']=$this->common_model->getTableData('currencycontent',array('status'=>1))->result();
		$data['currency'] = $this->common_model->getTableData('currency',array('status'=>'1','type'=>'digital'),'','','','','','', array('sort_order', 'ASC'))->result();

		$data['pairs'] = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
		
		$this->load->view('front/common/home',$data);
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
    function reg_subscribe()
	{		
		$this->form_validation->set_rules('email_detail', 'Email', 'trim|required|xss_clean');
		if ($this->input->post())
		{ 
			$email = $this->input->post('email_detail'); 
			$check1=$this->common_model->getTableData('reg_subscribe',array('email'=>$email,'status'=>1));
		    if ($check1->num_rows()!=0)
			{				
				$this->session->set_flashdata('error', 'You already subscribed');
				front_redirect('home', 'refresh');
			}
			$user_data = array('email'=> $email);
			
			$id=$this->common_model->insertTableData('reg_subscribe', $user_data);
			// EMAIL FUNCTIONALITY
			$email_template = 'subscribers';
			$site_common      =   site_common();
			$activation_code = base64_encode($id);
			$special_vars = array(
			'###LINK###' => front_url().'unsubscribe/'.$activation_code
			);			
			$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
			$this->session->set_flashdata('success','Your Subscription submitted successfully');
			front_redirect('home', 'refresh');
		}		
	}
	function unsubscribe($code)
	{
		$id = base64_decode($code);
		$check1=$this->common_model->getTableData('reg_subscribe',array('id'=>$id));
	    if ($check1->num_rows()>0)
	    {
	    	$updateTableData = array('status'=>'0');
	    	$this->common_model->updateTableData('reg_subscribe', array('id' => $id), $updateTableData);
	    	$this->session->set_flashdata('success',$this->lang->line('Your Subscription cancelled successfully'));
			front_redirect('', 'refresh');
	    }
	    else
	    {
	    	$this->session->set_flashdata('error',$this->lang->line('Something went wrong'));
			front_redirect('', 'refresh');
	    }
	}
    function cms($link)
	{
		if($this->block() == 1)
		{
		front_redirect('block_ip');
		}
		$data['cms'] = $this->common_model->getTableData('cms', array('status' => 1, 'link'=>$link))->row();
		$data['meta_content'] = $this->common_model->getTableData('cms', array('status' => 1, 'link'=>$link))->row();
		$data['home_footer'] = $this->common_model->getTableData('static_content',array('slug'=>'home_footer'))->row();
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
		$data['testimonials'] = $this->common_model->getTableData('testimonials',array('status'=>1))->result();
		$data['home_banner7'] = $this->common_model->getTableData('static_content',array('slug'=>'home_banner7'))->row();	
		
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
     	
     	$data['home_banner7'] = $this->common_model->getTableData('static_content',array('slug'=>'home_banner7'))->row();
     	$data['testimonials'] = $this->common_model->getTableData('bidex_testimonials',array('status'=>1))->result();   
        $this->load->view('front/common/faq', $data);
	}
	function fee()
	{
		if($this->block() == 1)
		{
		front_redirect('block_ip');
		}
		$data['currency'] = $this->common_model->getTableData('currency',array('status'=>'1'),'','','','','','', array('currency_name', 'ASC'))->result();
        $data['site_common'] = site_common();
        $data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'fee'))->row();
        
        $this->load->view('front/common/fee', $data);
	}
    
    function execute_order($amount,$price,$limit_price,$total,$fee,$ordertype,$pair,$type,$loan_rate,$pagetype,$user_id,$exchange_type,$sorting_type)
	{		
		$response = array('status'=>'','msg'=>'');
		if($user_id !="")
		{			
			$response 	= $this->site_api->createOrder($user_id,$amount,$price,$limit_price,$total,$fee,$pair,$ordertype,$type,$loan_rate,$pagetype,$exchange_type,$sorting_type);
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
			$res_id 		= $result_id->trade_id;
			$table_name     = 'coin_order';
		}
		else
		{
			$result = $this->common_model->updateTableData('trade_paircoins',array('type'=>$type,'price'=>$price,'quantity'=>$amount),array('click_status'=>1));
			$result_id = $this->common_model->getTableData('trade_paircoins',array('type'=>$type,'price'=>$price,'quantity'=>$amount))->row();
			$res_id 		= $result_id->id;
			$table_name     = 'api_coin_order';
		}	
		
		$data['res_id'] 	= $res_id;
		$data['table_name'] = $table_name;
		$result 		= json_encode($data);
		return $result;
		
	}
	function home_integration()
	{
		$today = date("Y-m-d");
		$data['currency'] = $this->common_model->getTableData('currency',array('status'=>'1','type'=>'digital','expiry_date>='=>$today),'','','','','','', array('sort_order', 'ASC'))->result();
		$result = json_encode($data);
		return  $result;
	}

	function market_prices() {
		$data['localpair_details'] = $this->markets_localpair_details();
		$result = json_encode($data);
		return  $result;
	}

	function markets_localpair_details()
    {
      error_reporting(0);
      $pair_details = $this->common_model->getTableData('trade_pairs',array('status' => 1))->result();
      if(count($pair_details)>0)
      {
      	$market_table='';
      
        foreach($pair_details as $pair_detail)
        { 
          if($pair_detail->api_status==1){
          $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
        $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();
        $currency = getcryptocurrencydetail($from_currency->id);

        if($from_currency->currency_symbol=="BTC")
          $from_currency_symbol1 = "BTC";
        elseif($from_currency->currency_symbol=='USD')
          $from_currency_symbol1 ='USDC';
        else
          $from_currency_symbol1 = $from_currency->currency_symbol;
        
        if($to_currency->currency_symbol=="LTC")
          $to_currency_symbol1 = "LTC";
        elseif($to_currency->currency_symbol=='USD')
          $to_currency_symbol1 ='USDC';
        else
          $to_currency_symbol1 = $to_currency->currency_symbol;

      if($to_currency->currency_symbol=="LTC")
          $to_currency_symbol1 = "LTC";
        elseif($to_currency->currency_symbol=='USD')
          $to_currency_symbol1 ='USDC';
        else
          $to_currency_symbol1 = $to_currency->currency_symbol;

        
        $pair_symbol = $from_currency_symbol1.$to_currency_symbol1;
        $pair_symbols1 = $from_currency_symbol1.'/'.$to_currency_symbol1;
        $pair_url = $from_currency_symbol1.'_'.$to_currency_symbol1;

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
                $volume =  $ress['volume'];
                $change_highs = $ress['highPrice'];
                $change_lows = $ress['lowPrice'];
                $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
                );
              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
              	
              	
            $clrpriceChangePercent = ($priceChangePercent>0)?"grn":"rdn"; 
            $market_table.='<tr><td><img src="'.$currency->image.'" width="24" alt="'.$pair_symbols1.'" class="table-cryp"> <a href="'.$base_url.$pair_url.'">'.$pair_symbols1.'</a></td><td class="text-center">'.TrimTrailingZeroes($lastPrice).'</td><td class="text-center"> <span class="'.$clrpriceChangePercent.'">'.number_format($priceChangePercent,2).'%</span></td><td class="text-center">'.TrimTrailingZeroes($change_highs).'</td><td class="text-center">'.TrimTrailingZeroes($change_lows).'</td><td class="text-center">'.TrimTrailingZeroes($volume).'</td><td class="text-center"><a href="'.$base_url.$pair_url.'" class="btn">'.$this->lang->line("Trade").'</a> </td></tr>';
            	// return $market_table;
              	// echo $pair_symbol." reverse Updated <br/>";
              }
              else
              {
                $dbrsymbol = $to_currency_symbol1."/".$from_currency_symbol1;
                $dbsymbol = $from_currency_symbol1."/".$to_currency_symbol1;
                $dbrQuery = $this->db->query("SELECT * FROM `bidex_coin_order` WHERE `pair_symbol`='".$dbrsymbol."' AND status='filled' ORDER BY `trade_id` DESC LIMIT 1")->row();
                $dbQuery = $this->db->query("SELECT * FROM `bidex_coin_order` WHERE `pair_symbol`='".$dbsymbol."' AND status='filled' ORDER BY `trade_id` DESC LIMIT 1")->row();
                if(count($dbrQuery)>0)
                { 
                  $priceChangePercent = pricechangepercent($pair_detail->id);
                  $url = "https://api.binance.com/api/v1/ticker/24hr?symbol=".str_replace('/', '', $dbrsymbol);
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              $ress = json_decode($result,true);
                      $priceChangePercent_new = $ress['priceChangePercent'];

                  $priceChangePercent = ($priceChangePercent=='')?$priceChangePercent_new:$priceChangePercent;

                  $lastPrice =  $dbrQuery->Price;
                  $volume = volume($pair_detail->id);
                  $change_highs = change_high($pair_detail->id);
                $change_lows = change_low($pair_detail->id);
                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice);
                $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
                // echo $pair_symbol." DB REV Updated <br/>";
            
            $clrpriceChangePercent = ($priceChangePercent>0)?"grn":"rdn"; 
            $market_table.='<tr><td><img src='.$currency->image.' width="24" alt='.$pair_symbols1.' class="table-cryp"> <a href="'.$base_url.$pair_url.'">'.$pair_symbols1.'</a></td><td class="text-center">'.TrimTrailingZeroes($lastPrice).'</td><td class="text-center"> <span class="'.$clrpriceChangePercent.'">'.number_format($priceChangePercent,2).'%</span></td><td class="text-center">'.TrimTrailingZeroes($change_highs).'</td><td class="text-center">'.TrimTrailingZeroes($change_lows).'</td><td class="text-center">'.TrimTrailingZeroes($volume).'</td><td class="text-center"><a href="'.$base_url.$pair_url.'" class="btn">'.$this->lang->line("Trade").'</a></td></tr>';
            	// return $market_table;
                
                }
                elseif(count($dbQuery)>0)
                {
                  
                  $priceChangePercent = pricechangepercent($pair_detail->id);
                  $url = "https://api.binance.com/api/v1/ticker/24hr?symbol=".str_replace('/', '', $dbsymbol);
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              $ress = json_decode($result,true);
                      $priceChangePercent_new = $ress['priceChangePercent'];

                  $priceChangePercent = ($priceChangePercent=='')?$priceChangePercent_new:$priceChangePercent;
                  $lastPrice =  $dbQuery->Price;
                  $volume =  volume($pair_detail->id);

                  $change_highs = change_high($pair_detail->id);
                $change_lows = change_low($pair_detail->id);

                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice);
                $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
                // echo $pair_symbol." DB Updated <br/>";
			$clrpriceChangePercent = ($priceChangePercent>0)?"grn":"rdn";                 
            $market_table.='<tr><td><img src='.$currency->image.' width="24" alt='.$pair_symbols1.' class="table-cryp"> <a href="'.$base_url.$pair_url.'">'.$pair_symbols1.'</a></td><td class="text-center">'.TrimTrailingZeroes($lastPrice).'</td><td class="text-center"> <span class="'.$clrpriceChangePercent.'">'.number_format($priceChangePercent,2).'%</span></td><td class="text-center">'.TrimTrailingZeroes($change_highs).'</td><td class="text-center">'.TrimTrailingZeroes($change_lows).'</td><td class="text-center">'.TrimTrailingZeroes($volume).'</td><td class="text-center"><a href="'.$base_url.$pair_url.'" class="btn">'.$this->lang->line("Trade").'</a></td></tr>';
            	// return $market_table;    
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
                  $volume =  $ress['volume'];
                  $change_highs = $ress['highPrice'];
                $change_lows = $ress['lowPrice'];
                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
                );
                $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
                  // echo $pair_symbol." DUMMY <br/>";
            $clrpriceChangePercent = ($priceChangePercent>0)?"grn":"rdn";     
            $market_table.='<tr><td><img src='.$currency->image.' width="24" alt='.$pair_symbols1.' class="table-cryp"> <a href="'.$base_url.$pair_url.'">'.$pair_symbols1.'</a></td><td class="text-center">'.TrimTrailingZeroes($lastPrice).'</td><td class="text-center"> <span class="'.$clrpriceChangePercent.'">'.number_format($priceChangePercent,2).'%</span></td><td class="text-center">'.TrimTrailingZeroes($change_highs).'</td><td class="text-center">'.TrimTrailingZeroes($change_lows).'</td><td class="text-center">'.TrimTrailingZeroes($volume).'</td><td class="text-center"><a href="'.$base_url.$pair_url.'" class="btn">'.$this->lang->line("Trade").'</a></td></tr>';
            	// return $market_table;    
                }
                //echo $pair_symbol." Not Updated <br/>";
              }
          }
          else
          {
            $priceChangePercent = $res['priceChangePercent'];
              $lastPrice =  $res['lastPrice'];
              $volume =  $res['volume'];
              $change_highs = $res['highPrice'];
                $change_lows = $res['lowPrice'];
              $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
                );
            $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
            // echo $pair_symbol." Updated <br/>";
            // $market_table.='<tr><td>'.$pair_symbols1.'</td><td>'.TrimTrailingZeroes($lastPrice).'</td><td>'.number_format($priceChangePercent,2).'</td><td>'.TrimTrailingZeroes($change_high).'</td><td>'.TrimTrailingZeroes($change_lows).'</td><td>'.TrimTrailingZeroes($volume).'</td><td>--</td></tr>';
            	// return $market_table;
          }
        }
        else{
          //database
          $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
        $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();

        $pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;

       
        
          $coin_order = $this->db->query("SELECT * FROM `bidex_coin_order` WHERE `pair_symbol`='".$pair_symbol."' AND status='filled' ORDER BY `trade_id` DESC")->result();
          if(isset($coin_order) && !empty($coin_order)){
          $lastPrice = lastmarketprice($pair_detail->id);
          $volume = volume($pair_detail->id);
          $change_highs = change_high($pair_detail->id);
          $change_lows = change_low($pair_detail->id);
           
          /* $Price_change = $lastPrice - $change_lows;
              $Per = $change_lows/100 ;
              $priceChangePercent = $Price_change/$Per;*/

              $priceChangePercent = pricechangepercent($pair_detail->id);
              $priceChangePercent = ($priceChangePercent=='')?'0':$priceChangePercent;

              $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                'change_high'=>$change_highs,
                  'change_low'=>$change_lows
                );

              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
              // return $pair_symbol." Database<br/>";
          }
          else{

          	$updateTableData = array('priceChangePercent'=>0,
                  'lastPrice'=>0,
                  'volume'=>0,
                'change_high'=>0,
                  'change_low'=>0,
                  'buy_rate_value'=>0,
                  'sell_rate_value'=>0
                );

              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
          	// echo $pair_symbol." DB Empty<br/>";
          }
          
        
        }
      }
      	return $market_table;
      }
    }

	function trade_integration($pair_id,$user_id,$type='',$pair)
	{
		$data['pairs'] = trade_pairs($type);
		$this->newtrade_prices($pair_id,$type,$user_id);
		$data['transactionhistory'] = $this->transactionhistory($pair_id,$user_id);
		$data['markettrendings'] = $this->markettrendings($pair_id);
		$data['liquidity']=$this->liquiditydata($pair_id);
		$data['sellResult'] = $this->gettradeopenOrders('Sell',$pair_id);
		$data['buyResult'] = $this->gettradeopenOrders('Buy',$pair_id);
		$data['api_sellResult'] = $this->gettradeapisellOrders($pair);
		$data['api_buyResult'] = $this->gettradeapibuyOrders($pair);
		$data['market_trades'] = $this->market_trades($pair_id);
		$data['market_api_trades'] = $this->market_api_trades($pair);
		$data['current_trade'] = $this->current_trade_pair($pair_id);


		$pair_details = $this->common_model->getTableData('trade_pairs',array('id'=>$pair_id),'from_symbol_id,to_symbol_id')->row();
		$fromID = $pair_details->from_symbol_id;
        $toID = $pair_details->to_symbol_id;
        $getfrom_symbols= $this->common_model->getTableData('currency',array('id'=>$fromID),'','',array())->row();
        $getto_symbols= $this->common_model->getTableData('currency',array('id'=>$toID),'','',array())->row();
        if($getfrom_symbols->currency_symbol =='USD')
         $format = 2;
        else if($getfrom_symbols->currency_symbol =='USDT')  
         $format = 6; 
        else
          $format = 8; 
        if($getto_symbols->currency_symbol =='USD')
         $format1 = 2;
        else if($getto_symbols->currency_symbol =='USDT')  
         $format1 = 6; 
        else
         $format1 = 8; 
		if($type!='home')
		{
			if($user_id&&$user_id!=0)
			{
				$data['open_orders']=$this->get_active_order($user_id);
				$data['open_orders_limit']=$this->get_active_limitorder($user_id);
				$data['open_orders_market']=$this->get_active_marketorder($user_id);
				$data['open_orders_stop']=$this->get_active_stoporder($user_id);
				$data['cancel_orders']=$this->get_cancel_order($pair_id,$user_id);
				$data['stop_orders']=$this->get_stop_order($pair_id,$user_id);
				$data['active_orders'] = $this->get_active_userorder($pair_id,$user_id);
			}
			else
			{
				$data['open_orders']=0;
				$data['cancel_orders']=0;
				$data['stop_orders']=0;
				$data['active_orders'] = 0;
			}
		}
		
		if($this->user_balance!=0)
		{
			$balance=$this->user_balance;
			$data['from_currency'] = to_decimal($balance[$pair_details->from_symbol_id], $format);
            $data['to_currency'] = to_decimal($balance[$pair_details->to_symbol_id], $format1);
			$data['from_symbol'] = $getfrom_symbols->currency_symbol;
            $data['to_symbol'] = $getto_symbols->currency_symbol;			
		}
		else
		{
			$data['from_currency']=0;
			$data['to_currency']=0;	
			$data['from_symbol'] = 0;
            $data['to_symbol'] = 0;
		}
		$data['bidex_userid']=$this->user_id;
		$data['current_buy_price']=to_decimal($this->marketprice,8);
		$data['current_sell_price']=to_decimal($this->marketprice,8);
		$data['lastmarketprice']=to_decimal($this->lastmarketprice,8);

		$pair_details1 = $this->common_model->getTableData('trade_pairs',array('id'=>$pair_id))->row();

		$data['change']=to_decimal($pair_details1->priceChangePercent,2);
		$data['high']=to_decimal($pair_details1->change_high,8);
		$data['low']=to_decimal($pair_details1->change_low,8);
		$data['volume']=to_decimal($pair_details1->volume,2);

		$data['web_trade'] = '1';
		$result = json_encode($data);
		return  $result;
	}
   /*function gettradeapisellOrders($pair)
	{
	  $pair_value=explode('_',$pair);
      if(count($pair_value) > 0) 
      {
       $first_pair  = strtoupper($pair_value[0]);
       $second_pair = strtoupper($pair_value[1]);
       $coin_pair = $first_pair.$second_pair;
    
      $json=file_get_contents('https://api.binance.com/api/v1/depth?symbol='.$coin_pair.'&limit=20');
      $newresult = json_decode($json,true);
        $buy_orders = $newresult['bids'];
        $sell_orders = $newresult['asks'];
        $sell_res = array();
        $i=1;
        foreach($sell_orders as $sell)
        {
        	$sellData['id'] = $i;
	        $sellData['price'] = $sell[0];
	        $sellData['quantity'] = $sell[1];
            $sell_res[] = $sellData;
            $i++;
        }
       return $sell_res;
       }
    }*/
    	function gettradeapisellOrders($pair)
{
	$orderBy=array('price','desc');
  $sellresult = $this->common_model->getTableData("api_orders",array("pair_symbol"=>$pair,'type'=>'sell'),'price,quantity','','','','',20,$orderBy)->result();
 
        if(isset($sellresult)>0 && !empty($sellresult))
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

/*	function gettradeapibuyOrders($pair)
	{
		
	      $pair_value=explode('_',$pair);
	      if(count($pair_value) > 0) 
	      {
	        $first_pair  = strtoupper($pair_value[0]);
	        $second_pair = strtoupper($pair_value[1]);
	        $coin_pair = $first_pair.$second_pair;
	    
	        $json= file_get_contents('https://api.binance.com/api/v1/depth?symbol='.$coin_pair.'&limit=20');
	        $newresult = json_decode($json,true);
	        $buy_orders = $newresult['bids'];
	        $sell_orders = $newresult['asks'];
	        $buy_res = array();
	        $i=1;
	        foreach($buy_orders as $buy)
	        {
	        	$buyData['id'] = $i;
		        $buyData['price'] = $buy[0];
		        $buyData['quantity'] = $buy[1];
	            $buy_res[] = $buyData;
	            $i++;
	        }
	        return $buy_res;
	       }
	}*/
	function gettradeapibuyOrders($pair)
{
  $buyresult = $this->common_model->getTableData("api_orders",array("pair_symbol"=>$pair,'type'=>'buy'),'price,quantity','','','','',20)->result();
        
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

	function newtrade_prices($pair,$pagetype='',$user_id)
	{
		$this->marketprice = marketprice($pair);
		$this->lowestaskprice = lowestaskprice($pair);
		$this->highestbidprice = lowestaskprice($pair);
		$this->lastmarketprice = lastmarketprice($pair);
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
	public function transactionhistory($pair_id,$user_id)
  {
    $user_id = $user_id;
    $joins = array('coin_order as b'=>'a.sellorderId = b.trade_id','coin_order as c'=>'a.buyorderId = c.trade_id');
    // $where = array('c.pair'=>$pair_id,'c.userId'=>$user_id);
    $where = array('c.pair'=>$pair_id);
    //$where_or = array('c.userId'=>$user_id);
    $where_or = '';
    $transactionhistory = $this->common_model->getJoinedTableData('ordertemp as a',$joins,$where,'a.*,
       date_format(b.datetime,"%H:%i:%s") as sellertime,b.trade_id as seller_trade_id,date_format(c.datetime,"%H:%i:%s") as buyertime,c.trade_id as buyer_trade_id,a.askPrice as sellaskPrice,c.Price as buyaskPrice,b.Fee as sellerfee,c.Fee as buyerfee,b.Total as sellertotal,c.Total as buyertotal,c.pair_symbol as pair_symbol, c.status as status','',$where_or,'','','20',array('a.tempId','desc'))->result();
    
        $newquery = $this->common_model->customQuery('select trade_id, Type, Price, Amount, Fee, Total, status, date_format(datetime,"%d-%b-%Y %h:%i %p") as tradetime, pair_symbol from bidex_coin_order where userId = '.$user_id.' and pair = '.$pair_id.' and status = "cancelled"')->result();

    if((isset($transactionhistory) && !empty($transactionhistory)) || (isset($newquery) && !empty($newquery)))
    {
        $transactionhistory_1 = array_merge($transactionhistory,$newquery);
        // $transactionhistory_1 = $transactionhistory;
        $historys = $transactionhistory_1;
    }
    else
    {
        $historys='0';
    }
    // return $this->db->last_query();
    return $historys;
  }
	// public function transactionhistory($pair_id,$user_id)
	// {
	// 	$user_id = $user_id;
	// 	$joins = array('coin_order as b'=>'a.sellorderId = b.trade_id','coin_order as c'=>'a.buyorderId = c.trade_id');
	// 	$where = array('a.pair'=>$pair_id,'b.userId'=>$user_id);
	// 	$where_or = array('c.userId'=>$user_id);
	// 	$transactionhistory = $this->common_model->getJoinedTableData('ordertemp as a',$joins,$where,'a.*,
	// 		 date_format(b.datetime,"%d-%m-%Y %H:%i %p") as sellertime,b.trade_id as seller_trade_id,date_format(c.datetime,"%d-%m-%Y %H:%i %p") as buyertime,c.trade_id as buyer_trade_id,a.askPrice as sellaskPrice,c.Price as buyaskPrice,b.Fee as sellerfee,c.Fee as buyerfee,b.Total as sellertotal,c.Total as buyertotal','',$where_or,'','','',array('a.tempId','desc'))->result();
		
 //        $newquery = $this->common_model->customQuery('select trade_id, Type, Price, Amount, Fee, Total, status, date_format(datetime,"%d-%m-%Y %H:%i %p") as tradetime from bidex_coin_order where userId = '.$user_id.' and pair = '.$pair_id.' and status = "cancelled"')->result();
	// 	if(count($transactionhistory)>0 || count($newquery))
	// 	{
	// 	    $transactionhistory_1 = array_merge($transactionhistory,$newquery);
	// 	    $historys = $transactionhistory_1;
	// 	}
	// 	else
	// 	{
	// 	    $historys=0;
	// 	}
	// 	return $historys;
	// }

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
			$joins 			= 	array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
			$where 			= 	array('a.id'=>$pair_id);
			$pair_details 	= 	$this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'b.currency_symbol as from_currency_symbol,c.currency_symbol as to_currency_symbol,a.to_symbol_id')->row();
			$pair_symbol	=	$pair_details->from_currency_symbol.'_'.$pair_details->to_currency_symbol;
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
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
		$names = array('active', 'partially', 'margin');
		$where=array('CO.Type'=>$type,'CO.pair'=>$pair_id);
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
			$orders=$result;
		}
		else
		{
			$orders=0;
		}
		return $orders;
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
	function get_active_order($user_id)
	{
		$user_id = $user_id;
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
		$names = array('active', 'partially', 'margin','stoporder');
		$where=array('CO.userId'=>$user_id);
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
		// echo "<pre>";print_r($open_orders_text);
		return $open_orders_text;
	}
	function get_active_limitorder($user_id)
	{
		$user_id = $user_id;
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
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
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
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
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
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
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
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
		$query = $this->common_model->customQuery('select trade_id, Type, Price, Amount, Fee, Total, status, date_format(datetime,"%d-%m-%Y %H:%i %a") as tradetime from bidex_coin_order where userId = '.$user_id.' and status = "stoporder" and pair = '.$pair_id.'');
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
        $url = "https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=USD&api_key=a3dc6836d17d7040a503055aa67d2fccfbc8272aa327c9c4066e210a0f5af9ed";
		$curres = $coin_symbol;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		//$result = convercurr($coin_symbol,'USD');
		$res = json_decode($result);
		return $res->USD;
    }
    public function update_usd_price()
    {
        $currency_results=$this->common_model->update_usd_price();
        foreach($currency_results as $cvalue){
            $currency_symbol=$cvalue->currency_symbol;
            $equal_usd = $this->coinprice($currency_symbol);
            $currency_arr[$currency_symbol]=$equal_usd;
            if($currency_symbol=="COCO")
            {
            	$equal_usd = 1;
            }
            $updateData = array(
                'online_usdprice' => $equal_usd,
            );
            $this->common_model->updateTableData('currency', array('id' => $cvalue->id), $updateData);
        }
        print_r($currency_arr);
    }

    public function eurocoinprice($coin_symbol)
    {
        $url = "https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=EUR&api_key=a2ae4b9817a848ef5d2311a115856baa97c65d15a8b3e41cb4abf2295ed4d1aa";
		$curres = $coin_symbol;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		//$result = convercurr($coin_symbol,'USD');
		$res = json_decode($result);
		return $res->EUR;
    }
    public function update_euro_price()
    {
        $currency_results=$this->common_model->update_usd_price();
        foreach($currency_results as $cvalue){
            $currency_symbol=$cvalue->currency_symbol;
            $equal_usd = $this->eurocoinprice($currency_symbol);
            $currency_arr[$currency_symbol]=$equal_usd;
            if($currency_symbol=="LIR")
            {
            	$equal_usd = 1;
            }
            $updateData = array(
                'online_europrice' => $equal_usd,
            );
            $this->common_model->updateTableData('currency', array('id' => $cvalue->id), $updateData);
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
                    $fin_symbol = str_replace('bidex%3A', '', $fin_symbol);
                    $symbol_details = $this->common_model->getTableData('coins_symbols', array('name' => $fin_symbol))->result_array();

                    $chart = '{"name":"' . $symbol_details[0]['name'] . '","exchange-traded":"bidex","exchange-listed":"bidex","timezone":"' . $symbol_details[0]['timezone'] . '","minmov2":0,"pointvalue":1,"has_intraday":true,"has_no_volume":false,"description":"' . $symbol_details[0]['description'] . '","type":"' . $symbol_details[0]['type'] . '","supported_resolutions":["1","3", "5", "60", "D", "2D","W","3W","M","6M"],"pricescale":1000000,"ticker":"' . $symbol_details[0]['name'] . '","session":"0000-2400|0000-2400:17","intraday_multipliers": ["1","60"]}';
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
	   
	    	$str = file_get_contents(FCPATH."chart/".$json_pair);
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
            // echo "<br>".$order_value['id'].$coin_pair;
            $this->tradechart_check($order_value['id'], $coin_pair);
        }
    }
    public function tradechart_check($pair, $pair_val)
    {
        $timestamp = strtotime('today midnight');
        $end_date = date("Y-m-d H:i:s", $timestamp);
        $start_date = date('Y-m-d H:i:s', strtotime($end_date . '- 15 days'));
        
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

        $taken = date('Y-m-d H:i:s', strtotime($taken . ' - 15 days'));
                
            $startTime = strtotime($taken) * 1000;

            $destination = date('Y-m-d H:i:s');

            $endTime = strtotime($destination) * 1000;

        $names = array('filled');
        $where_in = array('status', $names);
		$coinorder_data = $this->common_model->getTableData('coin_order', array('pair' => $pair), '', '', '', '', '', '', '', '', '', $where_in)->result();
		// echo "<pre>";print_r($coinorder_data);die;
        if (count($coinorder_data) > 5) 
        // if (count($coinorder_data)!='') 
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
            $opens1 = "";
            $closes1 = "";
            $highs1 = "";
            $lows1 = "";
            $volumes1 = "";
            $newchart = "";
            for ($i = $start; $i <= $end; $i += $int) {
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
                    if ($Open != '') {$open = $Open;}
                    $chart2 .= $open.',';
                }
                if (isset($api_ClosechartResult)) {
                    $Close = $api_ClosechartResult->close;
                    $close1 = $api_ClosechartResult->close;
                    if ($Close != '') {$close = $Close;}
                    $chart1 .= $close.',';
                }
                if ($date_time != '' && $open1 != '' && $high1 != '' && $close1 != '' && $low1 != '') {
                    $chartdata .= '[' . $date_time . '000' . ',' . $open1 . ',' . $high1 . ',' . $low1 . ',' . $close1 . '],';
                }
                $chart_new = $chartdata;
            }
            $pair_val_file = strtolower($pair_val);
            $json_pair = $pair_val_file . '.json';
            echo $json_pair."<br/>";
            $newchart = '{"t":[' . trim($chart, ',') . '],"o":[' . trim($chart2, ',') . '],"h":[' . trim($chart3, ',') . '],"l":[' . trim($chart4, ',') . '],"c":[' . trim($chart1, ',') . '],"v":[' . trim($chart5, ',') . '],"s":"ok"}';
            $fp = fopen(FCPATH . 'chart/' . $json_pair, 'w');
            fwrite($fp, $newchart);
            fclose($fp);
			echo $json_pair . " -- Coin Order success <br>";
			
        } 
        else 
        { 
        	//CALL API BINANCE
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
           // echo $url;die;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $res = json_decode($result, true);
            if ($res['code'] == '-1003') { echo "if";die;
                $pair_val_file = strtolower($pair_val);
                $json_pair = $pair_val_file . '.json';
                $json_pair . '-- IP banned from BInance <br>';
            } 
            else if ($res['code'] == '-1121') 
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
	                    $volume .= $row['5'] . ',';
	                    $volume1 = $volume;
	                }
	                $pair_value = explode('_', $pair_val);
	                $first_pair = $first_pair;
	                $second_pair = $sec_pair;
	                $pairss_name = $first_pair . '_' . $second_pair;
	                $pair_val_file = strtolower($pairss_name);
	                $json_pair = $pair_val_file . '.json';
	                echo $json_pair."<br/>";
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
	                 $url = "https://api.binance.com/api/v1/klines?symbol=" . $pairss . "&interval=1m";
	                //echo $url;
	                $ch = curl_init();
	                curl_setopt($ch, CURLOPT_URL, $url);
	                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	                $result = curl_exec($ch);
	                $res = json_decode($result, true);
	                    foreach ($res as $row) {
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
	                        $volume .= $row['5'] . ',';
	                        $volume1 = $volume;
	                    }
	                    $first_pair = $first_pair;
	                    $second_pair = $sec_pair;
	                    $pairss_name = $first_pair . '_' . $second_pair;
	                    $pair_val_file = strtolower($pairss_name);
	                    $json_pair = $pair_val_file . '.json';
	                    echo $json_pair."<br/>";
	                    $newchart = '{"t":[' . trim($datetime1, ',') . '],"o":[' . trim($open1, ',') . '],"h":[' . trim($high1, ',') . '],"l":[' . trim($low1, ',') . '],"c":[' . trim($close1, ',') . '],"v":[' . trim($volume1, ',') . '],"s":"ok"}';
	                    $fp = fopen(FCPATH . 'chart/test.json', 'w');
	                    //$fp = fopen(FCPATH . 'chart/'.$json_pair, 'w');
	                    fwrite($fp, $newchart);
	                    fclose($fp);
	                    echo $pairss_name . " -- Dummyt success  <br>";
	                    $this->common_model->customQuery("UPDATE bidex_trade_pairs SET chart_load_status=1 WHERE id='".$pair."'");
	                    
	            }
            } 
            else if ($res['code'] != '-1121') 
            { 
                foreach ($res as $row) {
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
                    $volume .= $row['5'] . ',';
                    $volume1 = $volume;
                }
                $first_pair = $first_pair;
                $second_pair = $sec_pair;
                if ($second_pair == "USDC") {
                    $second_pair = "USD";
                }
                $pairss_name = $first_pair . '_' . $second_pair;
                $pair_val_file = strtolower($pairss_name);
                $json_pair = $pair_val_file . '.json';
                echo $json_pair."<br/>";
                $newchart = '{"t":[' . trim($datetime1, ',') . '],"o":[' . trim($open1, ',') . '],"h":[' . trim($high1, ',') . '],"l":[' . trim($low1, ',') . '],"c":[' . trim($close1, ',') . '],"v":[' . trim($volume1, ',') . '],"s":"ok"}';
                // echo FCPATH . 'chart/' . $json_pair	;die;
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
                 $url = "https://api.binance.com/api/v1/klines?symbol=" . $pairss . "&interval=1m";
                //echo $url;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                $res = json_decode($result, true);
                if ($res['code'] == '-1003') {
                    $pair_val_file = strtolower($pair_val);
                    $json_pair = $pair_val_file . '.json';
                    $pair_val_file . '-- IP banned from BInance <br>';
                } 
                else if ($res['code'] != '-1121') 
                {
                    foreach ($res as $row) {
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
                        $volume .= $row['5'] . ',';
                        $volume1 = $volume;
                    }
                    $first_pair = $first_pair;
                    $second_pair = $sec_pair;
                    $pairss_name = $first_pair . '_' . $second_pair;
                    $pair_val_file = strtolower($pairss_name);
                    $json_pair = $pair_val_file . '.json';
                    echo $json_pair."<br/>";
                    $newchart = '{"t":[' . trim($datetime1, ',') . '],"o":[' . trim($open1, ',') . '],"h":[' . trim($high1, ',') . '],"l":[' . trim($low1, ',') . '],"c":[' . trim($close1, ',') . '],"v":[' . trim($volume1, ',') . '],"s":"ok"}';
                    $fp = fopen(FCPATH . 'chart/test.json', 'w');
                    //$fp = fopen(FCPATH . 'chart/'.$json_pair, 'w');
                    fwrite($fp, $newchart);
                    fclose($fp);
                    echo $pairss_name . " -- Dummy success  <br>";
                    $this->common_model->customQuery("UPDATE bidex_trade_pairs SET chart_load_status=1 WHERE id='".$pair."'");
                }
            }
        }
        //}
	}
    public function get_depthchart()
    {
        $order = $this->common_model->getTableData('trade_pairs', array('status' => 1))->result_array();
        foreach ($order as $order_value) {
            $first_symbol_id = $this->common_model->getTableData('trade_pairs', array('id' => $order_value['id']), 'from_symbol_id')->row('from_symbol_id');
            $second_symbol_id = $this->common_model->getTableData('trade_pairs', array('id' => $order_value['id']), 'to_symbol_id')->row('to_symbol_id');
            $first_coin = $this->common_model->getTableData('currency', array('id' => $first_symbol_id), 'currency_symbol')->row('currency_symbol');
            $second_coin = $this->common_model->getTableData('currency', array('id' => $second_symbol_id), 'currency_symbol')->row('currency_symbol');
            $coin_pair = $first_coin . "_" . $second_coin;
            $this->depthchart_check($order_value['id'], $coin_pair);
        }
    }
    public function depthchart_check($pair, $pair_val)
    {
        $timestamp = strtotime('today midnight');
        $end_date = date("Y-m-d H:i:s", $timestamp);
        $start_date = date('Y-m-d H:i:s', strtotime($end_date . '- 0 days'));
        $start = strtotime($start_date);
        $end = time();
        $enddate = date('Y-m-d H:i:s', $end);
        $interval = 1 / 2;
        $int = 1 * 60 * 60 * $interval;
        $chart = "";
        $chart1 = "";
        $pair_value = explode('_', $pair_val);
        $first_pair = $pair_value[0];
        $second_pair = $pair_value[1];
        $names = array('active');
        $where_in = array('status', $names);
        $coinorder_data = $this->common_model->getTableData('coin_order', array('orderDate' => date('Y-m-d')), '', '', '', '', '', '', '', '', '', $where_in)->result();
        if (count($coinorder_data) > 20) {
            $sec_pair = $second_pair;
            if ($sec_pair == "USD") {
                $sec_pair = "USDC";
            }
            $pairss = $first_pair . $sec_pair;
            $price = "";
            $volume = "";
            $newchart = "";
            for ($i = $start; $i <= $end; $i += $int) {
                $taken = date('Y-m-d H:i:s', $i);
                $exp = explode(' ', $taken);
                $curdate = $exp[0];
                $time = $exp[1];
                $datetime = strtotime($taken);
                $date_time = strtotime($taken);
                $destination = date('Y-m-d H:i:s', strtotime($taken . ' +30 minutes'));
                $apibuy_chartResult = $this->common_model->getTableData('coin_order', array('datetime >= ' => $taken, 'datetime <= ' => $destination, 'pair' => $pair, 'Type' => 'buy'), 'SUM(Amount) as volume,Price as price', '', '', '', '', '', '', '', '', $where_in)->row();
                $apisell_chartResult = $this->common_model->getTableData('coin_order', array('datetime >= ' => $taken, 'datetime <= ' => $destination, 'pair' => $pair, 'Type' => 'sell'), 'SUM(Amount) as volume,Price as price', '', '', '', '', '', '', '', '', $where_in)->row();
                if (isset($apibuy_chartResult)) {
                    $volume_buy = $apibuy_chartResult->volume;
                    $price_buy = $apibuy_chartResult->price;
                    if ($volume_buy != "" && $price_buy != "") {
                        $askdet_buy .= '[' . $price_buy . ',' . $volume_buy . ']' . ',';
                    }
                    $ask_buy = $askdet_buy;
                }
                if (isset($apisell_chartResult)) {
                    $volume_sell = $apisell_chartResult->volume;
                    $price_sell = $apisell_chartResult->price;
                    if ($volume_sell != "" && $price_sell != "") {
                        $biddet_sell .= '[' . $price_sell . ',' . $volume_sell . ']' . ',';
                    }
                    $bid_sell = $biddet_sell;
                }
                /*$chart = '"[' . rtrim($bid_sell, ",") . ']",';
                $chart1 = '"[' . rtrim($ask_buy, ",") . ']"';*/
                 $chart = '"bids":['.rtrim($bid_sell,",").'],';
                 $chart1 = '"asks":['.rtrim($ask_buy,",").'],';
                

            }
            $pair_val_file = strtolower($pair_val);
            $json_pair = $pair_val_file . '.json';
            $curr_date = date("Y-m-d H:i:s");
            $timestamp = strtotime($curr_date);
            //$newchart = $chart . $chart1;
            $newchart = '{'.$chart.$chart1.'"isFrozen":"0","seq":'.$timestamp.'}';
            $fp = fopen(FCPATH . 'depthchart/' . $json_pair, 'w');
            fwrite($fp, $newchart);
            fclose($fp);
            echo $json_pair . " -- COIN ORDER SUCCESS<br>";
        }else{
            // API BINANCE
            $sec_pair = $second_pair;
            if ($second_pair == "USD") {
                $second_pair = "USDC";
            }
            $pairss = $first_pair . $second_pair;
            $price = "";
            $volume = "";
            $newchart = "";
            $url = 'https://api.binance.com/api/v1/depth?symbol=' . $pairss . '&limit=20';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $newresult = json_decode($result, true);
            if ($newresult['bids'] != '') // NORMAL PAIRS
            {
                $bids = $newresult['bids'];
                $asks = $newresult['asks'];
                foreach ($bids as $k) {
                    $price = $k[0];
                    $volume = $k[1];
                    $biddet .= '[' . $price . ',' . $volume . ']' . ',';
                    $bid = '"[' . rtrim($biddet, ",") . ']",';
                }
                foreach ($asks as $k1) {
                    $price1 = $k1[0];
                    $volume1 = $k1[1];
                    $askdet1 .= '[' . $price1 . ',' . $volume1 . ']' . ',';
                    $ask = '"[' . rtrim($askdet1, ",") . ']"';
                }
                $pair_val_file = strtolower($pair_val);
                $json_pair = $pair_val_file . '.json';
                $curr_date = date("Y-m-d H:i:s");
                $timestamp = strtotime($curr_date);
                //$newchart = $bid . $ask;
                $bid = '"bids":['.rtrim($biddet,",").'],';
                 $ask = '"asks":['.rtrim($askdet1,",").'],';
                $newchart = '{'.$bid.$ask.'"isFrozen":"0","seq":'.$timestamp.'}';
                $fp = fopen(FCPATH . 'depthchart/' . $json_pair, 'w');
                fwrite($fp, $newchart);
                fclose($fp);
                echo $json_pair . " -- NORMAL PAIR SUCCESS<br>";
            }
            else if ($newresult['code'] == '-1003') {
                $pair_val_file = strtolower($pair_val);
                $json_pair = $pair_val_file . '.json';
                echo $json_pair . '-- IP banned from Binance <br>'; 
                $curr_date = date("Y-m-d H:i:s");
                $timestamp = strtotime($curr_date);
                for ($mm = 1; $mm <= 15; $mm++) {
                    $dummy_array = '"[[3,1.934500000],[3.4,1.334500000],[1.6,1.834500000],[4.1,1.234500000],[2.3,1.534500000]]","[[1,1.00000000],[1.5,1.03200000],[2,1.230000000],[2.5,1.76000000],[2.7,1.03255000]]"';
                    $newchart = $dummy_array;
                }
                $pair_val_file = strtolower($pair_val);
                $json_pair = $pair_val_file . '.json';
                $fp = fopen(FCPATH . '/depthchart/' . $json_pair, 'w');
                fwrite($fp, $newchart);
                fclose($fp);
                echo $json_pair . " -- success<br>";
            } else if ($newresult['code'] == '-1121') // ONLY FOR REVERSE PAIRS
            {
                $coin_pair = $second_pair . $first_pair;
                $url = 'https://api.binance.com/api/v1/depth?symbol=' . $coin_pair . '&limit=20';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                $newresult = json_decode($result, true);
                if($newresult['bids'] !="")
                {
                $asks = $newresult['bids'];
                $bids = $newresult['asks'];
                foreach ($bids as $k) {
                    $price = $k[0];
                    $volume = $k[1];
                    $biddet .= '[' . $price . ',' . $volume . ']' . ',';
                    $bid = '"[' . rtrim($biddet, ",") . ']",';
                }
                foreach ($asks as $k1) {
                    $price1 = $k1[0];
                    $volume1 = $k1[1];
                    $askdet1 .= '[' . $price1 . ',' . $volume1 . ']' . ',';
                    $ask = '"[' . rtrim($askdet1, ",") . ']"';
                }
               }
               else 
               {
               	$url = 'https://api.binance.com/api/v1/depth?symbol=ETHBTC&limit=20';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                $newresult = json_decode($result, true);
                $asks = $newresult['bids'];
                $bids = $newresult['asks'];
                foreach ($bids as $k) {
                    $price = $k[0];
                    $volume = $k[1];
                    $biddet .= '[' . $price . ',' . $volume . ']' . ',';
                     $bid = '"[' . rtrim($biddet, ",") . ']",';
                }
                 
                foreach ($asks as $k1) {
                    $price1 = $k1[0];
                    $volume1 = $k1[1];
                    $askdet1 .= '[' . $price1 . ',' . $volume1 . ']' . ',';
                    $ask = '"[' . rtrim($askdet1, ",") . ']"';
                }
                  
               }
                $pair_val_file = strtolower($pair_val);
                $json_pair = $pair_val_file . '.json';
                $curr_date = date("Y-m-d H:i:s");
                $timestamp = strtotime($curr_date);
                 $bid = '"bids":['.rtrim($biddet,",").'],';
                 $ask = '"asks":['.rtrim($askdet1,",").'],';
                $newchart = '{'.$bid.$ask.'"isFrozen":"0","seq":'.$timestamp.'}';
               // $newchart = $bid . $ask;
                $fp = fopen(FCPATH . 'depthchart/' . $json_pair, 'w');
                fwrite($fp, $newchart);
                fclose($fp);
                echo $json_pair . " -- REVERSE PAIR SUCCESS<br>";
            } else {
                /*$url = 'https://api.binance.com/api/v1/depth?symbol=BNBBTC&limit=20';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                $newresult = json_decode($result,true);
                $asks = $newresult['asks'];
                $bids = $newresult['bids'];
                foreach ($bids as $k)
                {
                $price =  $k['price'];
                $volume = $k['size'];
                $biddet .= '["'.$price.'",'.$volume.']'.',';
                $bid = '"bids":['.rtrim($biddet,",").'],';
                }
                foreach ($asks as $k1)
                {
                $price1 =  $k1['price'];
                $volume1 = $k1['size'];
                $askdet1 .= '["'.$price1.'",'.$volume1.']'.',';
                $ask = '"asks":['.rtrim($askdet1,",").'],';
                }
                $pair_val_file = strtolower($pair_val);
                $json_pair = $pair_val_file.'.json';
                $curr_date = date("Y-m-d H:i:s");
                $timestamp = strtotime($curr_date);
                $newchart = '{'.$bid.$ask.'"isFrozen":"0","seq":'.$timestamp.'}';
                $fp = fopen(FCPATH.'depthchart/'.$json_pair, 'w');
                fwrite($fp, $newchart);
                fclose($fp);
                echo $json_pair." -- DUMMY PAIR SUCCESS<br>"; */
                $curr_date = date("Y-m-d H:i:s");
                $timestamp = strtotime($curr_date);
                for ($mm = 1; $mm <= 15; $mm++) {
                    $dummy_array = '"[[3,1.934500000],[3.4,1.334500000],[1.6,1.834500000],[4.1,1.234500000],[2.3,1.534500000]]","[[1,1.00000000],[1.5,1.03200000],[2,1.230000000],[2.5,1.76000000],[2.7,1.03255000]]"';
                    $newchart = $dummy_array;
                }
                $pair_val_file = strtolower($pair_val);
                $json_pair = $pair_val_file . '.json';
                $fp = fopen(FCPATH . '/depthchart/' . $json_pair, 'w');
                fwrite($fp, $newchart);
                fclose($fp);
                echo $json_pair . " -- dummy pair success<br>";
            }
        }
    }
    function update_adminaddress()
    {
        $Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'))->result();
        $whers_con = "id='1'";
        // $get_admin  =   $this->common_model->getrow("bidex_admin", $whers_con);
        // echo "<pre>"; print_r($Fetch_coin_list); exit();
        $admin_id = "1";
        $enc_email = getAdminDetails($admin_id, 'email_id');
		$email = decryptIt($enc_email); 		
        $get_admin = $this->common_model->getTableData('admin_wallet',array('id'=>'1'))->row();

        if(!empty($get_admin)) 
        {
            $get_admin_det = json_decode($get_admin->addresses, true);
            /*echo "<pre>";
            print_r($get_admin_det);
            exit();*/
			foreach($Fetch_coin_list as $coin_address)
			{			
				//$currency_exit =  array_key_exists($coin_address->currency_symbol, $get_admin_det)?true:false;
				
				if(array_key_exists($coin_address->currency_symbol, $get_admin_det))
				{
					//$currency_address_checker = (!empty($get_admin_det[$coin_address->currency_symbol]))?true:false;
					// echo "<pre>";
		   //          print_r($get_admin_det[$coin_address->currency_symbol]);
		            // exit();
		    		if(empty($get_admin_det[$coin_address->currency_symbol]))
		    		{

						$parameter = '';
						switch ($coin_address->coin_type) {
							case 'coin':
								
								switch ($coin_address->currency_symbol) {

									case 'ETH':
										$parameter='create_eth_account';
								
										$Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_eth_account', $email);
										if(!empty($Get_First_address) || $Get_First_address!=0)
										{
											$get_admin_det[$coin_address->currency_symbol] = $Get_First_address;
											$update['addresses'] = json_encode($get_admin_det);
				        					$this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update);
										}
										else {
											$Get_First_address = $this->common_model->update_address_again($admin_id, $coin_address->id,$parameter);
											if($Get_First_address) 
											{
												$get_admin_det[$coin_address->currency_symbol] = $Get_First_address;
												$update['addresses'] = json_encode($get_admin_det);
				        						$this->common_model->updateTableData("admin_wallet",array('user_id'=>$admin_id),$update);
											}
										}

										break;
									
									default:
										$parameter='getaccountaddress';
									// echo $coin_address->currency_symbol."--".$email;die;	
										$Get_First_address = $this->local_model->access_wallet($coin_address->id,'getaccountaddress', $email);

										if(!empty($Get_First_address) || $Get_First_address!=0)
										{
											$get_admin_det[$coin_address->currency_symbol] = $Get_First_address;
											$update['addresses'] = json_encode($get_admin_det);
											
				        					$this->common_model->updateTableData("admin_wallet",array('user_id'=>$admin_id),$update);
										}
										else{
											if($Get_First_address)
											{

												$Get_First_address = $this->common_model->update_address_again($admin_id, $coin_address->id,$parameter);
												$get_admin_det[$coin_address->currency_symbol] = $Get_First_address;
												$update['addresses'] = json_encode($get_admin_det);
				        						$this->common_model->updateTableData("admin_wallet",array('user_id'=>$admin_id),$update);
											}
										}
										break;
								}
								//break;
							case 'token':
								$get_admin_det[$coin_address->currency_symbol] = $get_admin_det['ETH'];
								$update['addresses'] = json_encode($get_admin_det);
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
    // public function admin_wallet_balance()
    // {

    // 	// echo "test";
    // 	// exit();
    // 	$Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'))->result();


    //     $whers_con = "id='1'";

    //     $admin_id = "1";

    //     $enc_email = getAdminDetails($admin_id, 'email_id');

    //     $email = decryptIt($enc_email);         

    //     $get_admin = $this->common_model->getrow("admin_wallet", $whers_con);


    //     if(!empty($get_admin)) 
    //     {
    //         $get_admin_det = json_decode($get_admin->wallet_balance, true);
    //         $get_admin_dets = json_decode($get_admin->addresses, true);
           

    //         foreach($Fetch_coin_list as $coin_address)
    //         {           
                
    //             $admin_address   =   $get_admin_dets[$coin_address->currency_symbol];
    //             echo $admin_address."<br/>";
                
    //             if(array_key_exists($coin_address->currency_symbol, $get_admin_det) && $coin_address->currency_symbol != "USD")
    //             {   
                	
    //             	$Crypto_type = getcoindetail($coin_address->currency_symbol)->crypto_type;
    //             	if($Crypto_type=='tron')
    //               {
    //             		$private_key = getadmintronPrivate(1);
    //             		$wallet_balance = $this->local_model->wallet_balance($coin_address->currency_name, $admin_address,$private_key);
    //             		//echo "JST token";
                		                	
    //               }
    //             	else
    //                 { //echo " CURRENCY => ".$coin_address->currency_name;echo '<br/>';
    //             		$wallet_balance = $this->local_model->wallet_balance($coin_address->currency_name, $admin_address);
    //             	}

    //                 echo $coin_address->currency_name.'==>'.$wallet_balance.'pila';
    //                     echo "<br>";
                    
    //                 $old_balance = $get_admin_det[$coin_address->currency_symbol];
    //                 if($old_balance != $wallet_balance && $wallet_balance !=0)
    //                 {
    //                     $get_admin_det[$coin_address->currency_symbol] = number_format($wallet_balance,8,'.', '');
    //                     //print_r($get_admin_det);
    //                     $update['wallet_balance'] = json_encode($get_admin_det);

    //                   // print_r($update);

                        

    //                    $update_qry = $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update); 
    //                 }
                    
    //             }
    //         }
    //         if($update_qry)
    //         {
    //             echo "updated success";
    //         }
    //         else
    //         {
    //             echo "updated failed";
    //         }
    //     }
    // }



       public function admin_wallet_balance()
    {


   exit();

     $Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'))->result();

    		//$Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1','currency_symbol'=>'BCH'))->result();

     // echo $this->db->last_query();
 
    	// exit();

        $whers_con = "id='1'";

        $admin_id = "1";

        $enc_email = getAdminDetails($admin_id, 'email_id');

        $email = decryptIt($enc_email);         

        $get_admin = $this->common_model->getrow("admin_wallet", $whers_con);


        if(!empty($get_admin)) 
        {
            $get_admin_det = json_decode($get_admin->wallet_balance, true);
            $get_admin_dets = json_decode($get_admin->addresses, true);

			// echo "<pre>";
			// print_r($Fetch_coin_list);
			//exit;
           

            foreach($Fetch_coin_list as $coin_address)
            {           
                echo "<pre>";
				print_r($coin_address);
                $admin_address   =   $get_admin_dets[$coin_address->currency_symbol];
                echo $admin_address."<br/>";
				// array_key_exists($coin_address->currency_symbol, $get_admin_det) && 
                if($coin_address->currency_symbol != "USD")
                {   
                	if($coin_address->crypto_type_other != '')
					{
						$crypto_type_other_arr = explode('|',$coin_address->crypto_type_other);
						foreach($crypto_type_other_arr as $val)
						{
							if($val=='tron')
							{
								$private_key = getadmintronPrivate(1);
								$crypto_type_other = array('crypto'=>$val,'tron_private'=>$private_key);
                                $admin_address   =   $get_admin_dets['TRX'];
								$wallet_balance= $this->local_model->wallet_balance($coin_address->currency_name, $admin_address,$crypto_type_other);

								if($coin_address->currency_name == 'Tether')
								{
									$get_admin_det['USDT-TRC'] = number_format($wallet_balance,8,'.', '');
									$update['wallet_balance'] = json_encode($get_admin_det);
									print_r($update);
									$update_qry = $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update); 
								} else {
									$get_admin_det[$coin_address->currency_symbol] = number_format($wallet_balance,8,'.', '');
									$update['wallet_balance'] = json_encode($get_admin_det);
									$update_qry = $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update); 
								}
							}
							else if($val == 'eth')
							{
								$crypto_type_other = array('crypto'=>$val);
                                $admin_address   =   $get_admin_dets['ETH'];
								$wallet_balance= $this->local_model->wallet_balance($coin_address->currency_name, $admin_address,$crypto_type_other);

								if($coin_address->currency_name == 'Tether')
								{
									$get_admin_det['USDT-ETH'] = number_format($wallet_balance,8,'.', '');
									$update['wallet_balance'] = json_encode($get_admin_det);
									print_r($update);
									$update_qry = $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update); 
								} else {
									$get_admin_det[$coin_address->currency_symbol] = number_format($wallet_balance,8,'.', '');
									$update['wallet_balance'] = json_encode($get_admin_det);
									$update_qry = $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update); 
								}
							}
                            else if($val == 'bsc'){
                                $crypto_type_other = array('crypto'=>$val);
                                $admin_address   =   $get_admin_dets['BNB'];
								$wallet_balance= $this->local_model->wallet_balance($coin_address->currency_name, $admin_address,$crypto_type_other);
                                //echo $wallet_balance; exit;
								if($coin_address->currency_name == 'Tether')
								{
									$get_admin_det['USDT-BSC'] = number_format($wallet_balance,8,'.', '');
									$update['wallet_balance'] = json_encode($get_admin_det);
									print_r($update);
									$update_qry = $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update); 
								} else {
									$get_admin_det[$coin_address->currency_symbol] = number_format($wallet_balance,8,'.', '');
									$update['wallet_balance'] = json_encode($get_admin_det);
									$update_qry = $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update); 
								}
                            }
						}
					} else {
						$Crypto_type = getcoindetail($coin_address->currency_symbol)->crypto_type;
						if($Crypto_type=='tron')
						{
								$private_key = getadmintronPrivate(1);
								$wallet_balance = $this->local_model->wallet_balance($coin_address->currency_name, $admin_address,$private_key);
								//echo "JST token";
													
						}
						else
						{ //echo " CURRENCY => ".$coin_address->currency_name;echo '<br/>';
							$wallet_balance = $this->local_model->wallet_balance($coin_address->currency_name, $admin_address);
						}	

						echo $coin_address->currency_name.'=>'.$wallet_balance;
                        echo "<br>";
                    
						$old_balance = $get_admin_det[$coin_address->currency_symbol];
						//if($old_balance != $wallet_balance && $wallet_balance !=0)
						//{
							$get_admin_det[$coin_address->currency_symbol] = number_format($wallet_balance,8,'.', '');
							//print_r($get_admin_det);
							$update['wallet_balance'] = json_encode($get_admin_det);

						// print_r($update);

							
							
						$update_qry = $this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update); 
						//}
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
    function make_depth_chart_points($obj_response){
        if($obj_response->success){
            $buy_orders=$obj_response->result->buy;
            $sell_orders=$obj_response->result->sell;
            foreach ($buy_orders as $bkey => $bvalue) {
                $buy_arr[]=array($bvalue->Quantity,$bvalue->Rate);
            }
            foreach ($sell_orders as $skey => $svalue) {
                $sell_arr[]=array($svalue->Quantity,$svalue->Rate);
            }
            $res_arr=array(
                'asks'=>$buy_arr,
                'bids'=>$sell_arr,
                'isFrozen'=>0,
                'seq'=>strtotime('now'),
            );
            return json_encode($res_arr);
        }
    }
    public function newdepthchart($pair_val)
    { 
        $pair_val_file = strtolower($pair_val);
        $json_pair = $pair_val_file . '.json';
        $path = base_url();
        $str = file_get_contents(FCPATH . 'depthchart/' . $json_pair);
        echo $str;exit;
    }
    function basic_localpair_details()
    {
    	$where_in = array('id', array('4','7','9'));
    	$pair_details = $this->common_model->getTableData('trade_pairs',array('status' => 1),'','','','','','','','','',$where_in)->result();
      	// echo "<pre>";print_r($pair_details);die;	
      if(count($pair_details)>0)
      {
        foreach($pair_details as $pair_detail)
        { 
          if($pair_detail->api_status==0){
          $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
        $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();

        if($from_currency->currency_symbol=="BTC")
          $from_currency_symbol1 = "BTC";
        elseif($from_currency->currency_symbol=='USD')
          $from_currency_symbol1 ='USDC';
        else
          $from_currency_symbol1 = $from_currency->currency_symbol;
        
        if($to_currency->currency_symbol=="LTC")
          $to_currency_symbol1 = "LTC";
        elseif($to_currency->currency_symbol=='USD')
          $to_currency_symbol1 ='USDC';
        else
          $to_currency_symbol1 = $to_currency->currency_symbol;

      if($to_currency->currency_symbol=="LTC")
          $to_currency_symbol1 = "LTC";
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
                $volume =  $ress['volume'];
                $change_highs = $ress['highPrice'];
                $change_lows = $ress['lowPrice'];
                $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
                );
              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
              echo $pair_symbol." reverse Updated <br/>";
              }
              else
              {
                $dbrsymbol = $to_currency_symbol1."/".$from_currency_symbol1;
                $dbsymbol = $from_currency_symbol1."/".$to_currency_symbol1;
                $dbrQuery = $this->db->query("SELECT * FROM `bidex_coin_order` WHERE `pair_symbol`='".$dbrsymbol."' AND status='filled' ORDER BY `trade_id` DESC LIMIT 1")->row();
                $dbQuery = $this->db->query("SELECT * FROM `bidex_coin_order` WHERE `pair_symbol`='".$dbsymbol."' AND status='filled' ORDER BY `trade_id` DESC LIMIT 1")->row();
                if(count($dbrQuery)>0)
                { 
                  $priceChangePercent = pricechangepercent($pair_detail->id);
                  $url = "https://api.binance.com/api/v1/ticker/24hr?symbol=".str_replace('/', '', $dbrsymbol);
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              $ress = json_decode($result,true);
                      $priceChangePercent_new = $ress['priceChangePercent'];

                  $priceChangePercent = ($priceChangePercent=='')?$priceChangePercent_new:$priceChangePercent;

                  $lastPrice =  $dbrQuery->Price;
                  $volume = volume($pair_detail->id);
                  $change_highs = change_high($pair_detail->id);
                $change_lows = change_low($pair_detail->id);
                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
              );
                $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
                echo $pair_symbol." DB REV Updated <br/>";
                }
                elseif(count($dbQuery)>0)
                {
                  
                  $priceChangePercent = pricechangepercent($pair_detail->id);
                  $url = "https://api.binance.com/api/v1/ticker/24hr?symbol=".str_replace('/', '', $dbsymbol);
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              $ress = json_decode($result,true);
                      $priceChangePercent_new = $ress['priceChangePercent'];

                  $priceChangePercent = ($priceChangePercent=='')?$priceChangePercent_new:$priceChangePercent;
                  $lastPrice =  $dbQuery->Price;
                  $volume =  volume($pair_detail->id);

                  $change_highs = change_high($pair_detail->id);
                $change_lows = change_low($pair_detail->id);

                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
              	);
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
                  $volume =  $ress['volume'];
                  $change_highs = $ress['highPrice'];
                $change_lows = $ress['lowPrice'];
                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
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
              $volume =  $res['volume'];
              $change_highs = $res['highPrice'];
                $change_lows = $res['lowPrice'];
              $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
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
      	
        
          $coin_order = $this->db->query("SELECT * FROM `bidex_coin_order` WHERE `pair_symbol`='".$pair_symbol."' AND status='filled' ORDER BY `trade_id` DESC")->result();
          if(isset($coin_order) && !empty($coin_order)){
          $lastPrice = lastmarketprice($pair_detail->id);
          $volume = volume($pair_detail->id);
          $change_highs = change_high($pair_detail->id);
          $change_lows = change_low($pair_detail->id);
           
          /* $Price_change = $lastPrice - $change_lows;
              $Per = $change_lows/100 ;
              $priceChangePercent = $Price_change/$Per;*/

              $priceChangePercent = pricechangepercent($pair_detail->id);
              $priceChangePercent = ($priceChangePercent=='')?'0':$priceChangePercent;

              $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                'change_high'=>$change_highs,
                  'change_low'=>$change_lows
                );

              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
              echo $pair_symbol." Database<br/>";
          }
          else{

          	$updateTableData = array('priceChangePercent'=>0,
                  'lastPrice'=>0,
                  'volume'=>0,
                'change_high'=>0,
                  'change_low'=>0,
                  'buy_rate_value'=>0,
                  'sell_rate_value'=>0
                );

              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
          	echo $pair_symbol." DB Empty<br/>";
          }
          
        
        }
      }
     }
    }

    function localpair_details()
    {
      error_reporting(0);
      //$where_not = array('id', array('4','7','9','8','10'));
      $pair_details = $this->common_model->getTableData('trade_pairs',array('status' => 1),'','','','','','','','','')->result();
      // echo "<pre>";print_r($pair_details);die;
      // $pair_details = $this->common_model->getTableData('trade_pairs',array('status' => 1))->result();
      if(count($pair_details)>0)
      {
        foreach($pair_details as $pair_detail)
        { 
          if($pair_detail->api_status==1){
          $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
        $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();

        if($from_currency->currency_symbol=="BTC")
          $from_currency_symbol1 = "BTC";
        elseif($from_currency->currency_symbol=='USD')
          $from_currency_symbol1 ='USDC';
        else
          $from_currency_symbol1 = $from_currency->currency_symbol;
        
        if($to_currency->currency_symbol=="LTC")
          $to_currency_symbol1 = "LTC";
        elseif($to_currency->currency_symbol=='USD')
          $to_currency_symbol1 ='USDC';
        else
          $to_currency_symbol1 = $to_currency->currency_symbol;

      if($to_currency->currency_symbol=="LTC")
          $to_currency_symbol1 = "LTC";
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
                $volume =  $ress['volume'];
                $change_highs = $ress['highPrice'];
                $change_lows = $ress['lowPrice'];
                $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
                );
              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
              echo $pair_symbol." reverse Updated <br/>";
              }
              else
              {
                $dbrsymbol = $to_currency_symbol1."/".$from_currency_symbol1;
                $dbsymbol = $from_currency_symbol1."/".$to_currency_symbol1;
                $dbrQuery = $this->db->query("SELECT * FROM `bidex_coin_order` WHERE `pair_symbol`='".$dbrsymbol."' AND status='filled' ORDER BY `trade_id` DESC LIMIT 1")->row();
                $dbQuery = $this->db->query("SELECT * FROM `bidex_coin_order` WHERE `pair_symbol`='".$dbsymbol."' AND status='filled' ORDER BY `trade_id` DESC LIMIT 1")->row();
                if(count($dbrQuery)>0)
                { 
                  $priceChangePercent = pricechangepercent($pair_detail->id);
                  $url = "https://api.binance.com/api/v1/ticker/24hr?symbol=".str_replace('/', '', $dbrsymbol);
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              $ress = json_decode($result,true);
                      $priceChangePercent_new = $ress['priceChangePercent'];

                  $priceChangePercent = ($priceChangePercent=='')?$priceChangePercent_new:$priceChangePercent;

                  $lastPrice =  $dbrQuery->Price;
                  $volume = volume($pair_detail->id);
                  $change_highs = change_high($pair_detail->id);
                $change_lows = change_low($pair_detail->id);
                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
              );
                $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
                echo $pair_symbol." DB REV Updated <br/>";
                }
                elseif(count($dbQuery)>0)
                {
                  
                  $priceChangePercent = pricechangepercent($pair_detail->id);
                  $url = "https://api.binance.com/api/v1/ticker/24hr?symbol=".str_replace('/', '', $dbsymbol);
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $result = curl_exec($ch);
              $ress = json_decode($result,true);
                      $priceChangePercent_new = $ress['priceChangePercent'];

                  $priceChangePercent = ($priceChangePercent=='')?$priceChangePercent_new:$priceChangePercent;
                  $lastPrice =  $dbQuery->Price;
                  $volume =  volume($pair_detail->id);

                  $change_highs = change_high($pair_detail->id);
                $change_lows = change_low($pair_detail->id);

                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
              	);
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
                  $volume =  $ress['volume'];
                  $change_highs = $ress['highPrice'];
                $change_lows = $ress['lowPrice'];
                  $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                    'lastPrice'=>$lastPrice,
                    'volume'=>$volume,
                  'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
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
              $volume =  $res['volume'];
              $change_highs = $res['highPrice'];
                $change_lows = $res['lowPrice'];
              $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                'change_high'=>$change_highs,
                  'change_low'=>$change_lows,
                  'buy_rate_value'=>$lastPrice,
                  'sell_rate_value'=>$lastPrice
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
      	
        
          $coin_order = $this->db->query("SELECT * FROM `bidex_coin_order` WHERE `pair_symbol`='".$pair_symbol."' AND status='filled' ORDER BY `trade_id` DESC")->result();
          // print_r($coin_order);exit;
          if(isset($coin_order) && !empty($coin_order)){
          // print_r($coin_order);exit;	
          $lastPrice = lastmarketprice($pair_detail->id);
          $volume = volume($pair_detail->id);
          $change_highs = change_high($pair_detail->id);
          $change_lows = change_low($pair_detail->id);
           
          /* $Price_change = $lastPrice - $change_lows;
              $Per = $change_lows/100 ;
              $priceChangePercent = $Price_change/$Per;*/

              $priceChangePercent = pricechangepercent($pair_detail->id);
              $priceChangePercent = ($priceChangePercent=='')?'0':$priceChangePercent;

              $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                  'lastPrice'=>$lastPrice,
                  'volume'=>$volume,
                'change_high'=>$change_highs,
                  'change_low'=>$change_lows
                );
              // print_r($updateTableData);exit;	
              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
              echo $pair_symbol." Database<br/>";
          }
          else{

          	$updateTableData = array('priceChangePercent'=>0,
                  'lastPrice'=>0,
                  'volume'=>0,
                'change_high'=>0,
                  'change_low'=>0,
                  'buy_rate_value'=>0,
                  'sell_rate_value'=>0
                );

              $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
          	echo $pair_symbol." DB Empty<br/>";
          }
          
        
        }
      }
      }
    }

function localpair_details_crypto()
{
	$where_in = array('id', array('4','7','9','10'));
    $pair_details = $this->common_model->getTableData('trade_pairs',array('status' => 1),'','','','','','','','','',$where_in)->result();
	// $pair_details = $this->common_model->getTableData('trade_pairs',array('status' => 1,'id'=>10),'','','','','','','','')->result();
	// echo "<pre>";print_r($pair_details);die;
	if(count($pair_details)>0)
	{
		foreach($pair_details as $pair_detail)
		{
			$from_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
			$to_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();
			$pair_symbol = $from_currency->currency_symbol.'-'.$to_currency->currency_symbol;


			$getInfo = $this->callAPI($from_currency->currency_symbol);

			$change = (($getInfo->last-$getInfo->open)/$getInfo->last)*100;
			// if(is_nan($change)) $changeVal = '';
   //          else $changeVal = round($change,2).'%';
			$updateTableData = array('priceChangePercent'=>$change,
                            'lastPrice'=>$getInfo->last,
                            'volume'=>$getInfo->volume,
                            'change_low'=>$getInfo->low,
                            'change_high'=>$getInfo->high,
                            'buy_rate_value'=>$getInfo->last,
                            'sell_rate_value'=>$getInfo->last
                        );              
            $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
            echo $pair_symbol." Updated <br/>";
		}
	}
}   

function callAPI($currency) 
{
	$url = 'https://api.pro.coinbase.com/products/'.$currency.'-EUR/stats';
	$agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
    $curlSession = curl_init();
    curl_setopt($curlSession, CURLOPT_URL, $url);
    curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($curlSession, CURLOPT_USERAGENT, $agent);
    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
    $jsonData = json_decode(curl_exec($curlSession));
    curl_close($curlSession);
    return $jsonData;
	// echo "<pre>";print_r($jsonData);die;
} 

function localpair_details_crypto1()
{
	 
$pair_details = $this->common_model->getTableData('trade_pairs',array('status' => 1,'id'=>10),'','','','','','','','')->result();

if(count($pair_details)>0)
{
foreach($pair_details as $pair_detail)
{
$from_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
$to_currency = $this->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();
$pair_symbol = $from_currency->currency_symbol.$to_currency->currency_symbol;
$url = file_get_contents("https://min-api.cryptocompare.com/data/pricemultifull?fsyms=".$from_currency->currency_symbol."&tsyms=".$to_currency->currency_symbol);
$res = json_decode($url,true);
// echo "<pre>";print_r( $url );

        $crypto_data = $res['DISPLAY'][$to_currency->currency_symbol][$from_currency->currency_symbol];
        
        if ($crypto_data == '') 
        {
            $urls = file_get_contents("https://min-api.cryptocompare.com/data/pricemultifull?fsyms=".$from_currency->currency_symbol."&tsyms=".$to_currency->currency_symbol);

            $ress = json_decode($urls,true);
            $crypto_datas = $ress['RAW'][$from_currency->currency_symbol][$to_currency->currency_symbol];
            if ($crypto_datas != '') 
            {
                    $priceChangePercent = trim($crypto_datas['CHANGEPCT24HOUR']);
                    
                    $lastPrice = trim(str_replace($crypto_datas['FROMSYMBOL'], '', $crypto_datas['PRICE']));
                    $volume = trim(str_replace($crypto_datas['TOSYMBOL'], '', $crypto_datas['TOPTIERVOLUME24HOUR']));
                    $highPrice = trim(str_replace($crypto_datas['FROMSYMBOL'], '', $crypto_datas['HIGH24HOUR']));
                    $lowPrice = trim(str_replace($crypto_datas['FROMSYMBOL'], '', $crypto_datas['LOW24HOUR']));
                   
                    $updateTableData = array('priceChangePercent'=>$priceChangePercent,
                            'lastPrice'=>$lastPrice,
                            'volume'=>$volume,
                            'change_low'=>$lowPrice,
                            'change_high'=>$highPrice,
                            'buy_rate_value'=>$lastPrice,
                            'sell_rate_value'=>$lastPrice
                        );  
                         
                $this->common_model->updateTableData('trade_pairs', array('id' => $pair_detail->id), $updateTableData);
                echo $pair_symbol." Updated <br/>";
            }
            else
            {
                echo $pair_symbol." Not Updated <br/>";
            }
        }
    }
}
}

    function getcurrency_localdetails()
    {
    	$currency_details = $this->common_model->getTableData('currency',array('status' => 1),"id, currency_name, currency_symbol")->result();
    	// echo "<pre>";print_r($currency_details);die;
    	if(count($currency_details)>0)
    	{
    		foreach($currency_details as $row)
    		{
    			$currency_name = strtolower($row->currency_name);
    			$url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=".$currency_name;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec($ch);
				$res = json_decode($result,true); 
				$market_cap =  $res[0]['market_cap_change_percentage_24h'];
				$coin_symbol = $row->currency_symbol;
				/*$urls = "https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=USD&api_key=86b5e03cde761b72e73d89e11b9bbb4c50c2bbe2576fb1904d1e2afeaab9323a";
				$chs = curl_init();
				curl_setopt($chs, CURLOPT_URL, $urls);
				curl_setopt($chs, CURLOPT_RETURNTRANSFER, 1);
				$result1 = curl_exec($chs);
				$errorMessage = curl_error($chs);*/

				//$result1 = convercurr($coin_symbol,'USD');
				$result1 = $this->coinprice($coin_symbol);
				$res1 = json_decode($result1);
				$usd_cap = $res1->USD;
				$updateTableData = array(
					'market_cap_change_percentage_24h' =>$market_cap,
					'usd_cap' =>$usd_cap
				);
				// echo "<pre>";print_r($updateTableData);
				$this->common_model->updateTableData('currency', array('id' => $row->id), $updateTableData);
    		}
    	}
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
        $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');
        if (!empty($_POST)) 
        { 
            if ($this->form_validation->run()) 
            {
                $name = validateTextBox(strip_tags(trim($this->db->escape_str($this->input->post('name')))));
                $email = validateEmail(strip_tags(trim($this->db->escape_str($this->input->post('email')))));
                $subject = validateTextBox(strip_tags(trim($this->db->escape_str($this->input->post('subject')))));
                $comments = validateTextBox(strip_tags(trim($this->db->escape_str($this->input->post('message')))));
              
                $status = 0;
                $contact_data = array(
                    'username' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $comments,
                    //'phone' => $phone,
                    'status' => $status,
                    'created_on' => date("Y-m-d h:i:s")
                );
                $id = $this->common_model->insertTableData('contact_us', $contact_data);
                $email_template = 'Contact_user';
                $email_template1 = 'Contact_admin';
				$username=$this->input->post('name');
				$message = $this->input->post('message');
				$link = base_url().'bidex_admin/contact';
				$site_common      =   site_common();
				$admin_admin = site_common()['site_settings']->site_email;
				$special_vars = array(					
				'###USERNAME###' => $username,
				'###MESSAGE###' => $message
				);
				$special_vars1 = array(					
				'###USERNAME###' => $username,
				'###MESSAGE###' => $message,
				'###LINK###' => $link
				);

				// print_r($email_template);
				// echo "------------------";
				// print_r($special_vars1); die;

				    
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
				$this->email_model->sendMail($admin_admin, '', '', $email_template1, $special_vars1);
                if ($id) 
                {
                    $this->session->set_flashdata('success', 'Your message successfully sent to our team');
                    // echo $this->session->flashdata('success');die;
                    front_redirect('contact_us', 'refresh');
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Error occur!! Please try again');
                    front_redirect('contact_us', 'refresh');
                }
            } 
            else 
            {
                $this->session->set_flashdata('error', validation_errors());
                front_redirect('contact_us', 'refresh');
            }
        }
        $data['cms'] = $this->common_model->getTableData('cms', array('status' => 1, 'link'=>'contact'))->row();
        $data['site_common'] = site_common();
        $data['action'] = front_url() . 'contact_us';
        $data['site_details'] = $this->common_model->getTableData('site_settings', array('id' => '1'))->row();
        $data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'contact-us'))->row();
        /*$data['heading'] = $meta->heading;
        $data['title'] = $meta->title;
        $data['meta_keywords'] = $meta->meta_keywords;
        $data['meta_description'] = $meta->meta_description;*/
        $data['js_link'] = 'contact_us';
        $this->load->view('front/common/contact_us', $data);
    }

    public function contac_home()
    { 
        if($this->block() == 1)
		{ 
		front_redirect('block_ip');
		}
        $this->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');
        if (!empty($_POST)) 
        { 
            if ($this->form_validation->run()) 
            {
                $name = validateTextBox(strip_tags(trim($this->db->escape_str($this->input->post('name')))));
                $email = validateEmail(strip_tags(trim($this->db->escape_str($this->input->post('email')))));
                $subject = validateTextBox(strip_tags(trim($this->db->escape_str($this->input->post('subject')))));
                $comments = validateTextBox(strip_tags(trim($this->db->escape_str($this->input->post('message')))));
              //  $phone = $this->db->escape_str($this->input->post('phone'));
                $status = 0;
                $contact_data = array(
                    'username' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $comments,
                    //'phone' => $phone,
                    'status' => $status,
                    'created_on' => date("Y-m-d h:i:s")
                );
                $id = $this->common_model->insertTableData('contact_us', $contact_data);
                $email_template = 'Contact_user';
                $email_template1 = 'Contact_admin';
				$username=$this->input->post('name');
				$message = $this->input->post('message');
				$link = base_url().'bidex_admin/contact';
				$site_common      =   site_common();
				$admin_admin = site_common()['site_settings']->site_email;
				$special_vars = array(					
				'###USERNAME###' => $username,
				'###MESSAGE###' => $message
				);
				$special_vars1 = array(					
				'###USERNAME###' => $username,
				'###MESSAGE###' => $message,
				'###LINK###' => $link
				);

				// print_r($email_template);
				// echo "------------------";
				// print_r($special_vars1); die;

				    
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
				$this->email_model->sendMail($admin_admin, '', '', $email_template1, $special_vars1);
                if ($id) 
                {
                    $this->session->set_flashdata('success', 'Your message successfully sent to our team');
                    // echo $this->session->flashdata('success');die;
                    front_redirect('home', 'refresh');
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Error occur!! Please try again');
                    front_redirect('home', 'refresh');
                }
            } 
            else 
            {
                $this->session->set_flashdata('error', validation_errors());
                front_redirect('home', 'refresh');
            }
        }
        //$data['cms'] = $this->common_model->getTableData('cms', array('status' => 1, 'link'=>'contact'))->row();
        $data['site_common'] = site_common();
        $data['action'] = front_url() . 'contac_home';
        //$data['site_details'] = $this->common_model->getTableData('site_settings', array('id' => '1'))->row();
        //$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'contact-us'))->row();
        /*$data['heading'] = $meta->heading;
        $data['title'] = $meta->title;
        $data['meta_keywords'] = $meta->meta_keywords;
        $data['meta_description'] = $meta->meta_description;*/
        $data['js_link'] = 'contac_home';
        front_redirect('home', 'refresh');
    }


function common_test_details(){

  // $coin_name = $transfer_currency = "BNB";
  //     $model_name = strtolower($coin_name).'_wallet_model';
  //      $model_location = 'wallets/'.strtolower($coin_name).'_wallet_model';
  //     $this->load->model($model_location,$model_name);

  //     $from_address = "0x28fddae2c3a6d9dcbb0b4e1d113e416256049e67";
  //     $to_address = "0x28fddae2c3a6d9dcbb0b4e1d113e416256049e67";

  //     $GasLimit = 50000;

  //     $GasPrice = 5000000000;

  //     $eth_amount1=0;
  //     $Nonce = 3;

  //     $eth_trans = array('from'=>$from_address,'to'=>$to_address,'value'=>(float)$eth_amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice,'nonce'=>$Nonce);

  //      $send_money_res = $this->local_model->make_transfer($transfer_currency,$eth_trans);
  //     echo "Success";


  }
    /*public function common_test_details(){
    	//echo date('Y-m-d H:i:s');
    	echo decryptIt('eWJ1SFY2TWJSQ21vYkhnM01PbDhzMTBJbUdmU254TGtodjRHOGNiQ3pmVT0=')."<br/>";
    	echo decryptIt('ZXJRSGdFejlHaEgwTndCbTJXV3B0UT09')."<br/>";
    	exit();
    	

    	$Users_List = $this->common_model->getTableData('users',array('verified'=>1))->result();
    	foreach($Users_List as $Users){
    		echo getUserEmail($Users->id)." -#- ".decryptIt($Users->bidex_password)."<br/>";
    	}
    }*/

    // public function common_test_details(){
    	// $coin_name = $transfer_currency = "Ethereum";
     //  $model_name = strtolower($coin_name).'_wallet_model';
     //   $model_location = 'wallets/'.strtolower($coin_name).'_wallet_model';
     //  $this->load->model($model_location,$model_name);
     //  $eth_amount1=0.0075 * 1000000000000000000;
    	// $from_address = "0x702C7ff7da06B036d3FE9F83b4fC35f3F3bd0e66";
     //  $to_address = "0x94Ee71F8E4618858a81E72406b69a8ce08E23535";
     //  $GasLimit = 70000;
     //  $GasPrice = $this->$model_name->eth_gasPrice();

     //  $eth_trans = array('from'=>$from_address,'to'=>$to_address,'value'=>(float)$eth_amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
     //  $send_money_res = $this->local_model->make_transfer($transfer_currency,$eth_trans);
     //  echo "Success";

    // }
    public function market_trades($pair_id)
{
$tradehistory_via_api = $this->common_model->getTableData('site_settings',array('tradehistory_via_api'=>1))->row('tradehistory_via_api');
if($tradehistory_via_api ==0){
//$selectFields='CO.*,date_format(CO.datetime,"%H:%i:%s") as trade_time,sum(OT.filledAmount) as totalamount,CO.Type as ordertype,CO.Price as price';
$selectFields='CO.Amount,date_format(CO.datetime,"%H:%i:%s") as trade_time,sum(OT.filledAmount) as totalamount,CO.Type as ordertype,CO.Price as price';
//$names = array('active', 'partially', 'margin');
$names = array('filled');
$where=array('CO.pair'=>$pair_id);
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
}
//     public function market_trades($pair_id)
// {
// 	/*$tradehistory_via_api = $this->common_model->getTableData('site_settings',array('tradehistory_via_api'=>1))->row('tradehistory_via_api');
// 	if($tradehistory_via_api ==0){*/
// 	$selectFields='CO.*,date_format(CO.datetime,"%H:%i:%s") as trade_time,sum(OT.filledAmount) as totalamount,CO.Type as ordertype,CO.Price as price';
// 	$names = array('active', 'partially', 'margin');
// 	$where=array('CO.pair'=>$pair_id);
// 	$orderBy=array('CO.trade_id','desc');
// 	$groupBy=array('CO.trade_id');
// 	$where_in=array('CO.status', $names);
// 	$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
// 	$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
// 	// echo "<pre>";print_r($query->result());
// 	if($query->num_rows() >= 1)
// 	{
// 		$result = $query->result();
// 	}
// 	else
// 	{
// 		$result = 0;
// 	}
// 	if($result&&$result!=0)
// 	{
// 		$orders=$result;
// 	}
// 	else
// 	{
// 		$orders=0;
// 	}
// 	return $orders;
// }

function market_api_trades($pair){
	$pair_value=explode('_',$pair);
  if(count($pair_value) > 0) 
  {
    $first_pair  = strtoupper($pair_value[0]);
    $second_pair1 = strtoupper($pair_value[1]);
    if($second_pair1=='USD'){
    	$second_pair = 'USDC';
    }
    else{
    	$second_pair = $second_pair1;
    }
    $coin_pair = $first_pair.$second_pair;
	$json  		= file_get_contents('http://api.binance.com/api/v1/depth?symbol='.$coin_pair.'&limit=20');
	$newresult = json_decode($json,true);
    if(count($newresult)>0 && !empty($newresult))
    {
    	$buy_orders = $newresult['bids'];
        $sell_orders = $newresult['asks'];
        $res_data = array();
        $i=1;
        foreach($sell_orders as $sell)
        {
        	$sellData['id'] = $i;
	        $sellData['price'] = $sell[0];
	        $sellData['quantity'] = $sell[1];
	        $sellData['ordertype'] = 'Sell';
	        $res_data[] = $sellData;
            $i++;
        }
        foreach($buy_orders as $buy)
        {
        	$buyData['id'] = $i;
	        $buyData['price'] = $buy[0];
	        $buyData['quantity'] = $buy[1];
	        $buyData['ordertype'] = 'Buy';
	        $res_data[] = $buyData;
            $i++;
        }
    }
    else
    {
    	$coin_pairrev = $second_pair.$first_pair;
    	$json_rev  		= file_get_contents('http://api.binance.com/api/v1/depth?symbol='.$coin_pairrev.'&limit=20');
	    $newresult_rev = json_decode($json_rev,true);
	    if(count($newresult_rev)>0 && !empty($newresult_rev))
        {
        	$buy_orders = $newresult_rev['bids'];
	        $sell_orders = $newresult_rev['asks'];
	        $res_data = array();
	        $i=1;
        foreach($sell_orders as $sell)
        {
        	$sellData['id'] = $i;
	        $sellData['price'] = $sell[0];
	        $sellData['quantity'] = $sell[1];
	        $sellData['ordertype'] = 'Sell';
	        $res_data[] = $sellData;
            $i++;
        }
        foreach($buy_orders as $buy)
        {
        	$buyData['id'] = $i;
	        $buyData['price'] = $buy[0];
	        $buyData['quantity'] = $buy[1];
	        $buyData['ordertype'] = 'Buy';
	        $res_data[] = $buyData;
            $i++;
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
         $res_data = 0;
    }
    
   return $res_data;
}
}

function news()
	{
		if($_POST)
		{
			$limit = $this->input->post('limit');
		}
		else
		{
			$limit = 30;
		}
		$this->session->set_userdata(array('limit'  => $limit));
		$data['news'] = $this->common_model->getTableData('news', array('status' => 1),'','','','','',$limit,array('id', 'DESC'))->result();
		
		$data['no_news'] = $this->common_model->getTableData('news', array('status' => 1))->num_rows();
		$data['js_link'] = '';
		$data['site_common'] = site_common();
		$data['action'] = front_url() . 'news';
		$meta = $this->common_model->getTableData('meta_content', array('link' => 'news'))->row();
		$data['heading'] = $meta->heading;
		$data['title'] = $meta->title;
		$data['meta_keywords'] = $meta->meta_keywords;
		$data['meta_description'] = $meta->meta_description;
		$this->load->view('front/common/news', $data);
	}

	function rss_feed()
	{ 
		$feeds = array(
            "https://cointelegraph.com/rss/tag/bitcoin"
        );
        
        //Read each feed's items
        $entries = array();
        foreach($feeds as $feed) {
            $xml = simplexml_load_file($feed);
            $entries = array_merge($entries, $xml->xpath("//item"));
        }
        
        //Sort feed entries by pubDate
        usort($entries, function ($feed1, $feed2) {
            return strtotime($feed2->pubDate) - strtotime($feed1->pubDate);
        });
        foreach ($entries as $entry) {
        	$Title =  $entry->title;
        	$Image = $entry->enclosure['url'];
        	$Link = $entry->link;
        	$num_rows = $this->common_model->getTableData('news', array('link' => $Link))->num_rows();
        	if($num_rows==0){
        	$contact_data = array(
					'english_title' => $Title,
					'link'       =>$Link,
					'image'     =>str_replace('http://', 'https://', $Image),
					'status'	=>'1'
					);
				$contact_dataclean = $this->security->xss_clean($contact_data);
				$id=$this->common_model->insertTableData('news', $contact_dataclean);
				echo "success<br/>";
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
				$volume 	= $chartResult->volume;
				$low 		= $chartResult->low; 
				$high	 	= $chartResult->high;
			}
			
				
			

		$lowestaskprice = lowestaskprice($pairs->id);
		$highestbidprice = highestbidprice($pairs->id);
		$lastmarketprice = lastmarketprice($pairs->id);

			$Insert_data['cur'] 		= $pairs->from_currency_symbol;
			$Insert_data['symbol'] 		= $pairs->from_currency_symbol.'_'.$pairs->to_currency_symbol;
			$Insert_data['last']		= (float)number_format((float)$lastmarketprice, 8, '.', '');
			$Insert_data['high'] 		= (float)number_format((float)$high, 8, '.', '');
			$Insert_data['low'] 		= (float)number_format((float)$low, 8, '.', '');
			$Insert_data['volume'] 		= (float)number_format((float)$volume, 8, '.', '');
			$Insert_data['vwap'] 		= $thisDataArray['last'];
			$Insert_data['max_bid'] 	= (float)number_format((float)$highestbidprice, 8, '.', '');
			$Insert_data['min_ask'] 	= (float)number_format((float)$lowestaskprice, 8, '.', '');
			$Insert_data['best_bid'] 	= (float)number_format((float)$highestbidprice, 8, '.', '');
			$Insert_data['best_ask'] 	= (float)number_format((float)$lowestaskprice, 8, '.', '');
			$Insert_data['updated_on']	= date('Y-m-d H:i:s');
			$Insert_data['db']			= 'db';

			$id=$this->common_model->insertTableData('api', $Insert_data);
		}
		else{
		$Trade_Pairs = $pairs->from_currency_symbol.'/'.$pairs->to_currency_symbol;
		

		if($Trade_Pairs=='ETC/BTC'){
				$thisData  		= file_get_contents('https://poloniex.com/public?command=returnTicker');
    			
    			
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

					$Insert_data['cur'] 		= $thisDataArray['BTC_ETC']['cur'] = 'ETC';
					$Insert_data['symbol'] 		= $thisDataArray['BTC_ETC']['symbol'] = 'ETC_BTC';
					$Insert_data['last']		= $thisDataArray['BTC_ETC']['last'] = $last;
					$Insert_data['high'] 		= $thisDataArray['BTC_ETC']['high'] = $high;
					$Insert_data['low'] 		= $thisDataArray['BTC_ETC']['low'] = $low;
					$Insert_data['volume'] 		= $thisDataArray['BTC_ETC']['volume'] = $volume1 + $volume;
					$Insert_data['vwap'] 		= $thisDataArray['BTC_ETC']['last'] = $last;
					$Insert_data['max_bid'] 	= $thisDataArray['BTC_ETC']['max_bid'] = $max_bid;
					$Insert_data['min_ask'] 	= $thisDataArray['BTC_ETC']['min_ask'] = $min_ask;
					$Insert_data['best_ask'] 	= $thisDataArray['BTC_ETC']['best_ask'] = $best_ask;
					$Insert_data['best_bid'] 	= $thisDataArray['BTC_ETC']['best_bid'] = $max_bid;
					$Insert_data['updated_on']	= date('Y-m-d H:i:s');
					$Insert_data['db']			= 'poloniex';

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

					$Insert_data['cur'] 		= $thisDataArray['cur'];
					$Insert_data['symbol'] 		= $thisDataArray['symbol'];
					$Insert_data['last']		= $thisDataArray['last'];
					$Insert_data['high'] 		= $thisDataArray['high'];
					$Insert_data['low'] 		= $thisDataArray['low'];
						if(isset($thisDataArray['volume']) && !empty($thisDataArray['volume'])){
							$Insert_data['volume'] = $thisDataArray['volume'] + $volume;
								}
					$Insert_data['vwap'] 		= $thisDataArray['vwap'];
					$Insert_data['max_bid'] 	= $thisDataArray['max_bid'];
					$Insert_data['min_ask'] 	= $thisDataArray['min_ask'];
					$Insert_data['best_bid'] 	= $thisDataArray['best_bid'];
					$Insert_data['best_ask'] 	= $thisDataArray['best_ask'];
					$Insert_data['updated_on']	= date('Y-m-d H:i:s');
					$Insert_data['db']			= 'livecoin';

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
					$Insert_data['cur'] 		= $Api->cur;
					$Insert_data['symbol'] 		= $Api->symbol;
					$Insert_data['last']		= $Api->last;
					$Insert_data['high'] 		= $Api->high;
					$Insert_data['low'] 		= $Api->low;
					$Insert_data['volume'] 		= $Api->volume;
					$Insert_data['vwap'] 		= $Api->last;
					$Insert_data['max_bid'] 	= $Api->max_bid;
					$Insert_data['min_ask'] 	= $Api->min_ask;
					$Insert_data['best_bid'] 	= $Api->best_bid;
					$Insert_data['best_ask'] 	= $Api->best_ask;

					$newDataArray[] = $Insert_data;
				}
	
echo json_encode($newDataArray,true);


}
function favorite_adds(){
		$this->load->library('session');
$user_id=$this->session->userdata('user_id');
// echo "<pre>";
$currency_id=$this->input->post('id');
// echo "<pre>";
//$from = $this->input->post('fid');
//echo "<pre>";
//$to = $this->input->post('tid');

//$status=$this->input->get('status');
$Ip_Address = get_client_ip();

if( $user_id!="")
{    
    $Table = $this->common_model->getTableData('favoritepairs', array('user_id' => $user_id,'pair_id'=>$currency_id));
    if($Table->num_rows()==0){
        $insertData = array(
    'pair_id'=> $currency_id,
    //'from_symbol'=> $from,
    //'to_symbol'=> $to,
    'user_ip' => $Ip_Address,
    'user_id'=> $user_id
    );
        //print_r($insertData);

    $insert = $this->common_model->insertTableData('favoritepairs', $insertData);
    if($insert){
        $data['msg'] = 'added';
    }
    }else{

  $Table = $this->common_model->getTableData('favoritepairs', array('pair_id'=>$currency_id))->row();
   $Condition = array(
    'id'=> $Table->id,
  );
    $delete = $this->common_model->deleteTableData('favoritepairs', $Condition);
   //echo $this->db->last_query();


    }
}
else{
   $Table = $this->common_model->getTableData('favoritepairs', array('user_ip' => $Ip_Address,'pair_id'=>$currency_id));
    if($Table->num_rows()==0){
        $insertData = array(
    'pair_id'=> $currency_id,
    //'from_symbol'=> $from,
    //'to_symbol'=> $to,
    'user_ip' => $Ip_Address,
    //'user_id'=> $user_id
    );

    $insert = $this->common_model->insertTableData('favoritepairs', $insertData);

    if($insert){
        $data['msg'] = 'added';
    }
    }else{

  
     $Table = $this->common_model->getTableData('favoritepairs', array('pair_id'=>$currency_id))->row();
   $Condition = array(
    'id'=> $Table->id,
  );
    $delete = $this->common_model->deleteTableData('favoritepairs', $Condition);

    }
}
echo json_encode($data);
}

function fav_add(){
		$this->load->library('session');

		$user_id=$this->session->userdata('user_id');
		$currency_id=$this->input->get('currency_id');
		$status=$this->input->get('status');
		$Ip_Address = get_client_ip();
		if($status=='mark'){
		if($user_id!="")
		{	
			$Table = $this->common_model->getTableData('favourite_currency', array('user_id' => $user_id,'currency_id'=>$currency_id));
			if($Table->num_rows()==0){
				$insertData = array(
	    	'currency_id'=> $currency_id,
	    	'user_id'=> $user_id,
	    	'ip' => $Ip_Address
	        );
	        $insert = $this->common_model->insertTableData('favourite_currency', $insertData);
	        if($insert){
	        	$data['msg'] = 'added';
	        }
			}
		}
		else{
			$Table = $this->common_model->getTableData('favourite_currency', array('ip' => $Ip_Address,'currency_id'=>$currency_id));
			if($Table->num_rows()==0){
				$insertData = array(
	    	'currency_id'=> $currency_id,
	    	'user_id'=> 0,
	    	'ip' => $Ip_Address
	        );
	        $insert = $this->common_model->insertTableData('favourite_currency', $insertData);

	        if($insert){
	        	$data['msg'] = 'added';
	        }

			}
		}
	}
	if($status=='unmark'){
		if($user_id!="")
		{	
		$Table = $this->common_model->getTableData('favourite_currency', array('user_id' => $user_id,'currency_id'=>$currency_id));
			if($Table->num_rows()>0){
				$Condition = array(
	    	'id'=> $Table->row('id')
	        );
	        $delete = $this->common_model->deleteTableData('favourite_currency', $Condition);

	        if($delete){
	        	$data['msg'] = 'deleted';
	        }
			}
		}
		else{
			$Table = $this->common_model->getTableData('favourite_currency', array('ip' => $Ip_Address,'currency_id'=>$currency_id));
			if($Table->num_rows()>0){
				$Condition = array(
	    	'id'=> $Table->row('id')
	        );
	        $delete = $this->common_model->deleteTableData('favourite_currency', $Condition);
	        if($delete){
	        	$data['msg'] = 'deleted';
	        }
			}
		}
	
}
echo json_encode($data);
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
        $second_pair1 = strtoupper($to_symbol);

        if($second_pair1=='USD'){
        	$second_pair='USDC';
        	$insert_pair = 'USD';
        }else{
        	$second_pair = $second_pair1;
        	$insert_pair = $second_pair1;
        }
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
                  $buyData['pair_symbol'] = $first_pair.'_'.$insert_pair;
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
                    $sellData['pair_symbol'] = $first_pair.'_'.$insert_pair;
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
                  $buyData['pair_symbol'] = $first_pair.'_'.$insert_pair;
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
                    $sellData['pair_symbol'] = $first_pair.'_'.$insert_pair;
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
                $buyData['pair_symbol'] = $first_pair.'_'.$insert_pair;
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
                  $sellData['pair_symbol'] = $first_pair.'_'.$insert_pair;
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

	function membership()
	{
		if($this->block() == 1)
		{
		front_redirect('block_ip');
		}
		$data['membership'] = $this->common_model->getTableData('membership', array('status' => 1))->result();
        $data['site_common'] = site_common();
        $data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'faq'))->row();
     	
     	$data['home_banner7'] = $this->common_model->getTableData('static_content',array('slug'=>'home_banner7'))->row();
     	$data['testimonials'] = $this->common_model->getTableData('bidex_testimonials',array('status'=>1))->result();   
        $this->load->view('front/common/membership', $data);
	}


function wallet_check(){
	$wallet = $this->common_model->getTableData('transactions', array('wallet_type_cron' => 0,'type'=>'Withdraw','status'=>'Pending','payment_method'=>'crypto'))->result();
	if(isset($wallet) && !empty($wallet)){
		foreach($wallet as $wallet_type){
			
			$Currency_Id = $wallet_type->currency_id;
			$Crypto_Address = $wallet_type->crypto_address;

			$Get_all_address = getAllAddress($Currency_Id);

			$Search_Address = array_search($Crypto_Address,$Get_all_address);
			if(isset($Search_Address) && !empty($Search_Address)){
				$updateTableData = array('wallet_type'=>1,'wallet_type_cron'=>1);	    	
			}
			else{
				$updateTableData = array('wallet_type'=>0,'wallet_type_cron'=>1);
			}
			$this->common_model->updateTableData('transactions', array('trans_id' => $wallet_type->trans_id), $updateTableData);
			echo $wallet_type->trans_id." - success<br/>";
		}
	}
}

function markets()
{		 
	$this->load->library('session');
	$data['site_common'] = site_common();
	$Ip_Address = get_client_ip();
	$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'markets'))->row();
	$data['pairs'] = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
	$data['usdt_pair'] = $this->common_model->getTableData('trade_pairs',array('status'=>'1','to_symbol_id'=>3),'','','','','','', array('id', 'ASC'))->result();
	$data['currency_pair'] = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
	$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
	$data['currency_symbol'] = $this->common_model->getTableData('currency',array('status'=>'1'),'currency_symbol','','','','','', array('id', 'ASC'))->result();
	$data['currency_info'] = $this->common_model->getTableData('currency',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
	$data['favpairs'] = $this->common_model->getTableData('favoritepairs',array('user_ip'=>$Ip_Address),'','','','','','', array('id', 'ASC'))->result();
	$this->load->view('front/common/markets', $data);
}

public function get_pairinfo()
{
	$coin = $_POST['coin'];
	$sym = $this->common_model->getTableData('currency',array('status'=>'1','currency_symbol'=>$coin),'id')->row();

	// $data['currency_pair']=$this->db->query("select * from bidex_trade_pairs WHERE status='1' and from_symbol_id='".$sym->id."' OR to_symbol_id='".$sym->id."'")->result();


		$data['currency_pair']=$this->db->query("select * from bidex_trade_pairs WHERE status='1' and to_symbol_id='".$sym->id."'")->result();



	// $data['currency_pair'] = $this->common_model->getTableData('trade_pairs',array('status'=>'1','to_symbol_id'=>$sym->id),'','','','','','', array('id', 'ASC'))->result();

	if(count($data['currency_pair'])>0) {
		$this->load->view('front/common/tradepair_filter',$data);	
	} else {
		echo 0;
	}
	
}

function public_api(){

    $data['site_common'] = site_common();
	$this->load->view('front/common/public_api',$data);
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
              $check_currency = $this->common_model->customQuery("SELECT * from bidex_trade_pairs where status = 1 AND from_symbol_id = ".$curr->id." OR to_symbol_id = ".$curr->id."")->row();
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
                $newDataArray[] = $Insert_data;
              
              }
            
          }
			$post_data_assets = array('status' => '1', 'message'=>'success', 'assets' => ($newDataArray));
          }
   else
   {
    $post_data_assets = array ('status' => false,'error' => 'Incorrect symbol',);

   }
   
  echo json_encode($post_data_assets,true);

  }

  function market_api_depth($pair_symbol){
        $pair_id = getPair($pair_symbol)->id;
        $checkapi = checkapi($pair_id);
        $limit = ($_GET['depth']!='')?$_GET['depth']/2:0;
		$level = ($_GET['level']!='')?$_GET['level']:1000000;
		if($level == 2)$level = 4;
		if($level == 3)$level = 10000000;
        header('Content-Type: application/json');
        $data = array();
        if($checkapi=='1')
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
				$Api = $this->common_model->getdataapi($pair_id);
            }
            else
            {
				$Api = $this->common_model->getdataapi($pair_id, $limit);
            }

            if(count($Api)>0)
            {            
                $bids = array();
                foreach($Api as $key => $row)
                {
					if($level > $key){
						array_push($bids, array(trailingZeroes(numberFormatPrecision($row['Price'])),trailingZeroes(numberFormatPrecision($row['Amount']))));
					}
                }
                $data['bids'] = $bids;           
            }
            if($limit==0)
            {
				$Apis = $this->common_model->getdataapi($pair_id, 0, 'sell', 'asc');
            }
            else
            {
				$Apis = $this->common_model->getdataapi($pair_id, $limit, 'sell', 'asc');
            }
            
             if(count($Apis)>0)
             {
                $sells = array();
                foreach($Apis as $key => $row)
                {	
					if($level > $key){
						array_push($sells, array(trailingZeroes(numberFormatPrecision($row['Price'])),trailingZeroes(numberFormatPrecision($row['Amount']))));
					}
                }
                
                $data['asks'] = $sells;           
            }
        }
        
        if(count($Api)>0 || count($Apis)>0)
        {
        $lastUpdateId = strtotime(date("Y-m-d H:i:s"));
        $data['timestamp'] = $lastUpdateId;
			
		$post_data_orderbook = array('status' => '1', 'data' => ($data));

        echo json_encode($post_data_orderbook,true);
        }
        else
        {
        $data['response'] = "No orders found";

        echo json_encode($data,true);
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
						$Insert_data['trading_pairs'] 		= $Api->symbol;
						$Insert_data['base_currency']		= $symbol[0];
						$Insert_data['quote_currency']		= $symbol[1];
						$Insert_data['last_price']		= trailingZeroes(numberFormatPrecision($Api->last_price));
						$Insert_data['lowest_ask'] 		= trailingZeroes(numberFormatPrecision($Api->ask_price));
						$Insert_data['highest_bid'] 	= trailingZeroes(numberFormatPrecision($Api->bid_price));
						$Insert_data['base_volume'] 	= trailingZeroes(numberFormatPrecision($Api->volume));
						$Insert_data['quote_volume'] 	= trailingZeroes(numberFormatPrecision($Api->last_price)) * trailingZeroes(numberFormatPrecision($Api->volume));
						$Insert_data['price_change_percent_24h'] 	= trailingZeroes(numberFormatPrecision($Api->price_change_percent));
						$Insert_data['highest_price_24h'] 	= trailingZeroes(numberFormatPrecision($Api->high_price));
						$Insert_data['lowest_price_24h'] 	= trailingZeroes(numberFormatPrecision($Api->low_price));

						$newDataArray[] = $Insert_data;
	$post_data_allticker = array('status' => '1', 'markets' => ($newDataArray));

					}
		
	echo json_encode($post_data_allticker,true);


	}

	function trades($pair_symbol){

		header('Content-Type: application/json');
  $sym_array = explode("_",$pair_symbol);
  $symbol = $sym_array[0]."/".$sym_array[1];
   $order_list = $this->common_model->gettrades($symbol);
	
   if(count($order_list)>0)
   {
	   $price = $base_volume = $quote_volume1 = $timestamp = 0;
    foreach($order_list as $list){	
		$quote_volume = $list['Amount'] * $list['Price'];
		
		/*if($_SERVER['REMOTE_ADDR'] == '91.103.249.72'){
			echo $price .'!='. trailingZeroes(numberFormatPrecision($list['Price'])).'<br>';
			echo $base_volume .'!='. trailingZeroes(numberFormatPrecision($list['Amount'])).'<br>';
			echo $quote_volume1 .'!='. trailingZeroes(numberFormatPrecision($quote_volume)).'<br>';
			echo $timestamp .'!='. strtotime($list['datetime']).'<hr>';
		}*/
		
		if($price != trailingZeroes(numberFormatPrecision($list['Price'])) || $base_volume != trailingZeroes(numberFormatPrecision($list['Amount'])) || $quote_volume1 != trailingZeroes(numberFormatPrecision($quote_volume)) || $timestamp != strtotime($list['datetime'])){		
            $Insert_data['trade_id']    = $list['trade_id'];
            $Insert_data['price']    = trailingZeroes(numberFormatPrecision($list['Price']));
            $Insert_data['base_volume']    =  trailingZeroes(numberFormatPrecision($list['Amount']));
            $Insert_data['quote_volume']     = trailingZeroes(numberFormatPrecision($quote_volume));
            $Insert_data['timestamp']    = strtotime($list['datetime']);
            $Insert_data['type']     = $list['Type'];
            $newDataArray[] = $Insert_data;
		}
		
		$price = trailingZeroes(numberFormatPrecision($list['Price']));
		$base_volume = trailingZeroes(numberFormatPrecision($list['Amount']));
		$quote_volume1 = trailingZeroes(numberFormatPrecision($quote_volume));
		$timestamp = strtotime($list['datetime']);
		
      }
    $post_data_trades = array('status' => '200', 'message'=>'success', 'data' => ($newDataArray));

   }
   else
   {
    $post_data_trades = array ('status' => false,'error' => 'Incorrect pair',);

   }
   
	/*if($_SERVER['REMOTE_ADDR'] == '91.103.249.72'){
		echo '<pre>';
		print_r($post_data_trades);
		echo '</pre>';
	}*/
	
  echo json_encode($post_data_trades,true);

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
            $Insert_data['quote_volume']     = trailingZeroes(numberFormatPrecision($market_data->volume * $market_data->last_price));

            if($pair->status=='1'){
                $Status = 0;
            }
            else{
                $Status = 1;
            }
            $Insert_data['isFrozen']    = $Status;
            $newDataArray[$market_data->symbol] = $Insert_data;
      }
			$post_data_ticker = array('status' => '1', 'message'=>'success', 'tickers' => ($newDataArray));
   }
      else
   {
    $post_data_ticker = array ('status' => false,'error' => 'Incorrect pair',);

   }
   
  echo json_encode($post_data_ticker,true);

  }


function test_email(){
	 $this->load->library('email');
	   		$config['protocol']    = 'smtp';
        $config['smtp_host']    = 'smtpout.secureserver.net';
        $config['smtp_port']    = '465';
        // $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'support@bidexcrypto.com';
        $config['smtp_pass']    = 'MGTHEkr3HNRq83xs';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'text'; // or html
        $config['smtp_crypto'] = 'ssl';
        // $config['validation'] = TRUE; // bool whether to validate email or not    

		// 	'protocol' => 'smtp',
		// 	'smtp_host' => 'smtpout.secureserver.net',
		// 	'smtp_port' => 465,
		// 	'smtp_user' => 'support@bidexcrypto.com',
		// 	'smtp_pass' => 'MGTHEkr3HNRq83xs',
		// 	'mailtype' => 'html',
		// 	'smtp_crypto' => 'ssl',
		// 	'charset' => 'utf-8',
		// 	'wordwrap' =>TRUE


        $this->email->initialize($config);

        $this->email->from('support@bidexcrypto.com', 'Testing');
        $this->email->to('muthappan3.muthuvelan@gmail.com'); 

        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');  

        $this->email->send();

        echo $this->email->print_debugger();
}

function tester() {

	// User Deposit
	// $User_dep_data = array('user_id'    => 110,
	// 				'currency_id'   	=> 1,
	// 				'type'       		=> "Deposit",
	// 				'currency_type'		=> "crypto",
	// 				'description'		=> "Bitcoin Payment",
	// 				'amount'     		=> 2.56,
	// 				'transfer_amount'	=> 2.56,
	// 				'information'		=> '',
	// 				'wallet_txid'       => '',
	// 				'crypto_address'	=> 'bc1qe90km6ezcecvp4zk2puuel8xts7a0zf9ces9wj',
	// 				'status'     		=> "Completed",
	// 				'datetime' 			=> date("Y-m-d H:i:s"),
	// 				'user_status'		=> "Completed",
	// 				'crypto_type'       => 'BTC',
	// 				'transaction_id'	=> rand(100000000,10000000000)
	// 			);
	

	// User Withdraw
	// $User_dep_data = array('user_id'    => 110,
	// 				'currency_id'   	=> 1,
	// 				'type'       		=> "Withdraw",
	// 				'currency_type'		=> "crypto",
	// 				'description'		=> "",
	// 				'amount'     		=> 2.56,
	// 				'transfer_amount'	=> 2.56,
	// 				'information'		=> '',
	// 				'wallet_txid'       => '',
	// 				'crypto_address'	=> '12n7wG8RvGaxsS6H7XebwTtvZVCQkkBxR3',
	// 				'status'     		=> "Completed",
	// 				'datetime' 			=> date("Y-m-d H:i:s"),
	// 				'user_status'		=> "Completed",
	// 				'crypto_type'       => 'BTC',
	// 				'transaction_id'	=> rand(100000000,10000000000)
	// 			);
	// echo "<pre>";print_r($User_dep_data); 
	// echo $ins_id = $this->common_model->insertTableData('transactions',$User_dep_data);
	die;
	
	// Admin Transactions
	// $dep_data = array( 'user_id'=>1,
	// 					'type'=>'Withdraw',
	// 					'amount'=>0.14088,
	// 					'crypto_address'=>'bc1qjnh7gnvw4zk4zwljy63qt0l2a2jp22acf6sjh5',
	// 					'currency_name'=>'BTC',
	// 					'currency_id'=>1,
	// 					'status'=>'Completed',
	// 					'description'=>'Withdraw',
	// 					'datetime'=>strtotime(date("Y-m-d H:i:s"))
	// 				);

	// echo "<pre>";	print_r($dep_data); 
	// echo $ins_id = $this->common_model->insertTableData('admin_transactions',$dep_data);
	die;

	$users = $this->common_model->getTableData('users','','id,verified','','','',3433,3681,array('id','asc'))->result();
	// $users = $this->common_model->getTableData('users',array('verified'=>0),'id,verified','','','','','',array('id','asc'))->result();
	foreach ($users as $key => $user) {
		
		$user_id = $user->id;

		$updateVerified = array('verified'=>1);
		// $this->common_model->updateTableData('users', array('id' => $user_id), $updateVerified);
		

		$getBTCBalance=getBalance($user_id, 1, '8');
		$getETHBalance=getBalance($user_id, 2, '8');

        $finalBTCBalance = $getBTCBalance + '0.01030';
        $finalETHBalance = $getETHBalance + '0.1428';

        // $updateBTCbalance = updateBalance($user_id, 1, $finalBTCBalance,'crypto');
        // $updateETHbalance = updateBalance($user_id, 2, $finalETHBalance,'crypto');

		// echo $user_id.'-- BTC '.$getBTCBalance.'-- ETH '.$getETHBalance;
		// echo "<br>";
	}
	// echo "<pre>";print_r($users);
}


    
}