<?php 
$count_result =$this->common_model->getTableData('users', array('verify_level2_status'=>'Pending','verified'=>1))->num_rows();
if($count_result!=0){
?>
<li class="dropdown messages-menu">
 	<a href="<?php echo admin_url(); ?>users/verification" class="dropdown-toggle">
			<!-- <i class="fa fa-envelope-o"></i> --><div style="color: #fff;">KYC verfication</div>
			<span class="label label-success"><?php echo $count_result; ?></span>
	</a>
</li>
<?php } ?>
