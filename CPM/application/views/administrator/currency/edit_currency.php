<div class="panel-body">
    <?php $attributes=array('class'=>'form-horizontal','id'=>'edit_currency');
				echo form_open_multipart($action,$attributes); ?>
    <fieldset>
        <div class="form-group">
            <label class="col-md-4 control-label">Currency Name</label>
            <div class="col-md-4">
                <input type="text" name="currency_name" id="currency_name" class="form-control"
                    placeholder="Currency Name" value="<?php echo $currency->currency_name; ?>" readonly />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Currency Symbol</label>
            <div class="col-md-4">
                <input type="text" name="currency_symbol" id="currency_symbol" class="form-control"
                    placeholder="Currency Symbol" value="<?php echo $currency->currency_symbol; ?>" readonly />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Currency Type</label>
            <div class="col-md-4">
                <select data-live-search="true" id="currency_type" name="currency_type" class="form-control selectpicker"
                    onchange="change_dep_type(this.value)">
                    <option <?php if($currency->type=='fiat'){echo 'selected';}?> value="fiat">Fiat
                    </option>
                    <option <?php if($currency->type=='digital'){echo 'selected';}?> value="digital">
                        Digital</option>

                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Image</label>
            <div class="col-md-4">
                <input type="file" name="image" id="image" />
                <?php $im = $currency->image; ?>
                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $currency->image; ?>" />
                <?php if($currency->image!='') { ?>
                <img src="<?php echo $im; ?>" style="width:65px;height:65px;" />
                <?php } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Status</label>
            <div class="col-md-4">
                <select data-live-search="true" id="status" name="status" class="form-control selectpicker">
                    <option <?php if($currency->status==1){echo 'selected';}?> value="1">Active</option>
                    <option <?php if($currency->status==0){echo 'selected';}?> value="0">De-active
                    </option>
                </select>
            </div>
        </div>
        <?php if($curn_type!='token') { ?>
        <div class="form-group">
            <label class="col-md-4 control-label">Reserve Amount</label>
            <div class="col-md-4">
                <input type="text" name="reserve_Amount" id="reserve_Amount" class="form-control"
                    placeholder="Reserve Amount" value="<?php echo $currency->reserve_Amount; ?>" />
            </div>
        </div>
    <?php } ?>
        <div class="form-group">
            <label class="col-md-4 control-label">Minimum Withdraw Limit</label> 
            <div class="col-md-4">
                <input type="text" name="min_withdraw_limit" id="min_withdraw_limit" class="form-control"
                    placeholder="Minimum Withdraw Limit" value="<?php echo $currency->min_withdraw_limit; ?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Maximum Withdraw Limit</label>
            <div class="col-md-4">
                <input type="text" name="max_withdraw_limit" id="max_withdraw_limit" oninput="process(this)" class="form-control"
                    placeholder="Maximum Withdraw Limit" value="<?php echo $currency->max_withdraw_limit; ?>" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Withdraw Fees Type</label>
            <div class="col-md-4">
                <select data-live-search="true" id="withdraw_fees_type" name="withdraw_fees_type" class="form-control selectpicker">
                    <option <?php if($currency->withdraw_fees_type=='Percent'){echo 'selected';}?> value="Percent">
                        Percent</option>
                    <option <?php if($currency->withdraw_fees_type=='Amount'){echo 'selected';}?> value="Amount">Amount
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Withdraw Fees</label>
            <div class="col-md-4">
                <input type="text" name="withdraw_fees" id="withdraw_fees" oninput="process(this)" class="form-control"
                    placeholder="Withdraw Fees" value="<?php echo $currency->withdraw_fees; ?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Maker Fee</label>
            <div class="col-md-4">
                <input type="text" name="maker_fee" id="maker_fee" oninput="process(this)" class="form-control"
                    placeholder="Maker Fee" value="<?php echo $currency->maker_fee; ?>" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Taker Fee</label>
            <div class="col-md-4">
                <input type="text" name="taker_fee" id="taker_fee" oninput="process(this)" class="form-control"
                    placeholder="Taker Fee" value="<?php echo $currency->taker_fee; ?>" />
            </div>
        </div>
        <div id="deposit_seg"
            <?php if($currency->type=='fiat'){echo 'style="display: block;"';}else{echo 'style="display: none;"';}?>>
            <div class="form-group">
                <label class="col-md-4 control-label">Minimum Deposit Limit</label>
                <div class="col-md-4">
                    <input type="text" name="min_deposit_limit" id="min_deposit_limit" class="form-control"
                        placeholder="Minimum Deposit Limit" value="<?php echo $currency->min_deposit_limit; ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Maximum Deposit Limit</label>
                <div class="col-md-4">
                    <input type="text" name="max_deposit_limit" id="max_deposit_limit" class="form-control"
                        placeholder="Maximum Deposit Limit" value="<?php echo $currency->max_deposit_limit; ?>" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Deposit Fees Type</label>
                <div class="col-md-4">
                    <select data-live-search="true" id="deposit_fees_type" name="deposit_fees_type" class="form-control selectpicker">
                        <option <?php if($currency->deposit_fees_type=='Percent'){echo 'selected';}?> value="Percent">
                            Percent</option>
                        <option <?php if($currency->deposit_fees_type=='Amount'){echo 'selected';}?> value="Amount">
                            Amount</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label">Deposit Fees</label>
                <div class="col-md-4">
                    <input type="text" name="deposit_fees" id="deposit_fees" class="form-control"
                        placeholder="Deposit Fees" value="<?php echo $currency->deposit_fees; ?>" />
                </div>
            </div>
        </div>
         <?php if($curn_type!='token') { ?>
        <div class="form-group">
            <label class="col-md-4 control-label">USD Exchange Rate</label>
            <div class="col-md-4">
                <input type="text" name="online_usdprice" id="online_usdprice" class="form-control"
                    placeholder="USD Exchange Rate" value="<?php echo $currency->online_usdprice; ?>" />
            </div>
        </div>
        
        <?php if($currency->currency_symbol !="USD") { ?>
        <div class="form-group">
            <label class="col-md-4 control-label">USD Price Reference link</label>
            <div class="col-md-4">
                <input type="text" name="usdpice_ref_site" id="usdpice_ref_site" class="form-control"
                    placeholder="USD Price Reference link" value="<?php echo $currency->usdpice_ref_site; ?>" />
            </div>
        </div>
        <?php } }?>
        <!-- 07052019 -->
        <?php if($curn_type!='token') { ?>
        <div class="form-group">
            <label class="col-md-4 control-label">Select asset type</label>
            <div class="col-md-4">
                <div class="form-check-inline">
                    
                    <label class="customradio">
                        <span class="radiotextsty">Coin</span>
                        <input type="radio" name="assets_types" value="1" <?php echo ($currency->asset_type==1)?"checked":""; ?>>
                        <span class="checkmark"></span>
                    </label> 
                          
                    <label class="customradio">
                        <span class="radiotextsty">Token</span>
                        <input type="radio" name="assets_types" value="0" <?php echo ($currency->asset_type==0)?"checked":""; ?>>
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
        </div>
    <?php }
    else{
        ?>
        <input type="hidden" name="assets_types" value="0" class="assets_types">
        <?php
    } ?>
        <!-- <div class="form-group">
            <label class="col-md-4 control-label">BAC Token Value</label>
            <div class="col-md-4">
                <input type="text" name="bac_token" id="bac_token" class="form-control"
                    placeholder="BAC Token" value="<?php echo $currency->token_bac_value; ?>" />
            </div>
        </div> -->
        <?php if($curn_type=='token') 
              { 
                $block = 'block';
              }
              else
              { 
                $block = 'none';
              } 
        ?>
        <div class="form-group contract_sec" style="display:<?=$block?>;">
            <label class="col-md-4 control-label">Contract address</label>
            <div class="col-md-4">
                <input type="text" name="contract_address" id="contract_address" class="form-control" placeholder="" 
                value="<?php echo $currency->contract_address; ?>" />
            </div>
        </div>
        <div class="form-group decimal_sec" style="display:<?=$block?>;">
            <label class="col-md-4 control-label">Currency decimal</label>
            <div class="col-md-4">
                <input type="text" name="currency_decimal" id="currency_decimal" class="form-control" placeholder="" 
                value="<?php echo $currency->currency_decimal; ?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Listing Priority</label>
            <div class="col-md-4">
                <select data-live-search="true" id="priority" name="priority" class="form-control">
                    <option class="opt selectpicker" value="1" <?php echo ($currency->priority==1)?"selected":""; ?>>High (Cost)</option>
                    <option class="opt" value="2" <?php echo ($currency->priority==2)?"selected":""; ?>>Standard (Cost)</option>
                    <option class="opt" value="3" <?php echo ($currency->priority==3)?"selected":""; ?>>Low (Cost)</option>
                </select>
            </div>
        </div>
        <div class="form-group" >
            <label class="col-md-4 control-label">Show decimal</label>
            <div class="col-md-4">
                <select data-live-search="true" id="show_decimal" name="show_decimal" class="form-control">
                    <option class="opt selectpicker" value="2" <?php echo ($currency->show_decimal==2)?"selected":""; ?>>2 Decimals</option>
                    <option class="opt" value="4" <?php echo ($currency->show_decimal==4)?"selected":""; ?>>4 Decimals</option>
                    <option class="opt" value="6" <?php echo ($currency->show_decimal==6)?"selected":""; ?>>6 Decimals</option>
                    <option class="opt" value="8" <?php echo ($currency->show_decimal==8)?"selected":""; ?>>8 Decimals</option>
                </select>
                <!-- <input type="text" name="show_decimal" id="show_decimal" class="form-control" placeholder="" 
                value="<?php echo $currency->show_decimal; ?>" /> -->
            </div>
        </div>
       <div class="form-group">
            <label class="col-md-4 control-label">Expiry Date on Home page</label>
            <div class="col-md-4">
                <input type="text" name="expiry_date" id="expiry_date" class="datepicker form-control"
                    placeholder="Expiry Date" value="<?php echo date("d/m/Y",strtotime($currency->expiry_date)); ?>" />
            </div>
        </div>


       

        <div class="form-group">
            <label class="col-md-12 control-label text-center">
                <h4>Community</h4>
            </label>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Coin Market Caplink</label>
            <div class="col-md-4">
                <input type="text" name="marketcap_link" id="marketcap_link" class="form-control" placeholder=""
                    value="<?php echo $currency->marketcap_link; ?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Coin Link</label>
            <div class="col-md-4">
                <input type="text" name="coin_link" id="coin_link" class="form-control" placeholder="" 
                value="<?php echo $currency->coin_link; ?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Official Twitter Link</label>
            <div class="col-md-4">
                <input type="text" name="twitter_link" id="twitter_link" class="form-control" placeholder="" 
                value="<?php echo $currency->twitter_link; ?>" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Sort Order</label>
            <div class="col-md-4">
                <input type="text" name="sorting_order" id="sorting_order" class="form-control"
                    placeholder="Ex-1,2" value="<?php echo $currency->sort_order; ?>" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Deposit</label>
            <div class="col-md-4">
                <div class="form-check-inline">
                    <label class="customradio">
                        <span class="radiotextsty">Enable</span>
                        <input type="radio" name="deposit_status" value="1"  <?php if($currency->deposit_status==1){echo 'checked';} ?>>
                        <span class="checkmark"></span>
                    </label> 
                    <label class="customradio">
                        <span class="radiotextsty">Disable</span>
                        <input type="radio" name="deposit_status" value="0" <?php if($currency->deposit_status==0){echo 'checked';} ?> >
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
        </div> 
        <div class="form-group">
            <label class="col-md-4 control-label">Withdraw</label>
            <div class="col-md-4">
                <div class="form-check-inline">
                    <label class="customradio">
                        <span class="radiotextsty">Enable</span>
                        <input type="radio" name="withdraw_status" value="1"  <?php if($currency->withdraw_status==1){echo 'checked';} ?>>
                        <span class="checkmark"></span>
                    </label> 
                    <label class="customradio">
                        <span class="radiotextsty">Disable</span>
                        <input type="radio" name="withdraw_status" value="0" <?php if($currency->withdraw_status==0){echo 'checked';} ?> >
                        <span class="checkmark"></span>
                    </label>
                </div>
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

<script type="text/javascript">
    
    function process(input){
let value = input.value;
let numbers = value.replace(/[^0-9]/g, "");
input.value = numbers;
}

</script>