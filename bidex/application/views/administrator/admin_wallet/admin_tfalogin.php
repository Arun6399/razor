<!-- begin #content -->
<div id="content" class="content">
    <?php
$error = $this->session->flashdata('error');
// if ($error != '') {
//     $error =validation_errors();
// echo '<div class="alert alert-danger">
// <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>' . $error . '</div>';
// }
$success = $this->session->flashdata('success');
if ($success != '') {
echo '<div class="alert alert-success">
<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>' . $success . '</div>';
}

$error = $this->session->flashdata('error');
if ($error != '') {
echo '<div class="alert alert-danger">
<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>' . $error . '</div>';
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

    .abtn {
        font-size: 11px;
        border: 1px solid #C84E2D;
        padding: 5px 10px;
        border-radius: 3px;
        color: #000;
    }

    /*
    input[type="text"],
    input[type="text"]:active {
    border-radius: 3px;
    border: 0px;
    height: 31px;
    box-shadow: 0px 3px 6px #00000016;
    background-color: #0b262e;
    color: #ffffff;
    padding: 6px;
    font-size: 15px;
    flex-grow: 2;
    }*/
    .input-group-text {
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding: .375rem .75rem;
        margin-bottom: 0;
        font-size: 15px;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: .25rem;
    }

    .input-group {
        display: block;
    }
    </style>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li>
            <a href="<?php echo admin_url(); ?>">Home
            </a>
        </li>
        <li class="active">Admin Wallet Withdraw
        </li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <?php echo $title ?>
        <!--<small>header small text goes here...</small>-->
    </h1>
    <p class="text-right m-b-10">
        <!--<a href="<?php echo admin_url() . 'pair/add'; ?>" class="btn btn-primary">Add New</a>-->
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
                            data-click="panel-expand">
                            <i class="fa fa-expand">
                            </i>
                        </a>
                        <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                            data-click="panel-collapse">
                            <i class="fa fa-minus">
                            </i>
                        </a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger"
                            data-click="panel-remove">
                            <i class="fa fa-times">
                            </i>
                        </a>
                    </div>
                    <h4 class="panel-title">
                        <?php echo $title ?>
                    </h4>
                </div>
       
                <div class="panel-body">
                    <div class="dash-card">
                        <div class="row">
                            <div class="col-md-3">&nbsp;
                            </div>
                            <div class="col-md-6">

                             <!--       <img src="<?php echo $url;?>" style="width: 100px;"> -->

                                   <br>

                                <!--    <p> <?php echo $secret; ?></p> -->


                                <p>
                                    <h2 class="text-center">
                                        <?php echo strtoupper($curr); ?> TFA
                                    </h2>
                                </p>
                                <br>
                                   <?php
                                    $attributes = array('class' => 'margin-bottom-0','id' => 'walletwithdraw');
                                    echo form_open($action, $attributes);
                                    ?>  
                                <div class="form-group">
                                    <label for="withdrawalAmount"> TFA Code</label>
                                    <input type="text" class="form-control" name="code" id="code" placeholder="Please Enter TFA Code">
                                     <input type="hidden" class="form-control" name="coin" value="<?php  echo $coin; ?>"  id="coin">

                                   
                                </div>
                            
                                <div class="form-group text-center">
                                    <button type="submit" name="tfa_sub" class="btn btn-primary">Submit
                                    </button>
                                   
                                </div>
                                <?php echo form_close(); ?>
                                <p class="text-center">
                                    <br>
                                    <br>
                            </div>
                            <div class="col-md-3">&nbsp;
                            </div>
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
<script src="<?php echo admin_source(); ?>/plugins/jquery/jquery-1.9.1.min.js">
</script>
<script src="<?php echo admin_source(); ?>/plugins/jquery/jquery-migrate-1.1.0.min.js">
</script>
<script src="<?php echo admin_source(); ?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js">
</script>
<script src="<?php echo admin_source(); ?>/plugins/bootstrap/js/bootstrap.min.js">
</script>
<!--[if lt IE 9]>
<script src="<?php echo admin_source(); ?>/crossbrowserjs/html5shiv.js"></script>
<script src="<?php echo admin_source(); ?>/crossbrowserjs/respond.min.js"></script>
<script src="<?php echo admin_source(); ?>/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<script src="<?php echo admin_source(); ?>/plugins/slimscroll/jquery.slimscroll.min.js">
</script>
<script src="<?php echo admin_source(); ?>/plugins/jquery-cookie/jquery.cookie.js">
</script>
<!-- ================== END BASE JS ================== -->
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="<?php echo admin_source(); ?>/plugins/gritter/js/jquery.gritter.js">
</script>
<script src="<?php echo admin_source(); ?>/plugins/flot/jquery.flot.min.js">
</script>
<script src="<?php echo admin_source(); ?>/plugins/flot/jquery.flot.time.min.js">
</script>
<script src="<?php echo admin_source(); ?>/plugins/flot/jquery.flot.resize.min.js">
</script>
<script src="<?php echo admin_source(); ?>/plugins/DataTables/js/jquery.dataTables.js">
</script>
<script src="<?php echo admin_source(); ?>/js/jquery.validate.min.js">
</script>
<script src="<?php echo admin_source(); ?>/js/apps.min.js">
</script>
<script src="<?php echo admin_source(); ?>/plugins/ckeditor/ckeditor.js">
</script>
<script src="<?php echo admin_source();?>js/jquery.mask.js">
</script>
<?php
if ($view) {
if ($view != 'view_all') {?>
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
    $('.money').mask("#,##0", {
        reverse: true
    });
    $('#walletwithdraw').validate({
        rules: {
            withdraw_to_address: {
                required: true
            },
            destinationTag: {
                required: true
            },
            withdrawalAmount: {
                required: true,
                number: true
            }
        },
        messages: {
            withdraw_to_address: {
                required: 'Withdraw to address is required'
            },
            destinationTag: {
                required: 'Destination tag is required'
            },
            withdrawalAmount: {
                required: 'Amount is required',
                number: 'Amount is Invalid'
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
<script>


function copy_function() {
    var copyText = document.getElementById("crypto_address");
    copyText.select();
    document.execCommand("<?php echo $this->lang->line("
        COPY "); ?>");
    $('.copy_but').html('<?php echo $this->lang->line("COPIED"); ?>');
}
</script>
<!--
LANGUAGRE DISPLAY IN CSS -->
<style>
.samelang {
    display: none;
}
</style>
<!-- ================== END PAGE LEVEL JS ================== -->