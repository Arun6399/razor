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
				<li><a href="<?php echo admin_url();?>">Home </a></li>
				<li class="active">Wallet</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Users Wallet Management <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Users Wallet Management</h4>
                        </div>
                        <?php if($view=='view_all'){ ?>
                        <div class="panel-body">
	                        <div class="clearfix">
	                     
							</div>
							<br/><br/>
                            <div class="table-responsive">
                                <table id="wallet-data" class="table table-striped table-bordered" id="view_all">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <!-- <th class="text-center">User ID</th> -->
											<th class="text-center">Email</th>

											<th class="text-center">Status</th>	
											<th class="text-center">View Balances</th>
											<th class="text-center">View Addresses</th>
											<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
								/*if ($users->num_rows() > 0) {
									 $i = 1; 
									foreach($users->result() as $user) {
										 if($user->usertype==1)
                                        {
                                            $usertype ="Personal";
                                            $username = 'bidex_username';
                                        }
                                        else 
                                        {
                                            $usertype ="Company";
                                            $username = 'company_name';
                                        }
                                        $username = 'bidex_username';
										$wallet_det = $this->common_model->getTableData("wallet",array('user_id'=>$user->id))->row();

										if($wallet_det->status==1)
										{
										
										echo '<tr>';
										echo '<td class="text-center">' . $i . '</td>';
										echo '<td class="text-center" title="'.$user->$username.'">' . character_limiter($user->$username, 20) . '</td>';
										
										if ($user->verified == 1) {
											$status = '<label class="label label-info">Activated</label>';
										} else {
											$status = '<label class="label label-danger">De-Activated</label>';
										}
										echo '<td class="text-center">'.$status.'</td>';
										
										echo '<td class="text-center">';
										echo '<a href="' . admin_url() . 'wallet/view/' . $user->id . '" title="View User Balance"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '<td class="text-center">';
										echo '<a href="' . admin_url() . 'wallet/view_address/' . $user->id . '" title="View User Crypto Address"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '<td class="text-center">';
										echo '<a href="' . admin_url() . 'wallet/delete/' . $user->id . '" title="Delete"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}
									}					
								} */
								?>
                                    </tbody>
                                </table>
                             
                            </div>
                        </div>
                        <?php }elseif($view=='view_wallet'){ ?>
                        <div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'users');
				echo form_open_multipart('',$attributes); ?>
                                <fieldset style="margin-left: 335px;font-size: 14px;">
                                <?php 
								if(!empty($wallets->crypto_amount))
								{$fiat_amount=unserialize($wallets->crypto_amount);
								$crypto_amount=unserialize($wallets->crypto_amount);}

								//print_r($urid);
								?>
                              	<div class="form-group">
                                        <label class="col-md-2 control-label"><strong>Username</strong></label>
                                        <div class="col-md-6 control-label text-left">
										<?php
											$username = get_prefix().'username';
										 	echo $userdetails->$username; ?>
                                        </div>

                                </div>

                                <div class="form-group">
                                        <label class="col-md-2 control-label"><strong>Email</strong></label>
                                        <div class="col-md-6 control-label text-left">
										<?php
											$useremail = getUserEmail($urid);
										 	echo $useremail; ?>



                                        </div>

                                </div>
								
								<?php 
								if(!empty($crypto_amount)){
								foreach($crypto_amount as $keys => $values)
								{

								 ?>
								<?php 
								$id_inc1=1;
								foreach($values as $key1 => $value1)
								{
									if(isset($crypto_currency[$key1])){ 


										$cointotal = to_decimal($value1,8); 

										$symbol = $crypto_currency[$key1]; 

										$currencyDetails = $this->common_model->getTableData('currency', array('currency_symbol'=>$symbol,'status'=>1))->row(); 

									     $currencyDetail = $currencyDetails->online_usdprice;

										$usd_total = $currencyDetail*$cointotal;
										$usdPrice = to_decimal($usd_total,2);

										?>
								        <div class="form-group">
                                        <label class="col-md-2 control-label"><strong><?php echo $crypto_currency[$key1]; ?>
                                        </strong></label>
                                        <div class="col-md-6 control-label text-left">
										<?php echo to_decimal($value1,8); ?> / <b><?=$usdPrice?> USD</b>
                                        </div>
                                       </div>
								<?php
								$id_inc1++;
								}
							}
								}}
								else 
									echo "<center>  ..Nil Balance..   </center>";
								?>
                                    
                                </fieldset>
                            </form>
                        </div>
					<?php } else { ?> 
						<div class="panel-body">
							<?php $address=unserialize($wallets->address); 
							//print_r($address);?>
						<!--new-wallet-design-->
						<div class="address-box">
									<div class="address-coin">
										<h2><span id="mycur_name"></span> Deposit Address</h2>
									</div>
									<form action="">
										<div class="form-group">
											<label for="exampleFormControlSelect1">Select Coin</label>
											<select onchange="change_currency(this.value)" class="form-control" id="exampleFormControlSelect1">
												<option value="0">Choose Currency</option>
									<?php 
									foreach($address as $key => $value)
									{ 
									 ?>
									 <option style="display:<?=($crypto_address[$key]=='')?'none':'block'?>" value="<?php echo $crypto_address[$key]; ?>/<?=$value?>"><?php echo $crypto_address[$key]; ?></option>
									<?php } ?>

												
											</select>
										</div>
										<div style="display:none" class="coin form-group">
											<div class="qr-img">
												<img src="https://smdex.com/assets/admin/img/qr.png" alt="" class="img-responsive">
											</div>
										</div>
										<div style="display:none" class="coin form-group">
										<div class="input-group mb-3">
											<input readonly id="crypto_address" type="text" class="myaddress form-control" placeholder="d58e278a7g5e427g87eg87b" aria-label="Recipient's username" aria-describedby="basic-addon2">
											<div class="input-group-append">
												<span onclick="copy_function()" class="input-group-text" id="basic-addon2">Copy</span>
											</div>
											</div>
										</div>
									</form>
								</div>


								<input type="hidden" id="getuser" value="<?=$userid?>"/>
								
                        </div>
					<?php } ?>
                    </div>
                    <!-- end panel -->
                </div>
			</div>
			<!-- end row -->
		</div>

	<div id="myModal" class="modal fade">
	<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="icon12 minia-icon-close"></span></button>
          <h3>Reason to reject</h3>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
             <center><?php echo form_open(admin_url().'users/verify_level2_reject/'.$users->id.'/Rejected'); ?>
			<textarea name="reject_mail_content" class="form-control"></textarea>
			<br/>
			<button class="btn btn-small btn-danger" style='margin-left:20px;'>REJECT</button>
			<?php echo form_close(); ?></center>
            </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
        </div>
    </div>
    </div>
    </div>
		<!-- end #content -->
<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo admin_source();?>/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/bootstrap/js/bootstrap.min.js"></script>
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

	<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
	<script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>

	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
		<script>
			var admin_url='<?php echo admin_url(); ?>';
		$(document).ready(function() {
			App.init();
		});
		 $(document).ready(function() {
    $('#wallet-data').DataTable( {
    	"responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ordering": false,
        "ajax": admin_url+"wallet/wallet_ajax"
    });
 });
    function change_currency(val)
    {
    	//alert(val);
    	if(val!=0)
    	{
	    	var userid = $("#getuser").val();
			var _this = val;
			var myvar = _this.split("/"); 	
			
			$.ajax({
					url: admin_url+"wallet/get_address/"+userid,
	                type: "POST",
	                data: "currency_symbol="+myvar[0]+"&address="+myvar[1],
	                success: function(data) {
	                	var result = JSON.parse(data);
	                	var imag = result.img;
	                	$(".coin").show();
	                	$(".qr-img").html('<img src="'+imag+'" alt="" class="img-responsive">');
	                	$(".myaddress").val(myvar[1]);
	                	$("#mycur_name").html(myvar[0]);
	                }
	            }); 
	    }
	    else
	    {
	    	$(".coin").hide();
	    }  
      }

function copy_function() {
  var copyText = document.getElementById("crypto_address");
  copyText.select();
  document.execCommand("COPY");
  $('.copy_but').html('COPIED');
} 
		
	</script>
	 <script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>
