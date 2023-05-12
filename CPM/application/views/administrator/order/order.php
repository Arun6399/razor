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
				<li class="active">Order</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Order Management <!--<small>header small text goes here...</small>--></h1>
			<p class="text-right m-b-10">
								<!--<a href="<?php echo admin_url().'pair/add';?>" class="btn btn-primary">Add New</a>-->
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
                            <h4 class="panel-title"><?php if($view=='buy') { echo 'Buy Order'; } elseif($view=='sell'){ echo 'Sell Order'; } else { echo 'Edit Order';  } ?></h4>
                        </div>
					<?php if($view=='buy'){ ?>
                        <div class="panel-body">
                        <div class="clearfix">
                        <div class="input-group inp_grp1">
                        <input type="text" name="search_string" id="search_string" class="form-control" placeholder="Search" value="<?php if(isset($_GET['search_string']) && !empty($_GET['search_string'])){ echo $_GET['search_string']; } else{ echo ''; } ?>" />
                        <span class="input-group-addon">
                        <button class="btn btn-small btn-success" onclick="search()" style='margin-left:20px;'><div class="fa fa-refresh"></div></button> 
                        </span>
                        </div>
                        </div>
                        <br/><br/>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Date & Time</th>
										<th class="text-center">Pair</th>
										<th class="text-center">Send Amount</th>
										<th class="text-center">Fees</th>
										<th class="text-center">Receive Amount</th>
										<th class="text-center">Total</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								if ($order->num_rows() > 0) {
									if($this->uri->segment(4)!=''){ $i = $this->uri->segment(4)+1; }else { $i = 1; }
									foreach($order->result() as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result->username . '</td>';
                                        echo '<td>' . date('Y-m-d h:i:s',$result->datecreated) . '</td>';
										echo '<td>' . $result->from_currency_symbol.'-'.$result->to_currency_symbol . '</td>';
										echo '<td>' . $result->send_amount . '</td>';
										echo '<td>' . $result->fees . '</td>';
										echo '<td>' . $result->receive_amount . '</td>';
										echo '<td>' . $result->final_amount . '</td>';				
										echo '<td>'.$result->send_status.'</td>';
										if($result->send_status=='Pending')
										{
										echo '<td>';
										echo '<a href="' . admin_url() . 'order/edit/' . $result->order_id . '" title="Edit this order"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										}
										else if($result->send_status=='Completed' || $result->send_status=='Cancelled')
										{
										echo '<td>';
										
										echo '</td>';
										}

										echo '</tr>';
										$i++;
									}					
								} else {
                                    echo '<tr>';
                                    echo '<td colspan="10">' . 'No Records Found!!' . '</td>';
                                    echo '</tr>';
                                }
								?>
                                    </tbody>
                                </table>
                                <?php if(isset($_GET['search_string']) && !empty($_GET['search_string'])){ }else { ?>
                                        <ul class="pagination">
                                         <?php echo $this->pagination->create_links(); ?>
                                        </ul>
                                 <?php } ?>
                            </div>
                        </div>
					<?php }elseif($view=='sell'){ ?>
                        <div class="panel-body">
                        <div class="clearfix">
                        <div class="input-group inp_grp1">
                        <input type="text" name="search_string1" id="search_string1" class="form-control" placeholder="Search" value="<?php if(isset($_GET['search_string1']) && !empty($_GET['search_string1'])){ echo $_GET['search_string1']; } else{ echo ''; } ?>" />
                        <span class="input-group-addon">
                        <button class="btn btn-small btn-success" onclick="search1()" style='margin-left:20px;'><div class="fa fa-refresh"></div></button> 
                        </span>
                        </div>
                        </div>
                        <br/><br/>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Date & Time</th>
                                        <th class="text-center">Pair</th>
                                        <th class="text-center">Send Amount</th>
                                        <th class="text-center">Fees</th>
                                        <th class="text-center">Receive Amount</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    <?php
                                if ($order->num_rows() > 0) {
                                   if($this->uri->segment(4)!=''){ $i = $this->uri->segment(4)+1; }else { $i = 1; }
                                    foreach($order->result() as $result) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $result->username . '</td>';
                                        echo '<td>' . date('Y-m-d h:i:s',$result->datecreated) . '</td>';
                                        echo '<td>' . $result->from_currency_symbol.'-'.$result->to_currency_symbol . '</td>';
                                        echo '<td>' . $result->send_amount . '</td>';
                                        echo '<td>' . $result->fees . '</td>';
                                        echo '<td>' . $result->receive_amount . '</td>';
                                        echo '<td>' . $result->final_amount . '</td>';              
                                        echo '<td>'.$result->send_status.'</td>';
                                        if($result->send_status=='Pending')
                                        {
                                        echo '<td>';
                                        echo '<a href="' . admin_url() . 'order/edit/' . $result->order_id . '" title="Edit this order"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                                        echo '</td>';
                                        }
                                        else if($result->send_status=='Completed' || $result->send_status=='Cancelled')
                                        {
                                        echo '<td>';
                                        
                                        echo '</td>';
                                        }

                                        echo '</tr>';
                                        $i++;
                                    }                   
                                } else {
                                    echo '<tr>';
                                    echo '<td colspan="10">' . 'No Records Found!!' . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                                    </tbody>
                                </table>
                                <?php if(isset($_GET['search_string1']) && !empty($_GET['search_string1'])){ }else { ?>
                                <ul class="pagination">
                                 <?php echo $this->pagination->create_links(); ?>
                                 </ul>
                                 <?php } ?>
                            </div>
                        </div>
                    <?php } else{ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'order');
				echo form_open_multipart($action,$attributes); 
										$user = getUserDetails($order->user_id);
										$usernames = $prefix.'username';
										$username = $user->$usernames;
                                        if($order->type=='buy')
                                        {
                                            $from_currency = getfiatcurrency($order->from_currency);
                                            $to_currency = getcryptocurrency($order->to_currency);
                                        }
                                        else
                                        {
                                            $from_currency = getcryptocurrency($order->from_currency);
                                            $to_currency = getfiatcurrency($order->to_currency);
                                        }
										 ?>

									<div class="form-group">
                                        <label class="col-md-2 control-label">Username</label>
                                        <div class="col-md-8 control-label text-left">
										<?php echo $username; ?>
                                        </div>
                                    </div>
                                  
									<div class="form-group">
                                        <label class="col-md-2 control-label">Pair</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $from_currency.'-'.$to_currency; ?>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Type</label>
                                        <div class="col-md-8 control-label text-left">
										<?php echo ucfirst(strtolower($order->type)); ?>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Send Amount</label>
                                        <div class="col-md-8 control-label text-left">
										<?php echo $order->send_amount.'&nbsp;'.$from_currency; ?>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Receive Amount</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $order->receive_amount.'&nbsp;'.$to_currency; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Fees</label>
                                        <div class="col-md-8 control-label text-left">
										<?php
                                        if($order->type=='buy')
                                        {
                                            $cu = $from_currency;
                                        }
                                        else
                                        {
                                            $cu = $to_currency;
                                        }
                                         echo $order->fees.'&nbsp;'.$cu; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Total</label>
                                        <div class="col-md-8 control-label text-left">
										<?php 
                                         if($order->type=='buy')
                                        {
                                            $cu = $from_currency;
                                        }
                                        else
                                        {
                                            $cu = $to_currency;
                                        } echo $order->final_amount.'&nbsp;'.$cu; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Ordered On</label>
                                        <div class="col-md-8 control-label text-left">
										<?php echo gmdate("d-m-Y h:i:s", $order->datecreated); ?>
                                        </div>
                                    </div>

                                    <?php if($order->type=='sell'){ ?>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Payment Method</label>
                                        <div class="col-md-8 control-label text-left">
										<?php if($order->payment_method=='bankwire'){ echo 'Bank Wire'; } else { echo 'Balance Update'; } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if($order->payment_method=='bankwire') { ?>
                                     <h4 style="text-align:center">Bank Details</h4>
                                     <?php $bank = get_user_bank_details($order->bank, $order->user_id); ?>
                                      <div class="form-group">
                                        <label class="col-md-2 control-label">Bank Account Number</label>
                                        <div class="col-md-8 control-label text-left">
										<?php echo $bank->bank_account_number; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Bank Swift</label>
                                        <div class="col-md-8 control-label text-left">
										<?php echo $bank->bank_swift; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Bank Account Name</label>
                                        <div class="col-md-8 control-label text-left">
										<?php echo $bank->bank_account_name; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Bank Name</label>
                                        <div class="col-md-8 control-label text-left">
										<?php echo $bank->bank_name; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Bank Postal Code</label>
                                        <div class="col-md-8 control-label text-left">
										<?php echo $bank->bank_postalcode; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Bank City</label>
                                        <div class="col-md-8 control-label text-left">
										<?php echo $bank->bank_city; ?>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Bank Country</label>
                                        <div class="col-md-8 control-label text-left">
										<?php
										$country = get_countryname($bank->bank_country);
										 echo $country->country_name; ?>
                                        </div>
                                    </div>

                                    
                                    <?php } ?>

                               
                            </form>

                            <?php if($order->send_status == 'Pending'){ ?>
								<a class="btn btn-small btn-danger" href="#myModal"  data-toggle="modal" style='margin-left:20px;'>Cancel</a>
							<?php } ?>
							<?php if($order->send_status == 'Pending'){ 
								if($order->payment_method=='bankwire')
								{
								?>
								<a class="btn btn-small btn-success" href="#myModal1"  data-toggle="modal" style='margin-left:20px;'>Complete</a>
							<?php }
								else { 
									$url = admin_url().'order/status/'.$order->order_id.'/Completed';
									?>
								<a class="btn btn-small btn-success" href="<?php echo $url; ?>"   style='margin-left:20px;'>Complete</a>

							<?php }
							 } ?>
                        </div>
					<?php } ?> 
                    </div>
                    <!-- end panel -->
                </div>
			</div>
			<!-- end row -->
		</div>
	<div id="myModal" class="modal fade adm_pop">
	<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="fa fa-close"></span></button>
          <h4>Reason to reject</h4>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
             <center><?php echo form_open(admin_url().'order/reject/'.$order->order_id); ?>
			<textarea name="mail_content" class="form-control" required></textarea>
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

    <div id="myModal1" class="modal fade adm_pop">
	<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header grn_mdh">
          <button type="button" class="close" data-dismiss="modal"><span class="fa fa-close"></span></button>
          <h4>Transaction Id</h4>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
             <center><?php echo form_open(admin_url().'order/status/'.$order->order_id.'/Completed'); ?>
			<textarea name="transaction_id" class="form-control" required></textarea>
			<br/>
			<button class="btn btn-small btn-success" style='margin-left:20px;'>Complete</button>
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
	<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	
	<!-- ================== END PAGE LEVEL JS ================== -->
	 
	<script>
		$(document).ready(function() {
			App.init();
		});
        function search() {
            var search = $('#search_string').val();
            var url = '<?php echo admin_url(); ?>';
            if(search!=''){
            window.location.href=url+'order/buy/?search_string='+search; }
            else { window.location.href=url+'order/buy'; }
        }
        function search1() {
            var search1 = $('#search_string1').val();
            var url = '<?php echo admin_url(); ?>';
            if(search1!=''){
            window.location.href=url+'order/sell/?search_string1='+search1; }
            else { window.location.href=url+'order/sell'; }
        }
	</script>
	<script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','<?php echo admin_source()."www.google-analytics.com/analytics.js";?>','ga');
      ga('create', 'UA-53034621-1', 'auto');
      ga('send', 'pageview');
    </script>