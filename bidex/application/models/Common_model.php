<?php
/**
 * Common Model
 * @package Spiegel Technologies
 * @subpackage smdex
 * @category Models
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
class Common_model extends CI_Model
{
	// Constructor 
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * INSERT data into table model
	 * 
	 * @access Public
	 * @param $tableName - Name of the table(required)
	 * @param $data - Specifies the insert data(required)
	 * @return Last insert ID
	 */
	public function insertTableData($tableName = '', $data = array())
	{
		$this->db->insert($tableName, $data);
		return $this->db->insert_id();
	}
	/**
	 * DELETE data from table
	 * 
	 * @access Public
	 * @param $tableName - Name of the table(required)
	 * @param $where - Specifies the which row will be delete(optional)
	 * @return Affected rows
	 */
	public function deleteTableData($tableName = '', $where = array())
	{
		if ((is_array($where)) && (count($where) > 0)) {
			$this->db->where($where);
		}
		$this->db->delete($tableName);
		return $this->db->affected_rows();
	}
	/**
	 * UPDATE data to table
	 * 
	 * @access Public
	 * @param $tableName - Name of the table(required)
	 * @param $where - Specifies the where to update(optional)
	 * @param $data - Modified data(required) 
	 * @return Affected rows
	 */
	public function updateTableData($tableName = '', $where = array(), $data = array())
	{
		//return $tableName;
		if ((is_array($where)) && (count($where) > 0)) {
			$this->db->where($where);
		}
		return $this->db->update($tableName, $data);
	}

	public function getrow($tableName = '', $where = '')
	{
	  	$this->db->select('*');

	  	$this->db->from($tableName);

	  	$this->db->where($where);

	  	$query = $this->db->get();

	  	if($query)
	  	{
	    	return $query->row();
	  	}
	} 
	/**
	 * SELECT data from table
	 * 
	 * @access Public
	 * @param $tableName - Name of the table(required)
	 * @param $where - Specifies the where to update(optional)
	 * @param $data - Modified data(required) 
	 * @return Affected rows
	 */
	public function getTableData($tableName = '', $where = array(), $selectFields = '', $like = array(), $where_or = array(), $like_or = array(), $offset = '', $limit = '', $orderBy = array(), $groupBy = array(), $where_not = array(), $where_in = array())
	{
		// WHERE AND conditions
		if ((is_array($where)) && (count($where) > 0)) {
			$this->db->where($where);
		}
		// WHERE NOT conditions
		if ((is_array($where_not)) && (count($where_not) > 0)) {
			//echo "<pre>";
			//print_r($where_not);die;
			$this->db->where_not_in($where_not[0], $where_not[1]);
		}
		// WHERE IN conditions
		if ((is_array($where_in)) && (count($where_in) > 0)) {
			$this->db->where_in($where_in[0], $where_in[1]);
		}
		// WHERE OR conditions
		if ((is_array($where_or)) && (count($where_or) > 0)) {
			$this->db->or_where($where_or);
		}
		// $this->db->group_start();
		//LIKE AND 
		if ((is_array($like)) && (count($like) > 0)) {
			$this->db->like($like);
		}
		//LIKE OR 
		if ((is_array($like_or)) && (count($like_or) > 0)) {
			$this->db->or_like($like_or);
		}
		// $this->db->group_end();
		//SELECT fields
		if ($selectFields != '') {
			$this->db->select($selectFields);
		}
		//Group By
		if (is_array($groupBy) && (count($groupBy) > 0)) {
			$this->db->group_by($groupBy[0]);
		}
		//Order By
		if (is_array($orderBy) && (count($orderBy) > 0)) {
			if (count($orderBy) > 2) {
					$this->db->order_by($orderBy[0] . ' ' . $orderBy[1] . ',' . $orderBy[2] . ' ' . $orderBy[3]);
				} else {
					$this->db->order_by($orderBy[0], $orderBy[1]);
				}
		}
		//OFFSET with LIMIT
		if ($limit != '' && $offset != '') {
			$this->db->limit($limit, $offset);
		}
		// LIMIT
		if ($limit != '' && $offset == '') {
			$this->db->limit($limit);
		}
		return $this->db->get($tableName);
	}
	/**
	 * CUSTOM SQL query
	 * 
	 * @access Public
	 * @param SQL query
	 * @return Response  
	 */
	public function customQuery($query)
	{
		return $this->db->query($query);
	}

	public function getJoinedTableData1($tableName = '', $joins = array(),  $where = array(), $selectFields = '', $like = array(), $where_or = array(), $like_or = array(), $offset = '', $limit = '', $orderBy = array(), $group_by = array(), $where_in = array())
	{

		$this->db->from($tableName);
		//join tables list
		if ((is_array($joins)) && (count($joins) > 0)) {
			foreach ($joins as $jointb => $joinON) {
				$this->db->join($jointb, $joinON);
			}
		}

		// WHERE AND conditions
		if ((is_array($where)) && (count($where) > 0)) {
			$this->db->where($where);
		}
		// WHERE OR conditions
		if ((is_array($where_or)) && (count($where_or) > 0)) {
			$this->db->or_where($where_or);
		}
		if ((is_array($where_in)) && (count($where_in) > 0)) {
			$this->db->where_in($where_in[0], $where_in[1]);
		}
		//LIKE AND 
		if ((is_array($like)) && (count($like) > 0)) {
			$this->db->like($like);
		}
		//LIKE OR 
		if ((is_array($like_or)) && (count($like_or) > 0)) {
			$this->db->or_like($like_or);
		}
		//SELECT fields
		if ($selectFields != '') {
			$this->db->select($selectFields, false);
		}
		//Order By
		if (is_array($orderBy) && (count($orderBy) > 0)) {
			$this->db->order_by($orderBy[0], $orderBy[1]);
		}

		//Group By
		if (is_array($group_by) && (count($group_by) > 0)) {
			$this->db->group_by($group_by[0]);
		}
		//OFFSET with LIMIT
		if ($limit != '' && $offset != '') {
			$this->db->limit($limit, $offset);
		}
		// LIMIT
		if ($limit != '' && $offset == '') {
			$this->db->limit($limit);
		}
		return $this->db->get();
	}

	//select records from joined tables
	public function getJoinedTableData($tableName = '', $joins = array(),  $where = array(), $selectFields = '', $like = array(), $where_or = array(), $like_or = array(), $offset = '', $limit = '', $orderBy = array(), $group_by = array())
	{

		$this->db->from($tableName);
		//join tables list
		if ((is_array($joins)) && (count($joins) > 0)) {
			foreach ($joins as $jointb => $joinON) {
				$this->db->join($jointb, $joinON);
			}
		}

		// WHERE AND conditions
		if ((is_array($where)) && (count($where) > 0)) {
			$this->db->where($where);
		}
		// WHERE OR conditions
		if ((is_array($where_or)) && (count($where_or) > 0)) {
			$this->db->or_where($where_or);
		}
		//LIKE AND 
		if ((is_array($like)) && (count($like) > 0)) {
			$this->db->like($like);
		}
		//LIKE OR 
		if ((is_array($like_or)) && (count($like_or) > 0)) {
			$this->db->or_like($like_or);
		}
		//SELECT fields
		if ($selectFields != '') {
			$this->db->select($selectFields, false);
		}
		//Order By
		if (is_array($orderBy) && (count($orderBy) > 0)) {
			$this->db->order_by($orderBy[0], $orderBy[1]);
		}

		//Group By
		if (is_array($group_by) && (count($group_by) > 0)) {
			$this->db->group_by($group_by[0]);
		}
		//OFFSET with LIMIT
		if ($limit != '' && $offset != '') {
			$this->db->limit($limit, $offset);
		}
		// LIMIT
		if ($limit != '' && $offset == '') {
			$this->db->limit($limit);
		}
		return $this->db->get();
	}

	public function getTableDatas($tableName = '', $where = array(), $selectFields = '', $like = array(), $where_or = array(), $like_or = array(), $offset = '', $limit = '', $orderBy = array(), $groupBy = array(), $where_not = array(), $where_in = array())
	{
		// WHERE AND conditions
		if ((is_array($where)) && (count($where) > 0)) {
			$this->db->where($where);
		}
		// WHERE NOT conditions
		if ((is_array($where_not)) && (count($where_not) > 0)) {
			//echo "<pre>";
			//print_r($where_not);die;
			$this->db->where_not_in($where_not[0], $where_not[1]);
		}
		// WHERE IN conditions
		if ((is_array($where_in)) && (count($where_in) > 0)) {
			$this->db->where_in($where_in[0], $where_in[1]);
		}
		// WHERE OR conditions
		if ((is_array($where_or)) && (count($where_or) > 0)) {
			$this->db->or_where($where_or);
		}
		// $this->db->group_start();
		//LIKE AND 
		$this->db->group_start();
		if ((is_array($like)) && (count($like) > 0)) {
			$this->db->like($like);
		}
		//LIKE OR 
		if ((is_array($like_or)) && (count($like_or) > 0)) {
			$this->db->or_like($like_or);
		}
		$this->db->group_end();
		// $this->db->group_end();
		//SELECT fields
		if ($selectFields != '') {
			$this->db->select($selectFields);
		}
		//Group By
		if (is_array($groupBy) && (count($groupBy) > 0)) {
			$this->db->group_by($groupBy[0]);
		}
		//Order By
		if (is_array($orderBy) && (count($orderBy) > 0)) {
			if (count($orderBy) > 2) {
					$this->db->order_by($orderBy[0] . ' ' . $orderBy[1] . ',' . $orderBy[2] . ' ' . $orderBy[3]);
				} else {
					$this->db->order_by($orderBy[0], $orderBy[1]);
				}
		}
		//OFFSET with LIMIT
		if ($limit != '' && $offset != '') {
			$this->db->limit($limit, $offset);
		}
		// LIMIT
		if ($limit != '' && $offset == '') {
			$this->db->limit($limit);
		}

		return $this->db->get($tableName);
	}

	public function getJoinedTableDatas($tableName = '', $joins = array(),  $where = array(), $selectFields = '', $like = array(), $where_or = array(), $like_or = array(), $offset = '', $limit = '', $orderBy = array(), $group_by = array())
	{

		$this->db->from($tableName);
		//join tables list
		if ((is_array($joins)) && (count($joins) > 0)) {
			foreach ($joins as $jointb => $joinON) {
				$this->db->join($jointb, $joinON);
			}
		}

		// WHERE AND conditions
		if ((is_array($where)) && (count($where) > 0)) {
			$this->db->where($where);
		}
		// WHERE OR conditions
		if ((is_array($where_or)) && (count($where_or) > 0)) {
			$this->db->or_where($where_or);
		}
		//LIKE AND 
		$this->db->group_start();
		if ((is_array($like)) && (count($like) > 0)) {
			$this->db->like($like);
		}
		//LIKE OR 
		if ((is_array($like_or)) && (count($like_or) > 0)) {
			$this->db->or_like($like_or);
		}
		$this->db->group_end();
		//SELECT fields
		if ($selectFields != '') {
			$this->db->select($selectFields, false);
		}
		//Order By
		if (is_array($orderBy) && (count($orderBy) > 0)) {
			$this->db->order_by($orderBy[0], $orderBy[1]);
		}

		//Group By
		if (is_array($group_by) && (count($group_by) > 0)) {
			$this->db->group_by($group_by[0]);
		}
		//OFFSET with LIMIT
		if ($limit != '' && $offset != '') {
			$this->db->limit($limit, $offset);
		}
		// LIMIT
		if ($limit != '' && $offset == '') {
			$this->db->limit($limit);
		}
		return $this->db->get();
	}

	//select records from joined tables
	public function getleftJoinedTableData($tableName = '', $joins = array(),  $where = array(), $selectFields = '', $like = array(), $where_or = array(), $like_or = array(), $offset = '', $limit = '', $orderBy = array(), $group_by = array(), $where_in = array())
	{

		$this->db->from($tableName);
		//join tables list
		if ((is_array($joins)) && (count($joins) > 0)) {
			foreach ($joins as $jointb => $joinON) {
				$this->db->join($jointb, $joinON, 'LEFT');
			}
		}

		// WHERE AND conditions
		if ((is_array($where)) && (count($where) > 0)) {
			$this->db->where($where);
		}
		// WHERE IN conditions
		if ((is_array($where_in)) && (count($where_in) > 0)) {
			$this->db->where_in($where_in[0], $where_in[1]);
		}
		// WHERE OR conditions
		if ((is_array($where_or)) && (count($where_or) > 0)) {
			$this->db->or_where($where_or);
		}
		//LIKE AND 
		if ((is_array($like)) && (count($like) > 0)) {
			$this->db->like($like);
		}
		//LIKE OR 
		if ((is_array($like_or)) && (count($like_or) > 0)) {
			$this->db->or_like($like_or);
		}
		//SELECT fields
		if ($selectFields != '') {
			$this->db->select($selectFields);
		}
		//Order By
		if (is_array($orderBy) && (count($orderBy) > 0)) {
			$this->db->order_by($orderBy[0], $orderBy[1]);
		}
		//Group By
		if (is_array($group_by) && (count($group_by) > 0)) {
			$this->db->group_by($group_by[0]);
		}
		//OFFSET with LIMIT
		if ($limit != '' && $offset != '') {
			$this->db->limit($limit, $offset);
		}
		// LIMIT
		if ($limit != '' && $offset == '') {
			$this->db->limit($limit);
		}

		return $this->db->get();
	}

	/*function last_activity($activity,$id='0',$type=''){      
        $date  = gmdate(time());
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $session_data = array(
				'last_ip_address' => $ip_address,
				'last_login_date' => $date,
		); 
		$this->session->set_userdata($session_data);
        $data = array('date'      => $date,
                        'ip_address'=> $ip_address,
                        'activity'  => $activity,
                        'browser_name' => getBrowser(),
                        'os_name'  => getOS(),
                        'type'  => $type,
                        'user_id'  => $id);      
        $this->insertTableData('user_activity',$data);
    }*/

	//New code for websocket use 23-5-18
	function last_activity($activity, $id = '0', $type = '', $ip_address = '', $get_os = '')
	{
		
		$date  = gmdate(time());
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$session_data = array(
			'last_ip_address' => $ip_address,
			'last_login_date' => $date,
		);
		$this->session->set_userdata($session_data);
		$browser = getBrowser();
		$data = array(
			'date'      => $date,
			'ip_address' => $ip_address,
			'activity'  => $activity,
			'browser_name' => $browser,
			'os_name'  => $get_os, //getOS(),
			'type'  => $type,
			'user_id'  => $id
		);

		/*$to      	= getUserEmail($id);
		$email_template = 37;
		$username = getUserDetails($id,'latin_username');
		$userdetails = getUserDetails($id);
		$usr_country = $userdetails->country;
		$countryname = get_countryname($usr_country);
		$special_vars = array(					
							'###USERNAME###' => $username,
							'###IPADDRESS###' => $ip_address,
							'###BROWSER###' => $browser,
							'###COUNTRY###' => $countryname
							);
		$this->email_model->sendMail($to, '', '', $email_template,$special_vars);*/

		$this->insertTableData('user_activity', $data);
	}
	//End 23-5-18

	function sitevisits()
	{
		$browser_name = getBrowser();
		if (is_cli()) {
			$ip_address = '127.0.0.1';
		} else {
			$ip_address = get_client_ip();
		}
		$date = date('Y-m-d');
		$insertData = array('ip_address' => $ip_address, 'browser' => $browser_name, 'date_added' => $date);
		$already = $this->getTableData('site_visits', $insertData);
		if ($already->num_rows() == 0) {
				$this->insertTableData('site_visits', $insertData);
			}
	}

	function close_ticket($ticket_id, $user_id)
	{
		$insertsData['close'] = '1';
		$update = $this->common_model->updateTableData('support', array('id' => $ticket_id), $insertsData);
		if ($update) {
			echo "1";
		} else {
			echo "0";
		}
	}

	function update_address_again($user_id, $coin_id, $parameter)
	{
		$Get_First_address = $this->local_model->access_wallet($coin_id, $parameter, getUserEmail($user_id));

		if (empty($Get_First_address) || $Get_First_address == 0) {
			$this->update_address_again($user_id, $coin_id, $parameter);
		} else {
			return true;
		}
	}

	function get_last_record($pair_id="",$dtime = "1", $type = "DAY")
	{
		$this->db->select('*');
		$this->db->from('latin_coin_order');
		$this->db->where("pair=$pair_id and datetime > DATE_SUB(NOW(), INTERVAL $dtime $type) AND datetime <= NOW()");
		$this->db->order_by("trade_id", "DESC");
		return $this->db->get();
	}

	public function update_usd_price(){
        $this->db->select("*");
        $this->db->from("currency");        
        $where = "status=1";
        $this->db->where($where);
        // $this->db->order_by("depid", "desc");
        $currency_query = $this->db->get();
        $currency_results = $currency_query->result();
        return $currency_results;
    }

   public function convet($coin_symbol)
	{
		if($coin_symbol=="BTC")
		{
			$coin_name ="bitcoin";
		}
		else if($coin_symbol=="ETH")
		{
			$coin_name ="ethereum";
		}
		else if($coin_symbol=="XRP")
		{
			$coin_name ="ripple";
		}
		else
		{
			$coin_name ="litecoin";
		}
		$coin_symbol = "BTC";
		$url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=".$coin_name."";
		$curres = $coin_symbol;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$res = json_decode($result,true);
		return $res[0]['market_cap_change_percentage_24h'];
	}

    public function convets($coin_name)
	{
		$url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=".$coin_name;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$res = json_decode($result,true); 
		return $res[0]['market_cap_change_percentage_24h'];
	}
	public function conveter($coin_symbol)
	{
		$url = "https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=USD";
		$curres = $coin_symbol;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$res = json_decode($result);
		return $res->USD;
	}

	function get_pair_exist($from_symbol_id,$to_symbol_id)
	{
		$this->db->select('*');
		$this->db->from('trade_pairs');
		//$this->db->where("(to_symbol_id=$to_symbol_id and from_symbol_id=$from_symbol_id) OR to_symbol_id=$from_symbol_id and from_symbol_id=$to_symbol_id");
		$this->db->where("to_symbol_id=$to_symbol_id and from_symbol_id=$from_symbol_id");
		return $this->db->get();
	}

	function sentLevelCommission($members_id,$spillover_id,$paymenthistory_amount,$level,$currency_id)
	{
 

		if($spillover_id!=0)
		{
			
	$matrixmemberlinkdetails = $this->common_model->getTableData('users', array('referralid' => $spillover_id,'verified'=>1))->row();
		
			if(count($matrixmemberlinkdetails)>0)
			{


				$nextspillover_id	 =$matrixmemberlinkdetails->parent_referralid;
				
			

				//$level_details = $this->common_model->getTableData('referral_commission', array('id' => $level))->row();
				//$level_details = $this->common_model->getTableData('referral_commission', array('id' => '1'))->row();


				//$commission_amt=($paymenthistory_amount*$level_details->commission)/100;
				$commission_amt=($paymenthistory_amount*50)/100;
				
					
							
				if($commission_amt>0){

				$balance_CBC = getBalance($matrixmemberlinkdetails->id,$currency_id,'crypto');
				$finalbalance_CBC = $balance_CBC+$commission_amt;
				$updatebalance_CBC = updateBalance($matrixmemberlinkdetails->id,$currency_id,$finalbalance_CBC,'crypto'); 
				//echo " Spilover Id ".$spillover_id." FINAL Balance ".$finalbalance_CBC. ' Pre Balance '.$balance_CBC.' <br> ';  


			$dep_data = array(
			'user_id'    		=> $matrixmemberlinkdetails->id,
			'payment_method'	=> 'bonus',
			'bonus_from'		=>	$members_id,
			'currency_id'   	=> $currency_id,
			'type'       		=> "Deposit",
			'currency_type'		=> "crypto",
			'description'		=> "Level".$level." Commission on Trade",
			'amount'     		=> $commission_amt,
			'transfer_amount'	=> $commission_amt,
			'wallet_txid'       => 'Level'.$level.' Commission on Trade',
			'status'     		=> "Completed",
			'datetime' 			=> strtotime(date('Y-m-d H:i:s')),
			'user_status'		=> "Completed",
			'transaction_id'	=> rand(100000000,10000000000),
			);
			// echo "<pre>";
			// print_r($dep_data);
			// echo "<pre>";

			//print_r($dep_data); exit();
			$ins_id = $this->insertTableData('transactions',$dep_data);
			}	
					


			}	
		
			$level+=1; 
			//echo " Spilover ".$nextspillover_id;
			// if($level<=3 && $nextspillover_id!=0){		

			// 	$this->sentLevelCommission($members_id,$nextspillover_id,$paymenthistory_amount,$level,$currency_id);
			// }
		//echo $commission_amt."<br/>";
		}


	}
	
	  public function gettrades($symbol)
	{
	  	$this->db->select('*');
	  	$this->db->from('coin_order');
	  	$this->db->where('pair_symbol="'.$symbol.'" AND orderDate>(NOW() - INTERVAL 2 DAY) AND status="filled"', '', FALSE);
		$this->db->order_by('trade_id', 'DESC');
		if($limit > 0){$this->db->order_by('limit', $limit);}
	  	$query = $this->db->get();
		$query = $query->result_array();  
		//echo $this->db->last_query().'<br>';
        return $query;
	}
}

/*
 * End of the file common_model.php
 * Location: application/models/common_model.php
 */
