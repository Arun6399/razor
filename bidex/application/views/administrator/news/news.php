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
				<li class="active">News</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">News Management <!--<small>header small text goes here...</small>--></h1>
			<p class="text-right m-b-10">
								<a href="<?php echo admin_url().'news/add';?>" class="btn btn-primary">Add New</a>
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
                            <h4 class="panel-title">News Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="datas-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
                                           <!-- <th class="text-center">Date</th> -->
										<th class="text-center">Heading</th>
										<!--<th class="text-center">Language</th>-->
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								/*if ($news->num_rows() > 0) {
									$i = 1;
									foreach(array_reverse($news->result()) as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										//echo '<td>' . gmdate("Y-m-d h:i:s", $result->created) . '</td>';
										echo '<td>' . $result->english_title . '</td>';
										//echo '<td>' . $result->name . '</td>';
										if ($result->status == 1) {
											$status = '<label class="label label-info">Activated</label>';
											$extra = array('title' => 'De-activate this news');
											$changeStatus = anchor(admin_url().'news/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
										} else {
											$status = '<label class="label label-danger">De-Activated</label>';
											$extra = array('title' => 'Activate this news');
											$changeStatus = anchor(admin_url().'news/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
										}
										echo '<td>'.$status.'</td>';
										echo '<td>';
										echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'news/edit/' . $result->id . '" title="Edit this news"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'news/delete/' . $result->id . '" title="Delete this news"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
                                        echo '<a href="' . admin_url() . 'news/send/' . $result->id . '" title="Send this news">Send mail</a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td></td><td></td><td class="text-center">No news added yet!</td><td></td><td></td></tr>';
								}*/
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
				<?php }else if($view=='add'){ ?>
					 <div class="panel-body">
						  <?php $attributes=array('class'=>'form-horizontal','id'=>'add');
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
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="english_title" id="english_title" class="form-control" placeholder="Title" value="" />
                                </div>
                            </div> 

                            <div class="form-group">
                                <label class="col-md-2 control-label">Slug</label>
                                <div class="col-md-8">
                                    <input type="text" name="news_slug" id="news_slug" class="form-control" placeholder="News Slug" value="" />
                                </div>
                            </div>                           
                            <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="english_meta_keywords" id="english_meta_keywords" class="form-control" placeholder="Meta Keywords" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea name="english_meta_description" id="english_meta_description" class="form-control" placeholder="Meta Description" rows="3"><?php echo set_value('meta_description'); ?></textarea>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Heading</label>
                                        <div class="col-md-8">
										<input type="text" name="english_heading" id="english_heading" class="form-control" placeholder="Heading" value="" />
                                        </div>
                                    </div>
                                      <div class="form-group">
                                        <label class="col-md-2 control-label">News Content</label>
                                        <div class="col-md-8">
										<textarea name="english_content" id="english_content" class="form-control" rows="3"><?php echo set_value('english_content'); ?></textarea>
										 <label id="contents-error" class="error" for="english_content" style="display:none">This field is required.</label>
                                        </div>
                                    </div>
                                   
                                      
                        </div>
                        <!-- End  -->

 				<!--Start-->
                        <div id="chinese" class="samelang">
                           <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="chinese_title" id="chinese_title" class="form-control" placeholder="Title" value="" />
                                </div>
                            </div>                            
                            <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="chinese_meta_keywords" id="chinese_meta_keywords" class="form-control" placeholder="Meta Keywords" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea name="chinese_meta_description" id="chinese_meta_description" class="form-control" placeholder="Meta Description" rows="3"><?php echo set_value('chinese_meta_description'); ?></textarea>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Heading</label>
                                        <div class="col-md-8">
										<input type="text" name="chinese_heading" id="chinese_heading" class="form-control" placeholder="Heading" value="" />
                                        </div>
                                    </div>
                                      <div class="form-group">
                                        <label class="col-md-2 control-label">News Content</label>
                                        <div class="col-md-8">
										<textarea name="chinese_content" id="chinese_content" class="form-control" rows="3"><?php echo set_value('chinese_content'); ?></textarea>
										 <label id="contents-error" class="error" for="chinese_content" style="display:none">This field is required.</label>
                                        </div>
                                    </div>
                                    
                        </div>
                        <!--Chines End-->
                        <!--Russian Start-->
                        <div id="russian" class="samelang">
                                <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="russian_title" id="russian_title" class="form-control" placeholder="Title" value="" />
                                </div>
                            </div>                            
                            <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="russian_meta_keywords" id="russian_meta_keywords" class="form-control" placeholder="Meta Keywords" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea name="russian_meta_description" id="russian_meta_description" class="form-control" placeholder="Meta Description" rows="3"><?php echo set_value('russian_meta_description'); ?></textarea>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Heading</label>
                                        <div class="col-md-8">
										<input type="text" name="russian_heading" id="russian_heading" class="form-control" placeholder="Heading" value="" />
                                        </div>
                                    </div>
                                      <div class="form-group">
                                        <label class="col-md-2 control-label">News Content</label>
                                        <div class="col-md-8">
										<textarea name="russian_content" id="russian_content" class="form-control" rows="3"><?php echo set_value('russian_content'); ?></textarea>
										 <label id="contents-error" class="error" for="russian_content" style="display:none">This field is required.</label>
                                        </div>
                                    </div>
                                    
                                   
                                    
                        </div>
                        <!--Russian End--> 
                        <!--Spanish Start-->
                        <div id="spanish" CLASS="samelang">
                              
                          <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="spanish_title" id="spanish_title" class="form-control" placeholder="Title" value="" />
                                </div>
                            </div>                            
                            <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="spanish_meta_keywords" id="spanish_meta_keywords" class="form-control" placeholder="Meta Keywords" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea name="spanish_meta_description" id="spanish_meta_description" class="form-control" placeholder="Meta Description" rows="3"><?php echo set_value('spanish_meta_description'); ?></textarea>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Heading</label>
                                        <div class="col-md-8">
										<input type="text" name="spanish_heading" id="spanish_heading" class="form-control" placeholder="Heading" value="" />
                                        </div>
                                    </div>
                                      <div class="form-group">
                                        <label class="col-md-2 control-label">News Content</label>
                                        <div class="col-md-8">
										<textarea name="spanish_content" id="spanish_content" class="form-control" rows="3"><?php echo set_value('spanish_content'); ?></textarea>
										 <label id="contents-error" class="error" for="spanish_content" style="display:none">This field is required.</label>
                                        </div>
                                    </div>
                                    
                               
                        </div>
                        <!--Spanish End-->    

         					 <div class="form-group">
                                        <label class="col-md-2 control-label">Image</label>
                                        <div class="col-md-8">
										<input type="file" name="image" id="image" />
                                        </div>
                                    </div>
                                    <input type="hidden" value="" id="id" name="id" >
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Link</label>
                                        <div class="col-md-8">
										<input type="text" name="link" id="link" class="form-control" placeholder="Link" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">News Status</label>
                                        <div class="col-md-8">
										<select id="status" name="status" class="form-control">
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
                    <?php $attributes=array('class'=>'form-horizontal','id'=>'edit_faq');
                echo form_open_multipart($action,$attributes); ?>
                                <fieldset>

                                	 <div class="form-group">
                            <label class="col-md-2 control-label">Laguage</label>
                            <div class="col-md-8">
                                <!--    <input type="text" name="question" id="question" class="form-control" placeholder="Question" value="<?php echo $faq->english_question; ?>" /> -->
                                <select id="lang" name="lang" class="form-control" onchange="language();">
                                            <option value="1">english</option>
                                           <!--  <option value="2">chinese</option>
                                            <option value="3">russian</option> -->
                                            <option value="4">spanish</option>
                                        </select>

                            </div>
                        </div>
                         <!--English Start-->
                    <div id="english">
                        
                        <div class="form-group">
                                        <label class="col-md-2 control-label">Title</label>
                                        <div class="col-md-8">
										<input type="text" name="english_title" id="english_title" class="form-control" placeholder="Title" value="<?php echo $news->english_title; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Slug</label>
                                        <div class="col-md-8">
                                            <input type="text" name="news_slug" id="news_slug" class="form-control" placeholder="News Slug" value="<?php echo $news->news_slug; ?>" />
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="english_meta_keywords" id="english_meta_keywords" class="form-control" placeholder="Meta Keywords" value="<?php echo $news->english_meta_keywords; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="english_meta_description" name="english_meta_description" rows="3"><?php echo $news->english_meta_description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Heading</label>
                                        <div class="col-md-8">
										<input type="text" name="english_heading" id="english_heading" class="form-control" placeholder="Heading" value="<?php echo $news->english_heading; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Content</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="english_content" name="english_content" rows="20"><?php echo $news->english_content; ?></textarea>
										<label id="contents-error" class="error" for="english_content" style="display:none">This field is required.</label>
                                        </div>
                                    </div>

                       
                    </div>
                    <!--English End-->
                    <!--Chines Start-->
                    <div id="chinese" class="samelang">
                        
                       <div class="form-group">
                                        <label class="col-md-2 control-label">Title</label>
                                        <div class="col-md-8">
										<input type="text" name="chinese_title" id="chinese_title" class="form-control" placeholder="Title" value="<?php echo $news->chinese_title; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="chinese_meta_keywords" id="chinese_meta_keywords" class="form-control" placeholder="Meta Keywords" value="<?php echo $news->chinese_meta_keywords; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="chinese_meta_description" name="chinese_meta_description" rows="3"><?php echo $news->chinese_meta_description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Heading</label>
                                        <div class="col-md-8">
										<input type="text" name="chinese_heading" id="chinese_heading" class="form-control" placeholder="Heading" value="<?php echo $news->chinese_heading; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Content</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="chinese_content" name="chinese_content" rows="20"><?php echo $news->chinese_content; ?></textarea>
										<label id="contents-error" class="error" for="chinese_content" style="display:none">This field is required.</label>
                                        </div>
                                    </div>
                    </div>
                    <!--Chines End-->
                    <!--Russian Start-->
                    <div id="russian" class="samelang">
                           <div class="form-group">
                                        <label class="col-md-2 control-label">Title</label>
                                        <div class="col-md-8">
										<input type="text" name="russian_title" id="russian_title" class="form-control" placeholder="Title" value="<?php echo $news->russian_title; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="russian_meta_keywords" id="russian_meta_keywords" class="form-control" placeholder="Meta Keywords" value="<?php echo $news->russian_meta_keywords; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="russian_meta_description" name="russian_meta_description" rows="3"><?php echo $news->russian_meta_description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Heading</label>
                                        <div class="col-md-8">
										<input type="text" name="russian_heading" id="russian_heading" class="form-control" placeholder="Heading" value="<?php echo $news->russian_heading; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Content</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="russian_content" name="russian_content" rows="20"><?php echo $news->russian_content; ?></textarea>
										<label id="contents-error" class="error" for="russian_content" style="display:none">This field is required.</label>
                                        </div>
                                    </div>
                         
                            
                    </div>
                    <!--Russian End--> 
                    <!--Spanish Start-->
                    <div id="spanish" CLASS="samelang">
                            
                     <div class="form-group">
                                        <label class="col-md-2 control-label">Title</label>
                                        <div class="col-md-8">
										<input type="text" name="spanish_title" id="spanish_title" class="form-control" placeholder="Title" value="<?php echo $news->spanish_title; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Meta keywords</label>
                                        <div class="col-md-8">
										<input type="text" name="spanish_meta_keywords" id="spanish_meta_keywords" class="form-control" placeholder="Meta Keywords" value="<?php echo $news->spanish_meta_keywords; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Meta Description</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="spanish_meta_description" name="spanish_meta_description" rows="3"><?php echo $news->spanish_meta_description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Heading</label>
                                        <div class="col-md-8">
										<input type="text" name="spanish_heading" id="spanish_heading" class="form-control" placeholder="Heading" value="<?php echo $news->spanish_heading; ?>" />
                                        </div>
                                    </div> 


                                     <div class="form-group">
                                        <label class="col-md-2 control-label">Content</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="spanish_content" name="spanish_content" rows="20"><?php echo $news->spanish_content; ?></textarea>
										<label id="contents-error" class="error" for="spanish_content" style="display:none">This field is required.</label>
                                        </div>
                                    </div>

                          
                    </div>
                    <!--Spanish End-->

				        <input type="hidden" value="<?php echo $news->id; ?>" id="id" name="id" >
                                    
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">News Image</label>
                                        <div class="col-md-8">
										<input type="file" name="image" id="image"/>
										<?php $im = $news->image; ?>
										<input type="hidden" name="oldimage" id="oldimage" value="<?php echo $news->image; ?>" />
										<?php if($news->image!='') { ?>
										<img src="<?php echo $im; ?>" style="width:65px;height:65px;" />
										<?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Link</label>
                                        <div class="col-md-8">
										<input type="text" name="link" id="link" class="form-control" placeholder="Link" value="<?php echo $news->link; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Status</label>
                                        <div class="col-md-8">
										<select id="status" name="status" class="form-control">
										<option <?php if($news->status==1){echo 'selected';}?> value="1">Active</option>
										<option <?php if($news->status==0){echo 'selected';}?> value="0">De-active</option>
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
	<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
    <script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	<?php
    if($view)
    {
        if($view=='view_all'){
        }
        else if($view=='add')
        {
            ?>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('english_content');
            CKEDITOR.replace('chinese_content');
            CKEDITOR.replace('russian_content');
            CKEDITOR.replace('spanish_content');
        });
    </script>
    <?php 
}
    else
    {
    ?>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('english_content');
            CKEDITOR.replace('chinese_content');
            CKEDITOR.replace('russian_content');
            CKEDITOR.replace('spanish_content');
        });
    </script>
    <?php   
        }
    }
    
    ?>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
       $(document).ready(function() {

            $('#add').validate({
                rules: {
                    english_title: {
                        required: true
                    },
                    english_meta_keywords: {
					required: true
					},
					english_meta_description: {
					required: true,
					},
					english_heading: {
					required: true,
					},
                    chinese_title: {
                        required: true
                    },
                    chinese_meta_keywords: {
					required: true
					},
					chinese_meta_description: {
					required: true,
					},
					chinese_heading: {
					required: true,
					},
                    russian_title: {
                        required: true
                    },
                    russian_meta_keywords: {
					required: true
					},
					russian_meta_description: {
					required: true,
					},
					russian_heading: {
					required: true,
					},
                    spanish_title: {
                        required: true
                    },
                    spanish_meta_keywords: {
					required: true
					},
					spanish_meta_description: {
					required: true,
					},
					spanish_heading: {
					required: true,
					},
                     
                    
                    link: {
					required: true,
					remote: {
					url: admin_url+'news/link_exists/',
					type: "post",
						data: {
							link: function() {
							return $( "#link" ).val();
							}
						}
					},
				},
				english_content: {
					required: function() 
                    {
                    CKEDITOR.instances.english_content.updateElement();
                    }
				},
			chinese_content: {
					required: function() 
                    {
                    CKEDITOR.instances.chinese_content.updateElement();
                    }
				},
				russian_content: {
					required: function() 
                    {
                    CKEDITOR.instances.russian_content.updateElement();
                    }
				},
				spanish_content: {
					required: function() 
                    {
                    CKEDITOR.instances.spanish_content.updateElement();
                    }
				},
				
			},
			messages: {
				link: {
				remote: "Link already exists",
				},
			},
                highlight: function(element) {
                    //$(element).parent().addClass('error')
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass('error')
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: front_url + "get_csrf_token",
                        type: "GET",
                        cache: false,
                        processData: false,
                        success: function(data) {
                            $("input[name=" + csrfName + "]").val(data);
                            setTimeout(function() {
                                form.submit();
                            }, 1000);
                        }
                    });
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
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '<?php echo admin_source()."www.google-analytics.com/analytics.js";?>', 'ga');

        ga('create', 'UA-53034621-1', 'auto');
        ga('send', 'pageview');
        var admin_url = '<?php echo admin_url(); ?>';
        // alert(admin_url);
    </script>

    <!-- 
  LANGUAGRE DISPLAY IN CSS -->
    <style>
        .samelang {
            display: none;
        }
    </style>
    <!--   LANGUAGE DISPLAY END IN CSS -->
    <!--  ONCHANGE LANGUAGE  SCRIPT FUNCTION START -->
    <SCRIPT>
        function language() {
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



        function language() {
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

        $("#english_title").keyup(function(){
        var blog_title = $("#english_title").val();        
        var blogslugs = blog_title.toLowerCase();
        var blogslugs1 = blogslugs.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
        var bgslug = blogslugs1.replace(/ /g,"-");
        var bgnewslug = bgslug.replace("--","-");
        $("#news_slug").val(bgnewslug);
     });
    </SCRIPT>
    <script type="text/javascript">
  
    var admin_url='<?php echo admin_url(); ?>';
        
$(document).ready(function() {
    $('#datas-table').DataTable( {
        responsive : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"news/news_ajax"
    });
        });
      
</script>