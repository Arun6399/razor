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
			<!-- begin page-header -->
            <div class="row">
                <div class="col-md-6">
                    <h1 class="page-header">
                        Currency request Management
                        <span class="text-right m-b-10">
                            <a href="<?php echo admin_url().'currency/add';?>" class="btn btn-primary">
                                <i class="fa fa-pencil"></i>&nbsp; Add New
                            </a>
                        </span>
                        <!--<small>header small text goes here...</small>-->
                    </h1>
                </div>
                <div class="col-md-6">
                    <!-- begin breadcrumb -->
                    <ol class="breadcrumb pull-right">
                        <li><a href="<?php echo admin_url();?>">Home</a></li>
                        <li class="active">Currency request Management</li>
                    </ol>
                    <!-- end breadcrumb -->
                </div>
            </div>
    <!-- end page-header -->

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
                            <h4 class="panel-title">Currency request Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                        <!-- <div class="clearfix">
                       <div class="input-group inp_grp1">                  

                        <input type="text" name="search_string" id="search_string" class="form-control" placeholder="Search" value="<?php if(isset($_GET['search_string'])){ echo $_GET['search_string']; } else{ echo ''; } ?>" />
                        <span class="input-group-addon">	
						<button class="btn btn-small btn-success" onclick="search()" style='margin-left:20px;'><div class="fa fa-refresh"></div></button> 
						</span>
						</div>
						</div>
						<br/><br/> -->
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Coin type</th>
										<th class="text-center">Coin name</th>
										<th class="text-center">Coin symbol</th>
										<th class="text-center">Maximum Supply</th>
										<th class="text-center">Coin price</th>
										<th class="text-center">User name</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								if ($coins_request->num_rows() > 0) {
									if($this->uri->segment(4)!=''){ $i = $this->uri->segment(4)+1; }else { $i = 1; }
									foreach($coins_request->result() as $result) {
                                        if($result->verify_request==0)
                                        {
                                            $status = 'Requested';
                                        }elseif($result->verify_request==1)
                                        {
                                            $status = 'Accepted';
                                        }else
                                        {
                                            $status = 'Cancelled';
                                        }

                                        if($result->asset_type==1)
                                        {
                                            $asset_type = 'coin';
                                        }else
                                        {
                                            $asset_type = 'token';
                                        }
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $asset_type . '</td>';
										echo '<td>' . $result->currency_name . '</td>';
										echo '<td>' . $result->currency_symbol . '</td>';
										echo '<td>' . $result->max_supply . '</td>';
										echo '<td>' . $result->online_usdprice . '</td>';
										echo '<td>' . $result->username . '</td>';
										echo '<td>' . $status . '</td>';
										echo '<td>';
										echo '<a href="' . admin_url() . 'request_coins/view/' . $result->id . '" title="View"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';

										echo '</tr>';
										$i++;
									}					
								}  else {
                                    echo '<tr><td></td><td></td><td></td><td></td><td class="text-center">No currency requested yet!</td><td></td><td></td><td></td><td></td></tr>';
                                }
								?>
                                    </tbody>
                                </table>
                                <!-- <?php if(isset($_GET['search_string'])){ }else { ?>
                                <ul class="pagination">
                                 <?php echo $this->pagination->create_links(); ?>
                                 </ul>
                                <?php } ?> -->
                            </div>
                        </div>
					<?php }else{ ?>
					<div class="panel-body">
                        <?php $attributes=array('class'=>'form-horizontal','id'=>'withdraw');
                echo form_open_multipart($action,$attributes); 
                ?>
						        <fieldset>
                                  
								<div class="form-group">
                                <label class="col-md-2 control-label">Coin type</label>
                                <div class="col-md-8 control-label text-left">
								<?php 
                                if($coins_request->asset_type==1)
                                {
                                    $asset_type = 'coin';
                                }else
                                {
                                    $asset_type = 'token';
                                }

                                echo $asset_type; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Coin name</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $coins_request->currency_name; ?>
                                </div>
                                </div>
                                
                                <div class="form-group">
                                <label class="col-md-2 control-label">Coin symbol</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $coins_request->currency_symbol; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Maximum supply</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $coins_request->max_supply; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Coin price</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $coins_request->online_usdprice; ?>
                                </div>
                                </div>
                                
                                <div class="form-group">
                                <label class="col-md-2 control-label">Priority</label>
                                <div class="col-md-8 control-label text-left">
                                <?php if($coins_request->priority==1) {
                                $priority = '1 (BTC/ETH)'; }
                                elseif($coins_request->priority==2)
                                {
                                  $priority = '2 (BTC,ETH)';  
                                }
                                else
                                {
                                   $priority = 'All (BTC, ETH, XERA, USDT)'; 
                                }
                                ?>
                                <?php echo $priority; ?>

                                </div>
                            	</div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Coin logo</label>
                                <div class="col-md-8 control-label text-left">
                                
                                <?php echo '<img src='.$coins_request->image.'>'; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Type of blockchain</label>
                                <div class="col-md-8 control-label text-left">
                                <?php if($coins_request->crypto_type !='') {
                                    
                                    $blockchain = $coins_request->crypto_type; 
                                 }
                                 else
                                 {
                                   $blockchain = $coins_request->token_type; 
                                 } ?>
                                <?php echo ucfirst($blockchain); ?>
                                </div>
                                </div>
                                <h4 style="text-align: center"> Contact Details </h4>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Coin marketcap link</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $coins_request->marketcap_link; ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Coin link</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $coins_request->coin_link; ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Website link</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $coins_request->website_link; ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Explorer link</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $coins_request->explorer_link; ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Official Telegram Group</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo ucfirst($coins_request->telegram_group); ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Refered BY</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo ucfirst($coins_request->referred_by); ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Twitter link</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $coins_request->twitter_link; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">User name</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $coins_request->username; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Email</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $coins_request->email; ?>
                                </div>
                                </div>

                                <?php if($coins_request->verify_request==0 || $coins_request->verify_request==2){ ?>
                                <a class="btn btn-small btn-success" href="#myModal1"  data-toggle="modal"  style='margin-left:20px;'>Accept</a>
                            <?php } ?>
                            <?php if($coins_request->verify_request==0 || $coins_request->verify_request==1) { ?>
                                <a class="btn btn-small btn-danger" href="#myModal"  data-toggle="modal" style='margin-left:20px;'>Cancel</a>
                                <!-- New code 11-5-18 -->
                                <?php } ?>
                                
                                </fieldset>
                                </form>
                        </div>
					<?php } ?> 
                    </div>
                    <!-- end panel -->
                </div>
			</div>
			<!-- end row -->

	<div id="myModal" class="modal fade adm_pop">
	<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="fa fa-close"></span></button>
          <h4>Cancel</h4>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
             <center><?php echo form_open(admin_url().'request_coins/reject/'.$coins_request->id); ?>
			<button class="btn btn-small btn-danger" style='margin-left:20px;'>Confirm</button>
            <button class="btn btn-small btn-danger" data-dismiss="modal" style='margin-left:20px;'>Cancel</button>
			<?php echo form_close(); ?></center>
            </div>
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
          <h4>Accept</h4>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
             <center><?php echo form_open(admin_url().'request_coins/status/'.$coins_request->id); ?>
			<button class="btn btn-small btn-success" style='margin-left:20px;'>Confirm</button>
            <button class="btn btn-small btn-success" style='margin-left:20px;' data-dismiss="modal">Cancel</button>
			<?php echo form_close(); ?></center>
            </div>
        </div>
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
		
		$('#cms').validate({
			rules: {
				heading: {
					required: true
				},
				title: {
					required: true
				},
				meta_keywords: {
					required: true,
				},
				meta_description: {
					required: true
				},
				content_description: {
					required: true,
				}
				
			},
			highlight: function (element) {
				//$(element).parent().addClass('error')
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

		function search() {
    		var search = $('#search_string').val();
    		var url = '<?php echo admin_url(); ?>';
    		if(search!=''){
    		window.location.href=url+'request_coins/?search_string='+search; }
    		else { window.location.href=url+'request_coins'; }
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
