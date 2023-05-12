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
				<li class="active">Coin Profit</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Coin Profit <!--<small>header small text goes here...</small>--></h1>
			<p class="text-right m-b-10">
								<!--<a href="<?php echo admin_url().'pair/add';?>" class="btn btn-primary">Add New</a>-->
							</p>
			<!-- end page-header -->
			<!-- begin row -->
			<div class="row">
				<div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Select Currency</h4>
                        </div>
                        <div class="panel-body">
                        <!--h4>Select Fiat Currency</h4>
                            	<fieldset>
                                  <?php if(!empty($fi_cu)) { 
                                  	$i = 1;
                                  	foreach($fi_cu as $fi) { 
                                  		$type="fiat";
                                  		?>
                                  		<a href="<?php echo admin_url() . 'admin/coin_profit_report/'.$fi->id.'/fiat'; ?>"><font class="<?php if($currency_symbol==$fi->currency_symbol){ echo 'btn btn-small btn-danger'; } else{ echo 'btn btn-small btn-success'; } ?>" style="margin-bottom: 10px;"><?php echo $fi->currency_symbol; ?></font></a>
                                  <?php $i++; } } ?>
                                </fieldset-->
                        <h4>Select Currency</h4>
                                <fieldset>
                                  <?php if(!empty($cu)) { 
                                  	foreach($cu as $cus) { 
                                  		$type="crypto";
                                  		?>
										<a href="<?php echo admin_url() . 'admin/coin_profit_report/'.$cus->id.'/crypto'; ?>"><font class="<?php if($currency_symbol==$cus->currency_symbol){ echo 'btn btn-small btn-danger'; } else{ echo 'btn btn-small btn-success'; } ?>" style="margin-bottom: 10px;"><?php echo $cus->currency_symbol; ?></font></a>
                                  		<!--<font class="btn btn-small btn-success" id="<?php echo $cus->currency_symbol; ?>" id="<?php echo $cus->currency_symbol; ?>" style="margin-bottom: 10px;" onclick="myFunction('<?php echo $cus->id; ?>', '<?php echo $type; ?>')"> <?php echo $cus->currency_symbol; ?> </font>--> 
                                  <?php } } ?>
                                </fieldset>
                        </div>
                    </div>
                </div>
			</div>
			
			<div class="row">

				<!-- begin col-12 -->
				<div class="col-md-12">
					<div class="panel panel-inverse" data-sortable-id="index-1">
						<div class="panel-heading">
							<div class="panel-heading-btn">
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
								<!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
							</div>
							<h4 class="panel-title">Profit Analytics</h4>
						</div>
						<div class="panel-body">
							<div id="interactive-chart" class="height-sm"></div>
						</div>
					</div>
				</div>
				<!-- end col-12 -->

				<div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Daily Coin Profit - <v id="daily_currency_name"><?php echo $currency_symbol; ?></v></h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="dailydata">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
										<th class="text-center">Date</th>
										<th class="text-center">Total</th>
										<!--<th class="text-center">Total Bonus</th>-->
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;" class="daily_profit">
									<?php
								if ($daily->num_rows() > 0) {
									$i = 1;
									foreach(array_reverse($daily->result()) as $result) {
										$bonus=$result->total+$result->bonus;
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result->dateval . '</td>';
										echo '<td>' . $bonus . '</td>';
										//echo '<td>' . $result->bonus . '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td colspan="3" class="text-center">No Daily Coin Profit!</td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
			</div>


			<div class="row">
				<div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Weekly Coin Profit - <v id="weekly_currency_name"><?php echo $currency_symbol; ?></v></h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="weeklydata">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
										<th class="text-center">Week Number</th>
										<th class="text-center">Year</th>
										<th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;" class="weekly_profit">
									<?php
								if ($weekly->num_rows() > 0) {
									$i = 1;
									foreach(array_reverse($weekly->result()) as $result1) {
										$bonus=$result1->total+$result1->bonus;
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result1->week_number . '</td>';
										echo '<td>' . $result1->yname . '</td>';
										echo '<td>' . $bonus . '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td colspan="4" class="text-center">No Monthly Coin Profit!</td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="row">
				<div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Monthly Coin Profit - <v id="monthly_currency_name"> <?php echo $currency_symbol; ?></v></h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="monthlydata">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
										<th class="text-center">Month</th>
										<th class="text-center">Year</th>
										<th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;" class="monthly_profit">
									<?php
								if ($monthly->num_rows() > 0) {
									$i = 1;
									foreach(array_reverse($monthly->result()) as $result2) {
										$bonus=$result2->total+$result2->bonus;
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result2->moname . '</td>';
										echo '<td>' . $result2->yname . '</td>';
										echo '<td>' . $bonus . '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td colspan="4" class="text-center">No Monthly Coin Profit!</td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="row">
				<div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Yearly Coin Profit - <v id="yearly_currency_name"><?php echo $currency_symbol; ?> </v></h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="yearlydata">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
										<th class="text-center">Year</th>
										<th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;" class="yearly_profit">
									<?php
								if ($yearly->num_rows() > 0) {
									$i = 1;
									foreach(array_reverse($yearly->result()) as $result3) {
										$bonus=$result3->total+$result3->bonus;
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result3->yname . '</td>';
										echo '<td>' . $bonus . '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td colspan="3" class="text-center">No Yearly Coin Profit!</td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
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
	<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
    <script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>


	<script src="<?php echo admin_source();?>/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js"></script>

	<!-- <script src="<?php echo admin_source();?>js/dashboard.min.js"></script> -->

	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		var chartdata='<?php echo json_encode($chartdata);?>';
		$(document).ready(function() {
			App.init();
			Profit.init();
			/*$('#dailydata').DataTable();
			$('#weeklydata').DataTable();
			$('#monthlydata').DataTable();
			$('#yearlydata').DataTable();*/
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


$("font").click(function(){
	$('font').removeClass('btn btn-small btn-danger');
	$('font').addClass('btn btn-small btn-success');
	var id = $(this).attr('id');
    $("#"+id).removeClass('');
    $("#"+id).addClass('btn btn-small btn-danger');
});



/*function myFunction(currency,type) {
	$('#dailydata').DataTable().destroy();
	$('#monthlydata').DataTable().destroy();
	$('#yearlydata').DataTable().destroy();
	$('#weeklydata').DataTable().destroy();
	  
    $.get(admin_url+"admin/coin_profit_report_ajax/"+currency+'/'+type,function(output){
	  var output = JSON.parse(output);
	  $('#dailydata').DataTable();
	  $('#monthlydata').DataTable();
	  $('#weeklydata').DataTable();
	  $('#yearlydata').DataTable();
	  $('.daily_profit').html(output.input_daily);
	  $('.weekly_profit').html(output.input_weekly);
	  $('.monthly_profit').html(output.input_monthly);
	  $('.yearly_profit').html(output.input_yearly);


	  $('#daily_currency_name').html(output.currency_name);
	  $('#weekly_currency_name').html(output.currency_name);
	  $('#monthly_currency_name').html(output.currency_name);
	  $('#yearly_currency_name').html(output.currency_name);
    });
}*/

var handleInteractiveChart = function() {
    "use strict";

    function e(e, t, n) {
        $('<div id="tooltip" class="flot-tooltip">' + n + "</div>").css({
            top: t - 45,
            left: e - 55
        }).appendTo("body").fadeIn(200)
    }
    if ($("#interactive-chart").length !== 0) {
		 var obj = JSON.parse(chartdata);
		 var t=obj[1];
		 var n=obj[2];
		 var r=obj[0];
        $.plot($("#interactive-chart"), [{
            data: t,
            label: "Profit",
            color: 'blue',
            lines: {
                show: true,
                fill: false,
                lineWidth: 2
            },
            points: {
                show: true,
                radius: 3,
                fillColor: "#fff"
            },
            shadowSize: 0
        }], {
            xaxis: {
                ticks: r,
                tickDecimals: 0,
                tickColor: "#ddd"
            },
            // yaxis: {
                // ticks: 10,
                // tickColor: "#ddd",
                // min: 0,
                // max: 10
            // },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#ddd",
                borderWidth: 1,
                backgroundColor: "#fff",
                borderColor: "#ddd"
            },
            legend: {
                labelBoxBorderColor: "#ddd",
                margin: 10,
                noColumns: 1,
                show: true
            }
        });
        var i = null;
        $("#interactive-chart").bind("plothover", function(t, n, r) {
            $("#x").text(n.x.toFixed(2));
            $("#y").text(n.y.toFixed(2));
            if (r) {
                if (i !== r.dataIndex) {
                    i = r.dataIndex;
                    $("#tooltip").remove();
                    var s = r.datapoint[1];
                    var o = r.series.label + " " + s;
                    e(r.pageX, r.pageY, o)
                }
            } else {
                $("#tooltip").remove();
                i = null
            }
            t.preventDefault()
        })
    }
};

var Profit = function() {
    "use strict";
    return {
        init: function() {
            handleInteractiveChart();
        }
    }
}()
</script>