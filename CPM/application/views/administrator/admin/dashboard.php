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
			<!-- <ol class="breadcrumb pull-right">
				<li><a href="<?php echo admin_url();?>">Home</a></li>
				<li class="active">Dashboard</li>
			</ol> -->
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Dashboard <!--<small>header small text goes here...</small>--></h1>
			<!-- end page-header -->
			
			<!-- begin row -->
			<div class="row">
				<!-- begin col-3 -->
				<div class="col-md-3 col-sm-6">
					<div class="widget widget-stats bg-green">
						<div class="stats-icon"><i class="fa fa-desktop"></i></div>
						<div class="stats-info">
							<h4>TOTAL USERS</h4>
							<p><?php echo $num_users; ?></p>	
						</div>
						<div class="stats-link">
							<a href="<?php echo admin_url().'users';?>">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
						</div>
					</div>
				</div>
				<!-- end col-3 -->
				<!-- begin col-3 -->
				<div class="col-md-3 col-sm-6">
					<div class="widget widget-stats bg-blue">
						<div class="stats-icon"><i class="fa fa-chain-broken"></i></div>
						<div class="stats-info">
							<h4>SELL ORDERS</h4>
							<p><?php echo $sell_orders; ?></p>	
						</div>
						<div class="stats-link">
							<a href="<?php echo admin_url().'trade_history/sell';?>">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
						</div>
					</div>
				</div>
				<!-- end col-3 -->
				<!-- begin col-3 -->
				<div class="col-md-3 col-sm-6">
					<div class="widget widget-stats bg-purple">
						<div class="stats-icon"><i class="fa fa-users"></i></div>
						<div class="stats-info">
							<h4>BUY ORDERS</h4>
							<p><?php echo $buy_orders; ?></p>	
						</div>
						<div class="stats-link">
							<a href="<?php echo admin_url().'trade_history/buy';?>">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
						</div>
					</div>
				</div>
				<!-- end col-3 -->
				<!-- begin col-3 -->
				<div class="col-md-3 col-sm-6">
					<div class="widget widget-stats bg-red">
						<div class="stats-icon"><i class="fa fa-clock-o"></i></div>
						<div class="stats-info">
							<h4>Deposit</h4>
							<p><?php echo $exchange_orders; ?></p>	
						</div>
						<div class="stats-link">
							<a href="<?php echo admin_url().'deposit/crypto_deposit';?>">View Detail <i class="fa fa-arrow-circle-o-right"></i></a>
						</div>
					</div>
				</div>
				<!-- end col-3 -->
			</div>
			<!-- end row -->
			<!-- begin row -->
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
							<h4 class="panel-title">Website Analytics (Last 7 Days)</h4>
						</div>
						<div class="panel-body">
							<div id="interactive-chart" class="height-sm"></div>
						</div>
					</div>
				</div>
				<!-- end col-12 -->
			</div>
			<div class="row">
			    <!--<div class="col-md-6">
			        <div class="panel panel-inverse" data-sortable-id="index-2">
			            <div class="panel-heading">
						<div class="panel-heading-btn">
								<a href="javascript:;" onclick="add_class_new()" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
							</div>
			                <h4 class="panel-title">Chat History</h4>
			            </div>
			            <div class="panel-body bg-silver">
                            <div data-scrollbar="true" data-height="225px">
                                <ul class="chats" id="msgList">
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="panel-footer">
                            
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" id="msgIpt" name="message" placeholder="Enter your message here.">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary btn-sm" id="pres_tx_btnSubmit" type="button">Send</button>
                                    </span>
                                </div>
                            
                        </div>
			        </div>
			        
			    </div>-->
			    <!-- end col-6 -->
				<!-- begin col-6 -->
				<div class="col-md-12">
					<div class="panel panel-inverse" data-sortable-id="index-3">
						<div class="panel-heading">
							<div class="panel-heading-btn">
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
								<!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
							</div>
							<h4 class="panel-title">Visitors User Agent</h4>
						</div>
						<div class="panel-body">
							<div id="donut-chart" class="height-sm"></div>
						</div>
					</div>
				</div>
				<!-- end col-6 -->
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
                            <h4 class="panel-title">Users Activity</h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="datas-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        	<th>S.No</th>
                                            <th>Email</th>
                                            <th>Date & Time</th>
                                            <th>IP Address</th>
                                            <th>Activity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
									/*$a=1;
									foreach($user_activity as $activity){ ?>
                                        <tr class="gradeX">
                                        <td><?php echo $a;?></td>
                                            <td><?php echo getUserEmail($activity->user_id); ?></td>
                                            <td><?php echo date('d-M-y H:i:s',$activity->date); ?></td>
                                            <td><?php echo $activity->ip_address; ?></td>
                                            <td><?php echo $activity->activity; ?></td>
                                        </tr>
									<?php $a++;}*/ ?>
                                    </tbody>
                                </table>
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
	<script>
	var chartdata='<?php echo json_encode($chartdata);?>';
	var sitevisits='<?php echo json_encode($sitevisits);?>';
	//alert(sitevisits);
	</script>
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo admin_source();?>/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.time.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.resize.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.pie.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/sparkline/jquery.sparkline.js"></script>
	<script src="<?php echo admin_source();?>/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="<?php echo admin_source();?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
	<script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo admin_source();?>js/dashboard.min.js"></script>
	<script src="<?php echo admin_source();?>js/apps.min.js"></script>
	<!-- <script src="https://cdn.firebase.com/v0/firebase.js"></script> -->
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
			Dashboard.init();
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
        "ajax": admin_url+"admin/dashboard_ajax"
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
	var user_image='<?php echo getSiteSettings('site_logo'); ?>';
	var base_url='<?php echo base_url(); ?>';
	var u = 'Admin';
     
	  
	  
	  
/*var myDataRef = new Firebase("https://coinchairs-project.firebaseio.com/");
$(document).keypress(function(e) {
if(e.which == 13 && e.target.id== 'msgIpt')
{
	var m = $('#msgIpt').val();
	if(u!=''&&m!='')
	{
		var start = new Date();
		myDataRef.push({name: u, text: m, date: start.getTime(),image: user_image});
		$('#msgIpt').val('');
	}
}
});
$('#pres_tx_btnSubmit').click(function() {
	var m = $('#msgIpt').val();
	if(u!=''&&m!='')
	{
		var start = new Date();
		myDataRef.push({name: u, text: m, date: start.getTime(),image: user_image});
		$('#msgIpt').val('');
	}
});
var dummyimg='<?php echo dummyuserImg(); ?>';
myDataRef.on('child_added', function(snapshot){
        var msg = snapshot.val();
		var start = new Date();
		var time_data=start.getTime()-10;
		var timemsg=get_date(msg.date);
		if(msg.image)
		{
			var image_user=msg.image;
		}
		else
		{
			var image_user=dummyimg;
		}
        displayMsg(msg.name, msg.text,image_user,timemsg);
});*/
function displayMsg(name, text, image, time_of_msg)
{
	if(u==name)
	{
		var class1='right';
		var class2='<a href="#" class="name"><span class="label label-primary">'+name+'</span> Me</a>';
	}
	else
	{
		var class1='left';
		var class2='<a href="javascript:;" class="name">'+name+'</a>';
	}
	//image=image.replace("base_url/", base_url);
	 var msg_text='<li class="'+class1+'"><span class="date-time">'+time_of_msg+'</span>'+class2+'<a href="javascript:;" class="image"><img alt="" src="'+image+'" /></a><div class="message">'+text+'</div></li>';
	$('#msgList').append(msg_text);
	$('#msgList')[0].scrollTop = $('#msgList')[0].scrollHeight;
}
function get_date(dt1) 
{
	var dt2   = new Date();
	var diff =(dt2.getTime() - dt1) / 1000;
	diff /= 60;
	var time_taken = Math.abs(Math.round(diff));
	if(time_taken==0)
	{
		var text = 'Just Now';
	}
	else
	{
		if(time_taken<60)
		{
			var text = time_taken+' mins ago';
		}
		else
		{
			var hours = Math.floor( time_taken / 60);
			if(hours<24)
			{
				if(hours==1)
				{
					var text = hours+' hour ago';
				}
				else
				{
					var text = hours+' hours ago';
				}
			}
			else
			{
				var days = Math.floor( hours / 24);
				if(days==1)
				{
					var text = days+' day ago';
				}
				else
				{
					var text = days+' days ago';
				}
			}
		}
	}
	return text;
}
var zoom_chat=0;
function add_class_new()
{
	if(zoom_chat==0)
	{
		$('.slimScrollDiv').css('height', '100%');
		$('.slimScrollDiv div:first-child').css('height', '100%');
		zoom_chat=1;
	}
	else
	{
		$('.slimScrollDiv').css('height', '225px');
		$('.slimScrollDiv div:first-child').css('height', '225px');
		zoom_chat=0;
	}
	
}



    </script>