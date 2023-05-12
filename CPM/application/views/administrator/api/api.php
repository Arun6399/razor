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
				<li class="active"><?php echo $title; ?></li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?php echo $title; ?> <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title"><?php echo $title; ?></h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Name</th>
										<th class="text-center">Language</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
								if ($category->num_rows() > 0) {
									$i = 1;
									foreach($category->result() as $result) {
										echo '<tr>';
										echo '<td class="text-center">' . $i . '</td>';
										echo '<td class="text-center">' . $result->name.'</td>';
										echo '<td class="text-center">' . $result->languagename .'</td>';
										echo '<td class="text-center">';
										echo '<a href="' . admin_url() . 'api/edit/' . $result->id . '" title="Edit this pair"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td colspan="2" class="text-center">No Category added yet!</td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='edit'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'trade_pair');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
                                   <div class="form-group">
                                        <label class="col-md-4 control-label">Name</label>
                                        <div class="col-md-4">
										<input type="text" name="name" id="name" class="form-control" placeholder="name" value="<?php echo $category->name; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Language</label>
                                        <div class="col-md-4">
										<select id="language" name="language" class="form-control">
										<?php foreach($language->result() as $lang){ ?>
										<option <?php if($lang->id==$category->language){echo 'selected';}?> value="<?php echo $lang->id;?>"><?php echo $lang->name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Content</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="api_content" name="api_content" rows="20"><?php echo $category->content; ?></textarea>
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
					<?php }else if($view=='view_all_sub'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Name</th>
										<th class="text-center">Category</th>
										<th class="text-center">Language</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
								if ($category->num_rows() > 0) {
									$i = 1;
									foreach($category->result() as $result) {
										echo '<tr>';
										echo '<td class="text-center">' . $i . '</td>';
										echo '<td class="text-center">' . $result->name.'</td>';
										echo '<td class="text-center">' . $result->categoryname .'</td>';
										echo '<td class="text-center">' . $result->languagename .'</td>';
										echo '<td class="text-center">';
										echo '<a href="' . admin_url() . 'api/edit_sub/' . $result->id . '" title="Edit this pair"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'api/delete_sub/' . $result->id . '" title="Delete this Sub Category"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td colspan="2" class="text-center">No Category added yet!</td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='edit_sub'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'trade_pair');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
                                   <div class="form-group">
                                        <label class="col-md-4 control-label">Name</label>
                                        <div class="col-md-4">
										<input type="text" name="name" id="name" class="form-control" placeholder="name" value="<?php echo $category->name; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Language</label>
                                        <div class="col-md-4">
										<select id="language" name="language" class="form-control">
										<?php foreach($language->result() as $lang){ ?>
										<option <?php if($lang->id==$category->language){echo 'selected';}?> value="<?php echo $lang->id;?>"><?php echo $lang->name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Category</label>
                                        <div class="col-md-4">
										<select id="category_id" name="category_id" class="form-control">
										<?php foreach($categories->result() as $cats){ ?>
										<option <?php if($cats->id==$category->category_id){echo 'selected';}?> value="<?php echo $cats->id;?>"><?php echo $cats->name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Content</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="api_content" name="api_content" rows="20"><?php echo $category->content; ?></textarea>
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
					<?php }else if($view=='add_sub'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'trade_pair');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
                                   <div class="form-group">
                                        <label class="col-md-4 control-label">Name</label>
                                        <div class="col-md-4">
										<input type="text" name="name" id="name" class="form-control" placeholder="name" value="" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Language</label>
                                        <div class="col-md-4">
										<select id="language" name="language" class="form-control" disabled>
										<?php foreach($language->result() as $lang){ ?>
										<option value="<?php echo $lang->id;?>"><?php echo $lang->name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Category</label>
                                        <div class="col-md-4">
										<select id="category_id" name="category_id" class="form-control">
										<?php foreach($categories->result() as $cats){ ?>
										<option value="<?php echo $cats->id;?>"><?php echo $cats->name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Content</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="api_content" name="api_content" rows="20"></textarea>
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
					<?php }else if($view=='view_all_code'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Category</th>
										<th class="text-center">Sub Category</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
								if ($category->num_rows() > 0) {
									$i = 1;
									foreach($category->result() as $result) {
										echo '<tr>';
										echo '<td class="text-center">' . $i . '</td>';
										echo '<td class="text-center">' . $result->categoryname .'</td>';
										echo '<td class="text-center">' . $result->contentname .'</td>';
										echo '<td class="text-center">';
										echo '<a href="' . admin_url() . 'api/edit_code/' . $result->id . '" title="Edit this pair"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'api/delete_code/' . $result->id . '" title="Delete this Sub Category"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td colspan="5" class="text-center">No Coding added yet!</td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='edit_code'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'trade_pair');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Category</label>
                                        <div class="col-md-4">
										<select id="category_id" name="category_id" class="form-control">
										<?php foreach($categories->result() as $cats){ ?>
										<option <?php if($cats->id==$category->category_id){echo 'selected';}?> value="<?php echo $cats->id;?>"><?php echo $cats->name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Sub Category</label>
                                        <div class="col-md-4">
										<select id="content_id" name="content_id" class="form-control">
										<?php foreach($subcategory->result() as $sub){ ?>
										<option <?php if($sub->param==$category->content_id){echo 'selected';}?> value="<?php echo $sub->param;?>"><?php echo $sub->name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Node JS</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="node_js" name="node_js" rows="20"><?php echo $category->node_js;?></textarea>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Phython</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="phython" name="phython" rows="20"><?php echo $category->phython;?></textarea>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Ruby</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="ruby" name="ruby" rows="20"><?php echo $category->ruby;?></textarea>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">PHP</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="php" name="php" rows="20"><?php echo $category->php;?></textarea>
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
					<?php }else if($view=='add_code'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'trade_pair');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Category</label>
                                        <div class="col-md-4">
										<select id="category_id" name="category_id" class="form-control">
										<?php foreach($categories->result() as $cats){ ?>
										<option value="<?php echo $cats->id;?>"><?php echo $cats->name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Sub Category</label>
                                        <div class="col-md-4">
										<select id="content_id" name="content_id" class="form-control">
										<?php foreach($subcategory->result() as $sub){ ?>
										<option value="<?php echo $sub->param;?>"><?php echo $sub->name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Node JS</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="node_js" name="node_js" rows="20"></textarea>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Phython</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="phython" name="phython" rows="20"></textarea>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Ruby</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="ruby" name="ruby" rows="20"></textarea>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">PHP</label>
                                         <div class="col-md-8">
										<textarea class="form-control" id="php" name="php" rows="20"></textarea>
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
	<?php
	if($view)
	{
		if($view=='edit'||$view=='edit_sub'||$view=='add_sub')
		{?>
		<script>
		$(document).ready(function() {
		CKEDITOR.replace('api_content');	
		});		
		</script>
		<?php }
		if($view=='edit_code'||$view=='add_code'){ ?>
		<script>
		$(document).ready(function() {
			CKEDITOR.replace('node_js');
			CKEDITOR.replace('phython');
			CKEDITOR.replace('ruby');
			CKEDITOR.replace('php');
			});
		</script>
		<?php 	
		}
	}
	
	?>
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