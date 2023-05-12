<?php 
$count_result1 =$this->common_model->getTableData('support', array('parent_id='=>'0','status'=>'1'))->num_rows();
if($count_result1!=0){
?>
<li class="dropdown messages-menu">
 	<a href="<?php echo admin_url(); ?>support" class="dropdown-toggle">
			<!-- <i class="fa fa-envelope-o"></i> --><div style="color: #fff;">Support</div>
			<span class="label label-success"><?php echo $count_result1; ?></span>
	</a>
</li>
<?php } ?>
