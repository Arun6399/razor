<div class="panel-body">
    <?php 
        $attributes=array('class'=>'form-horizontal','id'=>'currency');
        echo form_open_multipart($action,$attributes);
    ?>
    <fieldset>
        <div class="form-group">
            <label class="col-md-4 control-label">Token Name</label>
            <div class="col-md-4">
                <input type="text" name="currency_name" id="currency_name" class="form-control"
                    placeholder="ex - Bitcoin, Ethereum" value="" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Token Symbol</label>
            <div class="col-md-4">
                <input type="text" name="currency_symbol" id="currency_symbol" class="form-control"
                    placeholder="ex - BTC, ETH, LTC" value="" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Type</label>
            <div class="col-md-4">
                <select data-live-search="true" id="currency_type" name="currency_type" class="form-control selectpicker"
                    onchange="change_dep_type(this.value)">

                    <option value="digital">Digital</option>
                    <option value="fiat">Fiat</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Image</label>
            <div class="col-md-4">
                <input type="file" name="image" id="image" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Status</label>
            <div class="col-md-4">
                <select data-live-search="true" id="status" name="status" class="form-control selectpicker">
                    <option value="1">Active</option>
                    <option value="0">De-active</option>
                </select>
            </div>
        </div>
        <!-- <div class="form-group">
            <label class="col-md-4 control-label">Reserve Amount</label>
            <div class="col-md-4">
                <input type="text" name="reserve_Amount" id="reserve_Amount" class="form-control selectpicker"
                    placeholder="Reserve Amount" value="" />
            </div>
        </div> -->
        <div class="form-group">
            <label class="col-md-4 control-label">Minimum Withdraw Limit</label>
            <div class="col-md-4">
                <input type="text" name="min_withdraw_limit" id="min_withdraw_limit" class="form-control"
                    placeholder="Minimum Withdraw Limit" value="" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Maximum Withdraw Limit</label>
            <div class="col-md-4">
                <input type="text" name="max_withdraw_limit" id="max_withdraw_limit" class="form-control"
                    placeholder="Maximum Withdraw Limit" value="" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Withdraw Fees Type</label>
            <div class="col-md-4">
                <select data-live-search="true" id="withdraw_fees_type" name="withdraw_fees_type" class="form-control selectpicker">
                    <option value="Percent">Percent</option>
                    <option value="Amount">Amount</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Withdraw Fees</label>
            <div class="col-md-4">
                <input type="text" name="withdraw_fees" id="withdraw_fees" class="form-control"
                    placeholder="Withdraw Fees" value="" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Maker Fee</label>
            <div class="col-md-4">
                <input type="text" name="maker_fee" id="maker_fee" class="form-control"
                    placeholder="Maker Fee" value="" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Taker Fee</label>
            <div class="col-md-4">
                <input type="text" name="taker_fee" id="taker_fee" class="form-control"
                    placeholder="Taker Fee" value=""/>
                <label id="takerfee_err"></label>
            </div>
        </div>
        <div id="deposit_seg" style="display: none;">
            <div class="form-group">
                <label class="col-md-4 control-label">Minimum Deposit Limit</label>
                <div class="col-md-4">
                    <input type="text" name="min_deposit_limit" id="min_deposit_limit" class="form-control"
                        placeholder="Minimum Deposit Limit" value="" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Maximum Deposit Limit</label>
                <div class="col-md-4">
                    <input type="text" name="max_deposit_limit" id="max_deposit_limit" class="form-control"
                        placeholder="Maximum Deposit Limit" value="" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Deposit Fees Type</label>
                <div class="col-md-4">
                    <select data-live-search="true" id="deposit_fees_type" name="deposit_fees_type" class="form-control selectpicker">
                        <option value="Percent">Percent</option>
                        <option value="Amount">Amount</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">Deposit Fees</label>
                <div class="col-md-4">
                    <input type="text" name="deposit_fees" id="deposit_fees" class="form-control"
                        placeholder="Deposit Fees" value="" />
                </div>
            </div>
        </div>
        <!--<div class="form-group">
            <label class="col-md-4 control-label">USD Exchange Rate</label>
            <div class="col-md-4">
                <input type="text" name="online_usdprice" id="online_usdprice" class="form-control"
                    placeholder="USD Exchange Rate" value="" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">USD Price Reference link</label>
            <div class="col-md-4">
                <input type="text" name="usdpice_ref_site" id="usdpice_ref_site" class="form-control"
                    placeholder="USD Price Reference link" value="" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Select asset type</label>
            <div class="col-md-4">
                <div class="form-check-inline">
                    <label class="customradio">
                        <span class="radiotextsty">Coin</span>
                        <input type="radio" name="assets_types" value="1" class="assets_types">
                        <span class="checkmark"></span>
                    </label>        
                    <label class="customradio">
                        <span class="radiotextsty">Token</span>
                        <input type="radio" name="assets_types" value="0" class="assets_types">
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
        </div>-->

       <input type="hidden" name="assets_types" value="0" class="assets_types">

        <div class="form-group contract_sec" style="display:;">
            <label class="col-md-4 control-label">Contract address</label>
            <div class="col-md-4">
                <input type="text" name="contract_address" id="contract_address" class="form-control" placeholder="" value="" />
            </div>
        </div>

        <div class="form-group decimal_sec" style="display:;">
            <label class="col-md-4 control-label">Currency decimal</label>
            <div class="col-md-4">
                <input type="text" name="currency_decimal" id="currency_decimal" class="form-control" placeholder="" value="" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Listing Priority</label>
            <div class="col-md-4">
                <select data-live-search="true" id="priority" name="priority" class="form-control selectpicker">
                    <option class="opt" value="1">High (Cost)</option>
                    <option class="opt" value="2">Standard (Cost)</option>
                    <option class="opt" value="3">Low (Cost)</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Which Blockchain based on this</label>
            <div class="col-md-4">
                <!-- <select data-live-search="true" class="form-control selectpicker" name="crypto_type" id="crypto_type">
                    <option class="opt" value="">Select</option>
                    <option class="opt" value="eth">ETH</option>
                </select> -->
                <select data-live-search="true" class="form-control" name="token_type" id="token_type" style="display:">
                    <option class="opt" value="">Select</option>
                    <option class="opt" value="erc20">ERC20</option>
                    <option class="opt" value="tron">TRC20</option>
                    <option class="opt" value="bsc">BEP20</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Expiry Date on Home page</label>
            <div class="col-md-4">
                <input type="text" name="expiry_date" id="expiry_date" class="datepicker form-control"
                    placeholder="Expiry Date" value="" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-12 control-label text-center"><h4>Community</h4></label>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Coin Market Caplink</label>
            <div class="col-md-4">
                <input type="text" name="marketcap_link" id="marketcap_link" class="form-control" placeholder="" value="" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Coin Link</label>
            <div class="col-md-4">
                <input type="text" name="coin_link" id="coin_link" class="form-control" placeholder="" value="" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Official Twitter Link</label>
            <div class="col-md-4">
                <input type="text" name="twitter_link" id="twitter_link" class="form-control" placeholder="" value="" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Sort Order</label>
            <div class="col-md-4">
                <input type="text" name="sorting_order" id="sorting_order" class="form-control"
                    placeholder="Ex-1,2" value="" />
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
            </div>
        </div>
    </fieldset>
    <?php echo form_close(); ?>
</div>
