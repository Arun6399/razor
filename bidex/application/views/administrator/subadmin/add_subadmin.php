<!-- begin #content -->
<style type="text/css">
	
input[type='checkbox'] {
  width: 13px;
  height: 13px;
  padding: 0;
  margin-right:5px;

  vertical-align: bottom;
  position: relative;
  top: -1px;
  *overflow: hidden;
}
</style>
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
				<li class="active">Sub Admin Details</li>
			</ol>
	
			<h1 class="page-header">Sub Admin Details</h1>
			<p class="text-right m-b-10"></p>
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
                            <h4 class="panel-title">Sub Admin Details</h4>
                        </div>
					
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'add_subadmin_details');
							echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                 
									<div class="form-group">
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-8">
										<input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" value="" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Email ID</label>
                                        <div class="col-md-8">
										<input type="text" name="emailid" id="emailid" class="form-control" placeholder="Enter " value="" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Password</label>
                                        <div class="col-md-8">
										<input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Pattern Code</label>
                                        <div class="col-md-4">
										<div id="patternContainer"></div>
                                            <input type="hidden" value="" name="patterncode" id="patterncode" />
                                        </div>
                                        </div>
                                  
									  <div class="form-group">
                                        <label class="col-md-2 control-label">
                                        	Status
                                        </label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="status" id="status">
                                            <option value="1">Active</option>
                                            <option value="0">Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    	  <div class="form-group">
                                        <label class="col-md-2 control-label">
                                        	privilege
                                        </label>
                                        
                              
                         	<div class="col-md-3">
					         <input type="checkbox" name="privilege[]" value="dashboard"> Dashboard<br><br>
					 		 <input type="checkbox" name="privilege[]" value="users"> Users<br><br>
					 		  <input type="checkbox" name="privilege[]" value="wallet"> Wallet Management<br><br>
					 		  <input type="checkbox" name="privilege[]" value="curr" > Currency<br><br>
					 		  <input type="checkbox" name="privilege[]" value="trade" > Trade Pairs<br><br>
					 		  <input type="checkbox" name="privilege[]" value="deposit" > Deposit<br><br>
					 		   <input type="checkbox" name="privilege[]" value="crypto_deposit"> Crypto Deposit<br><br>
					 		   <input type="checkbox" name="privilege[]" value="contact">Contact Us<br>
                         	 </div>
                                  

                                              <div class="col-md-3">
					         <input type="checkbox" name="privilege[]" value="withdraw"> Withdraw<br><br>
					 		 <input type="checkbox" name="privilege[]" value="crypto_withdraw"> Crypto Withdraw<br><br>
					 		  <input type="checkbox" name="privilege[]" value="trade_history"> Trade History<br><br>
					 		  <input type="checkbox" name="privilege[]" value="profit"> Profit<br><br>
					 		  <input type="checkbox" name="privilege[]" value="users_TFA">User TFA<br><br>
					 		  <input type="checkbox" name="privilege[]" value="support"> Support<br><br>
					 		   <input type="checkbox" name="privilege[]" value="bank_details"> Bank Details<br>
                         			</div>


                         	<div class="col-md-3">
					         <input type="checkbox" name="privilege[]" value="faq"> FAQ Management<br><br>
					 		 <input type="checkbox" name="privilege[]" value="news">NEWS Management<br><br>
					 		  <input type="checkbox" name="privilege[]" value="cms"> CMS Management<br><br>
					 		  <input type="checkbox" name="privilege[]" value="email_template"> Email Template<br><br>
					 		  <input type="checkbox" name="privilege[]" value="static_content">Static Content<br><br>
					 		  <input type="checkbox" name="privilege[]" value="meta_content"> Meta Content<br><br>
					 		   <input type="checkbox" name="privilege[]" value="block_user_ip"> Block User IP<br>
                         			</div>
         
                                  	  </div>  
								
                                    <div class="form-group">
                                        <div class="col-md-7 col-md-offset-5">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
					
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
	<link href="<?php echo admin_source(); ?>/css/patternLock.css"  rel="stylesheet" type="text/css" />
<script src="<?php echo admin_source(); ?>/js/patternLock.js"></script>
	
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
		});
	</script>
	<script>
	$(document).ready(function() {
		
		$('#add_subadmin_details').validate({
			rules: {
				name: {
					required: true
				},
				password: {
					required: true
				},
				emailid: {
					required: true,
				},
				patterncode: {
					required: true
				},
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
var lock = new PatternLock("#patternContainer",{
	 onDraw:function(pattern){
			word();
    }
});
function word()
{
	var pat=lock.getPattern();
	$("#patterncode").val(pat);
}

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
