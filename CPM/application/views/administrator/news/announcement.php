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
				<li class="active">Announcement</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Announcement Management <!--<small>header small text goes here...</small>--></h1>
			<p class="text-right m-b-10">
				<a href="<?php echo admin_url().'announcement/create';?>" class="btn btn-primary poper" data-placement="top" data-toggle="popover" data-content="Add New Help Center">Add New</a>
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
                            <h4 class="panel-title">Announcement</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="datas-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>      
										   <th class="text-center">Title</th>
                                           <!-- <th class="text-center">Content</th>
                                           <th class="text-center">Image</th> -->
                                          <!--  <th class="text-center">Link</th> -->
										   <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								/*if ($static_content->num_rows() > 0) {
									$i = 1;
									foreach($static_content->result() as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';

										echo '<td>' . substr(strip_tags($result->english_name),0,30) . '</td>';
										echo '<td>' . $result->english_page . '</td>';
										//echo '<td>' . $result->languagename . '</td>';

										echo '<td>';
										echo '<a href="' . admin_url() . 'static_content/edit/' . $result->id . '" title="Edit this content"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td></td><td></td><td colspan="2" class="text-center">No Static content added yet!</td><td></td><td></td></tr>';
								}*/
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else{ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'announcement');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
<!-- ENGLISH START -->
                                  <div id="announcement">

                                  	<div class="form-group">
					                    <label class="col-md-2 control-label">Title</label>
					                    <div class="col-md-8">										
										<input type="text" name="title" id="title" class="form-control" placeholder="Title" value="<?php echo $announcement->title; ?>" />
					                     </div>
					                </div>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Content</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="contents" name="content" rows="20"><?php echo $announcement->content; ?></textarea>
                                        </div>
                                      </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Link</label>
                                        <div class="col-md-8">
										<input type="text" name="link" id="link" class="form-control" placeholder="Title" value="<?php echo $announcement->link; ?>" />
                                        </div>
                                    </div>
                                </div>
                          <!--  ENGLISH END -->

                    <!--    chinese strat         -->
                     				 <div id="chinese" class="samelang">
									<div class="form-group">
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-8">
										<input type="text" name="chinesename" id="name" class="form-control" placeholder="Name" value="<?php echo $static_content->chinese_name; ?>" />
                                        </div>
                                      </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Page</label>
                                        <div class="col-md-8">
										<input type="text" name="chinesepage" id="page" class="form-control" placeholder="Page" value="<?php echo $static_content->chinese_page; ?>" />
                                        </div>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Content</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="chinesecontents" name="chinesecontents" rows="20"><?php echo $static_content->chinese_content; ?></textarea>
                                        </div>
                                    </div>
                                </div> 
                      <!--  chinese END -->

              <!--         RUSSIAN START -->
                                      <!-- <div id="russian" class="samelang">
									<div class="form-group">
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-8">
										<input type="text" name="russianname" id="name" class="form-control" placeholder="Name" value="<?php echo $static_content->russian_name; ?>" />
                                        </div>
                                      </div>
                                    <div class="form-group" class="samelang">
                                        <label class="col-md-2 control-label">Page</label>
                                        <div class="col-md-8">
										<input type="text" name="russianpage" id="page" class="form-control" placeholder="Page" value="<?php echo $static_content->russian_page; ?>" />
                                        </div>
                                    </div>
                                    
                                     <div class="form-group" class="samelang">
                                        <label class="col-md-2 control-label">Content</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="russiancontents" name="russiancontents" rows="20"><?php echo $static_content->russian_content; ?></textarea>
                                        </div>
                                    </div>
                                </div> -->
                      <!--  RUSSION END   -->       

                    <!--   SPANISH START-->
                     <!-- <div id="spanish" CLASS="samelang">
									<div class="form-group">
                                        <label class="col-md-2 control-label">Name</label>
                                        <div class="col-md-8">
										<input type="text" name="spanishname" id="name" class="form-control" placeholder="Name" value="<?php echo $static_content->spanish_name; ?>" />
                                        </div>
                                      </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Page</label>
                                        <div class="col-md-8">
										<input type="text" name="spanishpage" id="page" class="form-control" placeholder="Page" value="<?php echo $static_content->spanish_page; ?>" />
                                        </div>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Content</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="spanishcontents" name="spanishcontents" rows="20"><?php echo $static_content->spanish_content; ?></textarea>
                                        </div>
                                    </div>
                                </div> -->
                              <!--   SPANISH END -->

<!-- //vv -->
									
									<!--<div class="form-group">
                                        <label class="col-md-2 control-label">Language</label>
                                        <div class="col-md-8">
                                        <select disabled name="lang" id="lang" class="form-control">
                                        <?php foreach($lang as $row){?>
										<option value="<?php echo $row->id;?>" <?php if($row->id==$static_content->language){echo "selected";} ?> ><?php echo $row->name; ?></option>
  										<?php } ?>
                                        </select>
                                    </div>-->

<!-- //vv -->                       
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Image</label>
                                        <div class="col-md-8">
										<input type="file" name="image" id="image"/>
										<?php $im = $announcement->image; ?>
										<input type="hidden" name="oldimage" id="oldimage" value="<?php echo $announcement->image; ?>" />
										<?php if($announcement->image!='') { ?>
										<!-- <img src="<?php //echo $im; ?>" style="width:65px;height:65px;" /> -->
										<?php } ?>
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
	<?php
	if($view)
	{
		if($view=='view_all'){
		}
		else
		{
			?>
		<script>
		$(document).ready(function() {
			CKEDITOR.replace('contents');
			CKEDITOR.replace('chinesecontents');
			//CKEDITOR.replace('russiancontents');
		//	CKEDITOR.replace('spanishcontents');
			});
		</script>
		<?php 	
		}
	}
	
	?>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
	$(document).ready(function() {
		
		$('#announcement').validate({
			rules: {
				title: {
					required: true
				},
				content: {
					required: true,
				},
				link: {
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
		var admin_url='<?php echo admin_url(); ?>';
		
$(document).ready(function() {
	    var t = $('#datas-table').DataTable( {
	        "columnDefs": [ {
	            "searchable": false,
	            "orderable": false,
	            "targets": 0
	        } ],
	        responsive : true,
	    	"processing" : true,
	    	"lengthChange": true,
	        "pageLength" : 10,
	     	"serverSide": true,
	     	"searching": true,
	        "order": [[ 1, 'asc' ]],
	        "ajax": admin_url+"announcement/announcement_ajax"
	    } );
	                 
	    t.on( 'draw.dt', function () {
	    var PageInfo = $('#datas-table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );
	} );
	</script>
	 <script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>
<!-- 
  LANGUAGRE DISPLAY IN CSS -->
<style>
.samelang
{
	 display: none;
}
</style>
<!--   LANGUAGE DISPLAY END IN CSS -->
 <!--  ONCHANGE LANGUAGE  SCRIPT FUNCTION START -->
 <SCRIPT>
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
 </SCRIPT>

  <!--  ONCHANGE LANGUAGE SCRIPT FUNCTION END -->
