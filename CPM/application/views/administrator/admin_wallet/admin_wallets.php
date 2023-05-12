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
        <style type="text/css">
            .table-btn1 {
                background-color: #C84E2D;
                border-radius: 3px;
                box-shadow: 0px 3px 6px #00000016;
                color: #fff;
                padding: 5px 10px;
            }
           .table-btn {
            background-color: #04b54f;
            border-radius: 3px;
            box-shadow: 0px 3px 6px #00000016;
            color: #fff;
            padding: 5px 10px;
            }
        </style>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="<?php echo admin_url();?>">Home</a></li>
        <li class="active">Admin Wallet</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">Admin Wallet
        <!--<small>header small text goes here...</small>-->
    </h1>
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
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default"
                            data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                            data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger"
                            data-click="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                    <h4 class="panel-title">Admin Wallet</h4>
                </div>

                <div class="panel-body">
                 <div class="panel-body">
	                        <div class="clearfix">
	                   
							</div>
							<br/><br/>
                            <div class="table-responsive">
                                <table id="datas-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>     
                                        <th>S.No</th>                                      
											<th >CURRENCY</th>
											<th >BALANCE</th>									
											<th >ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
/*
                                        foreach ($get_admin_det as $key => $value) 
                                        {

                                       $sym             =  $key;                                
                                       $whers_con       =  "currency_symbol='$sym'";                                      
                                       $curr            =  $this->common_model->getrow("currency",$whers_con);
                                       $whers_con1      =  "id='1'";                                
                                       $curr1           =  $this->common_model->getrow("admin_wallet",$whers_con1);
                                       $balance_admin   =  json_decode($curr1->wallet_balance);        
                                       if($sym=="XRP" || $sym=="ETH"){
                                            $withdraw_url = base_url() . "cpm_admin/admin_wallet/admin_withdraw_from_user/" . $sym;
                                       }else{
                                            $withdraw_url = base_url() . "cpm_admin/admin_wallet/admin_withdraw/" . $sym;
                                       }
                                       $withdraw_url = base_url() . "cpm_admin/admin_wallet/admin_withdraw/" . $sym;
                                       if($sym !='USD')
                                       {
                                           if($sym=='USDT')
                                           {
                                            $format = 6;
                                           }
                                           else
                                           {
                                            $format = 8;
                                           }
                                       }                                       
                                       else
                                       {
                                        $format = 2;
                                       }
                                       
                                      ?>

                                        <tr>
                                            <td style="width:35%;"><img src="<?php echo $curr->image; ?>" width="25" height="25"> <?php echo $curr->currency_name; ?></td>
                                            <td>
                                            <?php echo number_format($balance_admin->$key,$format).' '.$sym; ?>
                                            </td>
                                             <td style="width:35%;">
                                                <a href="<?php echo base_url() . "cpm_admin/admin_wallet/admin_deposit/" . $sym; ?>"
                                                class="table-btn">Deposit</a> 
                                                <a href="<?php echo $withdraw_url; ?>"
                                                class="table-btn1">Withdraw</a>
                                            </td>

                                        </tr>

                                        <?php 
                                        }*/
                                        
                                        ?>
								
                                    </tbody>
                                </table>
                             
                            </div>
                        </div>
                </div>
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
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
    <script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo admin_source();?>/js/jquery.mask.js"></script>
<?php
    if($view)
    {
        if($view!='view_all'){ ?>
<script>
$(document).ready(function() {
    CKEDITOR.replace('content_description');
    CKEDITOR.replace('chinesecontent_description');
    CKEDITOR.replace('russiancontent_description');
    CKEDITOR.replace('spanishcontent_description');

});
</script>
<?php   
        }
    }
    
    ?>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
$(document).ready(function() {

    // Numeric only control handler
    jQuery.fn.ForceNumericOnly =
        function() {
            return this.each(function() {
                $(this).keydown(function(e) {
                    var key = e.charCode || e.keyCode || 0;
                    if (e.keyCode == 110 && this.value.split('.').length > 1) {
                        console.log(e.keyCode);
                        return false;
                    }
                    // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                    // home, end, period, and numpad decimal
                    return (
                        key == 8 ||
                        key == 9 ||
                        key == 13 ||
                        key == 46 ||
                        key == 110 ||
                        key == 190 ||
                        (key >= 35 && key <= 40) ||
                        (key >= 48 && key <= 57) ||
                        (key >= 96 && key <= 105));
                });
            });
        };
    $(".moneyformat").ForceNumericOnly();
    $('.money').mask("#,##0", {reverse: true});
    
    $('#walletwithdraw').validate({
        rules: {
            address: {
                required: true
            },
            amount:
            {
                required:true,
                number:true
            }
            

        },
        messages:
        {
            address:
            {
                required:'Address is required'
            },
            amount:
            {
                required:'Amount is required',
                number:'Amount is Invalid'
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
 var admin_url='<?php echo admin_url(); ?>';
$(document).ready(function() {
    $('#datas-table').DataTable( {
        responsive : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": false,
        "ajax": admin_url+"admin_wallet/wallet_ajax"
        });
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
.samelang {
    display: none;
}
</style>
<!-- ================== END PAGE LEVEL JS ================== -->