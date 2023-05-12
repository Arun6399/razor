<!-- begin #content -->

<!-- <?php echo "hiiii"; ?> -->
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
                <li class="active">Admin balance Updated</li>
            </ol>
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header">Site Settings <!--<small>header small text goes here...</small>--></h1>
            <!-- end page-header -->
            <!-- begin row -->
            <div class="row">
                <!-- begin col-8 -->
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="form-stuff-4">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Admin balance Updated</h4>
                        </div>
                        <div class="panel-body">
                        <?php $attributes=array('class'=>'form-horizontal','id'=>'site_settings');
                echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <h4 style="text-align: center;">Weekly Profit</h4>
                                          <div class="form-group">
                                        <label class="col-md-4 control-label">Currency </label>
                                        <div class="col-md-4">
                                            <select data-live-search="true" class="selectpicker form-control" name="currency_id" id="currency_id">
                                                   <?php foreach($currencies as $co) {
?>

                            <option <?php if($co->id==$edit->currency_id) { echo "selected"; } ?>
                            value ="<?php echo $co->id; ?>"><?php echo $co->currency_symbol; ?></option>
                            <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Update balance</label>
                                        <div class="col-md-4">
                                            <input type="text" name="update_balance" class="form-control" value="<?php echo $edit->week_number;?>" id="update_balance" autocomplete="off"  >
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
                                </fieldset>
                                <?php echo form_close(); ?>
                        </div>
                    </div>
                    <!-- end panel -->
                </div>
                <div class="col-md-1"></div>
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
    <script src="<?php echo admin_source();?>/js/apps.min.js"></script>
    <script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
    <link href="<?php echo admin_source(); ?>/css/patternLock.css"  rel="stylesheet" type="text/css" />
<script src="<?php echo admin_source(); ?>/js/patternLock.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- ================== END PAGE LEVEL JS ================== -->
<script>
    $(document).ready(function() {
        $.validator.addMethod('positiveNumber',
    function (value) { 
        return Number(value) ;
    }, 'Enter a positive number.');
        $('#site_settings').validate({
            rules: {
                week_number: {
                    // required: true,
                    number:true
                },
                week_year: {
                    // required: true,
                    number:true
                },
                weekly_profit: {
                    // required: true,
                    number: true
                },
                month: {
                    // required: true
                   
                },
                monthly_year: {
                    // required: true,
                    number:true
                },
                monthly_profit: {
                    // required: true,
                    number:true
                },
                yearly_profit: {
                    // required: true,
                    number:true
                },
                yearly_year: {
                    // required: true,
                    number:true
                },
                state: {
                    required: true
                },
                country: {
                    required: true
                },
               
              
                zip: {
                    required: true,
                    number: true
                },
                site_email: {
                    required: true
                },
                withdraw_limit_1: {
                    required: true,
                    number: true
                },
                withdraw_limit_2: {
                    required: true,
                    number : true,
                },
                withdraw_limit_3: {
                    required: true,
                    number : true,
                },
                buy_offer_update_time: {
                    required: true,
                    number : true,
                },
                sell_offer_update_time: {
                    required: true,
                    number : true,
                },
                ios_app_link: {
                    required: true,
                },
                android_app_link: {
                    required: true,
                },
                margin_trading_percentage: {
                    required: true,
                },
                lending_min_loan_rate: {
                    required: true,
                },
                lending_fees: {
                    required: true,
                },
                base_price_calculation: {
                    required: true,
                },
                // liquidation_price_calculation: {
                    // required: true,
                // },
                google_captcha_sitekey: {
                    required: true,
                },
                google_captcha_secretkey: {
                    required: true,
                },
                referral_bonus: {
                    positiveNumber: true
                }
            },
            messages : {
                contactno: {
                    minlength: "Please enter atleast 10 digits",
                    maxlength: "Should not more than 20 digits"
                },
                altcontactno: {
                    minlength: "Please enter atleast 10 digits",
                    maxlength: "Should not more than 20 digits"
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
var lock = new PatternLock("#patternContainer",{
     onDraw:function(pattern){
            word();
    }
});
function word()
{
    var pat=lock.getPattern();
    $("#patterncode").val(pat);
}

</script>
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
            $('.chinese').hide();
            $('#spanish').hide();
            $('#russian').hide();
            $('.english').show();
        }
        else if(x==2)
        {
            $('.english').hide();
            $('#spanish').hide();
            $('#russian').hide();
            $('.chinese').show();
       
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