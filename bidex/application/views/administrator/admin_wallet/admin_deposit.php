begin #content -->
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
   .abtn 
   {
    font-size: 11px;
    border: 1px solid #C84E2D;
    padding: 5px 10px;
    border-radius: 3px;
    color: #000;

    }
    input[type="text"], input[type="text"]:active {
    border-radius: 3px;
    border: 0px; 
    height: 31px;
    box-shadow: 0px 3px 6px #00000016;
    background-color: #0b262e;
    color: #ffffff;
    /* padding: 6px; */
    font-size: 15px;
    flex-grow: 2; }

    .input-group-text 
    {
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
    .input-group{
        display: block;
    }
    input[type="text"], input[type="text"]:active {

    border-radius: 3px;
    border: 0px;
    height: 20px;
    background-color:#fff; 
    color: #555; 
    padding: 6px;
    font-size: 13px;
    flex-grow: 2;
    box-shadow:none !important;

    }
    .dash-card.admin-wallet 
    {
        max-width: 600px;
        margin: 0 auto;
        padding: 15px;
        background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;

    }
    .dash-card.admin-wallet .admin-hd {
        padding: 15px;
    margin: 0;
    font-size: 20px;
    }
    .dash-card.admin-wallet .br-code img {
        width: 200px;
        height: 200px;
        margin: 0 auto;
    }
    .admin-wallet .input-group {
        position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-align: stretch;
    -ms-flex-align: stretch;
    align-items: stretch;
    width: 100%;
    }
    .admin-wallet .input-group-append, .input-group-prepend {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    cursor: pointer;
}
.admin-wallet .input-group-append {
    margin-left: -1px;
}
.admin-wallet .input-group>.form-control {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    width: 1%;
    margin-bottom: 0;
}
.admin-wallet .input-group .form-control {
    border-radius: 3px;
    border: 0px;
    height: 30px;
    box-shadow: 0px 3px 6px #00000016;
    border: 1px solid #ced4da;
    color: #606471;
    /* padding: 6px; */
    font-size: 13px;
    flex-grow: 2;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.m-450 {
    max-width: 450px;
    margin: 0 auto;
}
.admin-wallet input[type="text"], input[type="text"] {
    border: 1px solid transparent;
    padding: 6px 12px;
}
.admin-wallet input[type="text"], input[type="text"]:active {
    border-radius: initial;
    border: 0;
    /* height: initial; */
    background-color: initial;
    color: initial;
    padding: 6px 12px;
    /* font-size: initial; */
    flex-grow: initial;
    box-shadow: none !important;
}
.admin-wallet input[type="text"], input[type="text"]:focus {
    padding: 6px 12px;
    border: 0;
    margin: 0;
    background: none;
}
.form-control[readonly]:focus {
    border: 1px solid transparent;
}
.form-control[readonly] {
    background: none;
}

    
        </style>
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="<?php echo admin_url();?>">Home</a></li>
        <li class="active">Admin Wallet Deposit</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header"><?php echo $title ?>
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
                    <h4 class="panel-title"><?php echo $title ?></h4>
                </div>

                <div class="panel-body">
 
                      <div class="dash-card <?php if($curr!='') { echo 'admin-wallet';} else { echo '';}?>">
                        <div class="row">

                               <?php
                            if($curr != "" ) 
                            {
                            ?>
                            <div class="col-md-12">
                                <p>
                                    <h2 class="admin-hd" >
                                        <?php echo strtoupper($curr);   ?> Deposit Address</h2>
                                </p>
                                <p class="text-center br-code">
                                    <img src="<?php echo $coin_img;?>" class="img-responsive" id="crypto_img" />
                                </p><br>

                                <p class="text-center" style="color:#fff !important;font-size:15px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group input-group-sm mb-3 m-450">
                                                <input type="text" readonly class="form-control input-group-text" aria-label="Small" aria-describedby="inputGroup-sizing-sm" id="crypto_address" placeholder="Address" required value="<?php echo $address;?>" name="search">
                                                <div class="input-group-append">
                                                    <span class="form-control input-group-text copy_but" id="inputGroup-sizing-sm"
                                                        onClick="copy_function()">Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </p><br>

                                <?php 
                                if($curr == "XRP")
                                {?>

                                   <p class="text-center" style="color:#fff !important;font-size:15px;">
                                    <div class="row">
                                        <div class="col-md-2">&nbsp;</div>
                                        <div class="col-md-8">
                                            <div class="input-group input-group-sm mb-3">
                                                <label>Destination Tag:</label>
                                                <input type="text" readonly class="form-control" aria-label="Small"
                                                    aria-describedby="inputGroup-sizing-sm" id="tag"
                                                    placeholder="<?php echo $tag;?>" required
                                                    value="<?php echo $tag;?>" name="search">
                                                <div class="input-group-append">
                                                <span class="input-group-text copy_but1" id="inputGroup-sizing-sm"
                                                        onClick="copy_function1()">Copy</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">&nbsp;</div>
                                    </div>
                                </p><br>
     

                                <?php

                                }

                                ?>

                                <p class="text-center"><a href="<?php echo base_url()."bidex_admin/admin_wallet"; ?>"
                                        class="abtn">Back</a>&nbsp;&nbsp;&nbsp;</p>
                                <br><br>
                            </div>

                               <?php } ?> <?php /*else { ?>

                             <table id="data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>USER EMAIL  
                                        </th>
                                        <th>BALANCE
                                        </th>
                                        <th>ADDRESS
                                        </th>
                                         <th>ACTION
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                    <?php
                                    $i=1;
                                    foreach ($usersinfo as $key => $value) {

                                    $get_address = getAddress($value->user_id,$cid);

                                    $users_list = $this->common_model->getTableData('wallet',array('user_id'=>$value->user_id))->row();

                                    $balance_get = $users_list->orginal_amount;



                                    $decode_balance = json_decode($balance_get);

                                    $get_balance = $decode_balance->$currency_name;


                                    if($get_balance !="0.00000000"){


                                    $crypto_amount = unserialize($value->crypto_amount);
                                    $email = getUserEmail($value->user_id);
                                    $balance_amt=0;
                                    $balance_amt=$crypto_amount['Exchange AND Trading'][$currency_id];
                                    $userid=$value->id;
                                        
                                    ?>
                                                    <tr>
                                                        <td style="width:35%;">
                                                            
                                                            <?php echo $email; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format($get_balance,8);
                                    ?>
                                                        </td>
                                                        <td style="width:35%;">
                                                          <input class="form-control mycopy" readonly type="text" value="<?php echo $get_address;?>"/>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);" data-address="0" class="copy_address table-btn1 grey-btn">Copy</a> 
                                                        </td>
                                                    </tr>
                                                    <?php
                                 $i++;
                                }
                                }
                            ?>
                                </tbody>
                            </table>


                        <?php } */ ?>







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
<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.js"></script>
<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo admin_source();?>js/jquery.mask.js"></script>
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

    $(".copy_address").click(function(){    
      $('td').removeAttr("id");
      $($(this).closest('td').prev('td')).attr('id', 'newID');
      var contnt = $("#newID input").select(); 
      document.execCommand('copy'); 
      $(".copy_address").html("Copy"); 
      $(this).html("Copied");    
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
<script>



  function copy_function() {

 

  var copyText = document.getElementById("crypto_address");
  copyText.select();
  document.execCommand("copy");
  $('.copy_but').html('Copied');
} 
  function copy_function1() {
  var copyText1 = document.getElementById("tag");
  copyText1.select();
  document.execCommand("copy");
  $('.copy_but1').html('Copied');
} 
  
</script>
<!-- 
 LANGUAGRE DISPLAY IN CSS -->
<style>
.samelang {
    display: none;
}
</style>
<!-- ================== END PAGE LEVEL JS ==================