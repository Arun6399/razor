<link href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css" rel="stylesheet">
<style type="text/css">
div.dt-buttons {
margin-left: 10px;
}
.buttons-excel {
color: #fff !important;
background: #348fe2 !important;
border-color: #348fe2 !important;
}
select.form-control{
    display: inline;
    width: 200px;
    margin-left: 25px;
  }
</style>
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
				<li class="active"><?php echo ucfirst($view); ?> Spot Fiat History</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?php echo ucfirst($view); ?> Spot Fiat History <!--<small>header small text goes here...</small>--></h1>
			<p class="text-right m-b-10">
								
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
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title"><?php echo ucfirst($view); ?> Spot Fiat History</h4>
                        </div>
					<?php if($view=='buy'){ ?>
                        <div class="panel-body">
                       <br/>
                            <div class="table-responsive">
                                
                                <table id="datas-table" class="table table-striped table-bordered" >
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Date & Time</th>
                                        <th class="text-center">User Email</th>
                                        <th class="text-center">Crypto</th>
                                        <th class="text-center">Fiat</th>
										<th class="text-center">Type</th>
                                        <th class="text-center">Card Type</th>
										<th class="text-center">Status</th>
                                        <th class="text-center">View</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                            <?php 
                                            $i=1;
                                                if(isset($buyspots)) {
                                                    foreach($buyspots as $buy) {
                                                        $crypto = getcurrencydetail($buy->cryptocurrency);
                                                        $fiat = getcurrencydetail($buy->fiat_currency);
                                                        $mail = getUserEmail($buy->user_id);

                                            ?>   
                                            <tr>
                                           <td><?=$i;?></td> 
                                           <td><?=$buy->datetime;?></td> 
                                           <td><?=$mail;?></td> 
                                           <td><?=$buy->crypto_amount.' '.$crypto->currency_symbol;?></td> 
                                           <td><?=$buy->fiat_amount.' '.$fiat->currency_symbol;?></td> 
                                           <td><?=ucfirst($buy->type);?></td> 
                                           <td><?=ucfirst($buy->bank_type);?></td> 
                                           <td><?=ucfirst($buy->status);?></td> 
                                           <td><a href="<?=admin_url()?>spot_history/view/<?=$buy->id?>" data-placement="top" data-toggle="popover" data-content="View the Order details." class="poper"><i class="fa fa-eye text-primary"></i></a></td> 

                                           </tr>


                                       <?php $i++; } } ?>


                                    </tbody>

                                </table>
                              
                            </div>
                        </div>
					<?php } else if($view=='sell') { ?>
						<div class="panel-body">
					<br/>
                            <div class="table-responsive">
                                
                                <table id="datas-table" class="table table-striped table-bordered" >
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Date & Time</th>
                                        <th class="text-center">User Email</th>
                                        <th class="text-center">Crypto</th>
                                        <th class="text-center">Fiat</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Card Type</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">View</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                            <?php 
                                            $j=1;
                                                if(isset($sellspots)) {
                                                    foreach($sellspots as $sell) {
                                                        $crypto = getcurrencydetail($sell->cryptocurrency);
                                                        $fiat = getcurrencydetail($sell->fiat_currency);
                                                        $mail = getUserEmail($sell->user_id);

                                            ?>   
                                            <tr>
                                           <td><?=$j;?></td> 
                                           <td><?=$sell->datetime;?></td> 
                                           <td><?=$mail;?></td> 
                                           <td><?=$sell->crypto_amount.' '.$crypto->currency_symbol;?></td> 
                                           <td><?=$sell->fiat_amount.' '.$fiat->currency_symbol;?></td> 
                                           <td><?=ucfirst($sell->type);?></td> 
                                            <td><?=ucfirst($buy->bank_type);?></td> 
                                           <td><?=ucfirst($sell->status);?></td> 
                                           <td><a href="<?=admin_url()?>spot_history/view/<?=$sell->id?>" data-placement="top" data-toggle="popover" data-content="View the Order details." class="poper"><i class="fa fa-eye text-primary"></i></a></td> 

                                           </tr>


                                       <?php $j++; } } ?>


                                    </tbody>

                                </table>
                               
                            </div>
                        </div>
					<?php } else {

                        $country = get_countryname($user_bankdetails->bank_country);
                        $crypto = getcurrencydetail($order->cryptocurrency);
                        $fiat = getcurrencydetail($order->fiat_currency);
                     ?>

                            <div class="panel-body">
                                <form class="form-horizontal">
                                                                <fieldset>
                                  
                                <div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo getUserDetails($order->user_id,'cpm_fname'); ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Mail</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo getUserEmail($order->user_id); ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Crypto Currency</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $order->crypto_amount.' - '.$crypto->currency_symbol; ?>
                                </div>
                                </div>
                           


                                <div class="form-group">
                                <label class="col-md-2 control-label">Fiat Currency</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $order->fiat_amount.' - '.$fiat->currency_symbol; ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Type</label>
                                <div class="col-md-8 control-label text-left text-uppercase <?php echo ($order->type=='buy') ? 'text-success' : 'text-danger' ?>" style="font-weight: bold;">
                                <?php echo ucfirst($order->type); ?>
                                </div>
                                </div>

                                 <div class="form-group">
                                <label class="col-md-2 control-label">Card Type</label>
                                <div class="col-md-8 control-label text-left text-uppercase <?php echo ($order->bank_type=='new') ? 'text-success' : 'text-warning' ?>" style="font-weight: bold;">
                                <?php echo ucfirst($order->bank_type); ?>
                                </div>
                                </div>


                                <div class="form-group">
                                <label class="col-md-2 control-label">Status</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo ucfirst($order->status); ?>
                                </div>
                                </div>


                                
                                
                                <div class="form-group">
                                <label class="col-md-2 control-label">Per Price</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $order->perprice.' - '.$fiat->currency_symbol; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Date Time</label>
                                <div class="col-md-8 control-label text-left">
                                <?php  echo $order->datetime;?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Pay Via</label>
                                <div class="col-md-8 control-label text-left">
                                Card
                                </div>
                                </div>

                               

                                <h4 style="text-align: center"> Bank Details </h4>

                                <?php if($order->bank_type=='old') { ?>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Account Number</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $user_bankdetails->bank_account_number; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Swift</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $user_bankdetails->bank_swift; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Account Name</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $user_bankdetails->bank_account_name; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Name</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $user_bankdetails->bank_name; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Address</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $user_bankdetails->bank_address; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Postal code</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $user_bankdetails->bank_postalcode; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank City</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $user_bankdetails->bank_city; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Country</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $country; ?> 
                                </div>
                                </div>
                                <?php } else if($order->bank_type=='new') { ?>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Card Name</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $order->card_name; ?> 
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Card Number</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $order->card_number; ?> 
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Expiry Date</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $order->expiry_date; ?> 
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">CCV</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $order->ccv; ?> 
                                </div>
                                </div>


                                <?php } ?>




                                </fieldset>
                            
                            </div>

                    <?php } ?>
                    </div>
                    <!-- end panel -->
                </div>
			</div>
			<!-- end row -->
		</div>

		<!-- end #content -->
<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo admin_source();?>plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/ckeditor/ckeditor.js"></script>
	
	<script src="<?php echo admin_source();?>plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo admin_source();?>plugins/gritter/js/jquery.gritter.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.time.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.resize.min.js"></script>



    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <!-- <script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script>  -->
<script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>

	<script src="<?php echo admin_source();?>js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>js/apps.min.js"></script>

 <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>


<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>


<!-- 
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> -->
   <!--  <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script> -->

	
	<!-- ================== END PAGE LEVEL JS ================== -->

	<script>
		$(document).ready(function() {
			App.init();
		});
		 /*$(document).ready(function() {
         $.fn.dataTableExt.sErrMode = 'throw';
         $('#buy').DataTable();
         $('#sell').DataTable();

        } );*/

		var admin_url='<?php echo admin_url(); ?>';
		
$(document).ready(function() {

    $('.table').DataTable(); 
});
        



	</script>
	 <script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>