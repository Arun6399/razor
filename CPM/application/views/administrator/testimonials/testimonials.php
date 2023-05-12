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
				<li class="active">Testimonials</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Testimonials Management <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Testimonials Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Name</th>
										<th class="text-center">Position</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
								if ($testimonials->num_rows() > 0) {
									$i = 1;
									foreach($testimonials->result() as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result->english_name . '</td>';
										echo '<td>' . $result->english_position .'</td>';
										if ($result->status == 1) {
											$status = '<label class="label label-info">Activated</label>';
											$extra = array('title' => 'De-activate this testimonials');
											$changeStatus = anchor(admin_url().'testimonials/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
										} else {
											$status = '<label class="label label-danger">De-Activated</label>';
											$extra = array('title' => 'Activate this testimonials');
											$changeStatus = anchor(admin_url().'testimonials/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
										}
										echo '<td>'.$status.'</td>';
										echo '<td>';
										echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'testimonials/edit/' . $result->id . '" title="Edit this testimonial"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'testimonials/delete/' . $result->id . '" title="Delete this testimonial"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td></td><td></td><td class="text-center">No Testimonials added yet!</td><td></td><td></td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='add'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'testimonials');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                	<div class="form-group">
                                <label class="col-md-2 control-label">Laguage</label>
                                <div class="col-md-8">

                                    <select id="lang" name="lang" class="form-control" onchange="language();">
                                                <option value="1" >english</option>
                                                <option value="2" >chinese</option>
                                                <option value="3" >russian</option>
                                                <option value="4" >spanish</option>
                                            </select>

                                </div>
                        </div>
                         <div id="english">
									<div class="form-group">
                                        <label class="col-md-4 control-label">Name</label>
                                        <div class="col-md-4">
										<input type="text" name="name" id="name" class="form-control" placeholder="Name" value="" />
                                        </div>
                                    </div>

									<div class="form-group">
                                        <label class="col-md-4 control-label">Position</label>
                                        <div class="col-md-4">
										<input type="text" name="position" id="position" class="form-control" placeholder="Position" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Comments</label>
                                        <div class="col-md-4">
										<textarea name="comments" id="comments" class="form-control" placeholder="Comments" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div id="chinese" class="samelang">
									<div class="form-group">
                                        <label class="col-md-4 control-label">Name</label>
                                        <div class="col-md-4">
										<input type="text" name="name" id="name" class="form-control" placeholder="Name" value="" />
                                        </div>
                                    </div>

									<div class="form-group">
                                        <label class="col-md-4 control-label">Position</label>
                                        <div class="col-md-4">
										<input type="text" name="position" id="position" class="form-control" placeholder="Position" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Comments</label>
                                        <div class="col-md-4">
										<textarea name="comments" id="comments" class="form-control" placeholder="Comments" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>


                                    <div class="form-group">
							            <label class="col-md-4 control-label">Image</label>
							            <div class="col-md-4">
							                <input type="file" name="image" id="image" />
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
						<?php $attributes=array('class'=>'form-horizontal','id'=>'testimonials');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>

                        <div class="form-group">
                                <label class="col-md-4 control-label">Laguage</label>
                                <div class="col-md-4">
                                    <select id="lang" name="lang" class="form-control" onchange="language();">
                                                <option value="1" >english</option>
                                                <option value="2" >chinese</option>
                                                <!-- <option value="3" >russian</option>
                                                <option value="4" >spanish</option> -->
                                    </select>
                                </div>
                        </div>

                                	 <div id="english">

                                	<div class="form-group">
                                        <label class="col-md-4 control-label">Name</label>
                                        <div class="col-md-4">
										<input type="text" name="english_name" id="name" class="form-control" placeholder="Name" value="<?php echo $testimonials->english_name; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Position</label>
                                        <div class="col-md-4">
										<input type="text" name="english_position" id="position" class="form-control" placeholder="Position" value="<?php echo $testimonials->english_position; ?>" />
                                        </div>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Comments</label>
                                        <div class="col-md-4">
										<textarea name="english_comments" id="comments" class="form-control" rows="5"><?php echo $testimonials->english_comments; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                 <div id="chinese" class="samelang">
                                	<div class="form-group">
                                        <label class="col-md-4 control-label">Name</label>
                                        <div class="col-md-4">
										<input type="text" name="chinese_name" id="name" class="form-control" placeholder="Name" value="<?php echo $testimonials->chinese_name; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Position</label>
                                        <div class="col-md-4">
										<input type="text" name="chinese_position" id="position" class="form-control" placeholder="Position" value="<?php echo $testimonials->chinese_position; ?>" />
                                        </div>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Comments</label>
                                        <div class="col-md-4">
										<textarea name="chinese_comments" id="comments" class="form-control" rows="5"><?php echo $testimonials->chinese_comments; ?></textarea>
                                        </div>
                                    </div>
                                </div>


                                     <div class="form-group">
							            <label class="col-md-4 control-label">Image</label>
							            <div class="col-md-4">
							                <input type="file" name="image" id="image" />
							                <?php $im = $testimonials->image; ?>
							                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $testimonials->image; ?>" />
							                <?php if($testimonials->image!='') { ?>
							                <img src="<?php echo $im; ?>" style="width:65px;height:65px;" />
							                <?php } ?>
							            </div>
							        </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Status</label>
                                        <div class="col-md-4">
										<select id="status" name="status" class="form-control">
										<option <?php if($testimonials->status==1){echo 'selected';}?> value="1">Active</option>
										<option <?php if($testimonials->status==0){echo 'selected';}?> value="0">De-active</option>
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
		$('#testimonials').validate({
			rules: {
				name: {
					required: true
				},
				position: {
					required: true
				},
				comments: {
					required: true
				},
				image: {
				//	required: true
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
     <style>
        .samelang {
            display: none;
        }
    </style>
     <SCRIPT>
        function language() 
        {
            var x = document.getElementById("lang").value;
            if (x == 1) {
                $('#chinese').hide();
                $('#spanish').hide();
                $('#russian').hide();
                $('#english').show();
            } else if (x == 2) {
                $('#english').hide();
                $('#spanish').hide();
                $('#russian').hide();
                $('#chinese').show();

            } else if (x == 3) {
                $('#spanish').hide();
                $('#english').hide();
                $('#chinese').hide();
                $('#russian').show();

            } else {
                $('#english').hide();
                $('#russian').hide();
                $('#chinese').hide();
                $('#spanish').show();

            }
        }

        
    </SCRIPT>