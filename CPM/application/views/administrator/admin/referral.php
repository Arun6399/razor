<!-- begin #content -->
		<div id="content" class="content">
			
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="<?php echo admin_url();?>">Home</a></li>
				<li class="active">Referral</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Block User IP <!--<small>header small text goes here...</small>--></h1>
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
                                <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Referral</h4>
                        </div>
						<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="data-table">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Email</th>
										
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php


								if (isset($referral) && !empty($referral)) {
									$i=0;
									foreach($referral as $referral_list){
										$i++;
										$User_Id = $referral_list['user_id'];
									?>
									<tr>
									<td><?php echo $i;?></td>
									<td><?php echo $referral_list['user_email'];?></td>
									<td><a href="<?php echo admin_url().'admin/referral_user/'.$User_Id;?>/1"><?php echo $referral_list['BTC_Bonus'];?></a></td>
									<td><a href="<?php echo admin_url().'admin/referral_user/'.$User_Id;?>/7"><?php echo $referral_list['CBC_Bonus'];?></a></td>
								</tr>
									<?php
								}
									}					
								 else {
									?>
									<tr><td colspan="4">No records found</td></tr>
									<?php
								}
								?>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
					<?php } if($view=='view_user'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="data-table">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Currency</th>
										<th class="text-center">Bonus From</th>
										<th class="text-center">Amount</th>
										<th class="text-center">Description</th>
										<th class="text-center">Date and Time</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								if (isset($transactions) && !empty($transactions)) {
									$i=0;
									foreach($transactions as $trans){
										$i++;
									?>
									<tr>
									<td><?php echo $i;?></td>
									<td><?php echo getcryptocurrency($trans->currency_id);?></td>
									<?php
									if($trans->bonus_from!=0){
										$Bonus_From = getUserEmail($trans->bonus_from);
									}
									else{
										$Bonus_From = 'Unknown';
									}
									?>
									<td><?php echo $Bonus_From;?></td>
									<td><?php echo $trans->amount;?></td>
									<td><?php echo $trans->description;?></td>
									<td><?php echo date('Y-m-d H:i A',$trans->datetime);?></td>
								</tr>
									<?php
								}
									}					
								 else {
									?>
									<tr><td colspan="6">No records found</td></tr>
									<?php
								}
								?>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
					<?php }?>
					
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
	<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	
	<!-- ================== END PAGE LEVEL JS ================== -->
	
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
	  var admin_url='<?php echo admin_url(); ?>';
	 // alert(admin_url);
	
    </script>
