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
				<li class="active">Team</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Team Management <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Team Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Name</th>
										<th class="text-center">Designation</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
								if ($team->num_rows() > 0) {
									$i = 1;
									foreach($team->result() as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result->name . '</td>';
										echo '<td>' . $result->designation .'</td>';
										if ($result->status == 1) {
											$status = '<label class="label label-info">Activated</label>';
											$extra = array('title' => 'De-activate this team');
											$changeStatus = anchor(admin_url().'team/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
										} else {
											$status = '<label class="label label-danger">De-Activated</label>';
											$extra = array('title' => 'Activate this team');
											$changeStatus = anchor(admin_url().'team/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
										}
										echo '<td>'.$status.'</td>';
										echo '<td>';
										echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'team/edit/' . $result->id . '" title="Edit this team"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'team/delete/' . $result->id . '" title="Delete this team"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										/*echo '<td>';
										echo '<a href="' . admin_url() . 'trade_pairs/fees_list/' . $result->id . '" class="btn btn-sm btn-inverse">Fees</a>';
										echo '</td>';*/
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td></td><td></td><td class="text-center">No Team added yet!</td><td></td><td></td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='add'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'team');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Name</label>
                                        <div class="col-md-4">
										<input type="text" name="name" id="name" class="form-control" placeholder="Name" value="" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Designation</label>
                                        <div class="col-md-4">
										<input type="text" name="designation" id="designation" class="form-control" placeholder="Designation" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
							            <label class="col-md-4 control-label">Image</label>
							            <div class="col-md-4">
							                <input type="file" name="image" id="image" />
							            </div>
							        </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Facebook Link</label>
                                        <div class="col-md-4">
										<input type="text" name="fb_link" id="fb_link" class="form-control" placeholder="Facebook Link" value="" />
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Twitter Link</label>
                                        <div class="col-md-4">
										<input type="text" name="twitter_link" id="twitter_link" class="form-control" placeholder="Twitter Link" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Linkedin Link</label>
                                        <div class="col-md-4">
										<input type="text" name="linkedin_link" id="linkedin_link" class="form-control" placeholder="Linkedin Link" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Instagram Link</label>
                                        <div class="col-md-4">
										<input type="text" name="insta_link" id="insta_link" class="form-control" placeholder="Instagram Link" value="" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Status</label>
                                        <div class="col-md-4">
										<select id="status" name="status" class="form-control">
										<option value="1">Active</option>
										<option value="0">De-active</option>
										</select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
					<?php }else if($view=='edit'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'team');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>

                                	<div class="form-group">
                                        <label class="col-md-4 control-label">Name</label>
                                        <div class="col-md-4">
										<input type="text" name="name" id="name" class="form-control" placeholder="Name" value="<?php echo $team->name; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Designation</label>
                                        <div class="col-md-4">
										<input type="text" name="designation" id="designation" class="form-control" placeholder="Designation" value="<?php echo $team->designation; ?>" />
                                        </div>
                                    </div>

                                     <div class="form-group">
							            <label class="col-md-4 control-label">Image</label>
							            <div class="col-md-4">
							                <input type="file" name="image" id="image" />
							                <?php $im = $team->image; ?>
							                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $team->image; ?>" />
							                <?php if($team->image!='') { ?>
							                <img src="<?php echo $im; ?>" style="width:65px;height:65px;" />
							                <?php } ?>
							            </div>
							        </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Facebook Link</label>
                                        <div class="col-md-4">
										<input type="text" name="fb_link" id="fb_link" class="form-control" placeholder="Facebook Link" value="<?php echo $team->fb_link; ?>" />
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Twitter Link</label>
                                        <div class="col-md-4">
										<input type="text" name="twitter_link" id="twitter_link" class="form-control" placeholder="Twitter Link" value="<?php echo $team->twitter_link; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Linkedin Link</label>
                                        <div class="col-md-4">
										<input type="text" name="linkedin_link" id="linkedin_link" class="form-control" placeholder="Linkedin Link" value="<?php echo $team->linkedin_link; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Instagram Link</label>
                                        <div class="col-md-4">
										<input type="text" name="insta_link" id="insta_link" class="form-control" placeholder="Instagram Link" value="<?php echo $team->insta_link; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Status</label>
                                        <div class="col-md-4">
										<select id="status" name="status" class="form-control">
										<option <?php if($team->status==1){echo 'selected';}?> value="1">Active</option>
										<option <?php if($team->status==0){echo 'selected';}?> value="0">De-active</option>
										</select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
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
	<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
	$(document).ready(function() {
		$('#team').validate({
			rules: {
				name: {
					required: true
				},
				designation: {
					required: true
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
	</script>
	<script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','<?php echo admin_source()."www.google-analytics.com/analytics.js";?>','ga');
    
      ga('create', 'UA-53034621-1', 'auto');
      ga('send', 'pageview');
    </script>