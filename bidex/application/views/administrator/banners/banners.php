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
                Banner Management
                <span class="text-right m-b-10">
                    <a href="<?php echo admin_url().'banner/add';?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i>&nbsp; Add New
                    </a>
                </span>
                <!--<small>header small text goes here...</small>-->
            </h1>
        </div>
        <div class="col-md-6">
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="<?php echo admin_url();?>">Home</a></li>
                <li class="active">Banners</li>
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
                        $this->load->view("administrator/banners/banner_list");
                    }else if($view=='add'){ 
                        $this->load->view("administrator/banners/add_banner");
                    }else{ 
                        $this->load->view("administrator/banners/edit_banner");
                    } 
                ?>
            </div>
            <!-- end panel -->
        </div>
    </div>
    <!-- end row -->
</div>
<!-- end #content -->
<?php  $tt = $this->uri->segment_array();
$page_name = $tt[2]; ?>
<script src="<?php echo admin_source();?>/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/bootstrap/js/bootstrap.min.js"></script>

<script src="<?php echo admin_source();?>/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/jquery-cookie/jquery.cookie.js"></script>
<!-- ================== END BASE JS ================== -->
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="<?php echo admin_source();?>/plugins/gritter/js/jquery.gritter.js"></script>
<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.time.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.resize.min.js"></script>

<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>

<script src="<?php echo admin_source();?>js/jquery.validate.min.js"></script>
<script src="<?php echo admin_source();?>js/apps.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<!-- <script src="<?php echo admin_source();?>js/admin.js"></script> -->
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
    $(document).ready(function() {
        $(document).on('mouseenter', '.poper', function(){
            $(this).popover("show");
            $(this).on("mouseleave", function () {
                $(this).popover('hide');
            });
        });
});

$(document).ready(function() {
    $('#banners').validate({
        rules: {
           /* banner_name: {
                required: true
            },*/
            image: {
                required: true
            },
            expiry_date: {
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
    $('#edit_banner').validate({
        rules: {
           /* banner_name: {
                required: true
            },*/
            expiry_date: {
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
$(function () {
  $('[data-toggle="popover"]').popover({
        placement : 'top',
        trigger : 'hover'
    });
});
</script>
<script>
$(document).ready(function() {
    App.init();
});
 var admin_url='<?php echo admin_url(); ?>';
 
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
            });
$(document).ready(function() {
    $('#datas-table').DataTable( {
        "responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"banner/banner_ajax"
    });
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