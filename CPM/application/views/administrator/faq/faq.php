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
        <li class="active">Faq</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">Faq Management
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
                    <h4 class="panel-title">Faq Management</h4>
                </div>
                <?php
                if($view=='view_all'){ ?>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="faq-data-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Questions</th>
                                    <!-- //vv -->
                                    <!--<th class="text-center">Language</th>-->
                                    <!-- //vv -->
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">
                                <?php
                                if ($faq->num_rows() > 0) {
                                    $i = 1;
                                    foreach(array_reverse($faq->result()) as $result) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $result->english_question . '</td>';
                                        //vv
                                        //echo '<td>' . $result->languagename . '</td>';
                                        //vv
                                        if ($result->status == 1) {
                                            $status = '<label class="label label-info">Activated</label>';
                                            $extra = array('title' => 'De-activate this faq');
                                            $changeStatus = anchor(admin_url().'faq/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
                                        } else {
                                            $status = '<label class="label label-danger">De-Activated</label>';
                                            $extra = array('title' => 'Activate this faq');
                                            $changeStatus = anchor(admin_url().'faq/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
                                        }
                                        echo '<td>'.$status.'</td>';
                                        echo '<td>';
                                        echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
                                        echo '<a href="' . admin_url() . 'faq/edit/' . $result->id . '" title="Edit this faq"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                                        echo '<a href="' . admin_url() . 'faq/delete/' . $result->id . '" title="Delete this faq"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
                                        echo '</td>';
                                        echo '</tr>';
                                        $i++;
                                    }
                                } else {
                                    echo '<tr><td></td><td></td><td colspan="2" class="text-center">No Faq added yet!</td><td></td><td></td></tr>';
                                }
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
                                    <select data-live-search="true" id="lang" name="lang" class="form-control selectpicker" onchange="language();">
                                                <option value="1" >english</option>
                                                <!-- <option value="2" >chinese</option> -->
                                               <!--  <option value="3" >russian</option>
                                                <option value="4" >spanish</option> -->
                                            </select>
                                </div>
                        </div>
                        <div id="english">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Question</label>
                                <div class="col-md-8">
                                    <input type="text" name="addenglish_question" id="addenglish_question" class="form-control" placeholder="Question" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-8">
                                    <textarea name="addenglish_description" id="addenglish_description" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Chines Start-->
                        <div id="chinese" class="samelang">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Question</label>
                                <div class="col-md-8">
                                    <input type="text" name="addchinese_question" id="question" class="form-control" placeholder="Question" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="addchinese_description" required="" name="addchinese_description" rows="20"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--Chines End-->
                        <!--Russian Start-->
                        <div id="russian" class="samelang">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Question</label>
                                    <div class="col-md-8">
                                        <input type="text" name="addrussian_question" required="" id="addrussian_question" class="form-control" placeholder="Question" value="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Description</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="addrussian_description" required="" name="addrussian_description" rows="20"></textarea>
                                    </div>
                                </div>
                        </div>
                        <!--Russian End-->
                        <!--Spanish Start-->
                        <div id="spanish" CLASS="samelang">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Question</label>
                                    <div class="col-md-8">
                                        <input type="text" name="addspanish_question" id="addspanish_question" required="" class="form-control" placeholder="Question" value="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Description</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control" id="addspanish_description" required="" name="addspanish_description" rows="20"></textarea>
                                    </div>
                                </div>
                        </div>
                        <!--Spanish End-->
                        <div class="form-group">
                            <label class="col-md-2 control-label">FAQ Status</label>
                            <div class="col-md-8">
                                <select data-live-search="true" id="add_status" name="add_status" class="form-control selectpicker">
                                        <option value="1">Active</option>
                                        <option value="0">De-active</option>
                                        </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-7 col-md-offset-5">
                                <button type="submit" value="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
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
                                <select data-live-search="true" id="lang" name="lang" class="form-control selectpicker" onchange="language();">
                                            <option value="1">english</option>
                                            <!-- <option value="2">chinese</option>
                                            <option value="3">russian</option>
                                            <option value="4">spanish</option> -->
                                        </select>
                            </div>
                        </div>
                    <!--English Start-->
                    <div id="english">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Question</label>
                            <div class="col-md-8">
                                <input type="text" name="english_question" id="english_question" class="form-control" placeholder="Question" value="<?php echo $faq->english_question; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Description</label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="english_description" name="english_description" rows="20"><?php echo $faq->english_description; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <!--English End-->
                    <!--Chines Start-->
                    <div id="chinese" class="samelang">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Question</label>
                            <div class="col-md-8">
                                <input type="text" name="chinese_question" id="question" class="form-control" placeholder="Question" value="<?php echo $faq->chinese_question; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Description</label>
                            <div class="col-md-8">
                                <textarea class="form-control" id="chinese_description" name="chinese_description" rows="20"><?php echo $faq->chinese_description; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <!--Chines End-->
                    <!--Russian Start-->
                    <div id="russian" class="samelang">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Question</label>
                                <div class="col-md-8">
                                    <input type="text" name="russian_question" id="question" class="form-control" placeholder="Question" value="<?php echo $faq->russian_question; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="russian_description" name="russian_description" rows="20"><?php echo $faq->russian_description; ?></textarea>
                                </div>
                            </div>
                    </div>
                    <!--Russian End-->
                    <!--Spanish Start-->
                    <div id="spanish" CLASS="samelang">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Question</label>
                                <div class="col-md-8">
                                    <input type="text" name="spanish_question" id="question" class="form-control" placeholder="Question" value="<?php echo $faq->spanish_question; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="spanish_description" name="spanish_description" rows="20"><?php echo $faq->spanish_description; ?></textarea>
                                </div>
                            </div>
                    </div>
                    <!--Spanish End-->
                            <div class="form-group">
                                <label class="col-md-2 control-label"> FAQ Status</label>
                                <div class="col-md-8">
                                    <select data-live-search="true" id="status" name="status" class="form-control selectpicker">
                                        <option <?php if($faq->status==1){echo 'selected';}?> value="1">Active</option>
                                        <option <?php if($faq->status==0){echo 'selected';}?> value="0">De-active</option>
                                    </select>
                                </div>
                            </div>
                        <div class="form-group">
                            <div class="col-md-7 col-md-offset-5">
                                <button type="submit" class="btn btn-sm btn-primary m-r-5" value="submit">Submit</button>
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
        else if($view=='add')
        {
            ?>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('addenglish_description');
            CKEDITOR.replace('addchinese_description');
            CKEDITOR.replace('addrussian_description');
            CKEDITOR.replace('addspanish_description');
        });
    </script>
    <?php
}
    else
    {
    ?>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('english_description');
            CKEDITOR.replace('chinese_description');
            CKEDITOR.replace('russian_description');
            CKEDITOR.replace('spanish_description');
        });
    </script>
    <?php
        }
    }
    ?>
    <!-- ================== END PAGE LEVEL JS ================== -->
    <script>
       $(document).ready(function() {
            $('#faq-data-table').DataTable(); 
            $('#add').validate({
                rules: {
                    addenglish_question: {
                        required: true
                    },
                    addenglish_description: {
                        required: true,
                    },
                    add_status: {
                        required: true
                    },
                    addchinese_description: {
                        required: true,
                    },
                    addchinese_question: {
                        required: true
                    },
                    addrussian_description: {
                        required: true,
                    },
                    addrussian_question: {
                        required: true
                    },
                    addspanish_description: {
                        required: true,
                    },
                    addspanish_question: {
                        required: true
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
 <script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>
    <script>
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
    </SCRIPT>