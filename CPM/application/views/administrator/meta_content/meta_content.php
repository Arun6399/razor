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
				<li class="active">Meta Content</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Meta Content Management <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Meta Content Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="datas-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Title</th>
										
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								/*if ($meta_content->num_rows() > 0) {
									$i = 1;
									foreach($meta_content->result() as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result->english_title . '</td>';
										
										echo '<td>';
										
										echo '<a href="' . admin_url() . 'meta_content/edit/' . $result->id . '" title="Edit this meta content"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td></td><td></td><td colspan="2" class="text-center">No meta content added yet!</td><td></td><td></td></tr>';
								}*/
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='add'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'faq');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                  <div class="form-group">
                                <label class="col-md-2 control-label">Laguage</label>
                                <div class="col-md-8">
                                    <select data-live-search="true" id="lang" name="lang" class="form-control selectpicker" onchange="language();">
                                                <option value="1" >english</option>
                                                <!-- <option value="2" >chinese</option> -->
                                                <!-- <option value="3" >russian</option>
                                                <option value="4" >spanish</option> -->
                                    </select>
                                </div>
                        </div>
                        <div id="english">
									<div class="form-group">
                                        <label class="col-md-2 control-label">Title</label>
                                        <div class="col-md-8">
										<input type="text" name="english_title" id="title" class="form-control" placeholder="Title" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="english_meta_keywords" id="meta_keywords" class="form-control" placeholder="Meta Keywords" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea name="english_meta_description" id="meta_description" class="form-control" placeholder="Meta Description" rows="3"><?php echo set_value('english_meta_description'); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div id="chinese" class="samelang">
									<div class="form-group">
                                        <label class="col-md-2 control-label">Title</label>
                                        <div class="col-md-8">
										<input type="text" name="chinese_title" id="title" class="form-control" placeholder="Title" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="chinese_meta_keywords" id="meta_keywords" class="form-control" placeholder="Meta Keywords" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea name="chinese_meta_description" id="meta_description" class="form-control" placeholder="Meta Description" rows="3"><?php echo set_value('chinese_meta_description'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Heading</label>
                                        <div class="col-md-8">
										<input type="text" name="heading" id="heading" class="form-control" placeholder="Heading" value="" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">News Content</label>
                                        <div class="col-md-8">
										<textarea name="contents" id="contents" class="form-control" rows="3"><?php echo set_value('contents'); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Link</label>
                                        <div class="col-md-8">
										<input type="text" name="link" id="link" class="form-control" placeholder="Link" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">News Status</label>
                                        <div class="col-md-8">
										<select data-live-search="true" id="status" name="status" class="selectpicker form-control">
										<option value="1">Active</option>
										<option value="0">De-active</option>
										</select>
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
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'meta_content');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                  <div class="form-group">
                                <label class="col-md-2 control-label">Language</label>
                                <div class="col-md-8">
                                    <select data-live-search="true" id="lang" name="lang" class="selectpicker form-control" onchange="language();">
                                                <option value="1" >english</option>
                                               <!--  <option value="2" >chinese</option> -->
                                                <!-- <option value="3" >russian</option>
                                                <option value="4" >spanish</option> -->
                                    </select>
                                </div>
                        </div>
                        <div id="english">
									<div class="form-group">
                                        <label class="col-md-2 control-label">Title</label>
                                        <div class="col-md-8">
										<input type="text" name="english_title" id="title" class="form-control" placeholder="Title" value="<?php echo $meta_content->english_title; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="english_meta_keywords" id="meta_keywords" class="form-control" placeholder="Meta Keywords" value="<?php echo $meta_content->english_meta_keywords; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="meta_description" name="english_meta_description" rows="3"><?php echo $meta_content->english_meta_description; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                               <div id="chinese" class="samelang">
									<div class="form-group">
                                        <label class="col-md-2 control-label">Title</label>
                                        <div class="col-md-8">
										<input type="text" name="chinese_title" id="title" class="form-control" placeholder="Title" value="<?php echo $meta_content->chinese_title; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="chinese_meta_keywords" id="meta_keywords" class="form-control" placeholder="Meta Keywords" value="<?php echo $meta_content->chinese_meta_keywords; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="chinese_meta_description" name="chinese_meta_description" rows="3"><?php echo $meta_content->chinese_meta_description; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Heading</label>
                                        <div class="col-md-8">
										<input type="text" name="heading" id="heading" class="form-control" placeholder="Heading" value="<?php echo $meta_content->heading; ?>" />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Link</label>
                                        <div class="col-md-8">
										<input type="text" name="link" id="link" class="form-control" placeholder="Heading" value="<?php echo $meta_content->link; ?>" />
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
	<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script> 
<script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
	$(document).ready(function() {
		
		$('#meta_content').validate({
			rules: {
				title: {
					required: true
				},
				meta_keywords: {
					required: true
				},
				meta_description: {
					required: true,
				},
				heading: {
					required: true,
				},
				link: {
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
     <script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>
	<script>
      
	  var admin_url='<?php echo admin_url(); ?>';
        
$(document).ready(function() {
    $('#datas-table').DataTable( {
        "responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"meta_content/metacontent_ajax"
    });
        });
	
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
