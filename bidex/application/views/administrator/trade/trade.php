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

				<li class="active">Trade</li>

			</ol>

			<!-- end breadcrumb -->

			<!-- begin page-header -->

			<h1 class="page-header">P2PTrade Management <!--<small>header small text goes here...</small>--></h1>

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

                                <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->

                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>

                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>

                            </div>

                            <h4 class="panel-title">P2PTrade Management</h4>

                        </div>

					<?php if($view=='view_all'){ ?>

                        <div class="panel-body">

                            <div class="table-responsive">

                                <table id="data-table" class="table table-striped table-bordered">

                                    <thead>

                                        <tr>

                                           <th class="text-center">S.No</th>

                                           <th class="text-center">Cryptos</th>

										<th class="text-center">Trade id</th>

										<th class="text-center">Seller</th>

										<th class="text-center">Country</th>

                                         <th class="text-center">Currency</th>

                                        <th class="text-center">Payment Method</th>

                                        <!--<th class="text-center">Trade Type</th>-->

                                        <th class="text-center">Trade Status</th>

                                        <th class="text-center">Trade History</th>

										<th class="text-center">Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>

									<?php

								if ($trade->num_rows() > 0) {

									$i = 1;

									foreach($trade->result() as $result) {

                                        $userdetails = $this->common_model->getTableData('users',array('id'=>$result->user_id))->row();

                                        $countrydetails = $this->common_model->getTableData('countries',array('id'=>$result->country))->row();

                                        $servicedetails = $this->common_model->getTableData('service',array('id'=>$result->payment_method))->row();

                                        $cryptocurrency=$this->common_model->getTableData('currency',array('id'=>$result->cryptocurrency))->row();

										echo '<tr>';

										echo '<td>' . $i . '</td>';

                                        echo '<td>' .$cryptocurrency->currency_symbol . '</td>';

										echo '<td>' . $result->tradeid . '</td>';

										echo '<td>' . $userdetails->lbc_username .'</td>';

                                        echo '<td>' . $countrydetails->country_name .'</td>';

                                        echo '<td>' . $result->currency .'</td>';

                                        echo '<td>' . $servicedetails->service_name .'</td>';

                                        //echo '<td>' . $result->type .'</td>';

                                        echo '<td>' . $result->tradestatus .'</td>';

										/*if ($result->status == 1) {

											$status = '<label class="label label-info">Activated</label>';

											$extra = array('title' => 'De-activate this testimonials');

											$changeStatus = anchor(admin_url().'testimonials/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);

										} else {

											$status = '<label class="label label-danger">De-Activated</label>';

											$extra = array('title' => 'Activate this testimonials');

											$changeStatus = anchor(admin_url().'testimonials/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);

										}*/

										echo '<td><a href="' . admin_url() . 'trade/trade_history/' . $result->id . '" title="Edit this testimonial"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;</td>';

										echo '<td>';

										//echo $changeStatus . '&nbsp;&nbsp;&nbsp;';

										echo '<a href="' . admin_url() . 'trade/view/' . $result->id . '" title="Edit this testimonial"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';

										//echo '<a href="' . admin_url() . 'trade/delete/' . $result->id . '" title="Delete this testimonial"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';

										echo '</td>';

										echo '</tr>';

										$i++;

									}					

								} else {

									echo '<tr><td></td><td></td><td class="text-center">No Trade added yet!</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

								}

								?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

					<?php }  else if($view=='selltrade'){ ?>  

                        
                                     <div class="panel-body">

                            <div class="table-responsive">

                                <table id="data-table" class="table table-striped table-bordered">

                                    <thead>

                                        <tr>

                                           <th class="text-center">S.No</th>

                                           <th class="text-center">Cryptos</th>

                                        <th class="text-center">Trade id</th>

                                        <th class="text-center">Seller</th>

                                        <th class="text-center">Country</th>

                                         <th class="text-center">Currency</th>

                                        <th class="text-center">Payment Method</th>

                                        <!--<th class="text-center">Trade Type</th>-->

                                        <th class="text-center">Trade Status</th>

                                        <th class="text-center">Trade History</th>

                                        <th class="text-center">Action</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    <?php

                                if ($selltrade->num_rows() > 0) {

                                    $i = 1;

                                    foreach($selltrade->result() as $result) {

                                        $userdetails = $this->common_model->getTableData('users',array('id'=>$result->user_id))->row();

                                        $countrydetails = $this->common_model->getTableData('countries',array('id'=>$result->country))->row();

                                        $servicedetails = $this->common_model->getTableData('service',array('id'=>$result->payment_method))->row();

                                        $cryptocurrency=$this->common_model->getTableData('currency',array('id'=>$result->cryptocurrency))->row();

                                        echo '<tr>';

                                        echo '<td>' . $i . '</td>';

                                        echo '<td>' . $cryptocurrency->currency_symbol . '</td>';

                                        echo '<td>' . $result->tradeid . '</td>';

                                        echo '<td>' . $userdetails->lbc_username .'</td>';

                                        echo '<td>' . $countrydetails->country_name .'</td>';

                                        echo '<td>' . $result->currency .'</td>';

                                        echo '<td>' . $servicedetails->service_name .'</td>';

                                        //echo '<td>' . $result->type .'</td>';

                                        echo '<td>' . $result->tradestatus .'</td>';

                                        /*if ($result->status == 1) {

                                            $status = '<label class="label label-info">Activated</label>';

                                            $extra = array('title' => 'De-activate this testimonials');

                                            $changeStatus = anchor(admin_url().'testimonials/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);

                                        } else {

                                            $status = '<label class="label label-danger">De-Activated</label>';

                                            $extra = array('title' => 'Activate this testimonials');

                                            $changeStatus = anchor(admin_url().'testimonials/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);

                                        }*/

                                        echo '<td><a href="' . admin_url() . 'trade/trade_history/' . $result->id . '" title="Edit this testimonial"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;</td>';

                                        echo '<td>';

                                        //echo $changeStatus . '&nbsp;&nbsp;&nbsp;';

                                        echo '<a href="' . admin_url() . 'trade/view/' . $result->id . '" title="Edit this testimonial"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';

                                        //echo '<a href="' . admin_url() . 'trade/delete/' . $result->id . '" title="Delete this testimonial"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';

                                        echo '</td>';

                                        echo '</tr>';

                                        $i++;

                                    }                   

                                } else {

                                    echo '<tr><td></td><td></td><td class="text-center">No Trade added yet!</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
                                }

                                ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>




                    <?php }

                    else if($view=='trade_history'){ ?>

                        <div class="panel-body">

                            <div class="table-responsive">

                                <table id="data-table" class="table table-striped table-bordered">

                                    <thead>

                                        <tr>

                                           <th class="text-center">S.No</th>

                                           <th class="text-center">Date Time</th>

                                        <th class="text-center">Trade id</th>

                                        <th class="text-center">Buyer</th>

                                        <th class="text-center">Amount of BTC</th>

                                         <th class="text-center">Message</th>

                                        <th class="text-center">Payment Amount</th>

                                        <!--<th class="text-center">Trade Type</th>-->

                                        <th class="text-center">Escrow Status</th>

                                        <th class="text-center">Payment Status</th>

                                        <!--<th class="text-center">Action</th>-->

                                        </tr>

                                    </thead>

                                    <tbody>
                                      <?php 
                                       
                                      
                                       $j=1;
                                        foreach ($historytrade as $tradehistory) {
                                           
                                          $userdetails = $this->common_model->getTableData('users',array('id'=>$tradehistory->buyerid))->row();

                                          $tradedetails=$this->common_model->getTableData('trade',array('id'=>$tradehistory->tradeid))->row();

                                          if($tradehistory->paymentconfirm=='1'){

                                                $paysstatus="completed"; 

                                          } else {

                                            $paysstatus="pending";
                                          }

                                          
                                          echo '<tr>';

                                        echo '<td>' . $j . '</td>';

                                        echo '<td>' . date('d-m-Y h:i:s',strtotime($tradehistory->datetime)) . '</td>';

                                        echo '<td>' . $tradedetails->tradeid . '</td>';

                                        echo '<td>' . $userdetails->lbc_username . '</td>';

                                        echo '<td>' . $tradehistory->amtofbtc . '</td>';

                                         echo '<td>' . $tradehistory->message . '</td>';

                                         echo '<td>' . $tradehistory->paymentamount . '</td>';

                                         echo '<td>' . $tradehistory->escrowstatus . '</td>';

                                         echo '<td>' . $paysstatus . '</td>';                               
 
                                       $j++; }


                                       
                                        ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    <?php } else if($view=='add'){ ?>

					<div class="panel-body">

						<?php $attributes=array('class'=>'form-horizontal','id'=>'trade');

				echo form_open_multipart($action,$attributes); ?>

                                <fieldset>

                                	<div class="form-group">

                                <label class="col-md-2 control-label">Laguage</label>

                                <div class="col-md-8">



                                    <select id="lang" name="lang" class="form-control" onchange="language();">

                                                <option value="1" >english</option>

                                                <!--<option value="2" >chinese</option>

                                                <option value="3" >russian</option>

                                                <option value="4" >spanish</option>-->

                                            </select>



                                </div>

                        </div>

                         <div id="english">

									<div class="form-group">

                                        <label class="col-md-4 control-label">Name</label>

                                        <div class="col-md-4">

										<input type="text" name="english_name" id="english_name" class="form-control" placeholder="Name"  />

                                        </div>

                                    </div>



									<div class="form-group">

                                        <label class="col-md-4 control-label">Position</label>

                                        <div class="col-md-4">

										<input type="text" name="english_position" id="english_position" class="form-control" placeholder="Position"/>

                                        </div>

                                    </div>



                                    <div class="form-group">

                                    <label class="col-md-4 control-label">Comments</label>

                                        <div class="col-md-4">

										<textarea name="english_comments" id="english_comments" class="form-control" placeholder="Comments" rows="5"></textarea>

                                        </div>

                                    </div>

                                </div>



                                <!--<div id="chinese" class="samelang">

									<div class="form-group">

                                        <label class="col-md-4 control-label">Name</label>

                                        <div class="col-md-4">

										<input type="text" name="name" id="name" class="form-control" placeholder="Name"  />

                                        </div>

                                    </div>



									<div class="form-group">

                                        <label class="col-md-4 control-label">Position</label>

                                        <div class="col-md-4">

										<input type="text" name="position" id="position" class="form-control" placeholder="Position"  />

                                        </div>

                                    </div>



                                    <div class="form-group">

                                        <label class="col-md-4 control-label">Comments</label>

                                        <div class="col-md-4">

										<textarea name="comments" id="comments" class="form-control" placeholder="Comments" rows="5"></textarea>

                                        </div>

                                    </div>

                                </div>-->





                                    <div class="form-group">

							            <label class="col-md-4 control-label">Image</label>

							            <div class="col-md-4">

							                <input type="file" name="image" id="image" />

							            </div>

							        </div>



									<div class="form-group">

                                        <label class="col-md-4 control-label">Status</label>

                                        <div class="col-md-4">

										<select id="status" name="status" class="form-control">

										<option value="1">Active</option>

										<option value="0">De-active</option>

										</select>

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <div class="col-md-8 col-md-offset-4">

                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>

                                        </div>

                                    </div>

                                </fieldset>

                            </form>

                        </div>

					<?php }else if($view=='edit'){ ?>

					<div class="panel-body">

						<?php $attributes=array('class'=>'form-horizontal','id'=>'testimonials');

				echo form_open_multipart($action,$attributes); ?>

                                <fieldset>

                        <div class="form-group">

                                <label class="col-md-4 control-label">Laguage</label>

                                <div class="col-md-4">

                                    <select id="lang" name="lang" class="form-control" onchange="language();">

                                                <option value="1" >english</option>

                                                <!--<option value="2" >chinese</option>

                                                 <option value="3" >russian</option>

                                                <option value="4" >spanish</option> -->

                                    </select>

                                </div>

                        </div>



                                	 <div id="english">



                                	<div class="form-group">

                                        <label class="col-md-4 control-label">Name</label>

                                        <div class="col-md-4">

										<input type="text" name="english_name" id="name" class="form-control" placeholder="Name" value="<?php echo $testimonials->english_name; ?>" />

                                        </div>

                                    </div>

									<div class="form-group">

                                        <label class="col-md-4 control-label">Position</label>

                                        <div class="col-md-4">

										<input type="text" name="english_position" id="position" class="form-control" placeholder="Position" value="<?php echo $testimonials->english_position; ?>" />

                                        </div>

                                    </div>

                                    

                                     <div class="form-group">

                                        <label class="col-md-4 control-label">Comments</label>

                                        <div class="col-md-4">

										<textarea name="english_comments" id="comments" class="form-control" rows="5"><?php echo $testimonials->english_comments; ?></textarea>

                                        </div>

                                    </div>

                                </div>



                                 <div id="chinese" class="samelang">

                                	<div class="form-group">

                                        <label class="col-md-4 control-label">Name</label>

                                        <div class="col-md-4">

										<input type="text" name="chinese_name" id="name" class="form-control" placeholder="Name" value="<?php echo $testimonials->chinese_name; ?>" />

                                        </div>

                                    </div>

									<div class="form-group">

                                        <label class="col-md-4 control-label">Position</label>

                                        <div class="col-md-4">

										<input type="text" name="chinese_position" id="position" class="form-control" placeholder="Position" value="<?php echo $testimonials->chinese_position; ?>" />

                                        </div>

                                    </div>

                                    

                                     <div class="form-group">

                                        <label class="col-md-4 control-label">Comments</label>

                                        <div class="col-md-4">

										<textarea name="chinese_comments" id="comments" class="form-control" rows="5"><?php echo $testimonials->chinese_comments; ?></textarea>

                                        </div>

                                    </div>

                                </div>





                                     <div class="form-group">

							            <label class="col-md-4 control-label">Image</label>

							            <div class="col-md-4">

							                <input type="file" name="image" id="image" />

							                <?php $im = $trade->image; ?>

							                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $testimonials->image; ?>" />

							                <?php if($trade->image!='') { ?>

							                <img src="<?php echo $im; ?>" style="width:65px;height:65px;" />

							                <?php } ?>

							            </div>

							        </div>

									<div class="form-group">

                                        <label class="col-md-4 control-label">Status</label>

                                        <div class="col-md-4">

										<select id="status" name="status" class="form-control">

										<option <?php if($trade->status==1){echo 'selected';}?> value="1">Active</option>

										<option <?php if($trade->status==0){echo 'selected';}?> value="0">De-active</option>

										</select>

                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <div class="col-md-8 col-md-offset-4">

                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>

                                        </div>

                                    </div>



                                </fieldset>

                            </form>

                        </div>

					    <?php } elseif($view=='view_trade'){
                          
                          $userdetails = $this->common_model->getTableData('users',array('id'=>$trade->user_id))->row();

                                        $countrydetails = $this->common_model->getTableData('countries',array('id'=>$trade->country))->row();

                                        $servicedetails = $this->common_model->getTableData('service',array('id'=>$trade->payment_method))->row();

                            ?>

                           <div class="panel-body">

                        <form action="#" class="form-horizontal" id="users" enctype="multipart/form-data" method="post" accept-charset="iso-8859-1">

                                <fieldset style="margin-left: 70px;font-size: 14px;">
                                  
                                  <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Trade Type</strong></label>

                                        <div class="col-md-6 control-label text-left">

                                        <?php echo $trade->type;?>
                                        </div>
                                 </div>
                                
                                <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Trade Id</strong></label>

                                        <div class="col-md-6 control-label text-left">
                                       <?php echo $trade->tradeid;?>
                                        
                                        </div>
                                 </div>

                                 <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Seller Name</strong></label>

                                        <div class="col-md-6 control-label text-left">

                                       <?php echo $userdetails->lbc_username;?>
                                        </div>
                                 </div>


                                 <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Country</strong></label>

                                        <div class="col-md-6 control-label text-left">
                                         <?php echo $countrydetails->country_name;?>
                                        
                                        </div>
                                 </div>

                                 <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Currency</strong></label>

                                        <div class="col-md-6 control-label text-left">

                                        <?php echo $trade->currency;?>
                                        </div>
                                 </div>


                                 <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Payment Method</strong></label>

                                        <div class="col-md-6 control-label text-left">

                                        <?php echo $servicedetails->service_name;?>
                                        </div>
                                 </div>

                                 <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Trade Amount</strong></label>

                                        <div class="col-md-6 control-label text-left">
                                          
                                          <?php echo $trade->price;?> <?php echo $trade->currency;?>
                                       
                                        </div>
                                 </div>

                                 

                                 <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Trade Limit</strong></label>

                                        <div class="col-md-6 control-label text-left">
                                            
                                            <?php echo $trade->minimumtrade;?> <?php echo $trade->currency;?> -  <?php echo $trade->maximumtrade;?> <?php echo $trade->currency;?>
                                        
                                        </div>
                                 </div>

                                 <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Comission</strong></label>

                                        <div class="col-md-6 control-label text-left">

                                        <?php echo $trade->comission;?> %
                                        </div>
                                 </div>

                                 <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Offer Time</strong></label>

                                        <div class="col-md-6 control-label text-left">

                                        <?php echo $trade->offertime;?> minites
                                        </div>
                                 </div>

                                 <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Additional Info</strong></label>

                                        <div class="col-md-6 control-label text-left">
                                        
                                         <?php echo $trade->addtional_info;?>
                                        
                                        </div>
                                 </div>

                                 <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>Terms & Conditions</strong></label>

                                        <div class="col-md-6 control-label text-left">
                                           
                                           <?php echo $trade->terms_conditions;?>
                                        
                                        </div>
                                 </div>



                                         <div class="form-group">

                                        <label class="col-md-2 control-label"><strong>DELETE</strong></label>

                                     <div class="col-md-6 control-label text-left">


                                        
                            <a  href="<?php echo admin_url();?>/trade/delete/<?php echo $trade->id;?>"  class="btn btn-danger">Delete Trade</a>
                                        
                                        </div>
                                 </div>




                                </fieldset>

                            </form>

                        </div>

                        <?php }?>


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

	<!-- ================== END PAGE LEVEL JS ================== -->

	<script>

	$(document).ready(function() {

		$('#testimonials').validate({

			rules: {

				english_name: {

					required: true

				},

				english_position: {

					required: true

				},

				english_comments: {

					required: true

				},

				image: {

				//	required: true

				}

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

	<script>

      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

      })(window,document,'script','<?php echo admin_source()."www.google-analytics.com/analytics.js";?>','ga');

    

      ga('create', 'UA-53034621-1', 'auto');

      ga('send', 'pageview');

    </script>

     <style>

        .samelang {

            display: none;

        }

    </style>

     <SCRIPT>

        function language() 

        {

            var x = document.getElementById("lang").value;

            if (x == 1) {

                $('#chinese').hide();

                $('#spanish').hide();

                $('#russian').hide();

                $('#english').show();

            } else if (x == 2) {

                $('#english').hide();

                $('#spanish').hide();

                $('#russian').hide();

                $('#chinese').show();



            } else if (x == 3) {

                $('#spanish').hide();

                $('#english').hide();

                $('#chinese').hide();

                $('#russian').show();



            } else {

                $('#english').hide();

                $('#russian').hide();

                $('#chinese').hide();

                $('#spanish').show();



            }

        }



        

    </SCRIPT>