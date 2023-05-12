<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends CI_Controller {
	public function __construct()
	{	
		parent::__construct();		
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header('Content-Type: application/json');
	}
	
	function getTickerLists()
	{
		$tickers = $this->common_model->getTableData('api')->result();
		$ticker_array = array();
		if(sizeof($tickers) > 0)
		{			
			foreach($tickers as $ticker)
			{
				$data['symbol']			= str_replace('/', '-', $ticker->symbol);;
				$data['last']			= $ticker->last;
				$data['high']			= $ticker->high;
				$data['low']			= $ticker->low;
				$data['volume']			= $ticker->volume;
				$data['vwap']			= $ticker->vwap;
				$data['max_bid']		= $ticker->max_bid;
				$data['min_ask']		= $ticker->min_ask;
				$data['best_bid']		= $ticker->best_bid;
				$data['best_ask']		= $ticker->best_ask;

				array_push($ticker_array, $data);
			}			
		}
		echo json_encode($ticker_array, true);
	}

	function getTickerList($pair_symbol)
	{
		$pair_symbol = str_replace('-', '/', $pair_symbol);
		$ticker = $this->common_model->getTableData('api',array("symbol"=>$pair_symbol))->row();

		$ticker_array = array();

		if(!empty($ticker))
		{
			$ticker_array['symbol']			= str_replace('/', '-', $ticker->symbol);
			$ticker_array['last']			= $ticker->last;
			$ticker_array['high']			= $ticker->high;
			$ticker_array['low']			= $ticker->low;
			$ticker_array['volume']			= $ticker->volume;
			$ticker_array['vwap']			= $ticker->vwap;
			$ticker_array['max_bid']		= $ticker->max_bid;
			$ticker_array['min_ask']		= $ticker->min_ask;
			$ticker_array['best_bid']		= $ticker->best_bid;
			$ticker_array['best_ask']		= $ticker->best_ask;
		}
	   echo json_encode($ticker_array,true);
	}
    
}