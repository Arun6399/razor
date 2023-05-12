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
				<li class="active">Email Template</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Email Template Management <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Email Template Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="email_template-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Template Name</th>
										<!--<th class="text-center">Language</th>-->
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								if ($email_template->num_rows() > 0) {
									$i = 1;
									foreach($email_template->result() as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result->name . '</td>';
										//echo '<td>' . $result->languagename . '</td>';
										if ($result->status == 1) {
											$status = '<label class="label label-info">Activated</label>';
										} else {
											$status = '<label class="label label-danger">De-Activated</label>';
										}
										echo '<td>'.$status.'</td>';
										echo '<td>';
										echo '<a href="' . admin_url() . 'email_template/edit/' . $result->id . '" title="Edit this Template"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'email_template/view/' . $result->id . '" title="View this Template"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}
								} else {
									echo '<tr><td></td><td></td><td colspan="2" class="text-center">No Email Template added yet!</td><td></td><td></td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }elseif($view=='edit'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'email_template');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-8">
										<input type="text" name="name" id="name" class="form-control" placeholder="Name" value="<?php echo $email_template->name; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Subject</label>
                                        <div class="col-md-8">
										<input type="text" name="subject" id="subject" class="form-control" placeholder="Subject" value="<?php echo $email_template->subject; ?>" />
                                        </div>
                                    </div>
									<!--<div class="form-group">
                                        <label class="col-md-2 control-label">Language</label>
                                        <div class="col-md-8">
										<select id="language" name="language" class="form-control" disabled>
										<?php foreach($languages as $language){ ?>
										<option <?php if($email_template->language==$language->id){echo 'selected';}?> value="<?php echo $language->id; ?>"><?php echo $language->name; ?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>-->
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Status</label>
                                        <div class="col-md-8">
										<select data-live-search="true" id="status" name="status" class="form-control selectpicker">
										<option <?php if($email_template->status==1){echo 'selected';}?> value="1">Active</option>
										<option <?php if($email_template->status==0){echo 'selected';}?> value="0">De-active</option>
										</select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">SMTP Type</label>
                                        <div class="col-md-8">
										<select data-live-search="true" id="type" name="type" class="form-control selectpicker">
										<option <?php if($email_template->type=='support'){echo 'selected';}?> value="support">Support</option>
										<option <?php if($email_template->type=='contact'){echo 'selected';}?> value="contact">Contact</option>
										<option <?php if($email_template->type=='other'){echo 'selected';}?> value="other">Other</option>
										</select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Template</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="template" name="template" rows="20"><?php echo $email_template->template; ?></textarea>
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
					<?php }else{ ?>
					<div class="wrapper">
					<?php
							$special_vars['###COPYRIGHT###'] 		= $emailConfig->copy_right_text;
							$special_vars['###SITEEMAIL###'] 		= $emailConfig->site_email;
							$special_vars['###SITELOGO###'] 		= getSiteLogo();
							$special_vars['###SITENAME###']			= getSiteName();
							$special_vars['###SITELINK###'] 		= base_url();
							$special_vars['###ABOUTUS###'] 			= base_url().'cms/about-us';
							$special_vars['###PRIVACY###'] 			= base_url().'cms/privacy-policy';
							$special_vars['###TERMS###'] 			= base_url().'cms/terms-and-conditions';
							$special_vars['###CONTACTUS###'] 		= base_url().'contact_us';
							/*$special_vars['###FACEBOOKIMAGE###'] 	= 'https://res.cloudinary.com/spiegelnetbluerico/image/upload/v1578475880/facebook_lavtyx.jpg';
							$special_vars['###TWITTERIMAGE###'] 	= 'https://res.cloudinary.com/spiegelnetbluerico/image/upload/v1578475939/twitter_v163zu.jpg';

							$special_vars['###TELEGRAMIMAGE###'] 	= 'https://res.cloudinary.com/spiegelnetbluerico/image/upload/v1578479502/telegram_xkaedk.png';

							$special_vars['###LINKEDINIMAGE###'] 	= 'https://res.cloudinary.com/dhpmwq4ln/image/upload/v1604927730/linkedin.png';
							$special_vars['###LINKEDINIMAGE###'] 	= 'https://res.cloudinary.com/dhpmwq4ln/image/upload/v1604927730/linkedin.png';*/
							if($emailConfig->facebooklink!='')
		                    {
								$special_vars['###FACEBOOKIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850139/intozvq0177rftxsbhob.png";
							}

							if($emailConfig->twitterlink!='')
		                    {
							$special_vars['###TWITTERIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850139/ly7jiql3qwkampbfkx3e.png";
							}
							if($emailConfig->telegramlink!='')
		                    {
							$special_vars['###TELEGRAMIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850139/tt6v6fgy5hoycn9uwp5f.png";
							}
							if($emailConfig->googlelink!='')
		                    {
							$special_vars['###GOOGLEIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850140/nsneghfafa2wtygh23ca.png";
							}
							if($emailConfig->youtube_link!='')
		                    {
							$special_vars['###YOUTUBEIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850140/rmageljnvvx7at2amdxd.png";
							}
							if($emailConfig->linkedin_link!='')
		                    {
							$special_vars['###LINKEDINIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850139/araz3ba7rerveqymitvz.png";
							}
							if($emailConfig->instagram_link!='')
		                    {
							$special_vars['###INSTAGRAMIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850139/ytwtj8ctbilacom93lxm.png";
							}
							if($emailConfig->pinterest_link!='')
		                    {
							$special_vars['###PINTERESTIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850241/ak8m7vtewpmzj7bujhbt.png";
							}
							if($emailConfig->dribble_link!='')
		                    {
							$special_vars['###DRIBBLEIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850140/xbsz5ghte4bkkg2kbyjp.png";
						    }

							$template = strtr($email_template->template, $special_vars);
					 ?>
					<?php echo htmlspecialchars_decode(stripslashes($template)); ?>
					<?php $backurl = admin_url() . 'email_template';  ?>
					 <a class="btn btn-sm btn-primary m-r-5" style="color: rgb(255, 255, 255) !important;" href="<?php echo $backurl; ?>" >Back</a>
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
	<script src="<?php echo admin_source();?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/ckeditor/ckeditor.js"></script>
	<!--[if lt IE 9]>
		<script src="<?php echo admin_source();?>/crossbrowserjs/html5shiv.js"></script>
		<script src="<?php echo admin_source();?>/crossbrowserjs/respond.min.js"></script>
		<script src="<?php echo admin_source();?>/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="<?php echo admin_source();?>plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo admin_source();?>plugins/gritter/js/jquery.gritter.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.time.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.resize.min.js"></script>

	<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
	<script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
	
	<script src="<?php echo admin_source();?>js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>js/apps.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

	<script>
	  var admin_url='<?php echo admin_url(); ?>';
    </script>
	<script type="text/javascript">
    $(document).ready(function() {
        var admin_url = '<?php echo admin_url(); ?>';
        $('#email_template-data-table').DataTable( {
        	"responsive" : true,
            "processing" : true,
            "pageLength" : 10,
            "serverSide": true,
            "order": [[0, "asc" ]],
            "searching": true,
            "ajax": admin_url+"email_template/email_template_ajax"
        });
    });
</script>
	<?php
	if($view)
	{
		if($view=='view_all' || $view=='view'){
		}
		else
		{
			?>
		<script>
		$(document).ready(function() {
			CKEDITOR.replace('template');
			});
		</script>
		<?php
		}
	}
	?>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
	$(document).ready(function() {
		$('#email_template').validate({
			rules: {
				name: {
					required: true
				},
				subject: {
					required: true
				},
				template: {
					required: true,
				},
				status: {
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
	<script>
		$(document).ready(function() {
			App.init();
		});
	</script>
	 <script async src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>
	
	