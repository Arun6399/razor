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
		<li class="active">Static Content</li>
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">Static Content Management <!--<small>header small text goes here...</small>--></h1>
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
                    <h4 class="panel-title">Static Content Management</h4>
                </div>
                <div class="panel-body">
					<?php 
						$attributes=array('class'=>'form-horizontal','id'=>'static_content');
						echo form_open_multipart($action,$attributes); 
					?>
		                <fieldset>
		                	<div class="form-group">
		                        <label class="col-md-2 control-label">language</label>
		                        <div class="col-md-8">										
									<select data-live-search="true" id="lang"  name="lang" class="form-control selectpicker" 
											onchange="language();">
										<option value="1" >english</option>
										<!-- <option value="2" >chinese</option> -->
										<!-- <option value="3" >russian</option>
										<option value="4" >spanish</option> -->
									</select>
		                        </div>
		                  	</div>
		    				<!-- ENGLISH START -->
		                  	<div id="english">
								<div class="form-group">
		                            <label class="col-md-2 control-label">Title</label>
		                            <div class="col-md-8">
										<input type="text" name="english_title" id="name" class="form-control" placeholder="Title" value="" />
		                            </div>
		                      	</div>
		                        <div class="form-group">
		                            <label class="col-md-2 control-label">Page</label>
		                            <div class="col-md-8">
									<input type="text" name="englishpage" id="page" class="form-control" placeholder="Page" value="" />(Add "homesection" for display the content in home page.)
		                            </div>
		                            
		                        </div>
		                        <div class="form-group">
		                            <label class="col-md-2 control-label">Content</label>
		                            <div class="col-md-8">
									<textarea class="form-control" id="contents" name="englishcontents" rows="20" placeholder="Content"></textarea>
		                            </div>
		                        </div>
		                    </div>
		                  	<!--  ENGLISH END -->
		                	<!--    chinese strat         -->
		 				 	<div id="chinese" class="samelang">
								<div class="form-group">
		                        	<label class="col-md-2 control-label">Name</label>
		                            <div class="col-md-8">
									<input type="text" name="chinesename" id="name" class="form-control" placeholder="Name" value="" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-md-2 control-label">Page</label>
		                            <div class="col-md-8">
									<input type="text" name="chinesepage" id="page" class="form-control" placeholder="Page" value="" />
		                            </div>
		                        </div>
		                     	<div class="form-group">
		                            <label class="col-md-2 control-label">Content</label>
		                            <div class="col-md-8">
									<textarea class="form-control" id="chinesecontents" name="chinesecontents" rows="20" placeholder="Content"></textarea>
		                            </div>
		                        </div>
		                    </div>           
		                 	<div class="form-group">
		                        <label class="col-md-2 control-label">Image</label>
		                        <div class="col-md-8">
									<input type="file" name="image" id="image"/>
									<!-- <img src="<?php echo $im; ?>" style="width:65px;height:65px;" /> -->
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
<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script> 
<script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>
<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() 
	{
		CKEDITOR.replace('contents');
		CKEDITOR.replace('chinesecontents');
		$('#static_content').validate({
			rules: {
				english_title: {
					required: true
				},
				englishpage: {
					required: true
				},
				contents: {
					required: true,
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
<script type="text/javascript">
	$(document).ready(function() {
		App.init();
	});
	var admin_url='<?php echo admin_url(); ?>';
</script>
 <script async src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script type="text/javascript">
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'G-FDX8TJF8SG');
</script>
	<!-- LANGUAGRE DISPLAY IN CSS -->
<style>
	.samelang
	{
	 	display: none;
	}
</style>
<!--   LANGUAGE DISPLAY END IN CSS -->
<!--  ONCHANGE LANGUAGE  SCRIPT FUNCTION START -->
<script type="text/javascript">
 	function language() 
 	{
 	  	var x = document.getElementById("lang").value;
	 	if(x==1)
	 	{
	 		$('#chinese').hide();
	 		$('#spanish').hide();
	 		$('#russian').hide();
	 		$('#english').show();
	   	}
	 	else if(x==2)
	 	{
	 		$('#english').hide();
	 		$('#spanish').hide();
	 		$('#russian').hide();
	 		$('#chinese').show();
	 	}
	 	else if(x==3)
	 	{
	 	    $('#spanish').hide();  
	 		$('#english').hide();
	 		$('#chinese').hide();
	 		$('#russian').show();
	 	}      
	 	else
	 	{
	 		$('#english').hide();
	 		$('#russian').hide();
	 		$('#chinese').hide();
	 		$('#spanish').show();
	 	}
	 }	
</script>