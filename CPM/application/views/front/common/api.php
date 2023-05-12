<?php 
$this->load->view('front/common/header');
?>
<style type="text/css">
	
	code a
	{
		font-weight: bold;
	}
.status
{
	color: #16b786 !important;
	font-weight: bold;
}
</style>
<div class=" cpm_mdl_cnt  ">

						<div class="container">
							<div class="cpm_hd_text   text-center">Faq</div>

						   
							<div class="cpm_sta_faq_set">
								<div class="main-inner-sec">
    <section class="wallet_sec" >
        <div class="container">
          <div class="row"></div>
            <div class="row">
              <div class="col-lg-3">
                <ul class="nav nav-pills" id="settings_tab" role="tablist" style="display: block !important;">
                        <li class="nav-item">
                            <a class="nav-link active" id="assets-tab" data-toggle="pill" href="#assets" role="tab" aria-controls="pills-assets" aria-selected="true">Assets</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="orderbook-tab" data-toggle="pill" href="#orderbook" role="tab" aria-controls="pills-orderbook" aria-selected="true">Order Book</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="ticker-tab" data-toggle="pill" href="#ticker" role="tab" aria-controls="pills-ticker" aria-selected="true">Ticker Info</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="trade_history-tab" data-toggle="pill" href="#trade_history" role="tab" aria-controls="pills-trade_history" aria-selected="true">Trade History</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="trade_pairs-tab" data-toggle="pill" href="#trade_pairs" role="tab" aria-controls="pills-trade_pairs" aria-selected="true">Trade Pairs</a>
                        </li>

                      </ul>
              </div>
                <div class="col-lg-9">

                  
                    <div class="lightGrayBox p-30">

                      <div class="tab-content" id="settings_tab_content">
                 <div class="tab-pane fade active show" id="assets" role="tabpanel" aria-labelledby="assets-tab">

                        <h4 class="cmsTitle">CPM
 - Rest API</h4>

                        <p>CPM
 provides API solutions for automated trading based on needs of individuals and institutions.</p><br/>
                        
                        <h5><b>Read all available assets from CPM
 exchange</b> </h5>
                        <div class="pre-divv">
                          <p ><pre class="status" style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre></p>
                          <p><pre  style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code><a href="https://cpmxchanger.com/api/v1/assets" target="_blank">https://cpmxchanger.com/api/v1/assets </a></code><br/><br/><code>Sample response :

                          

   {
    "code":"200",
    "msg":"success",
    "data":{
    "BTC":{
    "name":"bitcoin",
    "unified_cryptoasset_id":"1",
    "can_withdraw":"true",
    "can_deposit":"true",
    "min_withdraw":"0.1",
    "max_withdraw":"1",
    "maker_fee":"0.15",
    "taker_fee":"0.15"
  }
}
}

                               </code></font>
                              </pre></p>
                            </div>
                          </div>
                            
                 <div class="tab-pane fade" id="orderbook" role="tabpanel" aria-labelledby="orderbook-tab">
                  <h4 class="cmsTitle">CPM
 - Rest API</h4>

                        <p>CPM
 provides API solutions for automated trading based on needs of individuals and institutions.</p><br/>

                        <h5><b>Lists orders for provided pair</b> </h5>
                        <div class="pre-divv">
                          <p ><pre class="status" style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre></p>
                          <p><pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code><a href="https://cpmxchanger.com/api/v1/orderbook/TRX_BTC" target="_blank">https://cpmxchanger.com/api/v1/orderbook/TRX_BTC</a></code><br/><br/><code>Sample response :

  {
  "code": "200",
  "msg": "success",
  "data": {
    "bids": [
      [
        "52283.91",
        "0.184698"
      ],
      [
        "52283.17",
        "0.258579"
      ],
      [
        "52280.83",
        "0.018643"
      ],
      [
        "52280.28",
        "0.034072"
      ],
      [
        "52280",
        "0.120592"
      ]
    ],
    "timestamp": 1616647375
  }
}

                               </code></font>
                              </pre></p>
                            </div>
                          </div>
                           
                           <div class="tab-pane fade" id="ticker" role="tabpanel" aria-labelledby="ticker-tab">
                            <h4 class="cmsTitle">CPM
 - Rest API</h4>

                        <p>CPM
 provides API solutions for automated trading based on needs of individuals and institutions.</p><br/>

                        <h5><b>The ticker api is to provide a 24-hour pricing and volume summary for each market pair available on the exchange.</b> </h5>
                        <div class="pre-divv">
                          <p ><pre class="status" style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre></p>
                          <p><pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code><a href="https://cpmxchanger.com/api/v1/ticker" target="_blank">https://cpmxchanger.com/api/v1/ticker</a></code><br/><br/><code>Sample response :

  {
  "code": "200",
  "msg": "success",
  "data": {
    "XRP_BTC": {
      "base_id": "1",
      "quote_id": "3",
      "last_price": "52424.61",
      "base_volume": "90763.824532",
      "quote_volume": 4758258103.198532,
      "isFrozen": 0
    },
    "BTC_USDT": {
      "base_id": "4",
      "quote_id": "1",
      "last_price": "0.00000105",
      "base_volume": "455723147",
      "quote_volume": 478.50930435,
      "isFrozen": 0
    },
    "DOGE_USDT": {
      "base_id": "4",
      "quote_id": "3",
      "last_price": "0.05461",
      "base_volume": "2218210353.4",
      "quote_volume": 121136467.399174,
      "isFrozen": 0
    }
  }
}
                               </code></font>
                              </pre></p>
                            </div>


                            <h5><b>The ticker api is to provide a 24-hour pricing and volume summary for single pair available on the exchange.</b> </h5>
                        <div class="pre-divv">
                          <p ><pre class="status" style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre></p>
                          <p><pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code><a href="https://cpmxchanger.com/api/v1/ticker" target="_blank">https://cpmxchanger.com/api/v1/ticker</a></code><br/><br/><code>Sample response :

  {
  "code": "200",
  "msg": "success",
  "data": {
    "symbol": "XRP_BTC",
    "last": "52298.10000000",
    "high": "57200.00000000",
    "low": "51500.00000000",
    "volume": "90789.69631400",
    "volume_usd": 4746769495.0453825,
    "bidPrice": "34637.00000000",
    "askPrice": "34639.00000000",
    "price_change": "-1.00000000",
    "price_change_percent": "-3.67000000"
  }
}
                               </code></font>
                              </pre></p>
                            </div>
                            </div>

                            <div class="tab-pane fade" id="trade_history" role="tabpanel" aria-labelledby="trade_history-tab">
                              <h4 class="cmsTitle">CPM
 - Rest API</h4>

                        <p>CPM
 provides API solutions for automated trading based on needs of individuals and institutions.</p><br/>

                            <h5><b>Lists orders for provided pair.</b> </h5>
                        <div class="pre-divv">
                          <p ><pre class="status" style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre></p>
                          <p><pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code><a href="https://cpmxchanger.com/api/v1/trades/XRP_BTC" target="_blank">https://cpmxchanger.com/api/v1/trades/XRP_BTC</a></code><br/><br/><code>Sample response :

 {
  "code": "200",
  "msg": "success",
  "data": [
    {
      "trade_id": "340",
      "price": "52051.86636",
      "base_volume": "0.02",
      "quote_volume": "1041.0373272",
      "timestamp": 1616639782,
      "type": "buy"
    }
  ]
}
                               </code></font>
                              </pre></p>
                            </div>
                          </div>

                          <div class="tab-pane fade" id="trade_pairs" role="tabpanel" aria-labelledby="trade_pairs-tab">
                            <h4 class="cmsTitle">CPM
 - Rest API</h4>

                        <p>CPM
 provides API solutions for automated trading based on needs of individuals and institutions.</p><br/>
                             <h5><b>Read all available trade pairs from CPM
 exchange</b> </h5>
                        <div class="pre-divv">
                          <p ><pre class="status" style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre></p>
                          <p><pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code><a href="https://cpmxchanger.com/api/v1/trade_pairs" target="_blank">https://cpmxchanger.com/api/v1/trade_pairs</a></code><br/><br/><code>Sample response :

 {
  "code": "200",
  "msg": "success",
  "data": [
    {
      "trading_pairs": "XRP_BTC",
      "last_price": "52279.19",
      "lowest_ask": "34639",
      "highest_bid": "34637",
      "base_volume": "90783.047249",
      "quote_volume": 4746064175.909449,
      "price_change_percent_24h": "-3.38",
      "highest_price_24h": "57200",
      "lowest_price_24h": "51500"
    },
    {
      "trading_pairs": "BTC_USDT",
      "last_price": "0.00000105",
      "lowest_ask": "0.001",
      "highest_bid": "0.001",
      "base_volume": "456616984",
      "quote_volume": 479.44783319999993,
      "price_change_percent_24h": "-4.55",
      "highest_price_24h": "0.0000011",
      "lowest_price_24h": "0.00000102"
    }
  ]
}
                               </code></font>
                              </pre></p>
                            </div>
                          </div>

                         </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
								
								</div>
							</div>
					</div>


<?php 
$this->load->view('front/common/footer');
?>