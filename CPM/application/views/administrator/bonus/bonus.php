<style>
    .editable-submit, .editable-cancel { margin: 5px; }
</style>
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
                Bonus Management
               <!--  <span class="text-right m-b-10">
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
                <li class="active">Bonus</li>
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
                    <h4 class="panel-title"><?php echo $meta_description ?></h4>
                </div>

               <?php echo form_open(); echo form_close();?>
                <?php 
                    if($view=='view_all'){ 
                        $this->load->view("administrator/bonus/bonus_list");
                    }else if($view=='view'){ ?>
                        <div class="panel-body">
                        <?php $attributes=array('class'=>'form-horizontal','id'=>'bonus');
                echo form_open_multipart($action,$attributes);
                                        
                ?>
                                <fieldset>
                                  
                                <div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $bonus->cpm_username; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">User email</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo getUserEmail($bonus->id); ?>
                                </div>
                                </div>                              
                                <div class="form-group">
                                <label class="col-md-2 control-label">Referred by</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo getReferer($bonus->parent_referralid);?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Total No of Reference</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $bonus->successful_referral; ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Referred Users</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo getReferredUsers($bonus->referralid);?>
                                </div>
                                </div>

                                 <div class="form-group">
                                <label class="col-md-2 control-label">Referral Bonus Achieved</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $usage_count->total; ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Remaining Referral Bonus</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $remaining_count->total; ?>
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
<script type="text/javascript" src="https://seantheme.com/color-admin/admin/assets/plugins/x-editable-bs4/dist/bootstrap4-editable/js/bootstrap-editable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<!-- <script src="<?php echo admin_source();?>/js/admin.js"></script> -->
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


</script>

<script>
$(document).ready(function() {
    App.init();
});

var admin_url='<?php echo admin_url(); ?>';
$(document).ready(function() {
            var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';        
            
            $(document).on('click', '.sorting_my', function(e){
                $.fn.editable.defaults.mode = 'inline';
                $(this).editable();
            });

            $(document).on('click','.editable-submit',function(e, params) {
                var currency_id = $(this).parents("span").prev().data("pk");
                var newValue = $(this).parent('div').prev().children().val();
                $.ajax({
                    url: admin_url+"currency/currency_text", 
                    type: "POST",             
                    data: {'currency_id':currency_id,'currency_text':newValue},    
                    success: function(data) 
                    {                       
                        if(data==1)
                        {
                            window.location.href="https://moonex.com/cpm_admin/currency";
                        }
                    }
                });
            });

            $(document).on('click','.editable-cancel',function(e, params) {
                $(this).parents(".editableform").css("display",'none');

                $(this).parents(".editable-container").prev().css("display",'block');
            });
        });

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
        "ajax": admin_url+"bonus/bonus_ajax"
    });
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