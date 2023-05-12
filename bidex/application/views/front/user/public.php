<div class="verification section-padding mb-5">
<div class="tab-pane active" id="public" style="overflow: auto;">
<div class="page-body">
        <div class="container-xl">
            <div class="row">
              <!--for demo wrap-->
              <!-- <div class="col-2"></div> -->
              <div class="col-12" style="overflow: auto;margin-bottom: 50px;">



             <div class="row">

                <div class="col-md-3">
                <!-- Tabs nav -->
                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link mb-3 p-3 shadow active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">

                        <span class="font-weight-bold small text-uppercase">Assets</span></a>

                    <a class="nav-link mb-3 p-3 shadow" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">

                        <span class="font-weight-bold small text-uppercase">Order Book</span></a>

                    <a class="nav-link mb-3 p-3 shadow" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">

                        <span class="font-weight-bold small text-uppercase">Ticker Info</span></a>

                    <a class="nav-link mb-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">

                        <span class="font-weight-bold small text-uppercase">Trade History</span></a>

                    <a class="nav-link mb-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-pairs" role="tab" aria-controls="v-pills-settings" aria-selected="false">

                      <span class="font-weight-bold small text-uppercase">Trade Pairs</span></a>

                    </div>
            </div>


            <div class="col-md-9">
                <!-- Tabs content -->
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade shadow rounded bg-white show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <div class="card-header" style="display: block;">
                            <h3 class="card-title mb-3" style="color: #262261;">BIDEX - Rest Api</h3>
                            <p> BIDEX provides API solutions for automated trading based on needs of individuals and institutions.</p>
                          </div>
                        <div class="col-md-12">
                            <div class="card-trans">
                             <div class="card-body">
                             <h3 style="color: black;">Read all available assets from BIDEX exchange
                            </h3>
                            <pre style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre>
                            <p></p>
                            <p></p>
                            <pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code>https://www.bidexcrypto.com/api/v1/assets</code><br><br><code>Sample response :

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
                                                         </pre>
                              </div>
                            </div>
                          </div>





                    </div>

                    <div class="tab-pane fade shadow rounded bg-white" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                      <div class="card-header" style="display: block;">
                        <h3 class="card-title mb-3" style="color: #262261;">BIDEX - Rest Api</h3>
                        <p> BIDEX provides API solutions for automated trading based on needs of individuals and institutions.</p>
                      </div>
                    <div class="col-md-12">
                        <div class="card-trans">
                         <div class="card-body">
                         <h3 style="color: black;">Lists orders for provided pair
                        </h3>

                        <pre style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre>
                        <p></p>
                        <p></p>
                        <pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code>https://www.bidexcrypto.com/api/v1/assets</code><br><br><code>Sample response :
                          --request GET \  https://www.bidexcrypto.com/api/v1/orderbook/ETH_BTC

                                        Sample response :

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
                                                     </pre>
                          </div>

                        </div>
                      </div>
                          </div>



                            <div class="tab-pane fade shadow rounded bg-white" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                <div class="tab-pane fade shadow rounded bg-white show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                  <div class="card-header" style="display: block;">
                                    <h3 class="card-title mb-3" style="color: #262261;">BIDEX - Rest Api</h3>
                                    <p> BIDEX provides API solutions for automated trading based on needs of individuals and institutions.</p>
                                  </div>
                                <div class="col-md-12">
                                    <div class="card-trans">
                                     <div class="card-body">
                                     <h3 style="color: black;">The ticker api is to provide a 24-hour pricing and volume summary for each market pair available on the exchange.</h3>

                                    <pre style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre>
                                    <p></p>
                                    <p></p>
                                    <pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code>https://www.bidexcrypto.com/api/v1/assets</code><br><br><code>Sample response :
                                      --request GET \  https://www.bidexcrypto.com/api/v1/ticker

            Sample response :

              {
              "code": "200",
              "msg": "success",
              "data": {
                "ETH_BTC": {
                  "base_id": "1",
                  "quote_id": "3",
                  "last_price": "52424.61",
                  "base_volume": "90763.824532",
                  "quote_volume": 4758258103.198532,
                  "isFrozen": 0
                },
                "TRX_BTC": {
                  "base_id": "4",
                  "quote_id": "1",
                  "last_price": "0.00000105",
                  "base_volume": "455723147",
                  "quote_volume": 478.50930435,
                  "isFrozen": 0
                },
                "BCH_BTC": {
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
                                                                 </pre>
                                      </div>
                                      <div class="card-body">
                                        <h3 style="color: black;">The ticker api is to provide a 24-hour pricing and volume summary for single pair available on the exchange.</h3>

                                       <pre style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre>
                                       <p></p>
                                       <p></p>
                                       <pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code>https://www.bidexcrypto.com/api/v1/assets</code><br><br><code>Sample response :
                                        --request GET \  https://www.bidexcrypto.com/api/v1/ticker

            Sample response :

              {
              "code": "200",
              "msg": "success",
              "data": {
                "symbol": "ETH_BTC",
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
                                                                    </pre>
                                         </div>
                                    </div>
                                  </div>





                                </div>




                            </div>

                            <div class="tab-pane fade shadow rounded bg-white" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                <div class="tab-pane fade shadow rounded bg-white show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                  <div class="card-header" style="display: block;">
                                    <h3 class="card-title mb-3" style="color: #262261;">BIDEX - Rest Api</h3>
                                    <p> BIDEX provides API solutions for automated trading based on needs of individuals and institutions.</p>
                                  </div>
                                <div class="col-md-12">
                                    <div class="card-trans">
                                     <div class="card-body">
                                     <h3 style="color: black;">Lists orders for provided pair. </h3>
                                    <pre style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre>
                                    <p></p>
                                    <p></p>
                                    <pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code>https://www.bidexcrypto.com/api/v1/assets</code><br><br><code>Sample response :
                                      --request GET \  https://www.bidexcrypto.com/api/v1/trades/ETH_BTC

                                      Sample response :

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
                                                                 </pre>
                                      </div>
                                    </div>
                                  </div>





                                </div>

                            </div>

                            <div class="tab-pane fade shadow rounded bg-white" id="v-pills-pairs" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                              <div class="tab-pane fade shadow rounded bg-white show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <div class="card-header" style="display: block;">
                                  <h3 class="card-title mb-3" style="color: #262261;">BIDEX - Rest Api</h3>
                                  <p> BIDEX provides API solutions for automated trading based on needs of individuals and institutions.</p>
                                </div>
                              <div class="col-md-12">
                                  <div class="card-trans">
                                   <div class="card-body">
                                   <h3 style="color: black;">Read all available trade pairs from BIDEX exchange
                                  </h3>
                                  <pre style="margin: 0;"><i class="fa fa-circle"></i> 200 OK</pre>
                                  <p></p>
                                  <p></p>
                                  <pre style="background-color: #2D2D2D;padding: 20px 0px 25px 50px;"><font color="#fff">--request GET \  <code>https://www.bidexcrypto.com/api/v1/assets</code><br><br><code>Sample response :

                                    --request GET \  https://www.bidexcrypto.com/api/v1/trade_pairs

                                    Sample response :

                                     {
                                      "code": "200",
                                      "msg": "success",
                                      "data": [
                                        {
                                          "trading_pairs": "ETH_BTC",
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
                                          "trading_pairs": "TRX_BTC",
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
                                                               </pre>
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

        </div>
      </div>
    </div>