<!-- begin #content -->
<div id="content" class="content">
    <?php 
        $error = $this->session->flashdata('error');
        if($error != '') {
            echo '<div class="alert alert-danger">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>'.$error.'</div>';
        }
        $success = $this->session->flashdata('success');
        if($success != '') {
            echo '<div class="alert alert-success">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>'.$success.'</div>';
        } 
    ?>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="<?php echo admin_url();?>">Home</a></li>
        <li class="active">Coin Request</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">Coin Request <!--<small>header small text goes here...</small>--></h1>
    <p class="text-right m-b-10">
        <a href="<?php echo admin_url().'coin_request/create';?>" class="btn btn-primary">Add New</a>
    </p>
    <!-- end page-header -->
    <!-- begin row -->
    <div class="row">
        <div class="col-md-12">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                    <h4 class="panel-title">Coin Request</h4>
                </div>
                <?php 
                    if($view=='view_all')
                    { 
                ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="coin-request-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Coin Name</th>
                                        <th class="text-center">Coin Symbol</th>
                                        <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    <?php
                                if ($coin_request->num_rows() > 0) {
                                    $i = 1;
                                    foreach($coin_request->result() as $result) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $result->username . '</td>';
                                        echo '<td>' . $result->coin_name . '</td>';
                                        echo '<td>' . $result->coin_token . '</td>';
                                        echo '<td>';
                                        echo '&nbsp;&nbsp;&nbsp;';
                                        echo '<a href="' . admin_url() . 'coin_request/view/' . $result->coin_id . '" title="View this Request"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                                        echo '</td>';
                                        echo '</tr>';
                                        $i++;
                                    }                   
                                } else {
                                    echo '<tr><td></td><td></td><td colspan="2" class="text-center">No Coin Requests Received Yet!</td><td></td><td></td></tr>';
                                }
                                ?>
                                    </tbody>
                                </table>
                                 <ul class="pagination">
                                 <?php echo $this->pagination->create_links(); ?>
                                 </ul>
                            </div>
                        </div>
                <?php 
                    } else { 
                ?>
                        <div class="panel-body">
                            <?php 
                                $attributes=array('class'=>'form-horizontal','id'=>'coin_request-frm');
                                echo form_open_multipart($action,$attributes); 
                            ?>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Email Address</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->email; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Contact Telegram</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->contact; ?>
                                        </div>
                                    </div>
                                  
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Coin/Token Ticker</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->coin_name; ?>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                    <label class="col-md-2 control-label">Select Network Type</label>
                                    

                                    <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->crypto_type; ?>
                                        </div>
                                  
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Select Token Type</label>
                                   
                                
                                    <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->erc_token; ?>
                                        </div>
                                    
                                </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Use-Cases of your Coin/Token</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->coin_token; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Project Name</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->project_name; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Official Website Link</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php if($coin_request->webste_link!='') { echo $coin_request->webste_link; } else { echo '-'; } ?>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Token's smart contract address/ blockchain explorer</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php if($coin_request->smart_address!=''){ echo $coin_request->smart_address; } else { echo '-'; } ?>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Project's White paper</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->prjct_whitepaper; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Current Market Price of your Coin/Token</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->market_price; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Total Supply of your Coin/Token</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->supply_coin; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Name of the cryptocurrency Exchange Listed coin/token</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->coin_exchange; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <label class="col-md-2 control-label">Pay bounty/airdrop to our users *</label>
                                    <div class="col-md-8">        
                                        <div class="form-check-inline">
                                    
                                    <label class="customradio">
                                        <input type="radio" name="bounty_user" value="yes" <?php echo ($coin_request->bounty_user=='yes')?"checked":""; ?>>
                                        <span class="radiotextsty">Yes</span>
                                        
                                        
                                        <span class="checkmark"></span>
                                    </label> 
                                    <label class="customradio">
                                        <input type="radio" name="bounty_user" value="no" <?php echo ($coin_request->bounty_user=='no')?"checked":""; ?>>
                                        <span class="radiotextsty">No</span>
                                        
                                        <span class="checkmark"></span>
                                    </label>
                                     <label class="customradio">
                                        <input type="radio" name="bounty_user" value="maybe" <?php echo ($coin_request->bounty_user=='maybe')?"checked":""; ?>>
                                        <span class="radiotextsty">Maybe</span>
                                        
                                        <span class="checkmark"></span>
                                     </label>
                          
                                   </div>
                                   </div>
                                </div>
                                <div class="form-group">
                                        <label class="col-md-2 control-label">listing fee/bounty fee(BTC)</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->listing_btc; ?>
                                        </div>
                                    </div> 
                                   <div class="form-group">
                                    <label class="col-md-12 control-label text-center"><h4>Fees</h4></label>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Currency Name</label>
                                
                                <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->coin_name; ?>
                                        </div>
                               </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Currency Symbol</label>
                                
                                <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->coin_symbol; ?>
                                        </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Currency Type</label>
                               <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->type; ?>
                                        </div>

                               
                            </div> 

                                   <div class="form-group">
                                    <label class="col-md-2 control-label">Withdraw Fees Type</label>
                                    
                                    
                                <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->withdraw_fees_type; ?>
                                        </div>
                                </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Withdraw Fees</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->withdraw_fees; ?>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Maker Fee</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->maker_fee; ?>
                                        </div>
                                    </div>  
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Taker Fee</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->taker_fee; ?>
                                        </div>
                                    </div>  
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Minimum Withdraw Limit</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->min_withdraw_limit; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Maximum Withdraw Limit</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->max_withdraw_limit; ?>
                                        </div>
                                    </div>
                                    <div id="deposit_seg"
                                        <?php if($coin_request->type=='fiat'){echo 'style="display: block;"';}else{echo 'style="display: none;"';}?>>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Minimum Deposit Limit</label>
                                            
                                            <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->min_deposit_limit; ?>
                                        </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Maximum Deposit Limit</label>
                                            
                                             <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->max_deposit_limit; ?>
                                        </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Deposit Fees Type</label>
                                            
                                           
                                            <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->deposit_fees_type; ?>
                                        </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Deposit Fees</label>
                                            
                                            <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->deposit_fees; ?>
                                        </div>
                                        </div>
                                    </div> 
                                    <?php if($curn_type!='token') { ?>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Select asset type</label>
                                        <div class="col-md-8">
                                            <div class="form-check-inline">
                                                
                                                <label class="customradio">
                                                    <span class="radiotextsty">Coin</span>
                                                    <input type="radio" name="assets_types" value="1" <?php echo ($coin_request->asset_type==1)?"checked":""; ?>>
                                                    <span class="checkmark"></span>
                                                </label> 
                                                      
                                                <label class="customradio">
                                                    <span class="radiotextsty">Token</span>
                                                    <input type="radio" name="assets_types" value="0" <?php echo ($coin_request->asset_type==0)?"checked":""; ?>>
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
                                    <div class="form-group">
                                        <label class="col-md-12 control-label text-center"><h4>Community</h4></label>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Coin Market Caplink</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->marketcap_link; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Coin Link</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->coin_link; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Official Twitter Link</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->twitter_link; ?>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Sort Order</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $coin_request->sorting_order; ?>
                                        </div>
                                    </div>                               
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Created On</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo date("d-m-Y h:i a", strtotime($coin_request->added_date)); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Status</label>
                                        <div class="col-md-4 control-label text-left">
                                            <select id="status" name="status" class="form-control" <?=($coin_request->status=="1")?'disabled':''?> >
                                                <option value="0" <?=($coin_request->status=="0")?'Selected':''?> >Request</option>
                                                <option value="1" <?=($coin_request->status=="1")?'Selected':''?> >Accept</option>
                                                <option value="2" <?=($coin_request->status=="2")?'Selected':''?> >Reject</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="form-group cls-rej">
                                        <label class="col-md-2 control-label">Rejection Reason</label>
                                        <div class="col-md-4 control-label text-left">
                                           <textarea name="reject_reason" id="reject_reason" class="form-control" placeholder="Reject Reason" rows="5"><?=($coin_request->rejection_reason)?>
                                           </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <?php
                                                if($coin_request->status != "1")
                                                {
                                            ?>
                                                    <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button> &nbsp;
                                            <?php
                                                }
                                            ?>
                                            <a class="btn btn-sm btn-warning" href="<?php echo admin_url().'coin_request';?>">Back</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                <?php 
                    } 
                ?> 
            </div>
            <!-- end panel -->
        </div>
    </div>
    <!-- end row -->
</div>
<!-- end #content -->
<!-- ================== BEGIN BASE JS ================== -->
<script src="<?php echo admin_source();?>/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/ckeditor/ckeditor.js"></script>
<!--[if lt IE 9]>
    <script src="<?php echo admin_source();?>/crossbrowserjs/html5shiv.js"></script>
    <script src="<?php echo admin_source();?>/crossbrowserjs/respond.min.js"></script>
    <script src="<?php echo admin_source();?>/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<script src="<?php echo admin_source();?>/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/jquery-cookie/jquery.cookie.js"></script>
<!-- ================== END BASE JS ================== -->
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="<?php echo admin_source();?>/plugins/gritter/js/jquery.gritter.js"></script>
<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.time.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script> 
<script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>
<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script type="text/javascript">
    $(document).ready(function() {

        $('.cls-rej').hide();
        <?php
            if($coin_request->status == "2")
            {
        ?>
                $('.cls-rej').show();
        <?php
            }
        ?>
        var admin_url = '<?php echo admin_url(); ?>';
        $('#coin-request-data-table').DataTable( {
            "responsive" : true,
            "processing" : true,
            "pageLength" : 10,
            "serverSide": true,
            "order": [[0, "asc" ]],
            "searching": true,
            "ajax": admin_url+"coin_request/coin_request_ajax"
        });

        $(document).on('change', '#status', function(e){
            var status = $(this).val();
            if(status == "2")
            {
                $('.cls-rej').show();
                $('#reject_reason').text('');
            }
            else
            {
                $('#reject_reason').text('');
                $('.cls-rej').hide();
            }
        });
        $('#coin_request-frm').validate({
            rules: {
                status: {
                    required: true
                },
                reject_reason: {
                    required: true
                }               
            },
            highlight: function (element) {
                $(element).parent().addClass('error')
            },
            unhighlight: function (element) {
                $(element).parent().removeClass('error')
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        App.init();
    });
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','<?php echo admin_source()."www.google-analytics.com/analytics.js";?>','ga');
  ga('create', 'UA-53034621-1', 'auto');
  ga('send', 'pageview');
</script>

<script type="text/javascript">
var currencyByCategory = {
    ETH: ["ERC 20"],
    BSC: ["BSC 2", "BSC 20"],
    TRX: ["TRX 20"]
}

    function changecat(value) {
        if (value.length == 0) document.getElementById("erc_token").innerHTML = "<option></option>";
        else {
            var catOptions = "";
            var categoryId = "";
            for (categoryId in currencyByCategory[value]) {
                catOptions += "<option>" + currencyByCategory[value][categoryId] + "</option>";
            }
            document.getElementById("erc_token").innerHTML = catOptions;
        }
    }
</script>