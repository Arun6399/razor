<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: PUT, GET, POST, FILES");
if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
  $_POST = array_merge($_POST, (array) json_decode(trim(file_get_contents('php://input')), true));
}


class Spot extends CI_Controller {
 public function __construct()
  { 
    parent::__construct();    
    $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
    $this->output->set_header("Pragma: no-cache");
    $this->load->library(array('form_validation'));
    $this->load->library('session');
    $this->load->helper(array('url', 'language'));
    $this->load->library('pagination');
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
    $sitelan = $this->session->userdata('site_lang'); 
  }
 public function index(){
 $data['currency']=$this->common_model->getTableData('currency')->result();
 $data['country'] = $this->common_model->getTableData('countries')->result();
 $data['service'] = $this->common_model->getTableData('service')->result();
 $this->load->view('front/p2p/post-ad',$data);
  }


public function create_offer(){
  $user_id=$this->session->userdata('user_id'); 
  $data['site_common'] = site_common(); 
  $data['action'] = front_url() . 'create_offer';

$kyc = getUserDetails($user_id);
$kyc_status=$kyc->verify_level2_status;
  if($kyc_status != 'Completed'){

    $this->session->set_flashdata('error','KYC is Not Completed');
    front_redirect('offer','refresh');

  }

if($user_id=='') redirect('login');   

  
  

if($this->input->post('submit_create')){
  
  // echo "<pre>";
  // print_r($this->input->post());
  // echo "<pre>";
  // exit();


  $user_id=$this->session->userdata('user_id');
  $crypto = $this->db->escape_str($this->input->post('cryptocurrency'));
  $currency = $this->db->escape_str($this->input->post('fiat_currency'));
  $commission = $this->db->escape_str($this->input->post('commission'));
  $trade_amount = $this->db->escape_str($this->input->post('trade_amount'));
  $minimum_limit = $this->db->escape_str($this->input->post('minimum_limit'));
  $maximum_limit = $this->db->escape_str($this->input->post('maximum_limit'));
  $instraction = $this->db->escape_str($this->input->post('instraction'));
  $price = $this->db->escape_str($this->input->post('price'));
  $actualtrade = $this->db->escape_str($this->input->post('type'));
  $payment = $this->db->escape_str($this->input->post('payment'));
  $country = $this->db->escape_str($this->input->post('country'));
  $traderandom = mt_rand(10000000,99999999);




if($actualtrade=='buy'){
  $type="Sell";
}else{
  $type="Buy";
}

  $insert_offer=array(
    'cryptocurrency'=>$crypto,
    'country'=>$country,
    'price'=>$price,
    'trade_amount'=>$trade_amount,
    'payment_method'=>$payment,
    'minimumtrade'=>$minimum_limit,
    'maximumtrade'=>$maximum_limit,
    'fiat_currency'=>$currency,
    'comission'=>$commission,
    'addtional_info'=>$addinstraction,
    'terms_conditions'=>$instraction,
    'type'=>$type,
    'actualtradebuy'=>$actualtrade,
    'user_id'=>$user_id,
    'tradeid'=>$traderandom,
    'datetime'=>date("Y-m-d h:i:s"),
  );
   // print_r($insert_offer);
   //  exit();
   $user_data_clean = $this->security->xss_clean($insert_offer);
   $id=$this->common_model->insertTableData('p2p_trade', $user_data_clean);

   if($actualtrade=='sell')
   {
    // Seller Get Balance 
    $userbalance = getBalance($user_id,$crypto);
    if($userbalance >= $trade_amount)
    {
          $finalbalance=$userbalance-$trade_amount;
          // Update user balance  
          $updatebalance = updateBalance($user_id,$crypto,$finalbalance);
    }
    else
    {
        $this->session->set_flashdata('error', 'Amount you have entered is more than your current balance');
        front_redirect('offer','refresh');
     }

   }


  $this->session->set_flashdata('success','Order Created successfully');
  front_redirect('offer','refresh');

  }

$data['currency']=$this->common_model->getTableData('currency')->result();
$data['fiatcurrency']=$this->common_model->getTableData('fiat_currency',array('status' => 1))->result();
$data['country'] = $this->common_model->getTableData('countries')->result(); 
$data['services'] = $this->common_model->getTableData('service')->result();
$data['userInfo'] = $this->common_model->getTableData('users', array('id' => $user_id))->row();

$this->load->view('front/p2p/create_offer',$data);



}




  

public function p2porder()
{
    $user_id = $this->session->userdata('user_id');
 

  

    if($this->input->post('trade_id'))
    {

    


        $id=$this->db->escape_str($this->input->post('trade_id'));
        $gettrade = $this->common_model->getTableData('p2p_trade', array('tradeid' => $id))->row();

        // echo "Id - ".$id;

        
        $crypto_amt =  $this->db->escape_str($this->input->post('cryptocurrency'));

        $fiats_amt =  $this->db->escape_str($this->input->post('fiat_currency'));

        $insertData['tradeid'] = $this->db->escape_str($this->input->post('trade_id'));
        
        $insertData['crypto_amount'] =   $this->db->escape_str($this->input->post('cryptocurrency'));
        $insertData['fiat_amount'] =  $this->db->escape_str($this->input->post('fiat_currency'));

        $insertData['fiat_currency']=  $gettrade->fiat_currency;
        $insertData['cryptocurrency']= $gettrade->cryptocurrency;

        $insertData['escrowamount'] = $this->db->escape_str($gettrade->trade_amount);
        $insertData['tradeopentime'] =  date("Y-m-d H:i:s");
        $insertData['escrowstatus'] = 'waiting';             
        $insertData['type'] = $gettrade->type;

        if($gettrade->actualtradebuy == 'sell'){
          $insertData['sellerid'] =   $gettrade->user_id;
          $insertData['buyerid'] = $user_id;

          $seller = $gettrade->user_id;
          $buyer = $user_id;

        } else {
          $insertData['sellerid'] =  $user_id;
          $insertData['buyerid'] =  $gettrade->user_id;

          $seller = $user_id;
          $buyer = $gettrade->user_id;

        }
        
        $insertData['user_id']= $user_id;
        $insertData['tradestatus'] = 'open';

        // echo $crypto_amt.'- - '.$gettrade->minimumtrade;
            if($fiats_amt < $gettrade->minimumtrade)
            {

               $data['status'] = 'error';
              $data['msg'] = 'Amount you have entered is less than minimum p2p limit';
              $data['return_datas'] ='';

            }
            elseif($fiats_amt > $gettrade->maximumtrade)
            {
             
              $data['status'] = 'error';
              $data['msg'] = 'Amount you have entered is more than maximum p2p limit';
              $data['return_datas'] ='';
            }
             elseif($crypto_amt > $gettrade->trade_amount)
            {
             
              $data['status'] = 'error';
              $data['msg'] = 'Amount you have entered is more than User Trade Amount';
              $data['return_datas'] ='';
            }
            

        else {
 


        $insert = $this->common_model->insertTableData('p2ptradeorder', $insertData);
        if($insert)
        {
         
         $link=base_url().'p2ptrade/'.$gettrade->type.'/'.$id.'/'.$insert;
         $this->common_model->updateTableData('p2ptradeorder',array('id'=>$insert),array('link'=>$link));
         $this->common_model->updateTableData('p2p_trade',array('tradeid'=>$id),array('tradestatus'=>'filled'));
         $checktrade = $this->common_model->getTableData('p2p_trade', array('tradeid' => $id))->row();
         $checktradeorder=  $this->common_model->getTableData('p2ptradeorder', array('id' => $insert))->row();
        
         $buyerusername = $this->common_model->getTableData('users', array('id' => $buyer))->row('cpm_username');
         $sellername = $this->common_model->getTableData('users', array('id' => $seller))->row('cpm_username');
        
        $insopenchat['added'] =  date("Y-m-d H:i:s"); 
        $insopenchat['user_id'] = $user_id;
        $insopenchat['tradeuser_id'] = $gettrade->user_id;
        $insopenchat['tradeid'] = $gettrade->tradeid;
        $insopenchat['username'] = $buyerusername;
        $insopenchat['tradeorderid'] = $insert; 
        $insopenchat['imagetype'] = "alertmsg";
        $insopenchat['tradestatus'] = "open";
        $insopenchat['comment'] = "The Buyer will have ".$gettrade->offertime." minutes to make their payment and click on the PAID button before the trade expires.";

        $this->common_model->insertTableData('p2pchat_history', $insopenchat);   
        
        $getcrypto=$this->common_model->getTableData('currency', array('id' => $gettrade->cryptocurrency))->row();
        $buyeremail=getUserEmail($buyer);
        $selleremail=getUserEmail($seller);                                   

           // Update user balance  
           // $updatebalance = updateBalance($checktrade->user_id,$checktrade->cryptocurrency,$finalbalance,'');
           // check to see if we are Seller
           $email_templats = 'Trade_user_requests_buyer';
           $special_varsseller = array(
           '###USERNAME###' => $sellername,
           '###BUYER###' => $buyerusername,
           '###AMOUNT###'   =>  $checktradeorder->amount,
           '###COIN###'  =>   $checktradeorder->amtofbtc,
           '###TRADCURRENCY###' =>  $getcrypto->currency_symbol,
           '###TRADEID###'=> "#".$id,
           '###CRYPTO###'=> $getcrypto->currency_symbol,
           '###LINK###' => base_url().'exchange/#/chat/'.$id.'/'.$insert);

            $this->email_model->sendMail($selleremail, '', '', $email_templats, $special_varsseller);

            // check to see if we are Buyer 
            $email_template = 'Trade_user_requests_seller';
         
             $special_varsbuyer = array(
             '###USERNAME###' => $buyerusername,
             '###SELLER###' => $sellername,
             '###AMOUNT###'   =>  $checktradeorder->amount,
             '###COIN###'  =>   $checktradeorder->amtofbtc,
             '###TRADCURRENCY###' =>  $getcrypto->currency_symbol,
             '###TRADEID###'=> "#".$id,
             '###CRYPTO###'=> $getcrypto->currency_symbol,
             '###LINK###' => base_url().'exchange/#/chat/'.$id.'/'.$insert);

            $this->email_model->sendMail($buyeremail, '', '', $email_template, $special_varsbuyer);


            $data['return_datas'] = array(
              'type' => $gettrade->type,
              'tradeid' => $id,
              'insertid' => $insert,

            );
            $data['status'] = 'success';
            $data['msg'] = 'P2P Trade Started.Successfully Placed !';
            $data['Buyer-Seller'] = $buyeremail.' --  '.$selleremail;
 
        }

       } 



    }
    echo json_encode($data);
}








public function GetP2pOrders($fiat,$crypto,$type='')
{

  // $user_id = $this->session->userdata('user_id');
  $user_id = 1;

  if($user_id > 0) {

  if($fiat > 0 && $crypto > 0 && $type!='') {

    $minPrice = 0;
    // echo $minPrice." Min ";
    if($minPrice > 0 )
    {

      $order =  $this->common_model->getTableData('p2p_trade', array('price' => $minPrice,'cryptocurrency'=>$crypto,'fiat_currency'=>$fiat,'paid_status'=>'open','type'=>$type))->row();
        
        // echo $this->db->last_query();

       if($order!=null || $order!='' && $order->user_id!=$user_id)
       {

          $payment = get_servicename($order->payment_method);
          $data['status'] = 'success';
          $data['order'] = $order;
          $data['payments'] = $payment;
           $data['minPrice'] = $minPrice;

          
       }
       else
       {
        $data['status'] = 'error';
       }
       

     }
     else
     {
      $data['status'] = 'error';
     }
     echo json_encode($data); 

  }

 }

}



 
 function p2p_offer(){  
 $data['currency']=$this->common_model->getTableData('currency')->result();
 $data['country'] = $this->common_model->getTableData('countries')->result();
 $data['service'] = $this->common_model->getTableData('service')->result();
 $this->load->view('front/p2p/p2ppage',$data);
 }



// Spot Fiat Order Functions Start

 public function spotorder(){
     

   


      if($this->input->post())
      {
     
        
        $user_id=$this->input->post('user_id');
        $cryptocurrency=$this->input->post('cryptocurrency');
        $fiat_currency=$this->input->post('fiat_currency');
        $fiat_amount=$this->input->post('fiat_amount');
        $crypto_amount = $this->input->post('crypto_amount');
        $bank_id = $this->input->post('bank_id');
        $bank_type = $this->input->post('bank_type');
        $card_name = $this->input->post('card_name');
        $card_number = $this->input->post('card_number');
        $expiry_date = $this->input->post('expiry_date');
        $ccv = $this->input->post('ccv');
        $type = $this->input->post('type');
        $perprice = $this->input->post('perprice');
        $status = $this->input->post('status');
        $VaildText = $this->input->post('VaildText');

        if($type=='buy')
        {
          $user_bal = getBalance($user_id,$fiat_currency);
          $amount = $fiat_amount;

        }
        else if($type=='sell')
        {
          $user_bal = getBalance($user_id,$cryptocurrency);
           $amount = $crypto_amount;
        }


        if($VaildText=='LZEWJZ1KDE' && $user_id > 0 && $cryptocurrency!='') {

          if($user_bal > $amount) {

              $traderandom = mt_rand(10000000,99999999);
              $spotorders=array(

              'unique_id' =>  $traderandom,
              'user_id'=>$user_id,
              'cryptocurrency'=>$cryptocurrency,
              'fiat_currency'=>$fiat_currency,
              'fiat_amount'=>$fiat_amount,
              'crypto_amount'=>$crypto_amount,
              'bank_id'=>$bank_id,
              'bank_type'=>$bank_type,
              'card_name'=>$card_name,
              'card_number'=>$card_number,
              'expiry_date'=>$expiry_date,
              'ccv'=>$ccv,
              'type'=>$type,
              'perprice'=>$perprice,
              'status'=>$status,
              'datetime'=>date("Y-m-d h:i:s")
              );
           
           $insert = $this->common_model->insertTableData('spotfiat', $spotorders);
           if($insert)
           {
              $data['status'] ='success';
              $data['insertId'] = $insert;
              $data['msg'] ='Spot Order Placed Successfully';

           }
           else
           {
              $data['status'] ='error';
              $data['msg'] ='Failed! Please Try Again Later!';
           }
        }
        else
        {
              $data['status'] ='error';
              $data['msg'] ='Your account has insufficient balance. Please fund your account.';
        }   


    }
    else
    {
      $data['status'] ='error';
      $data['msg'] ='Invalid Datas. Please Try Again Later!';
    }
    

      echo json_encode($data);            


}

}

 public function userCPMBankList($user_id='')
  {
      
  if($user_id!='' && $user_id > 0)
    {
        $bank_details = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id,'currency'=>'6'))->row();
        if(isset($bank_details))
        {
          echo json_encode($bank_details); 
        }
    }
    
  }


   public function FiatP2pCurrencieslist()
  {
      // $user_id=$this->session->userdata('user_id');
    $user_id =1;
      if($user_id!='' && $user_id > 0)
    {
  $session_country = $this->session->userdata('country');
  if($session_country!='')
  {
  $fiatcurrency=$this->common_model->getTableData('fiat_currency',array('status' => 1,'country'=>$session_country))->result();
  }
  else
  {
    $fiatcurrency=$this->common_model->getTableData('fiat_currency',array('status' => 1))->result();
  }


        if(isset($fiatcurrency))
        {
          echo json_encode($fiatcurrency); 
        }
    }

  }


  public function CountryFiat()
  {

     // $user_id=$this->session->userdata('user_id');
    $user_id =1;
     $session_country = $this->session->userdata('country');

     if($user_id!='' && $user_id > 0)
    {

      if($session_country!='')
      {
        $data = $this->common_model->getTableData('fiat_currency',array('status' => 1,'country'=>$session_country))->row();
      }
      else
      {
        $data = $this->common_model->getTableData('fiat_currency',array('status' => 1))->row();
      }

      if(isset($data))
        {
          echo json_encode($data); 
        }

    }


  }



   public function userSpotOrders($user_id='')
  {
      
      // echo "user id ".$user_id;

    if($user_id!='' && $user_id > 0)
    {
        // $userSpotOrders = $this->common_model->getTableData('spotfiat',array('user_id'=>$user_id))->result();

        $where   = array('buyerid'=>$user_id);
        $whereor = array('sellerid'=>$user_id);
        $userSpotOrders = $this->common_model->getTableData('p2ptradeorder',$where,'','',$whereor,'','','',array('id','DESC'))->result();

        // print_r($userSpotOrders);
        if(isset($userSpotOrders))
        {




          $arr = array();
          $i=0;
          foreach($userSpotOrders as $orders)
          {
            $crypto = getcurrencydetail($orders->cryptocurrency);
            $fiat = getfiatcurrencydetail($orders->fiat_currency);

            $GetTrade = $this->common_model->getTableData('p2p_trade',array('tradeid'=>$orders->tradeid))->row();


            $crypto_img = $crypto->image;
            $fiat_img = $fiat->image;

            $senDatas[$i] = array(

              "crypto_sym" => $crypto->currency_symbol,
              "fiat_sym" => $fiat->currency_symbol,
              "fiat_img" => $fiat->image,
              "crypto_img" => $crypto->image,
              "fiat_amount" => $orders->fiat_amount,
              "crypto_amount" => $orders->crypto_amount,
              "perprice" => $GetTrade->price,
              "type" => ucfirst($orders->type),
              "status" => ucfirst($GetTrade->paid_status),
              "datetime" => $orders->tradeopentime

            );
             array_push($arr,$senDatas);
             $i++;

          }

          $datas = $senDatas;
        }
        else
        {
          $datas = [];
        }
        // echo "<pre>";
        // print_r($datas);
        // echo "<pre>";
         echo json_encode($datas); 
    }
    


  }

  public function Notify_seller()
  {

     if($this->input->post())
      {

      $user_id = $this->input->post('user_id');
      $spotid = $this->input->post('spotid');
      $sellerid = $this->input->post('sellerid');
      $fiatsymbol = $this->input->post('fiatsymbol');
      $cryptosymbol = $this->input->post('cryptosymbol');

      $spot_details = $this->common_model->getTableData('spotfiat',array('id'=>$spotid,'type'=>'sell','status'=>'pending'))->row();

      $seller_name = getUserDetails($sellerid,'cpm_username');
      $buyerusername = getUserDetails($user_id,'cpm_username');
      $selleremail=getUserEmail($sellerid);    


      if(isset($spot_details)) {

           $email_templats = 'Spot_Notify_Seller';
           $special_varsseller = array(
           '###USERNAME###' => $seller_name,
           '###BUYER###' => $buyerusername,
           '###AMOUNT###'   =>  $spot_details->crypto_amount,
           '###FIAT###'  =>   $spot_details->fiat_amount,
           '###TRADCURRENCY###' =>  $cryptosymbol,
           '###SPOTID###'=> "#".$spotid,
           '###CRYPTO###'=> $cryptosymbol,
           '###LINK###' => base_url().'spot/'.$spot_details->unique_id);
            $this->email_model->sendMail($selleremail, '', '', $email_templats, $special_varsseller);

            $data['status'] ='success';
            $data['msg'] ='Successfully sent to the Seller';
       }
       else
       {
              $data['status'] ='error';
              $data['msg'] ='No Records! Please Try Again Later!';
       }     
    }
    else
    {
              $data['status'] ='error';
              $data['msg'] ='Invalid Datas! Please Try Again Later!';
    }
    echo json_encode($data); 
  }


// Spot Fiat Order Functions End 


// Convert Section Start

  public function marketprice($currency='')
  {
    
    $data['market_price'] = 0.02439;
    echo json_encode($data);

  }


  public function Test()
  {
    
  }


  // Spot
  public function spot()
  {
    
    $user_id=$this->session->userdata('user_id');
    if($user_id=="")
    { 
      $this->session->set_flashdata('success', 'Please Login');
      redirect(base_url().'home');
    }


    $data['currency']=$this->common_model->getTableData('currency','','','','','','','',array('id','asc'))->result();
    $data['fiatcurrency']=$this->common_model->getTableData('fiat_currency',array('status' => 1))->result();
    $data['country'] = $this->common_model->getTableData('countries')->result(); 
    $data['services'] = $this->common_model->getTableData('service')->result();
    $data['userInfo'] = $this->common_model->getTableData('users', array('id' => $user_id))->row();
    $data['site_common'] = site_common();
    $this->load->view('front/p2p/spot',$data);


  }




}


