<?php
	// FINAL - Trade Library - Created by Pilaventhiran

class Tradelib 
{
    private $ci;
	function __construct()
	{
		$this->ci =& get_instance();
		//$this->api     = new Poloniex();
		//$this->lending_api     = new Lendinglib();
	}

	// function createOrder($user_id,$amount,$price=0,$limit_price=0,$total,$fee,$pair_id,$ordertype,$type,$loan_rate='',$pagetype='',$reorder=0)
	// {
	// 	$response = array('web_trade'=>3,'status'=>'','msg'=>'');

	// 	$response['user_id'] = $user_id;
	// 	$response['amount'] = $amount;		
	// 	$response['price'] = $price;
	// 	$response['limit_price'] = $limit_price;
	// 	$response['total'] = $total;
	// 	$response['pair_id'] = $pair_id;
	// 	$response['ordertype'] = $ordertype;
	// 	$response['type'] = $type;
	// 	$response['loan_rate'] = $loan_rate;
	// 	$response['pagetype'] = $pagetype;
	// 	$response['reorder'] = $reorder;

	// 	// return $response;exit;
		
	// 	$pair_details = $this->ci->common_model->getTableData('trade_pairs',array('id'=>$pair_id),'from_symbol_id,to_symbol_id,min_trade_amount');
	// 	// print_r($pair_details);exit;

	// 	$this->trade_prices($pair_id,$user_id);
		
	// 	$calculate_price= $this->price_calculation($ordertype,$type,$amount,$price);
	// 	$total    		= $calculate_price->tot;
	// 	$fees 			= $calculate_price->fees;
	// 	$fee 			= $calculate_price->fee;
	// 	$price 			= $calculate_price->price;
		
	// 	if($total < $pair_details->row('min_trade_amount'))
	// 	{
	// 		$response['status'] = "minimum_amount"; 
	// 		return $response; 
	// 	} 

	// 	$response = array('web_trade'=>3,'type'=>$type);

	// 	if($type == "buy")
	// 	{
	// 		$currency = $pair_details->row('to_symbol_id');
	// 		// return $currency;
	// 	}
	// 	else
	// 	{
	// 		$currency = $pair_details->row('from_symbol_id');
	// 	} 

	// 	if($amount==0 || $price==0 || $amount=="" || $price=="")
	// 	{   
	// 		$response['status'] = "balance";
	// 		// return $response;
	// 	}
	// 	else
	// 	{
	// 		if($reorder==1)
	// 		{
	// 			if($type == "buy")
	// 			{
	// 				$balance = $total;
	// 			}
	// 			else
	// 			{
	// 				$balance = $amount;
	// 			}
	// 		}
	// 		else
	// 		{
	// 			if($pagetype=='margin')
	// 			{
	// 				$balance = tradable_balance($user_id,$currency);//getBalance($user_id,$currency,'crypto','Margin Trading');
	// 			}
	// 			else
	// 			{
	// 				$balance = getBalance($user_id,$currency);
	// 			}
	// 		}

	// 		$response['currency'] = $currency;
	// 		// return $response['currency'];

	// 		$balance_new = $balance;

	// 		$response['balance'] = $balance_new;

	// 		$response['pair_details'] = $pair_details->row('min_trade_amount');


	// 		if(($total <= $balance && $type == "buy")||($amount <= $balance && $type == "sell"))
	// 		{
	// 			if($pagetype=='margin' && $reorder!=1)
	// 			{
	// 				if($type == "buy")
	// 				{
	// 					$margin_amount    = $total;
	// 				}
	// 				else
	// 				{
	// 					$margin_amount    = $amount;
	// 				}
	// 				$range       = 2;
	// 				$rate        = $loan_rate;
	// 				$auto_renew  = 0;
	// 				$array=$this->lending_api->create_swap($user_id,$currency,$margin_amount,$range,$rate,$auto_renew,'receive');
	// 				if($array['status']=='error')
	// 				{
	// 					$response['status'] = $array['msg']; 
	// 					// return $response; exit;
	// 				}
	// 				else
	// 				{
	// 					$swap_id=$array['swap_id'];
	// 				}
	// 			}

	// 			$current_date           =   date('Y-m-d');
	// 			$current_time           =   date('H:i A');
	// 			if($pagetype=='margin')
	// 			{
	// 				if($reorder==1)
	// 				{
	// 					$status= "active"; 
	// 				}
	// 				else
	// 				{
	// 					$status= "margin"; 
	// 				}
	// 				$wallet='Margin Trading';
	// 			}
	// 			else
	// 			{
	// 				if($ordertype=='stop')
	// 				{
	// 					$status         = "stoporder"; 
	// 				}
	// 				else
	// 				{
	// 					$status         = "active";
	// 				}
	// 				$wallet='Exchange AND Trading';
	// 				// return $status;exit;
	// 			}
	// 			// print_r($status);exit;
	// 			if($pagetype!='margin')
	// 			{
	// 				if($type == "buy")
	// 				{

	// 					$Balance    = $balance - $total;
	// 				}
	// 				else
	// 				{
	// 					$Balance    = $balance - $amount;
	// 				}

	// 				$updatequery = updateBalance($user_id,$currency,$Balance);
	// 			}
	// 			else
	// 			{
	// 				$updatequery = 1;
	// 			}
	// 			if($updatequery)
	// 			{
	// 				$micro_date = microtime();
	// 				$date_array = explode(" ",$micro_date);
	// 				$date = date("Y-m-d H:i:s",$date_array[1]);
	// 				$microtime = $date."_".$date_array[0];
	// 				$datetime   =date("Y-m-d H:i:s");
	// 				$updated_on = strtotime(date('Y-m-d H:i:s'));
 //                    $pair_symbol = getpairssymbol($pair_id);
	// 				if($ordertype=='stop')
	// 				{
	// 					$data    =   array(
	// 								'userId'		=>$user_id,
	// 								'Amount'		=>$amount,
	// 								'stoporderprice'=>$price,
	// 								'limit_price' => $limit_price,
	// 								'ordertype'		=>$ordertype,
	// 								'Fee'			=>$fees,
	// 								'Total'			=>$total,
	// 								'Price'			=>$price,
	// 								'Type'			=>$type,
	// 								'orderDate'		=>$current_date,
	// 								'orderTime'		=>$current_time,
	// 								'datetime'		=>$datetime,
	// 								'tradetime'		=>$datetime,
	// 								'pair'			=>$pair_id,
	// 								'pair_symbol'   =>$pair_symbol,
	// 								'status'		=>$status,
	// 								'fee_per'		=>$fee,
	// 								'wallet'		=>$wallet,
	// 								'updated_on'	=>$updated_on
	// 								);
	// 				}
	// 				else
	// 				{
	// 					$data   =   array(
	// 								'userId'	=>$user_id,
	// 								'Amount'	=>$amount,
	// 								'ordertype'	=>$ordertype,
	// 								'Fee'		=>$fees,
	// 								'Total'		=>$total,
	// 								'Price'		=>$price,
	// 								'Type'		=>$type,
	// 								'orderDate'	=>$current_date,
	// 								'orderTime'	=>$current_time,
	// 								'datetime'	=>$datetime,
	// 								'tradetime'	=>$datetime,
	// 								'pair'		=>$pair_id,
	// 								'pair_symbol' =>$pair_symbol,
	// 								'status'	=>$status,
	// 								'fee_per'	=>$fee,
	// 								'wallet'	=>$wallet,
	// 								'updated_on'	=>$updated_on
	// 								); 
	// 				}

	// 				$dup_coin_order = $this->ci->common_model->getTableData('coin_order',array('updated_on'=>$updated_on))->num_rows();

	// 				/*$get_counts = $this->ci->common_model->getTableData('coin_order',array('userId'=>$user_id,'Amount'=> $amount,'ordertype'=>$ordertype,'Fee'=> $fees,'Total'=>$total,'Price'=>$price,'Type'=>$type,'orderDate'=>$current_date,'orderTime'=>$current_time,'datetime'=>$datetime,'tradetime'=>$datetime,'pair'=>$pair_id,'status'=>$status,'fee_per'=>$fee,'wallet'=>$wallet))->num_rows();*/


	// 				if($dup_coin_order==0)
	// 				{
	// 					$insid=$this->ci->common_model->insertTableData('coin_order', $data);

	// 					// send mail start
	// 					$email_template = ucfirst($type);
	// 					$user 		= getUserDetails($user_id);
	// 					$prefix 	= get_prefix();
	// 					$usernames 	= $prefix.'username';
	// 					$username 	= $user->$usernames;
	// 					$email 	= getUserEmail($user_id);
	// 					$pair_details = $this->ci->common_model->getTableData('trade_pairs',array('id'=>$pair_id))->row();
	// 					$currency_name = getcryptocurrency($pair_details->from_symbol_id);
	// 					$site_common      =   site_common();
			            

	// 					$special_vars = array(					
	// 					'###USERNAME###' => $username,
	// 					'###AMOUNT###' => number_format($amount,8),
	// 					'###CURRENCY###' => $currency_name
	// 					);
	// 					$this->ci->email_model->sendMail($email, '', '', $email_template, $special_vars);
	// 					// send mail end
	// 				}
					
	// 				if($pagetype=='margin'&& $reorder!=1)
	// 				{
	// 					$this->ci->common_model->updateTableData('swap_order',array('swap_id'=>$swap_id),array('margin_order'=>$insid));
	// 					$this->lending_api->swap_mapping($swap_id);
	// 				}
	// 				$response['status'] = $this->mapping($insid);
	// 				$x = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$insid))->row();
	// 				if($type == "buy")
	// 				{
	// 					$ordertype_res = 'buyorderId';
	// 				}
	// 				else
	// 				{
	// 					$ordertype_res = 'sellorderId';
	// 				}
	// 				if($pagetype!='margin'||$reorder==1)
	// 				{
	// 					$remarket=getSiteSettings('remarket_concept');
	// 					if($remarket==1)
	// 					{
	// 						$this->integrate_remarket($insid);
	// 					}
	// 				}
	// 				$Sumamount 		= $this->checkOrdertemp($insid,$ordertype_res);
	// 				if($Sumamount)
	// 				{
	// 					$x->filledAmount=$Sumamount;
	// 				}
	// 				else
	// 				{
	// 					$x->filledAmount=0;
	// 				}
	// 				$response['msg']=$x;
	// 			}
	// 			else
	// 			{
	// 				$response['status'] = "balance";
	// 			}
	// 		}
	// 		else
	// 		{
	// 			$response['status'] = "balance";
	// 		}
	// 	}
	// 	return $response;
	// }
	function createOrder($user_id,$amount,$price=0,$limit_price=0,$total,$fee,$pair_id,$ordertype,$type,$loan_rate='',$pagetype='',$reorder=0)
	{
		$response = array('web_trade'=>3,'status'=>'','msg'=>'');

		$response['user_id'] = $user_id;
		$response['amount'] = $amount;		
		$response['price'] = $price;
		$response['limit_price'] = $limit_price;
		$response['total'] = $total;
		$response['pair_id'] = $pair_id;
		$response['ordertype'] = $ordertype;
		$response['type'] = $type;
		$response['loan_rate'] = $loan_rate;
		$response['pagetype'] = $pagetype;
		$response['reorder'] = $reorder;
		$fin_tot = $total;

		// return $response;
		// print_r($response['price']);echo "<br>";
		// print_r($response['amount']);echo "<br>";
		
		$pair_details = $this->ci->common_model->getTableData('trade_pairs',array('id'=>$pair_id),'from_symbol_id,to_symbol_id,min_trade_amount');

		$this->trade_prices($pair_id,$user_id);
		
		$calculate_price= $this->price_calculation($ordertype,$type,$amount,$price);
		$total    		= $calculate_price->tot;
		$fees 			= $calculate_price->fees;
		$fee 			= $calculate_price->fee;
		$price 			= $calculate_price->price;
		// echo $total;
		// echo "<br>";
		// echo $price;exit;
		if($total < $pair_details->row('min_trade_amount'))
		{
			$response['status'] = "minimum_amount"; 
			return $response; 
		}

		$response = array('web_trade'=>3,'type'=>$type);
		// print_r($response);exit;

		if($type == "buy")
		{
			$currency = $pair_details->row('to_symbol_id');
		}
		else
		{
			$currency = $pair_details->row('from_symbol_id');
		}

		if($amount==0 || $price==0 || $amount=="" || $price=="")
		{   
			$response['status'] = "balance";
		}
		else
		{
			if($reorder==1)
			{
				if($type == "buy")
				{
					$balance = $total;
				}
				else
				{
					$balance = $amount;
				}
			}
			else
			{
				if($pagetype=='margin')
				{
					$balance = tradable_balance($user_id,$currency);//getBalance($user_id,$currency,'crypto','Margin Trading');
				}
				else
				{
					$balance = getBalance($user_id,$currency);
				}
			}

			$response['currency'] = $currency;

			$balance_new = $balance;

			$response['balance'] = $balance_new;

			$response['pair_details'] = $pair_details->row('min_trade_amount');


			if(($total <= $balance && $type == "buy")||($amount <= $balance && $type == "sell"))
			{
				if($pagetype=='margin' && $reorder!=1)
				{
					if($type == "buy")
					{
						$margin_amount    = $total;
					}
					else
					{
						$margin_amount    = $amount;
					}
					$range       = 2;
					$rate        = $loan_rate;
					$auto_renew  = 0;
					$array=$this->lending_api->create_swap($user_id,$currency,$margin_amount,$range,$rate,$auto_renew,'receive');
					if($array['status']=='error')
					{
						$response['status'] = $array['msg']; 
						return $response; exit;
					}
					else
					{
						$swap_id=$array['swap_id'];
					}
				}

				$current_date           =   date('Y-m-d');
				$current_time           =   date('H:i A');
				if($pagetype=='margin')
				{
					if($reorder==1)
					{
						$status= "active"; 
					}
					else
					{
						$status= "margin"; 
					}
					$wallet='Margin Trading';
				}
				else
				{
					if($ordertype=='stop')
					{
						$status         = "stoporder"; 
					}
					else
					{
						$status         = "active";
					}
					$wallet='Exchange AND Trading';
				}
				if($pagetype!='margin')
				{
					if($type == "buy")
					{
                         
						//$Balance    = $balance - $total-$fees;
						$Balance    = $balance - $total;   
						
						//echo "BUY Details -> (Balance) ".$balance." (Total) ".$total." (Fees) ".$fees." FINAL BALANCE -> ".$Balance; 

					}
					else
					{
						$Balance    = $balance - $amount;  
					}

					$updatequery = 1;

					// if($user_id==1815)
					// {    
						


					// 	if($type == "buy")
					// 	{
	
					// 		$Balance    = $balance - $total;  
					// 	}
					// 	else
					// 	{
					// 		$Balance    = $balance - $amount;  
					// 	}

						
					// 	$updatequery = updateBalance(1815,$currency,$Balance);
					// }
					// else
					// {
					// 	// echo " Pre Bal ".$balance." After  ".$Balance;

					//  //    $updatequery = updateBalance($user_id,$currency,$Balance);
					// }
				}
				else
				{
					$updatequery = 1;
				}
				if($updatequery)
				{
					$micro_date = microtime();
					$date_array = explode(" ",$micro_date);
					$date = date("Y-m-d H:i:s",$date_array[1]);
					$microtime = $date."_".$date_array[0];
					$datetime   =date("Y-m-d H:i:s");
					$updated_on = strtotime(date('Y-m-d H:i:s'));
                    $pair_symbol = getpairssymbol($pair_id);
					if($ordertype=='stop')
					{
						$data    =   array(
									'userId'		=>$user_id,
									'Amount'		=>$amount,
									'stoporderprice'=>$price,
									'limit_price' => $limit_price,
									'ordertype'		=>$ordertype,
									'Fee'			=>$fees,
									'Total'			=>$total,
									'Price'			=>$price,
									'Type'			=>$type,
									'orderDate'		=>$current_date,
									'orderTime'		=>$current_time,
									'datetime'		=>$datetime,
									'tradetime'		=>$datetime,
									'pair'			=>$pair_id,
									'pair_symbol'   =>$pair_symbol,
									'status'		=>$status,
									'fee_per'		=>$fee,
									'wallet'		=>$wallet,
									'updated_on'	=>$updated_on,
									'fin_total'     => $fin_tot
									);
					}
					else
					{
						$data   =   array(
									'userId'	=>$user_id,
									'Amount'	=>$amount,
									'ordertype'	=>$ordertype,
									'Fee'		=>$fees,
									'Total'		=>$total,
									'Price'		=>$price,
									'Type'		=>$type,
									'orderDate'	=>$current_date,
									'orderTime'	=>$current_time,
									'datetime'	=>$datetime,
									'tradetime'	=>$datetime,
									'pair'		=>$pair_id,
									'pair_symbol' =>$pair_symbol,
									'status'	=>$status,
									'fee_per'	=>$fee,
									'wallet'	 =>$wallet,
									'updated_on' =>$updated_on,
									'fin_total'  => $fin_tot
									); 
					}
 
					$dup_coin_order = $this->ci->common_model->getTableData('coin_order',array('updated_on'=>$updated_on))->num_rows();

					/*$get_counts = $this->ci->common_model->getTableData('coin_order',array('userId'=>$user_id,'Amount'=> $amount,'ordertype'=>$ordertype,'Fee'=> $fees,'Total'=>$total,'Price'=>$price,'Type'=>$type,'orderDate'=>$current_date,'orderTime'=>$current_time,'datetime'=>$datetime,'tradetime'=>$datetime,'pair'=>$pair_id,'status'=>$status,'fee_per'=>$fee,'wallet'=>$wallet))->num_rows();*/


					if($dup_coin_order==0)
					{
						$insid=$this->ci->common_model->insertTableData('coin_order', $data);

							if($insid)
							{
								// echo " Pre Bal ".$balance." After  ".$Balance." User ID -> ( ".$user_id." )";
								$updatequery = updateBalance($user_id,$currency,$Balance);



							}



						// send mail start
						$email_template = ucfirst($type);
						$user 		= getUserDetails($user_id);
						$prefix 	= get_prefix();
						$usernames 	= $prefix.'username';
						$username 	= $user->$usernames;
						$email 	= getUserEmail($user_id);
						$pair_details = $this->ci->common_model->getTableData('trade_pairs',array('id'=>$pair_id))->row();
						$currency_name = getcryptocurrency($pair_details->from_symbol_id);
						$site_common      =   site_common();
			            

						$special_vars = array(					
						'###USERNAME###' => $username,
						'###AMOUNT###' => number_format($amount,8),
						'###CURRENCY###' => $currency_name
						);
						$this->ci->email_model->sendMail($email, '', '', $email_template, $special_vars);
						// send mail end
					}
					
					if($pagetype=='margin'&& $reorder!=1)
					{
						$this->ci->common_model->updateTableData('swap_order',array('swap_id'=>$swap_id),array('margin_order'=>$insid));
						$this->lending_api->swap_mapping($swap_id);
					}
					$response['status'] = $this->mapping($insid);
					$response['from_currency'] = getBalance($user_id,$pair_details->from_symbol_id);
					$response['to_currency'] = getBalance($user_id,$pair_details->to_symbol_id);
					$response['from_symbol'] = getcryptocurrency($pair_details->from_symbol_id);
					$response['to_symbol'] = getcryptocurrency($pair_details->to_symbol_id);
					$x = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$insid))->row();
					if($type == "buy")
					{
						$ordertype_res = 'buyorderId';
					}
					else
					{
						$ordertype_res = 'sellorderId';
					}
					if($pagetype!='margin'||$reorder==1)
					{
						$remarket=getSiteSettings('remarket_concept');
						if($remarket==1)
						{
							$this->integrate_remarket($insid);
						}
					}
					$Sumamount 		= $this->checkOrdertemp($insid,$ordertype_res);
					if($Sumamount)
					{
						$x->filledAmount=$Sumamount;
					}
					else
					{
						$x->filledAmount=0;
					}
					$response['msg']=$x;
				}
				else
				{
					$response['status'] = "balance";
				}
			}
			else
			{
				$response['status'] = "balance";
			}
		}
		return $response;
	}

function trade_prices($pair,$user_id='')
{
	$this->marketprice = marketprice($pair);
	$this->lastmarketprice = lastmarketprice($pair);
	$this->minimum_trade_amount = get_min_trade_amt($pair);
	$this->maker=getfeedetails_buy($pair);
	$this->taker=getfeedetails_sell($pair);
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
function price_calculation($order_type,$a,$amount,$price)
{
	$maker_fee=$this->maker;
	$taker_fee=$this->taker;
/*	if($order_type=='instant')
	{
		$liquidity = $this->ci->common_model->getTableData('site_settings',array('id'=>1),'liquidity_concept')->row('liquidity_concept');
		if($a=='buy')
		{
			if($liquidity!=1)
			{
				$price=$this->lowestaskprice;
			}
			$tot   = floatval($amount)*floatval($this->lowestaskprice);
			$fees  = floatval($amount)*floatval($this->lowestaskprice)*($maker_fee/100);
			$fee=$maker_fee;
			if($tot>0)
			{
				$tot = $tot+$fees;
			}
			else
			{
				$tot = 0;
			}
		}
		else
		{
			if($liquidity!=1)
			{
				$price=$this->highestbidprice;
			}
			$tot   = floatval($amount)*floatval($this->highestbidprice);
			$fees  = floatval($amount)*floatval($this->highestbidprice)*($taker_fee/100);
			$fee=$taker_fee;
			if($tot>0)
			{
				$tot = $tot-$fees;
			}
			else
			{
				$tot = 0;
			}
		}
	}*/
	/*else
	{*/
		if($a=='buy')
		{
			$tot   = floatval($amount)*floatval($price);
			$fees  = floatval($amount)*floatval($price)*($maker_fee/100);
			$fee=$maker_fee;
			if($tot>0)
			{
				// $tot = $tot+$fees;
				$tot = $tot;
			}
			else
			{
				$tot = 0;
			}
		}
		else
		{
			$tot   = floatval($amount)*floatval($price);
			$fees  = floatval($amount)*floatval($price)*($taker_fee/100);
			$fee=$taker_fee;
			if($tot>0)
			{
				$tot = $tot-$fees;
			}
			else
			{
				$tot = 0;
			}
		}
	//}
		// echo $tot;exit;
	$x = new stdClass();
	$x->tot =$tot;
	$x->fees =$fees;
	$x->fee =$fee;
	$x->price =$price;
	return $x;
}
function mapping($res)
{
	$buy = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$res))->row();
	$pair_id=$buy->pair;
	$this->check_stop_order($pair_id,$res);
	$this->initialize_mapping($res);
	//$this->check_stop_order($pair_id,$res);
	return "success";
}
function initialize_mapping($res)
{
	$names = array('active', 'partially');
	$where_in=array('status', $names);
	$buy = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$res),'','','','','','','','','',$where_in)->row();
	if($buy)
	{
		$pair_id=$buy->pair;
		if($buy->Type=='buy')
		{
			$final="";
			$buyorderId         = 	$buy->trade_id; 
			$buyuserId          = 	$buy->userId;
			$buyPrice           = 	$buy->Price;
			$buyOrertype        = 	$buy->ordertype;
			$buyPrice           = 	(float)$buyPrice;
			$buyAmount          = 	(float)$buy->Amount;
			$pair   			= 	$buy->pair;
			$buyWallet 			=	$buy->wallet;
			$Total				=	$buy->Total;
			$Fee				=	$buy->Fee;
			$fetchsellRecords 	= 	$this->getParticularsellorders($buyPrice,$buyuserId,$pair,$buyOrertype);
			if($fetchsellRecords)
			{
				$pair_details = $this->ci->common_model->getTableData('trade_pairs',array('id'=>$pair),'from_symbol_id,to_symbol_id')->row();
				$k=0;
				foreach($fetchsellRecords as $sell)
				{
					$k++;
					$sellorderId        = $sell->trade_id;
					$selluserId         = $sell->userId;
					$sellPrice          = $sell->Price;
					$sellOrdertype      = $sell->ordertype;
					$sellAmount         = $sell->Amount;
					$sellWallet        	= $sell->wallet;
					$pair   			= $sell->pair;
					$sellstatus  		= $sell->status;
					$Total1				= $sell->Total;
					$Fee1				= $sell->Fee;
					$sellSumamount 		= $this->checkOrdertemp($sellorderId,'sellorderId');
					if($sellSumamount)
					{
						$approxiAmount = $sellAmount-$sellSumamount;
						$approxiAmount=number_format($approxiAmount,8,'.','');
					}
					else
					{
						$approxiAmount = $sellAmount;
					}
					$buySumamount      = $this->checkOrdertemp($buyorderId,'buyorderId');
					if($buySumamount)
					{
						$buySumamount = $buyAmount-$buySumamount;
						$buySumamount=number_format($buySumamount,8,'.','');
					}
					else
					{
						$buySumamount = $buyAmount;
					}
					if($approxiAmount >= $buySumamount)
					{
						$amount = $buySumamount;
					}
					else
					{
						$amount = $approxiAmount;
					}
					if($approxiAmount!=0&&$buySumamount!=0)
					{
						$date               =   date('Y-m-d');
						$time               =   date("H:i:s");
						$datetime           =   date("Y-m-d H:i:s");
						$data               =   array(
												'sellorderId'       =>  $sellorderId,
												'sellerUserid'      =>  $selluserId,
												'askAmount'         =>  $sellAmount,
												'askPrice'          =>  $sellPrice,
												'filledAmount'      =>  $amount,
												'buyorderId'        =>  $buyorderId,
												'buyerUserid'       =>  $buyuserId,
												'sellerStatus'      =>  "inactive",
												'buyerStatus'       =>  "inactive",
												"pair"              =>  $pair,
												"datetime"          =>  $datetime
												);
						$inserted=$this->ci->common_model->insertTableData('ordertemp', $data);
						$theftprice=0;
						if($inserted)
						{
							if($buyPrice>$sellPrice)
							{
								$price1=$buyPrice-$sellPrice;
								$theftprice=$buyAmount*$price1;
								$theftdata   = array(
										'userId'        =>  $buyuserId,
										'theftAmount'   =>  $theftprice,
										'theftCurrency' =>  $pair_details->to_symbol_id,
										'date'          =>  $date,
										'time'          =>  $time,
										'theftOrderId'  =>  $buyorderId
										);
								$this->ci->common_model->insertTableData('coin_theft', $theftdata);
								/*$user_buy_bal            = getBalance($buyuserId,$pair_details->to_symbol_id);
								$buy_bal       =   $user_buy_bal+$theftprice;
								updateBalance($buyuserId,$pair_details->to_symbol_id,$buy_bal);*/
							}
							/*elseif($sellPrice>$buyPrice)
							{
								$price1=$sellPrice-$buyPrice;
								$theftprice1=$sellAmount*$price1;
								$theftdata   = array(
										'userId'        =>  $selluserId,
										'theftAmount'   =>  $theftprice,
										'theftCurrency' =>  $pair_details->to_symbol_id,
										'date'          =>  $date,
										'time'          =>  $time,
										'theftOrderId'  =>  $sellorderId
										);
								$this->ci->common_model->insertTableData('coin_theft', $theftdata);
								/*$user_sell_bal            = getBalance($buyuserId,$pair_details->to_symbol_id);
								$sell_bal       =   $user_sell_bal+$theftprice;
								updateBalance($buyuserId,$pair_details->to_symbol_id,$sell_bal);
							}*/
							if(trim($approxiAmount)==trim($amount))
							{
								$this->ordercompletetype($sellorderId,"sell",$inserted);
								$trans_data = array(
								'userId'=>$selluserId,
								'type'=>'Sell',
								'currency'=>$pair_details->to_symbol_id,
								'amount'=>$Total1+$Fee1,
								'profit_amount'=>$Fee1,
								'comment'=>'Trade Sell order #'.$sellorderId,
								'datetime'=>date('Y-m-d h:i:s'),
								'currency_type'=>'crypto'
								);
								$update_trans = $this->ci->common_model->insertTableData('transaction_history',$trans_data);
							}
							else
							{
								$this->orderpartialtype($sellorderId,"sell",$inserted);
								$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$sellorderId),array('status'=>"partially",'tradetime'=>date('Y-m-d H:i:s')));
							}
							$this->integrate_remarket($sellorderId);
							if((trim($approxiAmount)==trim($buySumamount))||($approxiAmount>$buySumamount))
							{
								$this->ordercompletetype($buyorderId,"buy",$inserted);
								$trans_data = array(
								'userId'=>$buyuserId,
								'type'=>'Buy',
								'currency'=>$pair_details->to_symbol_id,
								'amount'=>$Total,
								'profit_amount'=>$Fee,
								'comment'=>'Trade Buy order #'.$buyorderId,
								'datetime'=>date('Y-m-d h:i:s'),
								'currency_type'=>'crypto',
								'bonus_amount'=>$theftprice
								);
								$update_trans = $this->ci->common_model->insertTableData('transaction_history',$trans_data);
							}
							else
							{
								$this->orderpartialtype($buyorderId,"buy",$inserted);
								$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$buyorderId),array('status'=>"partially",'tradetime'=>date('Y-m-d H:i:s')));
							}
						}
					}
					else
					{
						break;
					}
				} 
			}
		}
		else if($buy->Type=='sell')
		{
			$sell=$buy;
			$final="";
			$sellorderId         = 	$sell->trade_id; 
			$selluserId          = 	$sell->userId;
			$sellPrice           = 	$sell->Price;
			$sellOrertype        = 	$sell->ordertype;
			$sellPrice           = 	(float)$sellPrice;
			$sellAmount          = 	(float)$sell->Amount;
			$pair   			= 	$sell->pair;
			$sellWallet 			=	$sell->wallet;
			$Total1				=	$sell->Total;
			$Fee1				=	$sell->Fee;
			$fetchbuyRecords 	= 	$this->getParticularbuyorders($sellPrice,$selluserId,$pair);
			if($fetchbuyRecords)
			{
				$pair_details = $this->ci->common_model->getTableData('trade_pairs',array('id'=>$pair),'from_symbol_id,to_symbol_id')->row();
				$k=0;
				foreach($fetchbuyRecords as $buy)
				{
					$k++;
					$buyorderId        = $buy->trade_id;
					$buyuserId         = $buy->userId;
					$buyPrice          = $buy->Price;
					$buyOrdertype      = $buy->ordertype;
					$buyAmount         = $buy->Amount;
					$buyWallet        	= $buy->wallet;
					$pair   			= $buy->pair;
					$buystatus  		= $buy->status;
					$Total				=	$buy->Total;
					$Fee				=	$buy->Fee;
					$buySumamount 		= $this->checkOrdertemp($buyorderId,'buyorderId');
					if($buySumamount)
					{
						$approxiAmount = $buyAmount-$buySumamount;
						$approxiAmount=number_format($approxiAmount,8,'.','');
					}
					else
					{
						$approxiAmount = $buyAmount;
					}
					$sellSumamount      = $this->checkOrdertemp($sellorderId,'sellorderId');
					if($sellSumamount)
					{
						$sellSumamount = $sellAmount-$sellSumamount;
						$sellSumamount=number_format($sellSumamount,8,'.','');
					}
					else
					{
						$sellSumamount = $sellAmount;
					}
					if($approxiAmount >= $sellSumamount)
					{
						$amount = $sellSumamount;
					}
					else
					{
						$amount = $approxiAmount;
					}
					if($approxiAmount!=0&&$sellSumamount!=0)
					{
						$date               =   date('Y-m-d');
						$time               =   date("H:i:s");
						$datetime           =   date("Y-m-d H:i:s");
						$data               =   array(
												'sellorderId'       =>  $sellorderId,
												'sellerUserid'      =>  $selluserId,
												'askAmount'         =>  $sellAmount,
												'askPrice'          =>  $sellPrice,
												'filledAmount'      =>  $amount,
												'buyorderId'        =>  $buyorderId,
												'buyerUserid'       =>  $buyuserId,
												'sellerStatus'      =>  "inactive",
												'buyerStatus'       =>  "inactive",
												"pair"              =>  $pair,
												"datetime"          =>  $datetime
												);
						$inserted=$this->ci->common_model->insertTableData('ordertemp', $data);
						$theftprice=0;
						if($inserted)
						{
							if($sellPrice<$buyPrice)
							{
								$price1=$buyPrice-$sellPrice;
								$theftprice=$buyAmount*$price1;
								$theftdata   = array(
										'userId'        =>  $buyuserId,
										'theftAmount'   =>  $theftprice,
										'theftCurrency' =>  $pair_details->to_symbol_id,
										'date'          =>  $date,
										'time'          =>  $time,
										'theftOrderId'  =>  $buyorderId
										);
								$this->ci->common_model->insertTableData('coin_theft', $theftdata);
								/*$user_sell_bal            = getBalance($buyuserId,$pair_details->to_symbol_id);
								$sell_bal       =   $user_sell_bal+$theftprice;
								updateBalance($buyuserId,$pair_details->to_symbol_id,$sell_bal);*/
							}

							/*elseif($sellPrice>$buyPrice)
							{
								$price1=$sellPrice-$buyPrice;
								$theftprice1=$sellAmount*$price1;
								$theftdata   = array(
										'userId'        =>  $selluserId,
										'theftAmount'   =>  $theftprice,
										'theftCurrency' =>  $pair_details->to_symbol_id,
										'date'          =>  $date,
										'time'          =>  $time,
										'theftOrderId'  =>  $sellorderId
										);
								$this->ci->common_model->insertTableData('coin_theft', $theftdata);
								/*$user_sell_bal            = getBalance($buyuserId,$pair_details->to_symbol_id);
								$sell_bal       =   $user_sell_bal+$theftprice;
								updateBalance($buyuserId,$pair_details->to_symbol_id,$sell_bal);
							}*/
							
							if(trim($approxiAmount)==trim($amount))
							{
								$this->ordercompletetype($buyorderId,"buy",$inserted);
								$trans_data = array(
								'userId'=>$buyuserId,
								'type'=>'Buy',
								'currency'=>$pair_details->to_symbol_id,
								'amount'=>$Total,
								'profit_amount'=>$Fee,
								'comment'=>'Trade Buy order #'.$buyorderId,
								'datetime'=>date('Y-m-d h:i:s'),
								'currency_type'=>'crypto',
								'bonus_amount'=>$theftprice
								);
								$update_trans = $this->ci->common_model->insertTableData('transaction_history',$trans_data);
							}
							else
							{
								$this->orderpartialtype($buyorderId,"buy",$inserted);
								$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$buyorderId),array('status'=>"partially",'tradetime'=>date('Y-m-d H:i:s')));
							}
							$this->integrate_remarket($buyorderId);
							if((trim($approxiAmount)>=trim($sellSumamount)))
							{
								$this->ordercompletetype($sellorderId,"sell",$inserted);
								$trans_data = array(
								'userId'=>$selluserId,
								'type'=>'Sell',
								'currency'=>$pair_details->to_symbol_id,
								'amount'=>$Total1+$Fee1,
								'profit_amount'=>$Fee1,
								'comment'=>'Trade Sell order #'.$sellorderId,
								'datetime'=>date('Y-m-d h:i:s'),
								'currency_type'=>'crypto'
								);
								$update_trans = $this->ci->common_model->insertTableData('transaction_history',$trans_data);
							}
							else
							{
								$this->orderpartialtype($sellorderId,"sell",$inserted);
								$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$sellorderId),array('status'=>"partially",'tradetime'=>date('Y-m-d H:i:s')));
							} 
						}
					}
					else
					{
						break;
					}
				} 
			}
		}
	}
}
/*function check_stop_order($pair,$res)
{
	$this->trade_prices($pair);
	$buy_rate = $this->lowestaskprice;
	$sell_rate = $this->lowestaskprice;
	$sell_rate = (float)$sell_rate;
	$buy_rate = (float)$buy_rate;
	$names = array('stoporder');
	$where_in=array('status', $names);
	$buy = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$res),'','','','','','','','','',$where_in)->row();
	if($buy)
	{
	$pair_id=$buy->pair;

	if($buy->Type=='sell')
	{
        $market_usersell_price = $buy->stoporderprice;
        $statuss = array('active','stoporder','partially');
	    $where_ins = array('status', $statuss);
        $stop_orders = $this->ci->common_model->getTableData('coin_order',array('Price >='=>$market_usersell_price,'Type'=>'buy','status'=>'active','pair'=>$pair),'','','','','','','','','',$where_ins)->result();
		if($stop_orders)
		{
			foreach($stop_orders as $sell_row)
			{
				//$trade_id       = $sell_row->trade_id;
				//$stoporderprice = $sell_row->limit_price;
				$trade_id       = $buy->trade_id;
				$stoporderprice = $buy->limit_price;			
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));

				$this->initialize_mapping($trade_id);
			}
		}
		else
		{
			$trade_id       = $buy->trade_id;
			$stoporderprice = $buy->stoporderprice;			
			$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));

			$this->initialize_mapping($trade_id);

		}
	}
	else
	{
		$market_userbuy_price = $buy->stoporderprice;
		$statuss = array('active','stoporder','partially');
	    $where_ins = array('status', $statuss);
		$buystop_orders = $this->ci->common_model->getTableData('coin_order',array('Price <='=>$market_userbuy_price,'Type'=>'sell','status'=>'active','pair'=>$pair),'','','','','','','','','',$where_ins)->result();
		if($buystop_orders)
		{
			foreach($buystop_orders as $buy_row)
			{
				$trade_id       = $buy->trade_id;
				$stoporderprice = $buy->limit_price;
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));
				$this->initialize_mapping($trade_id);
			}  
		}
		/*else
		{
			    $trade_id       = $buy->trade_id;
				$stoporderprice = $buy->stoporderprice;
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));
				$this->initialize_mapping($trade_id);

		}*/

	/*}
    }
} */

/*function check_stop_order($pair,$res)
{
	$this->trade_prices($pair);
	$buy_rate = $this->lowestaskprice;
	$sell_rate = $this->lowestaskprice;
	$sell_rate = (float)$sell_rate;
	$buy_rate = (float)$buy_rate;
	$names = array('stoporder');
	$where_in=array('status', $names);
	$buy = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$res),'','','','','','','','','',$where_in)->row();
    if($buy->Type =="buy")
    {
        $statuss = array('stoporder');
	    $where_ins=array('status', $statuss);
    	$stop_orders = $this->ci->common_model->getTableData('coin_order',array('Price <'=>$buy->stoporderprice,'Type'=>'sell','pair'=>$pair),'','','','','','','','','',$where_ins)->result();
		if($stop_orders)
		{
				
				$trade_id       = $buy->trade_id;
				$stoporderprice = $buy->limit_price;				
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));
				$this->initialize_mapping($trade_id);

				foreach($stop_orders as $sell_row)
				{
					if($sell_row->status=="stoporder")
					{
						$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$sell_row->trade_id),array('status'=>'active'));
				        $this->initialize_mapping($sell_row->trade_id);

					}

				}
		}
		else
		{
			$stop_orders = $this->ci->common_model->getTableData('coin_order',array('Price'=>$buy->stoporderprice,'Type'=>'sell','pair'=>$pair),'','','','','','','','','',$where_ins)->result();
			if($stop_orders)
			{
					$trade_id       = $buy->trade_id;
					$stoporderprice = $buy->stoporderprice;				
					$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));
					$this->initialize_mapping($trade_id);

					foreach($stop_orders as $sell_row)
					{
						if($sell_row->status=="stoporder")
						{
							$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$sell_row->trade_id),array('status'=>'active'));
					        $this->initialize_mapping($sell_row->trade_id);

						}

					}
			}

		}
    }
	else
	{
	$statuss = array('stoporder');
	$where_ins=array('status', $statuss);
	$buystop_orders = $this->ci->common_model->getTableData('coin_order',array('Price >'=>$buy->stoporderprice,'Type'=>'buy','pair'=>$pair),'','','','','','','','','',$where_ins)->result();
	if($buystop_orders)
	{

		$trade_id       = $buy->trade_id;
		$stoporderprice = $buy->limit_price;
		$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));
		$this->initialize_mapping($trade_id);
		foreach($buystop_orders as $buy_row)
		{
			if($buy_row->status=="stoporder")
			{
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$buy_row->trade_id),array('status'=>'active'));
		        $this->initialize_mapping($buy_row->trade_id);

			}

		}
	}
	else
	{
		$buystop_orders = $this->ci->common_model->getTableData('coin_order',array('Price'=>$buy->stoporderprice,'Type'=>'buy','pair'=>$pair),'','','','','','','','','',$where_ins)->result();
		if($buystop_orders)
		{

				$trade_id       = $buy->trade_id;
				$stoporderprice = $buy->limit_price;
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));
				$this->initialize_mapping($trade_id); 

				foreach($buystop_orders as $buy_row)
				{
					if($buy_row->status=="stoporder")
					{
						$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$buy_row->trade_id),array('status'=>'active'));
				        $this->initialize_mapping($buy_row->trade_id);

					}

				} 
		}

	}
    }
}*/

/*function check_stop_order($pair)
{
	$this->trade_prices($pair);
	$buy_rate = $this->lowestaskprice;
	$sell_rate = $this->lowestaskprice;
	$sell_rate = (float)$sell_rate;
	$buy_rate = (float)$buy_rate;
	$stop_orders = $this->ci->common_model->getTableData('coin_order',array('stoporderprice >='=>$sell_rate,'Type'=>'sell','pair'=>$pair))->result();
	echo $this->db->last_query();
	if($stop_orders)
	{
		foreach($stop_orders as $sell_row)
		{
			$trade_id       = $sell_row->trade_id;
			$stoporderprice = $sell_row->limit_price;				
			$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));
			$this->initialize_mapping($trade_id);
		}
	}
	$buystop_orders = $this->ci->common_model->getTableData('coin_order',array('stoporderprice <='=>$buy_rate,'Type'=>'buy','pair'=>$pair))->result();
	echo $this->db->last_query();
	if($buystop_orders)
	{
		foreach($buystop_orders as $buy_row)
		{
			$trade_id       = $buy_row->trade_id;
			$stoporderprice = $buy_row->limit_price;
			$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));
			$this->initialize_mapping($trade_id);
		}  
	}
}*/


function check_stop_order($pair,$res)
{
	$this->trade_prices($pair);
	$buy_rate = marketprice($pair);
	$sell_rate = marketprice($pair);
	//$sell_rate = (float)$sell_rate;
	//$buy_rate = (float)$buy_rate;
	$names = array('stoporder');
	$where_in=array('status', $names);
	$buy = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$res,'pair'=>$pair))->row();
	if($buy)
	{
	$pair_id=$buy->pair;
	if($buy->Type=='sell')
	{
        $stop_orders = $this->ci->common_model->getTableData('coin_order',array('Type'=>'buy','pair'=>$pair))->result();
        //echo $this->ci->db->last_query();
       // print_r($stop_orders);
		if($stop_orders)
		{
			foreach($stop_orders as $sell_row)
			{
				//$trade_id       = $sell_row->trade_id;
				//$stoporderprice = $sell_row->limit_price;
				
				$check_status = $sell_row->status;
				if($check_status=="stoporder")
				{

				$trade_id       = $sell_row->trade_id;
				$stoporderprice = $sell_row->stoporderprice;
				$stoporderlimit = $sell_row->limit_price;
                if($stoporderprice>=$buy_rate || $stoporderlimit>=$buy_rate)
                {
                $this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderlimit,'status'=>'active'));
                $this->initialize_mapping($trade_id);
                }
				}

				
			}
		}
	}
	else
	{
		$buystop_orders = $this->ci->common_model->getTableData('coin_order',array('Type'=>'sell','pair'=>$pair))->result();
		if($buystop_orders)
		{
			foreach($buystop_orders as $buy_row)
			{
				$check_status = $buy_row->status;
				if($check_status=="stoporder")
				{
				$trade_id       = $buy_row->trade_id;
				$stoporderprice = $buy_row->stoporderprice;
				$stoporderlimit = $buy_row->limit_price;
				if($stoporderprice<=$buy_rate || $stoporderlimit<=$buy_rate)
                {
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderlimit,'status'=>'active'));
				$this->initialize_mapping($trade_id);
			    }
                }
				
			}  
		}

	}
    }
	
	
}
/*function check_stop_order($pair,$res)
{
	$this->trade_prices($pair);
	$buy_rate = $this->lowestaskprice;
	$sell_rate = $this->lowestaskprice;
	$sell_rate = (float)$sell_rate;
	$buy_rate = (float)$buy_rate;
	$names = array('stoporder');
	$where_in=array('status', $names);
	$buy = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$res),'','','','','','','','','',$where_in)->row();
	if($buy)
	{
	$pair_id=$buy->pair;
	if($buy->Type=='sell')
	{
        $where_status = array('stoporder','active','partially');
        $stop_orders = $this->ci->common_model->getTableData('coin_order',array('stoporderprice >='=>$sell_rate,'Type'=>'buy','pair'=>$pair),'','','','','','','','','',$where_status)->result();
		if($stop_orders)
		{
			foreach($stop_orders as $sell_row)
			{
				//$trade_id       = $sell_row->trade_id;
				//$stoporderprice = $sell_row->limit_price;
				$trade_id       = $buy->trade_id;
				$stoporderprice = $buy->limit_price;			
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));

				$buytrade_id    = $sell_row->trade_id;
				$buystoporderprice =  $sell_row->limit_price;			
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$buytrade_id),array('status'=>'active'));

				$this->initialize_mapping($trade_id);
			}
		}
	}
	else
	{
		$where_status = array('stoporder','active','partially');
		$buystop_orders = $this->ci->common_model->getTableData('coin_order',array('stoporderprice <='=>$buy_rate,'Type'=>'sell','pair'=>$pair),'','','','','','','','','',$where_status)->result();
		if($buystop_orders)
		{
			foreach($buystop_orders as $buy_row)
			{
				$trade_id       = $buy->trade_id;
				$stoporderprice = $buy->limit_price;
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),array('Price'=>$stoporderprice,'status'=>'active'));

				$selltrade_id    = $buy_row->trade_id;
				$sellstoporderprice =  $buy_row->limit_price;			
				$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$selltrade_id),array('Price'=>$sellstoporderprice,'status'=>'active'));
				$this->initialize_mapping($trade_id);
			}  
		}

	}
    }
	
	
}*/
function getParticularsellorders($buyPrice,$buyuserId,$pair)
{
	$names = array('active', 'partially');
	$where_in=array('status', $names);
	$order_by = array('Price','asc');
	//$query = $this->ci->common_model->getTableData('coin_order',array('pair'=>$pair,'userId !='=>$buyuserId,'Type'=>'Sell','Price <='=>$buyPrice),'','','','','','',$order_by,'','',$where_in);

	$query = $this->ci->common_model->getTableData('coin_order',array('pair'=>$pair,'Type'=>'Sell','Price <='=>$buyPrice),'','','','','','',$order_by,'','',$where_in);
	if($query->num_rows() >= 1)
	{
		return $query->result();
	}
	else
	{
		return false;
	}
} 
function getParticularbuyorders($sellPrice,$selluserId,$pair)
{
	$names = array('active', 'partially');
	$where_in=array('status', $names);
	$order_by = array('Price','desc');
	//$query = $this->ci->common_model->getTableData('coin_order',array('pair'=>$pair,'userId !='=>$selluserId,'Type'=>'Buy','Price >='=>$sellPrice),'','','','','','',$order_by,'','',$where_in);

	$query = $this->ci->common_model->getTableData('coin_order',array('pair'=>$pair,'Type'=>'Buy','Price >='=>$sellPrice),'','','','','','',$order_by,'','',$where_in);
	if($query->num_rows() >= 1)
	{
		return $query->result();
	}
	else
	{
		return false;
	}
} 
function checkOrdertemp($id,$type)
{
	$query = $this->ci->common_model->getTableData('ordertemp',array($type=>$id),'SUM(filledAmount) as totalamount');
	if($query->num_rows() >= 1)
	{
		$row = $query->row();
		return $row->totalamount;
	}
	else
	{
		return false;
	}
}
function ordercompletetype($orderId,$type,$inserted)
{
	$trade_execution_type = $this->ci->common_model->getTableData('site_settings',array('id'=>1),'trade_execution_type')->row('trade_execution_type');
	if($trade_execution_type==1)
	{
		$this->removeOrder($orderId,$inserted);
	}
	else
	{
		$this->partial_balanceupdate($orderId,$inserted);
	}
	$current_time = date("Y-m-d H:i:s");
	$query  =   $this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$orderId),array('status'=>"filled",'datetime'=>$current_time));
	if($type=="buy")
	{
		$data = array('buyerStatus'=>"active");
		$where = array('tempId'=>$inserted,'buyorderId'=>$orderId);
	}
	else
	{
		$data = array('sellerStatus'=>"active");
		$where = array('tempId'=>$inserted,'sellorderId'=>$orderId);
	}
	$this->ci->common_model->updateTableData('ordertemp',$where,$data);
	return true;
}
function removeOrder($id,$inserted)
{
	$current_time = date("Y-m-d H:i:s");
	$query  =   $this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$id),array('status'=>"filled",'datetime'=>$current_time));
	if($query)
	{
		$trade = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$id))->row();
		$tradetradeId           = $trade->trade_id;
		$tradeuserId            = $trade->userId;
		$tradePrice             = $trade->Price;
		$tradeAmount            = $trade->Amount;
		$tradeFee               = $trade->Fee;
		$tradeType              = $trade->Type;
		$tradeTotal             = $trade->Total;
		$tradepair			    = $trade->pair;
		$orderDate              = $trade->orderDate;
		$orderTime              = $trade->orderTime;
		$wallet 				= $trade->wallet;
		$pair_details = $this->ci->common_model->getTableData('trade_pairs',array('id'=>$tradepair),'from_symbol_id,to_symbol_id')->row();
		if($wallet!="Margin Trading")
		{
			if($tradeType=="buy")
			{
				$userbalance            = getBalance($tradeuserId,$pair_details->from_symbol_id);
				$updatebuyBalance       =   $userbalance+$tradeAmount;
				updateBalance($tradeuserId,$pair_details->from_symbol_id,$updatebuyBalance);
			}
			else if($tradeType=="sell")
			{
				$userbalance            = getBalance($tradeuserId,$pair_details->to_symbol_id);
				$updatebuyBalance       =   $userbalance+$tradeTotal;
				updateBalance($tradeuserId,$pair_details->to_symbol_id,$updatebuyBalance);
			}
		}
		return true;
	}
	else
	{
		return false;
	}
}
function orderpartialtype($orderId,$type,$inserted)
{
	$trade_execution_type = $this->ci->common_model->getTableData('site_settings',array('id'=>1),'trade_execution_type')->row('trade_execution_type');
	if($trade_execution_type==2)
	{
		$this->partial_balanceupdate($orderId,$inserted);
	}
	return true;
}
function partial_balanceupdate($id,$inserted)
{
	$trade = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$id),'userId,fee_per,Price,Type,pair,wallet')->row();
	$ordertemp = $this->ci->common_model->getTableData('ordertemp',array('tempId'=>$inserted),'filledAmount')->row();
	$tradeuserId            = $trade->userId;
	$fee_per               	= $trade->fee_per;
	$Price               	= $trade->Price;
	$tradeType              = $trade->Type;
	$tradepair			    = $trade->pair;
	$wallet 				= $trade->wallet;
	$tradeAmount			= $ordertemp->filledAmount;
	$pair_details = $this->ci->common_model->getTableData('trade_pairs',array('id'=>$tradepair),'from_symbol_id,to_symbol_id')->row();
	if($wallet!="Margin Trading")
	{	
		if($tradeType=="buy")
		{
			$userbalance            = getBalance($tradeuserId,$pair_details->from_symbol_id);
			$updatebuyBalance       =   $userbalance+$tradeAmount;
			updateBalance($tradeuserId,$pair_details->from_symbol_id,$updatebuyBalance);
		}
		else if($tradeType=="sell")
		{
			$filledprice	=	$tradeAmount*$Price;
			$fees = ($filledprice*$fee_per)/100;
			$tradeTotal	=	$filledprice-$fees;
			$userbalance            = getBalance($tradeuserId,$pair_details->to_symbol_id);
			$updatebuyBalance       =   $userbalance+$tradeTotal;
			updateBalance($tradeuserId,$pair_details->to_symbol_id,$updatebuyBalance);
		}
	}
	return true;
}
function integrate_remarket($insid)
{
	$order = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$insid))->row();
	$remarket_order_id	= 	$order->remarket_order_id;
	$old_remarket_id	=	$order->old_remarket_id;
	if($remarket_order_id&&$remarket_order_id!=0)
	{
		$pair			=	$order->pair;
		$joins 			= 	array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
		$where 			= 	array('a.id'=>$pair);
		$pair_details 	= 	$this->ci->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'b.currency_symbol as from_currency_symbol,c.currency_symbol as to_currency_symbol,a.to_symbol_id')->row();
		$pair_symbol	=	$pair_details->from_currency_symbol.'_'.$pair_details->to_currency_symbol;
		$cancel_order = $this->api->cancel_order($pair_symbol,$remarket_order_id);
		if($cancel_order&&isset($cancel_order['success'])&&$cancel_order['success']==1)
		{
			if($old_remarket_id!='')
			{
				$old_remarket_id=$old_remarket_id.','.$remarket_order_id;
			}
			else
			{
				$old_remarket_id=$remarket_order_id;
			}
			$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$insid),array('old_remarket_id'=>$old_remarket_id));
		}
	}
	$remarket=getSiteSettings('remarket_concept');
	if($remarket==1)
	{
		if($order&&$order->status!='filled')
		{
			$pair			=	$order->pair;
			$Type			=	$order->Type;
			$activePrice	=	$order->Price;
			$activeAmount	= 	$order->Amount;
			$joins 			= 	array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
			$where 			= 	array('a.id'=>$pair);
			$pair_details 	= 	$this->ci->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'b.currency_symbol as from_currency_symbol,c.currency_symbol as to_currency_symbol,a.to_symbol_id')->row();
			$pair_symbol	=	$pair_details->from_currency_symbol.'_'.$pair_details->to_currency_symbol;
			$activefilledAmount = $this->checkOrdertemp($insid,$Type.'orderId');
			if($activefilledAmount)
			{
				$activefilledAmount = $activeAmount-$activefilledAmount;
			}
			else
			{
				$activefilledAmount = $activeAmount;
			}
			$price=$activefilledAmount*$activePrice;
			if($price>=0.0001)
			{
				if($Type=='buy')
				{
					$order_detail = $this->api->buy($pair_symbol,$activePrice,$activefilledAmount);
				}
				else
				{
					$order_detail = $this->api->sell($pair_symbol,$activePrice,$activefilledAmount);
				}
				if($order_detail&&isset($order_detail['orderNumber'])&&$order_detail['orderNumber']!='')
				{
					$orderNumber=$order_detail['orderNumber'];
					$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$insid),array('remarket_order_id'=>$orderNumber));
					$resultingTrades=$order_detail['resultingTrades'];
					if(isset($resultingTrades)&&count($resultingTrades)>0)
					{
						foreach($resultingTrades as $trades)
						{
							$trades['order_id']		=	$orderNumber;
							$trades['created_on']	=	time();
							$total	=	$trades['total'];
							$this->ci->common_model->insertTableData('remarket_trades', $trades);
							$orderid       	= $order->trade_id;
							$userId         = $order->userId;
							$Price          = $order->Price;
							$Amount         = $order->Amount;
							$Wallet        	= $order->wallet;
							$Total1			= $order->Total;
							$Fee1			= $order->Fee;
							$datetime       = date("Y-m-d H:i:s");
							$data           = array(											
												'askAmount'         =>  $Amount,
												'askPrice'          =>  $Price,
												'filledAmount'      =>  $total,
												'sellerStatus'      =>  "inactive",
												'buyerStatus'       =>  "inactive",
												"pair"              =>  $pair,
												"datetime"          =>  $datetime
												);
							if($Type=='buy')
							{
								$data['buyorderId']=$orderid;
								$data['buyerUserid']=$userId;
								$data['sellorderId']=0;
								$data['sellerUserid']=0;
							}
							else
							{
								$data['sellorderId']=$orderid;
								$data['sellerUserid']=$userId;
								$data['buyorderId']=0;
								$data['buyerUserid']=0;
							}
							$inserted=$this->ci->common_model->insertTableData('ordertemp', $data);
							if($inserted)
							{
								$activefilledAmount = $this->checkOrdertemp($insid,$Type.'orderId');
								if($activefilledAmount)
								{
									$activefilledAmount = $activeAmount-$activefilledAmount;
								}
								else
								{
									$activefilledAmount = $activeAmount;
								}
								if(trim($total)==trim($activefilledAmount))
								{
									$this->ordercompletetype($orderid,$Type,$inserted);
									$trans_data = array(
									'userId'=>$userId,
									'type'=>ucfirst($Type),
									'currency'=>$pair_details->to_symbol_id,
									'amount'=>$Total1+$Fee1,
									'profit_amount'=>$Fee1,
									'comment'=>'Trade '.ucfirst($Type).' order #'.$orderid,
									'datetime'=>date('Y-m-d h:i:s'),
									'currency_type'=>'crypto'
									);
									$update_trans = $this->ci->common_model->insertTableData('transaction_history',$trans_data);
								}
								else
								{
									$this->orderpartialtype($orderid,$Type,$inserted);
									$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$orderid),array('status'=>"partially",'tradetime'=>date('Y-m-d H:i:s')));
								}
							}
						}
					}
				}
				else
				{
					$balance_alert=getSiteSettings('balance_alert');
					if($balance_alert==1)
					{
						$dst=getSiteSettings('contactno');
						$text='Error Occured while place '.$pair_symbol.' order using api on your poloniex acount. ';
						if(isset($order_detail['error']))
						{
							$text=$text.$order_detail['error'];
						}
						else
						{
							$text=$text.'Not enough balance in your account';
						}
						send_otp_msg($dst,$text);
					}
				}
			}
		}
	}
}
function close_active_order($tradeid,$pair_id,$user_id)
{
	$where_in =array('status',array('active','partially','stoporder','margin','filled'));
	$order = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$tradeid,'userId'=>$user_id),'','','','','','','','','',$where_in)->row();
	if($order)
	{    
		if($order->status=='active'||$order->status=='partially'||$order->status=='stoporder'||$order->status=='margin'||($order->status=='filled'&&$order->wallet=='Margin Trading'))
		{
			$request_time = date("Y-m-d h:i:s");
			$data_up = array('tradetime'=>$request_time);
			if($order->status!='filled')
			{
				$data_up['status']="cancelled";
			}
			// print_r($data_up['status']);exit();
			$query=$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$tradeid),$data_up);
			$userId 				= $order->userId;
			$Type 					= $order->Type;
			$data['type']			= $order->Type;
			$activeAmount 			= $order->Amount;
			$activeTradeid 			= $order->trade_id;
			$Total 					= $order->Total;
			$fee                    = $order->fee_per;
			$activePrice 			= $order->Price;
			$wallet 				= $order->wallet;
			$trade_execution_type = $this->ci->common_model->getTableData('site_settings',array('id'=>1),'trade_execution_type')->row('trade_execution_type');
			$pair_details = $this->ci->common_model->getTableData('trade_pairs',array('id'=>$pair_id),'from_symbol_id,to_symbol_id')->row();
			//getcryptocurrency
			$data['from_symbol'] = getcryptocurrency($pair_details->from_symbol_id);
			$data['to_symbol']	= getcryptocurrency($pair_details->to_symbol_id);
			// send mail start
			$email_template = ucfirst($Type).'_Cancel';
			$user 		= getUserDetails($userId);
			$prefix 	= get_prefix();
			$usernames 	= $prefix.'username';
			$username 	= $user->$usernames;
			$email 	= getUserEmail($userId);
			$currency_name = getcryptocurrency($pair_details->from_symbol_id);
			$site_common      =   site_common();
			$special_vars = array(				
			'###USERNAME###' => $username,
			'###AMOUNT###' => $activeAmount,
			'###CURRENCY###' => $currency_name
			);
			// $this->ci->email_model->sendMail($email, '', '', $email_template, $special_vars);
            // send mail end

			if($wallet!='Margin Trading')
			{
				if($Type=="buy")
				{
					//echo "buy";
					$ordertemp = $this->ci->common_model->getTableData('ordertemp',array('buyorderId'=>$activeTradeid),'SUM(filledAmount) as totalamount');
					if($ordertemp->num_rows() >= 1&&$ordertemp->row('totalamount')!=0)
					{
						//echo "if";
						$row = $ordertemp->row();
						$activefilledAmount = $row->totalamount;
						if($trade_execution_type!=2)
						{
							//echo "if1";
							$userbalance            = getBalance($userId,$pair_details->from_symbol_id);
							$updatebuyBalance       =   $userbalance+$activefilledAmount;
							updateBalance($userId,$pair_details->from_symbol_id,$updatebuyBalance);


						}
						$activefilledAmount = $activeAmount-$activefilledAmount;
						$activeFees         = ($activefilledAmount*$activePrice)*$fee/100;
						$activeCalcTotal    = ($activefilledAmount*$activePrice);
					}
					else
					{  

						//echo "else";
						$activefilledAmount = $activeAmount;
						$activeCalcTotal = $Total;
					}
					$activefilledAmount;
					$currentbalance = getBalance($userId,$pair_details->to_symbol_id);
					$updatebalance  = $currentbalance+$activeCalcTotal;
					 updateBalance($userId,$pair_details->to_symbol_id,$updatebalance); 
					//exit;

					$data['first_balance'] = getBalance($userId,$pair_details->from_symbol_id);
					$data['second_balance'] = getBalance($userId,$pair_details->to_symbol_id);
				}
				else if($Type=="sell")
				{
					//echo "sell";
					$ordertemp = $this->ci->common_model->getTableData('ordertemp',array('sellorderId'=>$activeTradeid),'SUM(filledAmount) as totalamount');
					if($ordertemp->num_rows() >= 1&&$ordertemp->row('totalamount')!=0)
					{
						$row = $ordertemp->row();
						$activefilledAmount = $row->totalamount;
						if($trade_execution_type!=2)
						{
							$userbalance       = getBalance($userId,$pair_details->to_symbol_id);
							$activeCalcTotal   = $activefilledAmount*$activePrice;
							$activeFees        = $activefilledAmount*$fee/100;
							if(checkMarketingUser($userId) == '1')
							{
								$updatesellBalance = $userbalance+$activeCalcTotal;
							} else {
								$updatesellBalance = $userbalance+$activeCalcTotal+$activeFees;
							}
							updateBalance($userId,$pair_details->to_symbol_id,$updatesellBalance);
						}
						$activefilledAmount = $activeAmount-$activefilledAmount;
					}
					else
					{
						$activefilledAmount = $activeAmount;
					}
					$activefilledAmount;
					$currentbalance = getBalance($userId,$pair_details->from_symbol_id);
					$updatebalance  = $currentbalance+$activefilledAmount;
					updateBalance($userId,$pair_details->from_symbol_id,$updatebalance);

					$data['first_balance'] = getBalance($userId,$pair_details->from_symbol_id);
					$data['second_balance'] = getBalance($userId,$pair_details->to_symbol_id);
				}
			}
			
			if($ordertemp->num_rows() >= 1&&$ordertemp->row('totalamount')!=0)
			{
				$this->partial_close_order($tradeid);
				$activefilledAmount = $ordertemp->row('totalamount');
				$trade_id		= $order->trade_id;
				$userId			= $order->userId;
				$tradePrice 	= $order->Price;
				$tradeAmount 	= $order->Amount;
				$Fee 			= $order->Fee;
				$fee_per		= $order->fee_per;
				$Type 			= $order->Type;
				$Total 			= $order->Total;
				$activefilledAmount=$activefilledAmount*$tradePrice;
				$activefilledAmount=($activefilledAmount*$fee_per)/100;
				$activefilledAmount=to_decimal($activefilledAmount,8);
				$trans_data = array(
									'userId'=>$userId,
									'type'=>ucfirst($Type),
									'currency'=>$pair_details->to_symbol_id,
									'amount'=>$Total,
									'profit_amount'=>$activefilledAmount,
									'comment'=>'Trade '.ucfirst($Type).' order #'.$trade_id,
									'datetime'=>date('Y-m-d h:i:s'),
									'currency_type'=>'crypto'
									);
				$this->ci->common_model->insertTableData('transaction_history', $trans_data);
			}
			$data['result'] = 1;
			$data['web_trade'] = 2;
		    $data['status'] = 'cancelorder';
		    $data['msg']='success';
		}
	}
	else{
		$data['msg']='error';
		$data['result'] = 0;
		$data['web_trade'] = 2;
		$data['status'] = 'cancelorder';
	}
	$this->check_stop_order($pair_id,$tradeid);
	return $data;
}
function partial_close_order($trade_id)
{
	$get_insert_order = $this->ci->common_model->getTableData('coin_order',array('trade_id'=>$trade_id))->row_array();
	/*if($get_insert_order['status']!='filled')
	{*/
	$filledAmount = $this->checkOrdertemp($trade_id,$get_insert_order['Type'].'orderId');
	$ori_amount   = $get_insert_order['Amount'];
	if($filledAmount!=$ori_amount)
	{
		$price  	  = $get_insert_order['Price'];
		$fee_per      = $get_insert_order['fee_per'];
		$filled_fee   = ( $filledAmount * $price ) * ( $fee_per / 100 );
		$total = ($get_insert_order['Type']=='Buy')?(($filledAmount*$price)+$filled_fee):(($filledAmount*$price)-$filled_fee);
		unset($get_insert_order['trade_id']);
		$get_insert_order['Amount']     = $filledAmount;
		$get_insert_order['Fee']        = number_format((float)$filled_fee, 8, '.', '');
		$get_insert_order['Total']      = $total;
		$get_insert_order['status']     = 'filled';
		$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$trade_id),$get_insert_order);
		$cancelled_amount = $ori_amount - $filledAmount;
		$cancel_fee   = ( $cancelled_amount * $price ) * ( $fee_per / 100 );
		$total = ($get_insert_order['Type']=='Buy')?(($cancelled_amount*$price)+$cancel_fee):(($cancelled_amount*$price)-$cancel_fee);
		$get_insert_order['Amount']     = $cancelled_amount;
		$get_insert_order['Fee']        = number_format((float)$cancel_fee, 8, '.', '');
		$get_insert_order['Total']      = $total;
		$get_insert_order['status']     = 'cancelled';
		$this->ci->common_model->insertTableData('coin_order',$get_insert_order);
	}
}

function close_allactive_order($user_id)
{
	//echo "close active order";
	$where_in =array('status',array('active','partially','stoporder'));
	$orders = $this->ci->common_model->getTableData('coin_order',array('userId'=>$user_id),'','','','','','','','','',$where_in)->result();
	if(count($orders)>0)
	{
		foreach($orders as $order)
		{
			if($order->status=='active'||$order->status=='partially'||$order->status=='stoporder')
			{
				$request_time = date("Y-m-d h:i:s");
				$data_up = array('tradetime'=>$request_time);
				if($order->status!='filled')
				{
					$data_up['status']="cancelled";
				}
				$tradeid = $order->trade_id;
				$query=$this->ci->common_model->updateTableData('coin_order',array('trade_id'=>$tradeid),$data_up);
				$userId 				= $order->userId;
				$Type 					= $order->Type;
				$data['type']			= $order->Type;
				$activeAmount 			= $order->Amount;
				$activeTradeid 			= $order->trade_id;
				$Total 					= $order->Total;
				$fee                    = $order->fee_per;
				$activePrice 			= $order->Price;
				$wallet 				= $order->wallet;
				$commission             = $order->liquidity_fee;
				$pairID = $order->pair;
				$trade_execution_type = $this->ci->common_model->getTableData('site_settings',array('id'=>1),'trade_execution_type')->row('trade_execution_type');

				$pair_details = $this->ci->common_model->getTableData('trade_pairs',array('id'=>$pairID),'from_symbol_id,to_symbol_id')->row();

				//getcryptocurrency
				$data['from_symbol'] = getcryptocurrency($pair_details->from_symbol_id);
				$data['to_symbol']	= getcryptocurrency($pair_details->to_symbol_id);


				if($wallet!='Margin Trading')
				{
					if($Type=="buy")
					{
						$ordertemp = $this->ci->common_model->getTableData('ordertemp',array('buyorderId'=>$activeTradeid),'SUM(filledAmount) as totalamount');
						if($ordertemp->num_rows() >= 1&&$ordertemp->row('totalamount')!=0)
						{
							$row = $ordertemp->row();
							$activefilledAmount = $row->totalamount;
							if($trade_execution_type!=2)
							{
								$userbalance            = getBalance($userId,$pair_details->from_symbol_id);
								//$updatebuyBalance       = ($userbalance+$activefilledAmount)-$commission;
								$updatebuyBalance       = ($userbalance+$activefilledAmount);
								updateBalance($userId,$pair_details->from_symbol_id,$updatebuyBalance);


							}
							$activefilledAmount = $activeAmount-$activefilledAmount;
							$activeFees         = ($activefilledAmount*$activePrice)*$fee/100;
							$activeCalcTotal    = ($activefilledAmount*$activePrice)+$activeFees;
						}
						else
						{
							$activefilledAmount = $activeAmount;
							$activeCalcTotal = $Total;
							$userbalance = getBalance($userId,$pair_details->from_symbol_id);
							//$updatebuyBalance       = $userbalance-$commission;
							$updatebuyBalance       = $userbalance;
							updateBalance($userId,$pair_details->from_symbol_id,$updatebuyBalance);

						}
						$activefilledAmount;
						$currentbalance = getBalance($userId,$pair_details->to_symbol_id);
						//$updatebalance  = ($currentbalance-$commission)+$activeCalcTotal;
						$updatebalance  = $currentbalance+$activeCalcTotal;
						updateBalance($userId,$pair_details->to_symbol_id,$updatebalance);

						$data['first_balance'] = getBalance($userId,$pair_details->from_symbol_id);
						$data['second_balance'] = getBalance($userId,$pair_details->to_symbol_id);
					}
					else if($Type=="sell")
					{
						$ordertemp = $this->ci->common_model->getTableData('ordertemp',array('sellorderId'=>$activeTradeid),'SUM(filledAmount) as totalamount');
						if($ordertemp->num_rows() >= 1&&$ordertemp->row('totalamount')!=0)
						{  
							$row = $ordertemp->row();
							$activefilledAmount = $row->totalamount;
							if($trade_execution_type!=2)
							{ 
								$userbalance       = getBalance($userId,$pair_details->to_symbol_id);
								$activeCalcTotal   = $activefilledAmount*$activePrice;
								$activeFees        = $activeCalcTotal*$fee/100;
								$updatesellBalance1 = $userbalance+$activeCalcTotal-$activeFees;
								//$updatesellBalance = $updatesellBalance1-$commission;
								$updatesellBalance = $updatesellBalance1;
								updateBalance($userId,$pair_details->to_symbol_id,$updatesellBalance);
							}
							$activefilledAmount = $activeAmount-$activefilledAmount;
						}
						else
						{ 
							$activefilledAmount = $activeAmount;
							// COMMMSSION PROCESS
							$userbalance  = getBalance($userId,$pair_details->to_symbol_id);
							$updatesellBalance = $userbalance;
							updateBalance($userId,$pair_details->to_symbol_id,$updatesellBalance);

						}
						//$activefilledAmount;
						$currentbalance = getBalance($userId,$pair_details->from_symbol_id);
						$updatebalance  = $currentbalance+$activefilledAmount;
						updateBalance($userId,$pair_details->from_symbol_id,$updatebalance);

						$data['first_balance'] = getBalance($userId,$pair_details->from_symbol_id);
						$data['second_balance'] = getBalance($userId,$pair_details->to_symbol_id);
					}
				}
				
				if($ordertemp->num_rows() >= 1&&$ordertemp->row('totalamount')!=0)
				{
					$this->partial_close_order($tradeid);
					$activefilledAmount = $ordertemp->row('totalamount');
					$trade_id		= $order->trade_id;
					$userId			= $order->userId;
					$tradePrice 	= $order->Price;
					$tradeAmount 	= $order->Amount;
					$Fee 			= $order->Fee;
					$fee_per		= $order->fee_per;
					$Type 			= $order->Type;
					$Total 			= $order->Total;
					$activefilledAmount=$activefilledAmount*$tradePrice;
					$activefilledAmount=($activefilledAmount*$fee_per)/100;
					$activefilledAmount=to_decimal($activefilledAmount,8);
					$trans_data = array(
										'userId'=>$userId,
										'type'=>ucfirst($Type),
										'currency'=>$pair_details->to_symbol_id,
										'amount'=>$Total,
										'profit_amount'=>$activefilledAmount,
										'comment'=>'Trade '.ucfirst($Type).' order #'.$trade_id,
										'datetime'=>date('Y-m-d h:i:s'),
										'currency_type'=>'crypto'
										);
					$this->ci->common_model->insertTableData('transaction_history', $trans_data);
				}
				$data['result'] = 1;
			}
		}	
	}
	else{
		$data['result'] = 0;
	}
	//$this->check_stop_order($pair_id);
	return $data;
} 
	
}
?>