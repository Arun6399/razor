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
    <!-- begin page-header -->
    <div class="row">
        <div class="col-md-6">
            <h1 class="page-header">
                Email Management
                <!-- <span class="text-right m-b-10">
                    <a href="<?php echo admin_url().'currency/add';?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i>&nbsp; Add New
                    </a>
                </span> -->
                <!--<small>header small text goes here...</small>-->
            </h1>
        </div>
        <div class="col-md-6">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="<?php echo admin_url();?>">Home</a></li>
                <li class="active">Email</li>
            </ol>
            <!-- end breadcrumb -->
        </div>
    </div>
    <!-- end page-header -->
    <!-- begin row -->
    <div class="row">
        <div class="col-md-12">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default"
                            data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                            data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger"
                            data-click="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                    <h4 class="panel-title"><?php echo $title ?></h4>
                </div>
                <?php 
                    if($view=='view_all'){ 
                        $this->load->view("administrator/email_list/view_all");
                    }else if($view=='add'){ 
                        $this->load->view("administrator/email_list/add_email");
                    }else{ 
                        $this->load->view("administrator/email_list/edit_email");
                    } 
                ?>
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
<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script> 
<script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>
<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
<!-- <script src="<?php echo admin_source();?>js/admin.js"></script> -->
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
$(document).ready(function() {
if($('input[name="assets_types"]:radio').is(':checked')){
    var types = $("[name='assets_types']:checked").val();
     if(types ==0)
     {
        $(".contract_sec").show();
        $(".decimal_sec").show();
     }
     else
     {
        $(".contract_sec").hide();
        $(".decimal_sec").hide();
     }
}
$('input[name="assets_types"]:radio').change(function(){
     var type = $(this).val();
     if(type ==0)
     {
        $(".contract_sec").show();
        $(".decimal_sec").show();
     }
     else
     {
        $(".contract_sec").hide();
        $(".decimal_sec").hide();
     }
});
});

$(document).ready(function() {
    
    $('#edit_email').validate({
        rules: {
            smtp_email: {
                required: true
            },
            smtp_password: {
                required: true
            },
            smtp_host: {
                required: true
            },
            smtp_port: {
                required: true,
                number: true
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

        var admin_url='<?php echo admin_url(); ?>';
        
$(document).ready(function() {
    $('#datas-table').DataTable( {
        "responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"email_list/email_ajax"
    });
        });
      
    </script>
<script>
$(document).ready(function() {
    App.init();
});

function change_dep_type(sel) {
    if (sel == 'fiat') {
        $('#deposit_seg').css('display', 'block');
    } else {
        $('#deposit_seg').css('display', 'none');
    }
}
</script>
<script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>