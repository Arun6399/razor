<?php
/**
 * Admin Class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @category Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 *
 */
require_once APPPATH.'third_party/src/Google_Client.php';
require_once APPPATH.'third_party/src/contrib/Google_Oauth2Service.php';
class Admin_wallet extends CI_Controller
{
    // Constructor function
    public function __construct()
    {
        parent::__construct();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        $this->load->library(array('form_validation'));
        $this->load->helper(array('url', 'language'));

    }

    function wallet_ajax()
    {
        $sessionvar=$this->session->userdata('loggeduser');
        if (!$sessionvar) {
            admin_redirect('admin', 'refresh');
        }

        $draw = $this->input->get('draw');
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $order = $this->input->get("order");
        $search= $this->input->get("search");
        $search = $search['value'];
        
        $col = 0;
        $dir = "";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc")
        {
            $dir = "desc";
        }

        $valid_columns = array(
            0=>'id',
            1=>'addresses',
            2=>'wallet_balance',
            3=>'wallet_balance'
        );
        if(!isset($valid_columns[$col]))
        {
            $order = null;
        }
        else
        {
            $order = $valid_columns[$col];
        }
        if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }
        $like = '';

        if(!empty($search))
        { 
            $like = "currency_name LIKE '%".$search."%'";
            $query = "SELECT currency_name,currency_symbol FROM bidex_currency  WHERE ".$like." LIMIT ".$start.",".$length;

           //  $query = "SELECT * FROM bidex_admin_wallet  WHERE id=1".$like." LIMIT ".$start.",".$length;
           //  $countquery = $this->db->query("SELECT * FROM bidex_admin_wallet WHERE id=1".$like);

            $users_historys = $this->db->query($query);
           $users_history_results = $users_historys->result(); 
           $num_rows1 = count((array)$users_history_results);  
           if($num_rows1>0){
           $currency1 =  array_column($users_history_results, "currency_symbol");
           //echo "<pre>"; print_r($currency1);
           }
           
           // echo "<pre>"; print_r($users_history_result);
           // exit();
           // $get_admin_det = json_decode($users_history_result->addresses);
           // $num_rows = $countquery->num_rows();
            //$num_rows = count((array)$get_admin_det);    
        }
       
            $query = 'SELECT * FROM bidex_admin_wallet WHERE id=1 LIMIT '.$start.','.$length;

            $countquery = $this->db->query('SELECT * FROM bidex_admin_wallet WHERE id=1');
            $users_history = $this->db->query($query);
            $users_history_result = $users_history->row(); 
            $get_admin_det = json_decode($users_history_result->addresses);
            $num_rows = count((array)$get_admin_det);         
        
        $tt = $query;
        if($num_rows>0)
        {
            $get_admin_det2 = $users_history->row();
            $get_admin_det = json_decode($get_admin_det2->addresses);
            foreach ($get_admin_det as $key => $value) 
            {
                
               $sym =  $key;                                
               $whers_con       =  "currency_symbol='$sym'";                                      
               $curr            =  $this->common_model->getrow("currency",$whers_con);
               $whers_con1      =  "id='1'";                                
               $curr1           =  $this->common_model->getrow("admin_wallet",$whers_con1);
               $balance_admin   =  json_decode($curr1->wallet_balance);        
               // if($sym=="XRP" || $sym=="ETH"){
               //      $withdraw_url = base_url() . "bidex_admin/admin_wallet/admin_withdraw_from_user/" . $sym;
               // }else{
               //      $withdraw_url = base_url() . "bidex_admin/admin_wallet/admin_withdraw/" . $sym;
               // }
               // $withdraw_url = base_url() . "bidex_admin/admin_wallet/admin_withdraw/" . $sym;
                    $withdraw_url= base_url() . "bidex_admin/admin_wallet/admin_tfas/" . $sym;
               if($sym !='USD')
               {
                   if($sym=='USDT')
                   {
                    $format = 6;
                   }
                   else
                   {
                    $format = 8;
                   }
               }                                       
               else
               {
                $format = 2;
               }
               $i++;
               $img = $curr->image;
               $imag = '<img src="'.$img.'" width="25" height="25">';
               $name = $curr->currency_name;
               $link = base_url()."bidex_admin/admin_wallet/admin_deposit/".$sym;
               $deposit = '<a class="table-btn" href="'.$link.'">Deposit</a>';
               $withdraw = '<a href="'.$withdraw_url.'" class="table-btn1">Withdraw</a>';        
                if(!empty($currency1)){
                   if(in_array($key, $currency1)) {
                   $data[] = array(
                        $i, 
                        $imag." ".$name,
                        number_format($balance_admin->$key,$format).' '.$sym,
                        $deposit." ".$withdraw
                    );
                   }
                }else{
                $data[] = array(
                        $i, 
                        $imag." ".$name,
                        number_format($balance_admin->$key,$format).' '.$sym,
                        $deposit." ".$withdraw
                    );
                }
                }
        }
        else
        {
            $data = array();
        }
    
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $num_rows,
            "recordsFiltered" => $num_rows,
            "data" => $data,
            "query"=> $tt
        );
        echo json_encode($output);
    }



    public function admin_tfa(){ 
        $data['prefix'] = get_prefix();
        $data['title'] = 'Admin TFA';
        $data['meta_keywords'] = 'Admin TFA';
        $data['meta_description'] = 'Admin TFA';
        $data['main_content'] = 'admin_wallet/admin_tfa';
        $data['view'] = 'view_all';
        $data['currency_name'] = $coinname;
        $data['coinname'] = $currency_name;
        $data['cid'] = $currency_id;


         $this->load->library('Googleauthenticator');
        $data['site_settings'] = $this->common_model->getTableData('site_settings')->row();
        if($data['site_settings']->randcode=="enable" || $data['site_settings']->secret!="")
    { 
      $secret = $data['site_settings']->secret; 
      $data['secret'] = $secret;
      $ga = new Googleauthenticator();
      $data['url'] = $ga->getQRCodeGoogleUrl('Bidex', $secret);

    }
    else
    {
      $ga = new Googleauthenticator();
      $data['secret'] = $ga->createSecret();
      $data['url'] = $ga->getQRCodeGoogleUrl('Bidex', $data['secret']);
      $data['oneCode'] = $ga->getCode($data['secret']);
    }

        $site_settings=$this->common_model->getTableData('site_settings')->row();
        $data['secret'] = $site_settings->secret;
        $data['status'] = $site_settings->randcode;
        $data['action'] = admin_url() . 'admin_wallet/admin_tfa_submit';
        $data['withdraw_userid'] = $userid; 
         $this->db->select('*')
            ->from('wallet')
            ->join('users', 'wallet.user_id = users.id');
        $userWalletResult = $this->db->get();
        $userWalletInfo = $userWalletResult->result();
        $data['usersList'] = $userWalletInfo;
        $this->load->view('administrator/admin_template', $data);

    }

    public function admin_tfa_submit(){

  if(isset($_POST['tfa_sub']))
    {

      $this->load->library('Googleauthenticator');
      $ga = new Googleauthenticator();
      $secret_code = $this->db->escape_str($this->input->post('secret'));
      $onecode = $this->db->escape_str($this->input->post('code'));
      $code = $ga->verifyCode($secret_code,$onecode,$discrepancy = 6);
      $data['site_settings'] = $this->common_model->getTableData('site_settings')->row();
      if($data['site_settings']->randcode != "enable")
      {
        
        if($code==1)
        {
     
          $data1=array('secret'  => $secret_code,'randcode'  => "enable");
          $data1_clean = $this->security->xss_clean($data1);
          $this->common_model->updateTableData('site_settings',array('id'=>1),$data1_clean);

          $this->session->set_flashdata('success','TFA Enabled successfully');
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          // front_redirect('settings_security', 'refresh');
            admin_redirect('admin_wallet/admin_tfa', 'refresh');
        }
        else
        {
       
          $this->session->set_flashdata('error','Please Enter correct code to enable TFA');
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          admin_redirect('admin_wallet/admin_tfa', 'refresh');
        }
      }
      else
      { 
        if($code==1)
        {

          $data1=array('secret'  => $secret_code,'randcode'  => "disable");
          $data1_clean = $this->security->xss_clean($data1);
          $this->common_model->updateTableData('site_settings',array('id'=>1),$data1_clean);  
          $this->session->set_flashdata('success','TFA Disabled successfully');
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          admin_redirect('admin_wallet/admin_tfa', 'refresh');
        }
        else
        {

          
          $this->session->set_flashdata('error','Please Enter correct code to disable TFA');
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          admin_redirect('admin_wallet/admin_tfa', 'refresh');
        }
      }
    }
}
    
    function admin_tfas($id=''){


  $site=$this->common_model->getTableData("site_settings")->row();


           if($site->randcode=='disable'){

          $this->session->set_flashdata('error','Please Enable TFA');
              admin_redirect('admin_wallet', 'refresh');


     }


        if($this->input->post()){
           
         $login_tfa=$this->input->post('code');
           // echo "test";
         $sym= $this->input->post('coin');
      $site=$this->common_model->getTableData("site_settings")->row();

     if($site->randcode=='disable'){

          $this->session->set_flashdata('error','Please Enable TFA');
              admin_redirect('admin_wallet/admin_tfas/' .$sym, 'refresh');


     }

             $check='1';
             $check1 = $this->checktfa($check, $login_tfa);
             if($check1){
                  
             if($sym=="XRP" || $sym=="ETH"){
                     // $withdraw_url = base_url() . "bidex_admin/admin_wallet/admin_withdraw_from_user/" . $sym;
                      $this->session->set_flashdata('success','Login Successfully');
                       // admin_redirect("admin_wallet/admin_withdraw_from_user/" . $sym, 'refresh');
                       admin_redirect("admin_wallet/admin_withdraw/" . $sym, 'refresh');  

                  }else{
                     $this->session->set_flashdata('success','Login Successfully');
                     admin_redirect("admin_wallet/admin_withdraw/" . $sym, 'refresh');  

             }

         }else{
             $this->session->set_flashdata('error','Please Enter Valid TFA Code');
              admin_redirect('admin_wallet/admin_tfas/' .$sym, 'refresh');
          
             }
        }
        $data['prefix'] = get_prefix();
        $data['title'] = 'Admin TFA';
        $data['meta_keywords'] = 'Admin TFA';
        $data['meta_description'] = 'Admin TFA';
        $data['main_content'] = 'admin_wallet/admin_tfalogin';
        $data['view'] = 'view_all';
        $data['coin']=$id;
        $data['action'] = '';

 $this->load->view('administrator/admin_template', $data);

    }



          function checktfa($user_id,$code)
    {
        $this->load->library('Googleauthenticator');
        $ga     = new Googleauthenticator();
        $result = $this->common_model->getTableData('site_settings', array('id' => '1'))->row_array();
        if(count($result)){
            $secret = $result['secret'];
            $oneCode = $ga->verifyCode($secret,$code,$discrepancy = 3);
            if($oneCode==1)
            {
                return true;
            }
            else
            {
                return false;
            }
       }else
       return false;
    }

    public function index()
    {

        $sessionvar = $this->session->userdata('loggeduser');
        if (!$sessionvar) {
            admin_redirect('admin', 'refresh');
        }

        $data['prefix'] = get_prefix();
        $data['title'] = 'Admin Wallet Withdraw';
        $data['meta_keywords'] = 'Admin Wallet Withdraw';
        $data['meta_description'] = 'Admin Wallet Withdraw';
        $data['main_content'] = 'admin_wallet/admin_wallets';
        $data['view'] = 'view_all';

        $whers_con = "id='1'";
        $get_admin = $this->common_model->getrow("admin_wallet", $whers_con);

        $data['get_admin_det'] = json_decode($get_admin->addresses);

        $this->load->view('administrator/admin_template', $data);
    }

    public function admin_deposit($coinname)
    {
        

            $sessionvar = $this->session->userdata('loggeduser');
            if (!$sessionvar) {
                admin_redirect('admin', 'refresh');
            }

            $data['curr']=$coinname;
            $whers_con = "currency_symbol='$coinname'";
            $curr = $this->common_model->getrow("currency", $whers_con);
            if (!empty($curr)) {
                $data['currency_id'] = $curr->id;
                $currency_id = $curr->id;
                $currency_name = $curr->currency_name;
            }

            $data['prefix'] = get_prefix();
            $data['title'] = 'Admin Wallet Deposit';
            $data['meta_keywords'] = 'Admin Wallet Deposit';
            $data['meta_description'] = 'Admin Wallet Deposit';
            $data['main_content'] = 'admin_wallet/admin_deposit';
            $data['view'] = 'view_all';

            $whers_con = "id='1'";
            $get_admin = $this->common_model->getrow("admin_wallet", $whers_con);

            $get_admin_det = json_decode($get_admin->addresses);

            foreach ($get_admin_det as $key => $value) {
                if ($key == $coinname) {
                    $data['address'] = $value;
                    $data['curr'] = $coinname;

                }
            }
            $data['tag']=$get_admin->XRP_tag; 

            $this->db->select('*')
                 ->from('bidex_wallet')
                 ->join('bidex_users', 'bidex_wallet.user_id = bidex_users.id');
            $userinfo = $this->db->get();
            $userAddrInfo = $userinfo->result();
            $data['usersinfo'] = $userAddrInfo;
            $data['coinname'] = $currency_name;
             $data['currency_name'] = $coinname;
            $data['cid'] = $currency_id;

            $data['coin_img'] = "https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$" . $data['address'] . "&choe=UTF-8&chld=L";

            $this->load->view('administrator/admin_template', $data);
    }

    public function admin_withdraw_old($coinname, $userid = "")
    {
        /*error_reporting(E_ALL);
ini_set("display_errors",1);*/
        //If Already logged in
        $sessionvar = $this->session->userdata('loggeduser');
        if (!$sessionvar) {
            admin_redirect('admin', 'refresh');
        }
        $data['curr']=$coinname;
        $whers_con = "currency_symbol='$coinname'";
        $curr = $this->common_model->getrow("currency", $whers_con);
        if (!empty($curr)) {
            $data['currency_id'] = $curr->id;
            $currency_id = $curr->id;
            $currency_name = $curr->currency_name;
        }

        $whers_con = "id='1'";
        $get_admin = $this->common_model->getrow("admin_wallet", $whers_con);
        if(!empty($get_admin)){
            $get_admin_det = json_decode($get_admin->wallet_balance);
        }
        // When Post
        if ($this->input->post()) 
        {
           
            $this->form_validation->set_rules('withdraw_to_address', 'Withdraw to address', 'trim|required|xss_clean');
            $this->form_validation->set_rules('withdrawalAmount', 'Withdraw Amount', 'trim|required|xss_clean');
            if ($coinname == "XRP") {
                $this->form_validation->set_rules('destinationTag', 'Destination tag', 'trim|required|xss_clean');
            }
            
            if ($this->form_validation->run()==TRUE) {
                // Withdraw address
                $withdraw_to_address = $this->input->post('withdraw_to_address');
                $user_id = $userid;

                $amount = $this->input->post('withdrawalAmount');
                $address = $this->input->post('withdraw_to_address');
                $destinationTag = $this->input->post('destinationTag');

                $balance =0;

                $get_user_det = getAddress($userid,$currency_id);

                if ($coinname == "ETH" || $coinname == "ATRX" || $coinname == "USDT") 
                {
                    //get particular user balance from blockchain
                    $balance = $this->local_model->wallet_balance($currency_name,$get_user_det);

                }
                else
                {
                    $balance=$get_admin_det->$coinname;
                }
                //echo "BAL=>".$balance;

                $currency = getcryptocurrencydetail($currency_id);
                $currency_symbol = $coinname;
                $details_info['balance'] = $balance;

                // Convert amount to USD
                $market_price_USD = json_decode(convercurr($currency->currency_symbol, 'USD'));
                $curs_marketprice = $market_price_USD->USD; // Currency market price for USD respective to slctd
                $re_with_lim = $amount * $curs_marketprice; // convert the amount to usd

                $w_isValids = $this->common_model->getTableData('admin_transactions', array('user_id' => $user_id, 'type' => 'Withdraw', 'status' => 'Pending', 'currency_id' => $currency_id));
                $count = $w_isValids->num_rows();
                $withdraw_rec = $w_isValids->row();

                $final = 1;
                $Validate_Address = 0;
                if ($currency->currency_symbol == 'RVN') {
                    $Validate_Address = $this->local_model->validatate_address($currency->currency_name, $address);
                } else if ($currency->currency_symbol == "ATRX") {
                    $Validate_Address = $this->local_model->validatate_address($currency->currency_name, $address);
                } else if ($currency->currency_symbol == "ETH") {
                    $Validate_Address = $this->local_model->validatate_address($currency->currency_name, $address);
                } else if ($currency->currency_symbol == "USDT") {
                    $Validate_Address = $this->local_model->validatate_address($currency->currency_name, $address);
                } else { //echo "BTC";
                    $Validate_Address = $this->local_model->validatate_address($currency->currency_name, $address);
                }
//echo "CALID".$Validate_Address;
                if ($Validate_Address == 1) 
                {
                    if ($count > 0) 
                    {
                        $this->session->set_flashdata('cryerror', 'Sorry!!! Your previous ' . $currency_symbol . ' withdrawal is Processing. Please use other wallet or be patience');
                    } 
                    else 
                    {
                        if ($amount > $balance) 
                        {
                            $this->session->set_flashdata('cryerror', 'The amount entered exceeds the Balance that can be transfered.');
                        } 
                        elseif ($final != 1) 
                        {
                            $this->session->set_flashdata('cryerror', 'Please enter a valid Amount / Address');
                        } 
                        else 
                        {
                            $tagid = $this->input->post('destinationTag');
                            if ($tagid == "") 
                            {
                                $tagid = 0;
                            } 
                            else 
                            {
                                $tagid = $destinationTag;
                            }

                            if($coinname == "ATRX")
                            {
                                $eth_balance = $this->local_model->wallet_balance("Ethereum",$get_user_det);
                                if($eth_balance >= 0.05)
                                {
                                    $valid_withdraw = "1";
                                }
                                else
                                {
                                    $valid_withdraw = "0";
                                }
                            }
                            else if($coinname == "USDT")
                            {
                                $eth_balance = $this->local_model->wallet_balance("Ethereum",$get_user_det);
                                if($eth_balance >= 0.05)
                                {
                                    $valid_withdraw = "1";
                                }
                                else
                                {
                                    $valid_withdraw = "0";
                                }
                            }
                            else
                            {
                                $valid_withdraw = "1";
                            }

                            if($valid_withdraw == "1")
                            { 
                                $insertData = array(
                                'user_id' => $user_id,
                                'currency_id' => $currency_id,
                                'currency_name' => $currency_name,
                                'amount' => $amount,
                                'tag_id' => $tagid,
                                'crypto_address' => $address,
                                'type' => 'Withdraw',
                                'status' => 'Pending',
                                'datetime' => gmdate(time()),
                                );
                                $insert = $this->common_model->insertTableData('admin_transactions', $insertData);
                                if ($insert) {
                                    $id=$insert;
                                    $isValids = $this->common_model->getTableData('admin_transactions', array('trans_id' => $insert, 'type' => 'withdraw'));
                                    $isValid = $isValids->num_rows();
                                    $withdraw = $isValids->row();

                                    if ($isValid > 0) {
                                        $fromid = $withdraw->user_id;
                                        $fromuser = $this->common_model->getTableData('users', array('id' => $fromid))->row();
                                        $currency_id = $withdraw->currency_id;
                                        // echo "<br>currency id: ".$fromid." --" . $currency_id . "<br>";
                                        if ($currency_id == "5") {
                                            $fromacc = getAddress($fromid, $currency_id);
                                        } else {
                                            // $fromacc   = getUserEmail($fromid);
                                            $fromacc = getAddress($fromid, $currency_id);
                                        }
                                        // echo "<br>from_acc:" . $fromacc . "<br>";
                                        $xrp_tag_det = $this->common_model->getTableData('crypto_address', array('user_id' => $fromid))->row();
                                        if ($withdraw->status == 'Completed') {
                                            $this->session->set_flashdata('error', 'Your withdraw request already confirmed');
                                            admin_redirect('bidex_admin/admin_wallet', 'refresh');
                                        } else if ($withdraw->status == 'Cancelled') {
                                            $this->session->set_flashdata('error', 'Your withdraw request already cancelled');
                                            admin_redirect('bidex_admin/admin_wallet', 'refresh');
                                        } else {
                                            $amount = $withdraw->amount;
                                            $address = $withdraw->crypto_address;
                                            $tagid = $withdraw->tag_id;
                                            $currency = $withdraw->currency_id;
                                            $coin_name      = getcryptocurrencys($currency);
                                            // $coin_name = strtolower($coin_name);
                                            $coin_new_name = $coin_name;
                                            $coin_symbol = getcryptocurrency($currency);
                                            $coin_name = str_replace(" ", "", $coin_name);
                                            $from_address1 = getAddress($withdraw->user_id, $withdraw->currency_id);

                                            // echo $from_address1; 
                                           
                                            if ($coin_name != "") {

                                                    if ($coin_name == "Ethereum") 
                                                    {
                                                         $wallet_bal = $this->local_model->wallet_balance($coin_name, $from_address1);

                                                    }
                                                    else
                                                    {
                                                        $wallet_bal=$get_admin_det->$coinname;
                                                    }
                                                
                                                if ($wallet_bal >= $amount) {
                                                    switch ($coin_name) 
                                                    {
                                                        case 'ATEREX':
                                                            $from_address = getAddress($withdraw->user_id, $withdraw->currency_id);
                                                            $from_address = trim($from_address);
                                                            $to = trim($address);
                                                            $amounts = $amount * 1000000000000000000;
                                                            $amount1 = rtrim(sprintf("%u", $amounts), ".");
                                                            $GasLimit = 21000;
                                                            $Gwei = 30 * 1000000000;
                                                            $GasPrice = $Gwei;
                                                            $trans_det = array('from' => $from_address, 'to' => $to, 'value' => (float) $amount1, 'gas' => (float) $GasLimit, 'gasPrice' => (float) $GasPrice);

                                                            break;
                                                        case 'Tether':
                                                        $from_address = getAddress($withdraw->user_id, $withdraw->currency_id);
                                                        $from_address = trim($from_address);
                                                        $to = trim($address);
                                                      //  $amounts = $amount * 1000000000000000000;
                                                          $amounts = $amount * 1000000;
                                                        $amount1 = rtrim(sprintf("%u", $amounts), ".");
                                                        $GasLimit = 21000;
                                                        $Gwei = 30 * 1000000000;
                                                        $GasPrice = $Gwei;
                                                        $trans_det = array('from' => $from_address, 'to' => $to, 'value' => (float) $amount1, 'gas' => (float) $GasLimit, 'gasPrice' => (float) $GasPrice);

                                                        break;
                                                        case 'Ethereum':
                                                            $from_address = getAddress($withdraw->user_id, $withdraw->currency_id);
                                                            $from_address = trim($from_address);
                                                            $to = trim($address);
                                                            $amounts = $amount * 1000000000000000000;
                                                            $amount1 = rtrim(sprintf("%u", $amounts), ".");
                                                            $GasLimit = 21000;
                                                            $Gwei = 30 * 1000000000;
                                                            $GasPrice = $Gwei;
                                                            $trans_det = array('from' => $from_address, 'to' => $to, 'value' => (float) $amount1, 'gas' => (float) $GasLimit, 'gasPrice' => (float) $GasPrice);
                                                            //$trans_det         = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1);
                                                            /*$trans_det         = array('address'=>$address,'amount'=>(float)$amount,'comment'=>'Admin Confirms Withdraw');*/
                                                            break;

                                                        case 'Ripple':
                                                            $trans_det  = array('fromacc'=>$fromacc,'toaddress'=>$address,'amount'=>(float)$amount,'xrp_tag_det'=>$xrp_tag_det->auto_tag,'des_tag'=>$tagid,'secret'=>$xrp_tag_det->auto_gen,'comment'=>'Admin Confirms Withdraw','comment_to'=>'Completed');
                                                            break;
                                                        default:
                                                            $trans_det = array('address' => $address, 'amount' => (float) $amount, 'comment' => 'Admin Confirms Withdraw');
                                                            break;
                                                    }
                                                    // echo "<pre><br>";
                                                    // echo "coin_name:" . $coin_name;
                                                    // print_r($trans_det);
                                                    // die();
                                                     $send_money_res = $this->local_model->make_transfer($coin_name, $trans_det);
                                                     if($send_money_res!="" || $send_money_res!="error")
                                                     {

                                                        $updateData = array('status' => "Completed", 'hash_txid' => $send_money_res,'description' => $send_money_res);
                                                        $condition = array('trans_id' => $id, 'type' => 'Withdraw');
                                                        $update = $this->common_model->updateTableData('admin_transactions', $condition, $updateData);
         
                                                        if ($update) {
                                                            $this->session->set_flashdata('success', "Successfully Withdraw Amount");
                                                            admin_redirect('bidex_admin/admin_wallet', 'refresh');
                                                        } else {
                                                           $this->session->set_flashdata('error', "Error Withdraw Amount");
                                                           admin_redirect('bidex_admin/admin_wallet', 'refresh');
                                                        }
                                                     }
                                                     else
                                                     {
                                                         $this->session->set_flashdata('error', "Error Withdraw Amount");
                                                         admin_redirect('bidex_admin/admin_wallet', 'refresh');
                                                     }
                                                    
                                                } else {
                                                    $this->session->set_flashdata('error', 'Not enough balance to proceed the withdraw request amount');
                                                    admin_redirect('bidex_admin/admin_wallet', 'refresh');
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $this->session->set_flashdata('error','You cant withdraw the USDT Token, Need Eth balance greater than 0.05');
                            }

                            
                        }
                    }
                }
            } else {
                
                $this->session->set_flashdata('error', 'Please enter a valid Amount / Address.');
            }

        }

        $data['prefix'] = get_prefix();
        $data['title'] = 'Admin Wallet Withdraw';
        $data['meta_keywords'] = 'Admin Wallet Withdraw';
        $data['meta_description'] = 'Admin Wallet Withdraw';
        $data['main_content'] = 'admin_wallet/admin_withdraw';
        $data['view'] = 'view_all';
        $data['currency_name'] = $coinname;
        $data['coinname'] = $currency_name;
        $data['cid'] = $currency_id;
        $data['action'] = '';
        $data['withdraw_userid'] = $userid;

        $this->db->select('*')
            ->from('wallet')
            ->join('users', 'wallet.user_id = users.id');
        $userWalletResult = $this->db->get();
        $userWalletInfo = $userWalletResult->result();
        $data['usersList'] = $userWalletInfo;

        $this->load->view('administrator/admin_template', $data);
    }



        public function admin_withdraw($coinname, $userid = "")
    {
        /*error_reporting(E_ALL);
ini_set("display_errors",1);*/
        //If Already logged in
        $sessionvar = $this->session->userdata('loggeduser');
        if (!$sessionvar) {
            admin_redirect('admin', 'refresh');
        }
        $data['curr']=$coinname;
        $whers_con = "currency_symbol='$coinname'";
        $curr = $this->common_model->getrow("currency", $whers_con);
        if (!empty($curr)) {
            $data['currency_id'] = $curr->id;
            $currency_id = $curr->id;
            $currency_name = $curr->currency_name;
            $currency_symbol = $curr->currency_symbol;
        }

        $whers_con = "id='1'";
        $get_admin = $this->common_model->getrow("admin_wallet", $whers_con);
        if(!empty($get_admin)){
            $get_admin_det = json_decode($get_admin->wallet_balance);
        }
        $get_admin_address = json_decode($get_admin->addresses);
        $admin_address = $get_admin_address->$currency_symbol;
        // When Post
        if ($this->input->post()) 
        {

            $this->form_validation->set_rules('withdraw_to_address', 'Withdraw to address', 'trim|required|xss_clean');
            $this->form_validation->set_rules('withdrawalAmount', 'Withdraw Amount', 'trim|required|xss_clean');
            
            if ($this->form_validation->run()==TRUE) {
                // Withdraw address
                $withdraw_to_address = $this->input->post('withdraw_to_address');
                $user_id = 1;

                $amount = $this->input->post('withdrawalAmount');
                $address = $this->input->post('withdraw_to_address');

                $balance=$get_admin_det->$coinname;
                $currency = getcryptocurrencydetail($currency_id);
                $currency_symbol = $coinname;
                $details_info['balance'] = $balance;

                // Convert amount to USD
                $market_price_USD = json_decode(convercurr($currency->currency_symbol, 'USD'));
                $curs_marketprice = $market_price_USD->USD; // Currency market price for USD respective to slctd
                $re_with_lim = $amount * $curs_marketprice; // convert the amount to usd

                $w_isValids = $this->common_model->getTableData('admin_transactions', array('user_id' => $user_id, 'type' => 'Withdraw', 'status' => 'Pending', 'currency_id' => $currency_id));
                $count = $w_isValids->num_rows();
                $withdraw_rec = $w_isValids->row();

                $final = 1;
                $Validate_Address = 0;
               if ($currency->currency_symbol == "ETH") {
                    $Validate_Address = $this->local_model->validatate_address($currency->currency_name, $address);
                } else if ($currency->currency_symbol == "USDT") {
                    $Validate_Address = $this->local_model->validatate_address($currency->currency_name, $address);
                } else if ($currency->currency_symbol == "TRX") {
                    $Validate_Address = $this->local_model->validatate_address($currency->currency_name, $address);
                } else if ($currency->currency_symbol == "DOGE") {
                    $Validate_Address = $this->local_model->validatate_address($currency->currency_name, $address);
                } else { //echo "BTC";
                    $Validate_Address = $this->local_model->validatate_address($currency->currency_name, $address);
                }

                //print_r(expression)
                if ($Validate_Address == 1) 
                {
                    if ($count > 0) 
                    {
                        $this->session->set_flashdata('cryerror', 'Sorry!!! Your previous ' . $currency_symbol . ' withdrawal is Processing. Please use other wallet or be patience');
                    } 
                    else 
                    {
                        if ($amount > $balance) 
                        {
                            $this->session->set_flashdata('cryerror', 'The amount entered exceeds the Balance that can be transfered.');
                        } 
                        elseif ($final != 1) 
                        {
                            $this->session->set_flashdata('cryerror', 'Please enter a valid Amount / Address');
                        } 
                        else 
                        {
                            if($coinname == "USDT")
                            {
                                $eth_address = 
                                $eth_balance = $this->local_model->wallet_balance("Ethereum",$get_admin_address->ETH);
                                if($eth_balance >= 0.05)
                                {
                                    $valid_withdraw = "1";
                                }
                                else
                                {
                                    $valid_withdraw = "0";
                                }
                            }
                            else
                            {
                                $valid_withdraw = "1";
                            }

                            if($valid_withdraw == "1")
                            { 
                                $insertData = array(
                                'user_id' => $user_id,
                                'currency_id' => $currency_id,
                                'currency_name' => $currency_name,
                                'amount' => $amount,
                                'crypto_address' => $address,
                                'type' => 'Withdraw',
                                'status' => 'Pending',
                                'datetime' => gmdate(time()),
                                );
                                $insert = $this->common_model->insertTableData('admin_transactions', $insertData);

                                if($insert)
                                {
                                    $balance        = getadminBalance($user_id,$currency_id); // get admin bal
                                    $finalbalance   = $balance-$amount; // bal - withdraw amount
                                    $updatebalance  = updateadminBalance($user_id,$currency_id,$finalbalance); 
                                    $link_ids = base64_encode($insert);
                                    $enc_email = getAdminDetails('1','email_id');
                                    $email = decryptIt($enc_email);
                                    $email_template = 'Withdraw_Admin_Complete';
                                    $special_vars = array(
                                    '###USERNAME###' => 'Admin',
                                    '###AMOUNT###'   => $amount,
                                    '###CURRENCY###' => $currency_name,
                                    '###FEES###' => '',
                                    '###CONFIRM_LINK###' => admin_url().'admin/withdraw_admin_confirm/'.$link_ids,
                                    '###CANCEL_LINK###' => admin_url().'admin/withdraw_admin_cancel/'.$link_ids,
                                    );
                                    $this->email_model->sendMail($email, '', '', $email_template, $special_vars);

                                    $this->session->set_flashdata('success', "Withdraw request placed Successfully, Confirm request sent to your mail, please confirm this request by confirmation link");
                                    admin_redirect('admin_wallet', 'refresh');

                                }
                            }
                            else
                            {
                                $this->session->set_flashdata('error','You cant withdraw the USDT Token, Need Eth balance greater than 0.05');
                            }

                            
                        }
                    }
                }else{
                    $this->session->set_flashdata('error', 'Please enter a valid Address.');
                    admin_redirect('admin_wallet', 'refresh');
                }
            } else {
                
                $this->session->set_flashdata('error', 'Please enter a valid Amount / Address.');
            }

        }

        $data['prefix'] = get_prefix();
        $data['title'] = 'Admin Wallet Withdraw';
        $data['meta_keywords'] = 'Admin Wallet Withdraw';
        $data['meta_description'] = 'Admin Wallet Withdraw';
        $data['main_content'] = 'admin_wallet/admin_withdraw';
        $data['view'] = 'view_all';
        $data['currency_name'] = $coinname;
        $data['coinname'] = $currency_name;
        $data['cid'] = $currency_id;
        $data['action'] = '';
        $data['withdraw_userid'] = $userid;

        $this->db->select('*')
            ->from('wallet')
            ->join('users', 'wallet.user_id = users.id');
        $userWalletResult = $this->db->get();
        $userWalletInfo = $userWalletResult->result();
        $data['usersList'] = $userWalletInfo;

        $this->load->view('administrator/admin_template', $data);
    }

    public function admin_withdraw_from_user($coinname)
    {
        //If Already logged in
        $sessionvar = $this->session->userdata('loggeduser');
        if (!$sessionvar) {
            admin_redirect('admin', 'refresh');
        }

        // When Post
        if ($this->input->post()) {
            $this->form_validation->set_rules('withdraw_address', 'Withdraw Address', 'trim|required|xss_clean');

            if ($this->form_validation->run()) {
                // Withdraw address
                $withdraw_address = $this->input->post('withdraw_address');
                echo "<pre><br><br><br><br>";
                print_r($this->input->post());
                echo "</pre>";
            } else {
                $this->session->set_flashdata('error', 'Please enter a valid Address');
            }
        }

        $data['prefix'] = get_prefix();
        $data['title'] = 'Admin Wallet Withdraw';
        $data['meta_keywords'] = 'Admin Wallet Withdraw';
        $data['meta_description'] = 'Admin Wallet Withdraw';
        $data['main_content'] = 'admin_wallet/admin_withdraw_from_user';
        $data['view'] = 'view_all';
        $data['currency_name'] = $coinname;

        $whers_con = "id='1'";
        $get_admin = $this->common_model->getrow("admin_wallet", $whers_con);

        $get_admin_det = json_decode($get_admin->addresses);

        foreach ($get_admin_det as $key => $value) {
            if ($key == $coinname) {
                $data['address'] = $value;
                $data['curr'] = $coinname;
            }
        }
        $data['coin_img'] = "https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$" . $data['address'] . "&choe=UTF-8&chld=L";

        $this->load->view('administrator/admin_template', $data);
    }

}
