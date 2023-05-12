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

  $lablename1 = explode(",",$token->lable_name1);
  $lablevalue1 = explode(",",$token->lable_value1);
  $lablename2 = explode(",",$token->lable_name2);
  $lablevalue2 = explode(",",$token->lable_value2);
        ?>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="<?php echo admin_url();?>">Home</a></li>
        <li class="active">Token Page</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">Token Page Management
        <!--<small>header small text goes here...</small>--></h1>
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
                    <h4 class="panel-title">Token Page Management</h4>
                </div>
               <?php if($view=='edit') { ?>
                <div class="panel-body">
                    <?php $attributes=array('class'=>'form-horizontal','id'=>'cms');
                 echo form_open_multipart($action,$attributes); ?>
                    <fieldset>
                        <!-- ENGLISH START -->
                        <div id="english">
                            <h3>Section1</h3>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="sec1_title" id="sec1_title" class="form-control" placeholder="Title" value="<?php echo $token->sec1_title; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Sub Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="sec1_sub_title" id="sec1_sub_title" class="form-control" placeholder="Sub Title" value="<?php echo $token->sec1_sub_title; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description1</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="sec1_description1" id="sec1_description1" rows="20"><?php echo $token->sec1_description1; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description2</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="sec1_description2" id="sec1_description2" rows="20"><?php echo $token->sec1_description2; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description3</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="sec1_description3" id="sec1_description3" rows="20"><?php echo $token->sec1_description3; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description4</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="sec1_description4" id="sec1_description4" rows="20"><?php echo $token->sec1_description4; ?></textarea>
                                </div>
                            </div>
                            <h3>Section2</h3>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="sec2_title" id="sec2_title" class="form-control" placeholder="Title" value="<?php echo $token->sec2_title; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Sub Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="sec2_sub_title" id="sec2_sub_title" class="form-control" placeholder="Sub Title" value="<?php echo $token->sec2_sub_title; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description1</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="sec2_description1" name="sec2_description1" rows="20"><?php echo $token->sec2_description1; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Button Text</label>
                                <div class="col-md-8">
                                    <input type="text" name="sec2_btn_text" id="sec2_btn_text" class="form-control" placeholder="Button Text" value="<?php echo $token->sec2_btn_text; ?>" />
                                </div>
                            </div>

                             <h3>Section3</h3>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="sec3_title" id="sec3_title" class="form-control" placeholder="Title" value="<?php echo $token->sec3_title; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Sub Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="sec3_sub_title" id="sec3_sub_title" class="form-control" placeholder="Sub Title" value="<?php echo $token->sec3_sub_title; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                        <label class="col-md-2 control-label">Image1</label>
                                        <div class="col-md-8">
                                        <input type="file" name="sec3_image1" id="sec3_image1" />
                                        <?php $im1 = $token->sec3_image1; ?>
                                        <input type="hidden" name="oldimage1" id="oldimage1" value="<?php echo $im1; ?>" />
                                        <?php if($im1!='') { ?>
                                        <img src="<?php echo $im1; ?>" style="width:65px;height:65px;" />
                                        <?php } ?>
                                        </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description1</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="sec3_description1" rows="20"><?php echo $token->sec3_description1; ?></textarea>
                                </div>
                            </div>

                             <div class="form-group">
                                        <label class="col-md-2 control-label">Image2</label>
                                        <div class="col-md-8">
                                        <input type="file" name="sec3_image2" id="sec3_image2" />
                                        <?php $im2 = $token->sec3_image2; ?>
                                        <input type="hidden" name="oldimage2" id="oldimage2" value="<?php echo $im2; ?>" />
                                        <?php if($im2!='') { ?>
                                        <img src="<?php echo $im2; ?>" style="width:65px;height:65px;" />
                                        <?php } ?>

                                        </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description2</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="sec3_description2" rows="20"><?php echo $token->sec3_description2; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                        <label class="col-md-2 control-label">Image3</label>
                                        <div class="col-md-8">
                                        <input type="file" name="sec3_image3" id="sec3_image3" />
                                        <?php $im3 = $token->sec3_image3; ?>
                                        <input type="hidden" name="oldimage3" id="oldimage3" value="<?php echo $im3; ?>" />
                                        <?php if($im3!='') { ?>
                                        <img src="<?php echo $im3; ?>" style="width:65px;height:65px;" />
                                        <?php } ?>

                                        </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description3</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="sec3_description3" rows="20"><?php echo $token->sec3_description3; ?></textarea>
                                </div>
                            </div>

                             <div class="form-group">
                                        <label class="col-md-2 control-label">Image4</label>
                                        <div class="col-md-8">
                                        <input type="file" name="sec3_image4" id="sec3_image4" />
                                        <?php $im4 = $token->sec3_image4; ?>
                                        <input type="hidden" name="oldimage4" id="oldimage4" value="<?php echo $im4; ?>" />
                                        <?php if($im4!='') { ?>
                                        <img src="<?php echo $im4; ?>" style="width:65px;height:65px;" />
                                        <?php } ?>

                                        </div>
                            </div>

                          <h3 style="text-align:center;">Chart1 Values</h3>

                          <div class="form-group">
                                <label class="col-md-2 control-label">Chart Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="chart1_title" id="chart1_title" class="form-control" placeholder="Chart Title" value="<?php echo $token->chart1_title; ?>" />
                                </div>
                            </div>
                            
                            <?php if($lablename1[0] !="" && $lablevalue1[0] !="" ) { ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="<?php echo $lablename1[0];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue1[0];?>" />


                                </div>
                                <a href="javascript:void(0);" class="add_button" title="Add more field">Add more</a>
                            </div>
                        <?php } ?>

                                                         
                             <div class="chart-append">

                            <?php if($lablename1[1] !="" && $lablevalue1[1] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="<?php echo $lablename1[1];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue1[1];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>


                             <?php if($lablename1[2] !="" && $lablevalue1[2] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="<?php echo $lablename1[2];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue1[2];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>


                             <?php if($lablename1[3] !="" && $lablevalue1[3] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="<?php echo $lablename1[3];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue1[3];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename1[4] !="" && $lablevalue1[4] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="<?php echo $lablename1[4];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue1[4];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename1[5] !="" && $lablevalue1[5] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="<?php echo $lablename1[5];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue1[5];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename1[6] !="" && $lablevalue1[6] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="<?php echo $lablename1[6];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue1[6];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename1[7] !="" && $lablevalue1[7] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="<?php echo $lablename1[7];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue1[7];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename1[8] !="" && $lablevalue1[8] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="<?php echo $lablename1[8];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue1[8];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename1[9] !="" && $lablevalue1[9] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="<?php echo $lablename1[9];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue1[9];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                            </div>


                            <h3 style="text-align:center;">Chart2 Values</h3>

                          <div class="form-group">
                                <label class="col-md-2 control-label">Chart Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="chart2_title" id="chart2_title" class="form-control" placeholder="Chart Title" value="<?php echo $token->chart2_title; ?>" />
                                </div>
                            </div>

                             <?php if($lablename2[0] !="" && $lablevalue2[0] !="" ) { ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="<?php echo $lablename2[0]; ?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value2[]" id="lable_value2" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue2[0]; ?>" />


                                </div>
                                <a href="javascript:void(0);" class="add_button1" title="Add more field">Add more</a>
                            </div>
                           <?php } ?>

                                                         
                             <div class="chart-append1">
                             <?php if($lablename2[1] !="" && $lablevalue2[1] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="<?php echo $lablename2[1];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value2[]" id="lable_value2" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue2[1];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button1"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>


                             <?php if($lablename2[2] !="" && $lablevalue2[2] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="<?php echo $lablename2[2];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value2[]" id="lable_value2" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue2[2];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button1"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>


                             <?php if($lablename2[3] !="" && $lablevalue2[3] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="<?php echo $lablename2[3];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value2[]" id="lable_value2" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue2[3];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button1"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename2[4] !="" && $lablevalue2[4] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="<?php echo $lablename2[4];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value2[]" id="lable_value2" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue2[4];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button1"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename2[5] !="" && $lablevalue2[5] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="<?php echo $lablename2[5];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value2[]" id="lable_value2" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue2[5];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button1"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename2[6] !="" && $lablevalue2[6] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="<?php echo $lablename2[6];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value2[]" id="lable_value1" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue2[6];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button1"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename2[7] !="" && $lablevalue2[7] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="<?php echo $lablename2[7];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value2[]" id="lable_value2" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue2[7];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button1"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename2[8] !="" && $lablevalue2[8] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="<?php echo $lablename2[8];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value2[]" id="lable_value2" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue2[8];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button1"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

                            <?php } ?>

                             <?php if($lablename2[9] !="" && $lablevalue2[9] !="" ) { ?>
                             <div>
                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="<?php echo $lablename2[9];?>" />
                                </div>
                            </div>

                             <div class="form-group">
                                <label class="col-md-2 control-label">Label Value</label>
                                <div class="col-md-8">
                                    <input type="text" name="lable_value2[]" id="lable_value2" class="form-control" placeholder="Label Value" value="<?php echo $lablevalue2[9];?>" />


                                </div>
                                <a href="javascript:void(0);" class="remove_button1"><i class="fa fa-trash-o text-danger"></i></a>
                            </div>
                            </div>

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
<!-- <script src="<?php echo admin_source();?>/plugins/ckeditor/ckeditor.js"></script> -->
<script src="<?php echo admin_source();?>/plugins/ckeditor_new/ckeditor.js"></script>
<?php
    if($view)
    {
        if($view=='edit'){ ?>
    <script>
        $(document).ready(function() {
            
            CKEDITOR.replace( 'sec2_description1',
            {
                allowedContent: true,
                filebrowserBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserImageBrowseUrl : 'js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserFlashBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserUploadUrl  :'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=File',
                filebrowserImageUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Image',
                filebrowserFlashUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Flash'
            });

            CKEDITOR.replace( 'sec1_description1',
            {
                allowedContent: true,
                filebrowserBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserImageBrowseUrl : 'js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserFlashBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserUploadUrl  :'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=File',
                filebrowserImageUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Image',
                filebrowserFlashUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Flash'
            });

            CKEDITOR.replace( 'sec1_description2',
            {
                allowedContent: true,
                filebrowserBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserImageBrowseUrl : 'js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserFlashBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserUploadUrl  :'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=File',
                filebrowserImageUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Image',
                filebrowserFlashUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Flash'
            });


            CKEDITOR.replace( 'sec1_description3',
            {
                allowedContent: true,
                filebrowserBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserImageBrowseUrl : 'js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserFlashBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserUploadUrl  :'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=File',
                filebrowserImageUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Image',
                filebrowserFlashUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Flash'
            });

            CKEDITOR.replace( 'sec1_description4',
            {
                allowedContent: true,
                filebrowserBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserImageBrowseUrl : 'js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserFlashBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                filebrowserUploadUrl  :'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=File',
                filebrowserImageUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Image',
                filebrowserFlashUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Flash'
            }
            );
        
        });
    </script>
    <?php   
        }
    }
    
    ?>
    <!-- ================== END PAGE LEVEL JS ================== -->
    <script>
        $(document).ready(function() {

            $('#cms').validate({
                rules: {
                    sec1_title: {
                        required: true
                    },
                    sec1_sub_title: {
                        required: true
                    },
                    sec1_description1: {
                        required: true,
                    },
                    sec1_description2: {
                        required: true
                    },
                    sec2_title: {
                        required: true
                    },
                    sec2_sub_title: {
                        required: true
                    },
                    sec2_description1: {
                        required: true
                    },
                    sec2_description2: {
                        required: true
                    },
                    sec3_description1: {
                        required: true
                    }

                },
                highlight: function(element) {
                    //$(element).parent().addClass('error')
                },
                unhighlight: function(element) {
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
    <SCRIPT>
        function language() 
        {
            var x = document.getElementById("lang").value;
            if (x == 1)
             {
                $('#chinese').hide();
                $('#spanish').hide();
                $('#russian').hide();
                $('#english').show();
            } 
            else if (x == 2)
             {
                $('#english').hide();
                $('#spanish').hide();
                $('#russian').hide();
                $('#chinese').show();

            }
             else if (x == 3)
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
    <!-- ================== END PAGE LEVEL JS ================== -->

    <script>
    $(document).ready(function(){
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.chart-append'); //Input field wrapper
    var fieldHTML = '<div><div class="form-group"><label class="col-md-2 control-label">Label Name</label><div class="col-md-8"><input type="text" name="lable_name1[]" id="lable_name1" class="form-control" placeholder="Label Name" value="" /></div></div><div class="form-group"><label class="col-md-2 control-label">Label Value</label><div class="col-md-8"><input type="text" name="lable_value1[]" id="lable_value1" class="form-control" placeholder="Label Value" value=""/></div><a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash-o text-danger"></i></a></div></div>';
    <?php $count = count($lablename1); 
    if($count==0) {
    ?>
    var x = 1; //Initial field counter is 1
   <?php } else if($count==10) { ?>
    var x = 10;
   <?php } else { ?>
    var x = "<?php echo $count;?>";
    <?php } ?>
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
        else
        {
            alert("Only 10 Fields are allowed");
        }

    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent().parent().remove(); //Remove field html
        x--; //Decrement field counter
    });


     var maxField1 = 10; //Input fields increment limitation
    var addButton1 = $('.add_button1'); //Add button selector
    var wrapper1 = $('.chart-append1'); //Input field wrapper
    var fieldHTML1 = '<div><div class="form-group"><label class="col-md-2 control-label">Label Name</label><div class="col-md-8"><input type="text" name="lable_name2[]" id="lable_name2" class="form-control" placeholder="Label Name" value="" /></div></div><div class="form-group"><label class="col-md-2 control-label">Label Value</label><div class="col-md-8"><input type="text" name="lable_value2[]" id="lable_value2" class="form-control" placeholder="Label Value" value=""/></div><a href="javascript:void(0);" class="remove_button1"><i class="fa fa-trash-o text-danger"></i></a></div></div>';
     <?php $count1 = count($lablename2); 
    if($count1==0) {
    ?>
    var y = 1; //Initial field counter is 1
   <?php } else if($count1==10) { ?>
    var y = 10;
   <?php } else { ?>
    var y = "<?php echo $count1;?>";
    <?php } ?>
    
    //Once add button is clicked
    $(addButton1).click(function(){
        //Check maximum number of input fields
        if(y < maxField1){ 
            y++; //Increment field counter
            $(wrapper1).append(fieldHTML1); //Add field html
        }
        else
        {
            alert("Only 10 Fields are allowed");
        }
    });
    
    //Once remove button is clicked
    $(wrapper1).on('click', '.remove_button1', function(e){
        e.preventDefault();
        $(this).parent().parent().remove(); //Remove field html
        y--; //Decrement field counter
    });
});
    </script>
  