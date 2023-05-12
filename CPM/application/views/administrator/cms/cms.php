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
        <li class="active">CMS</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">CMS Management
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
                    <h4 class="panel-title">CMS Management</h4>
                </div>
                <?php if($view=='view_all'){ ?>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="cms-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Heading</th>
                                    <!--<th class="text-center">Language</th>-->
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">
                                <?php
                                if ($cms->num_rows() > 0) {
                                    $i = 1;
                                    foreach($cms->result() as $result) {
                                        if($result->link !="Token") {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $result->english_heading . '</td>';
                                        if ($result->status == 1) {
                                            $status = '<label class="label label-info">Activated</label>';
                                            $extra = array('title' => 'De-activate this cms');
                                            $changeStatus = anchor(admin_url().'cms/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
                                        } else {
                                            $status = '<label class="label label-danger">De-Activated</label>';
                                            $extra = array('title' => 'Activate this cms');
                                            $changeStatus = anchor(admin_url().'cms/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
                                        }
                                        //echo '<td>' . $result->name . '</td>';
                                        echo '<td>'.$status.'</td>';
                                        echo '<td>';
                                        echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
                                        echo '<a href="' . admin_url() . 'cms/edit/' . $result->id . '" title="Edit this cms"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                                        echo '</td>';
                                        echo '</tr>';
                                        $i++;
                                    }
                                    }                   
                                } else {
                                    echo '<tr><td></td><td></td><td colspan="2" class="text-center">No cms added yet!</td><td></td><td></td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php }else{ ?>
                <div class="panel-body">
                    <?php $attributes=array('class'=>'form-horizontal','id'=>'cms');
                 echo form_open_multipart($action,$attributes); ?>
                    <fieldset>
                         <!-- <div class="form-group">
                            <label class="col-md-2 control-label">language</label>
                            <div class="col-md-8">
                                <select data-live-search="true" id="lang" name="lang" class="form-control selectpicker" onchange="language();">
                                    <option value="1" >english</option>
                                    <option value="2" >chinese</option>
                                    <option value="3" >russian</option>
                                    <option value="4" >spanish</option>
                                </select>
                            </div>
                        </div>  -->

                        <!-- ENGLISH START -->
                        <div id="english">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Heading</label>
                                <div class="col-md-8">
                                    <input type="text" name="heading" id="heading" class="form-control" placeholder="Heading" value="<?php echo $cms->english_heading; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="<?php echo $cms->english_title; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Meta Keywords</label>
                                <div class="col-md-8">
                                    <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" placeholder="Meta Keywords" value="<?php echo $cms->english_meta_keywords; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Meta Description</label>
                                <div class="col-md-8">
                                    <input type="text" name="meta_description" id="meta_description" class="form-control" placeholder="Meta Description" value="<?php echo $cms->english_meta_description; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="content_description" name="content_description" rows="20"><?php echo $cms->english_content_description; ?></textarea>
                                </div>
                            </div>
                        </div> 

                        <!--  ENGLISH END -->

                        <!--    chinese strat -->

                        <div class="samelang" id="chinese">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Heading</label>
                                <div class="col-md-8">
                                    <input type="text" name="chineseheading" id="chineseheading" class="form-control" placeholder="Heading" value="<?php echo $cms->chinese_heading; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="chinesetitle" id="chinesetitle" class="form-control" placeholder="Title" value="<?php echo $cms->chinese_title; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Meta Keywords</label>
                                <div class="col-md-8">
                                    <input type="text" name="chinesemeta_keywords" id="chinesemeta_keywords" class="form-control" placeholder="Meta Keywords" value="<?php echo $cms->chinese_meta_keywords; ?>" />
                                </div>
                            </div> 

                            <div class="form-group">
                                <label class="col-md-2 control-label">Meta Description</label>
                                <div class="col-md-8">
                                    <input type="text" name="chinesemeta_description" id="chinesemeta_description" class="form-control" placeholder="Meta Description" value="<?php echo $cms->chinese_meta_description ; ?>" />
                                </div> 
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="chinesecontent_description " name="chinesecontent_description" rows="20"><?php echo $cms->chinese_content_description; ?></textarea>
                                </div>
                            </div>

                        </div> 
                        <!--    chinese end -->

                        <!--   RUSSION strat -->

                       <div class="samelang" id="russian">

                            <div class="form-group">
                                <label class="col-md-2 control-label">Heading</label>
                                <div class="col-md-8">
                                    <input type="text" name="russianheading" id="heading" class="form-control" placeholder="Heading" value="<?php echo $cms->russian_heading; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="russiantitle" id="title" class="form-control" placeholder="Title" value="<?php echo $cms->russian_title; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Meta Keywords</label>
                                <div class="col-md-8">
                                    <input type="text" name="russianmeta_keywords" id="meta_keywords" class="form-control" placeholder="Meta Keywords" value="<?php echo $cms->russian_meta_keywords; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Meta Description</label>
                                <div class="col-md-8">
                                    <input type="text" name="russianmeta_description" id="meta_description" class="form-control" placeholder="Meta Description" value="<?php echo $cms->russian_meta_description; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="russiancontent_descriptionrussiancontent_description" name="russiancontent_description" rows="20"><?php echo $cms->russian_content_description; ?></textarea>
                                </div>
                            </div>
                        </div>  
                        <!--    RUSSION end -->


                        <!-- SPANISEH START -->

                        <div class="samelang" id="spanish">

                            <div class="form-group">
                                <label class="col-md-2 control-label">Heading</label>
                                <div class="col-md-8">
                                    <input type="text" name="spanishheading" id="heading" class="form-control" placeholder="Heading" value="<?php echo $cms->spanish_heading; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-8">
                                    <input type="text" name="spanishtitle" id="title" class="form-control" placeholder="Title" value="<?php echo $cms->spanish_title; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Meta Keywords</label>
                                <div class="col-md-8">
                                    <input type="text" name="spanishmeta_keywords" id="meta_keywords" class="form-control" placeholder="Meta Keywords" value="<?php echo $cms->spanish_meta_keywords; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Meta Description</label>
                                <div class="col-md-8">
                                    <input type="text" name="spanishmeta_description" id="meta_description" class="form-control" placeholder="Meta Description" value="<?php echo $cms->spanish_meta_description; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="spanishcontent_description" name="spanishcontent_description" rows="20"><?php echo $cms->spanish_content_description; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!--     SPANESIH END -->
                        <div class="form-group">
                            <label class="col-md-2 control-label"> CMS Status</label>
                            <div class="col-md-8">
                                <select data-live-search="true" id="status" name="status" class="form-control selectpicker">
                                        <option <?php if($cms->status==1){echo 'selected';}?> value="1">Active</option>
                                        <option <?php if($cms->status==0){echo 'selected';}?> value="0">De-active</option>
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
<script src="<?php echo admin_source();?>plugins/jquery/jquery-1.9.1.min.js"></script>

<script src="<?php echo admin_source();?>plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="<?php echo admin_source();?>plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="<?php echo admin_source();?>plugins/bootstrap/js/bootstrap.min.js"></script>

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

<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script> 
<script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>

<script src="<?php echo admin_source();?>js/jquery.validate.min.js"></script>
<script src="<?php echo admin_source();?>js/apps.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="<?php echo admin_source();?>plugins/ckeditor_new/ckeditor.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
            var admin_url = '<?php echo admin_url(); ?>';
            $('#cms-data-table').DataTable( {
                "responsive" : true,
                "processing" : true,
                "pageLength" : 10,
                "serverSide": true,
                "order": [[0, "asc" ]],
                "searching": true,
                "ajax": admin_url+"cms/cms_ajax"
            });
        });
    </script>
<?php
    if($view)
    {
        if($view!='view_all'){ ?>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace( 'content_description',
                {
                    allowedContent: true,
                    filebrowserBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                    filebrowserImageBrowseUrl : 'js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                    filebrowserFlashBrowseUrl :'js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/connector.php',
                    filebrowserUploadUrl  :'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=File',
                    filebrowserImageUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Image',
                    filebrowserFlashUploadUrl : 'https://bluerico.com/assets/admin/plugins/ckeditor_new/filemanager/connectors/php/upload.php?Type=Flash'
                });

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
                    heading: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    meta_keywords: {
                        required: true,
                    },
                    meta_description: {
                        required: true
                    },
                    content_description: {
                        required: true,
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
  