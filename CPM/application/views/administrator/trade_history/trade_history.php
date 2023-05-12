<?php $tradepairInfo = $this->common_model->getTableData("trade_pairs",array('status'=>1))->result();

// echo "<pre>";print_r($curInfo);


?>

<link href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css" rel="stylesheet">
<style type="text/css">
div.dt-buttons {
margin-left: 10px;
}
.buttons-excel {
color: #fff !important;
background: #348fe2 !important;
border-color: #348fe2 !important;
}
select.form-control{
    display: inline;
    width: 200px;
    margin-left: 25px;
  }
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
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="<?php echo admin_url();?>">Home</a></li>
				<li class="active"><?php echo ucfirst($view); ?> Trade History</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?php echo ucfirst($view); ?> Trade History <!--<small>header small text goes here...</small>--></h1>
			<p class="text-right m-b-10">
								
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
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title"><?php echo ucfirst($view); ?> Trade History</h4>
                        </div>
					<?php if($view=='buy'){ ?>
                        <div class="panel-body">
                       <br/>
                            <div class="table-responsive">
                                <div class="category-filter">
                                  <select id="buyFilter" class="form-control">
                                    <option value="">Show All</option>
                                    <?php foreach ($tradepairInfo as $key => $pair) {
                                        $from = getcurrencydetail($pair->from_symbol_id);
                                        $to = getcurrencydetail($pair->to_symbol_id);
                                    ?>
                                        <option value="<?=$from->currency_symbol.'-'.$to->currency_symbol?>"><?=$from->currency_symbol.'-'.$to->currency_symbol?></option>
                                    <?php }?>

                                  </select>
                                </div>
                                <table id="datas-table" class="table table-striped table-bordered" >
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Date & Time</th>
                                        <th class="text-center">User Email</th>
                                        <th class="text-center">Pair</th>
                                        <th class="text-center">Amount</th>
										<th class="text-center">Price</th>
										<th class="text-center">Fee</th>
										<th class="text-center">Total</th>
										<th class="text-center">Type</th>
										<th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;"></tbody>

                                </table>
                              
                            </div>
                        </div>
					<?php } else { ?>
						<div class="panel-body">
					<br/>
                            <div class="table-responsive">
                                <div class="category-filter">
                                  <select id="sellFilter" class="form-control">
                                    <option value="">Show All</option>
                                    <?php foreach ($tradepairInfo as $key => $pair) {
                                        $from = getcurrencydetail($pair->from_symbol_id);
                                        $to = getcurrencydetail($pair->to_symbol_id);
                                    ?>
                                        <option value="<?=$from->currency_symbol.'-'.$to->currency_symbol?>"><?=$from->currency_symbol.'-'.$to->currency_symbol?></option>
                                    <?php }?>

                                  </select>
                                </div>
                                <table id="datas-tables" class="table table-striped table-bordered" id="sell">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Date & Time</th>
                                        <th class="text-center">User Email</th>
                                        <th class="text-center">Pair</th>
                                        <th class="text-center">Amount</th>
										<th class="text-center">Price</th>
										<th class="text-center">Fee</th>
										<th class="text-center">Total</th>
										<th class="text-center">Type</th>
										<th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									
								
                                    </tbody>
                                </table>
                               
                            </div>
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
	<script src="<?php echo admin_source();?>plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/ckeditor/ckeditor.js"></script>
	
	<script src="<?php echo admin_source();?>plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo admin_source();?>plugins/gritter/js/jquery.gritter.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.time.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.resize.min.js"></script>



    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <!-- <script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script>  -->
<script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>

	<script src="<?php echo admin_source();?>js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>js/apps.min.js"></script>

 <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>


<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>


<!-- 
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> -->
   <!--  <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script> -->

	
	<!-- ================== END PAGE LEVEL JS ================== -->

	<script>
		$(document).ready(function() {
			App.init();
		});
		 /*$(document).ready(function() {
         $.fn.dataTableExt.sErrMode = 'throw';
         $('#buy').DataTable();
         $('#sell').DataTable();

        } );*/

		var admin_url='<?php echo admin_url(); ?>';
		
$(document).ready(function() {
    var table = $('#datas-table').DataTable( {
    	"responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        dom: 'lBfrtip',
        buttons: [
        {extend: 'excel', text: 'Export Excel'}
        ],
        "ajax": admin_url+"trade_history/buy_ajax"
    });
    var table1 = $('#datas-tables').DataTable( {
    	"responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        dom: 'lBfrtip',
        buttons: [
        {extend: 'excel', text: 'Export Excel'}
        ],
        "ajax": admin_url+"trade_history/sell_ajax"

    });


    // var table = $('#datas-table').DataTable();

    $("#datas-table_filter.dataTables_filter").append($("#buyFilter"));
    $("#datas-tables_filter.dataTables_filter").append($("#sellFilter"));

    
    // var categoryIndex = 0;
    // $("#datas-table th").each(function (i) {
    //     if ($($(this)).html() == "Pair") {

    //         // console.log( $($(this)).html() )
    //       categoryIndex = i; return false;
    //     }
    // });

    $("#buyFilter").change(function (e) {
        pair=this.value;
        // table.draw();
        table.ajax.url(admin_url+"trade_history/buy_tradepair_ajax/"+pair).load();
    });
    $("#sellFilter").change(function (e) {
        pair1=this.value;
        console.log(admin_url+"trade_history/sell_tradepair_ajax/"+pair1)
        // table.draw();
        table1.ajax.url(admin_url+"trade_history/sell_tradepair_ajax/"+pair1).load();
    });
    table.draw();

} );
        



	</script>
	 <script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>