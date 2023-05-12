<?php 
$this->load->view('front/common/header');
?>



<div class=" cpm_mdl_cnt  ">
						<div class="container">
							<div class="cpm_hd_text   text-center">Support Ticket</div>

						    <?php
                                $attributes=array('id'=>'support_form',"autocomplete"=>"off","class"=>"mt-4");
                                $action = front_url() . 'support';
                                echo form_open_multipart($action,$attributes);
                            ?>
							<div class="row">


								<!-- <iframe frameborder="1" width="420" height="345" src="https://spiegeltechnologies.com"></iframe>  -->



								
								<div class="col-md-12 m-auto">
									<div class="cpm_log_set bx">
										<div class="row">
											<div class="col-lg-6 col-md-12">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Subject</div>
													<input type="text"  class="cpm_log_frm_s_input" id="subject" name="subject">
		
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Category</div>
														  <select name="category" class="cpm_log_frm_s_input"  id="category">
			                                                <?php foreach ($category as $category_value) { 
			                                                    ?>
			                                                        <option value="<?php echo $category_value->id; ?>"><?php echo ($category_value->name); ?></option>             
			                                                <?php } ?>

			                                        </select>
						
													
													</div>
											</div>

											
		
											<div class="col-lg-12 col-md-12">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Message</div>
													<textarea class="cpm_log_frm_s_input"  id="message" name="message" style="height: 100px; line-height: 1.3;"></textarea>
												</div>
											</div>
											<div class="col-lg-3 ml-auto" >
											<button class="cpm_log_frm_btn" type="submit"><i class="ti-lock"></i>Submit</button>	</div>
		
										</div>
									</div>
								</div>
							</div>
							<?php
		                      echo form_close();
		                    ?>

		                     <div class="cpm_rep_bdy" >
							  <div class="table-responsive ">
							  <div class="cpm_repo_tbl_out ">
	
								<table class="table cpm_repo_tbl datatable">
									<thead >
									  <tr>
										<th scope="col">Ticket ID</th>
										<th scope="col">Date & Time</th>
										<th scope="col">Subject</th>
										<th scope="col">Status</th>
									   
									  </tr>
									</thead>
									<tbody>
										     <?php
                                if(isset($support) && !empty($support))
                                {
                                    $a=0;
                                    $username = UserName($this->session->userdata('user_id'), $prefix.'username');
									foreach(array_reverse($support) as $support_list)
                                    {
                                        $a++;
                                        if($support_list->close==0){
                                            $ticket_type = "open-black";
                                        }else{
                                            $ticket_type = "close-red";
                                        }
                                        ?>
									  <tr>
										<td class="address"> <div class="cpm_repo_tbl_stat cpm_repo_stat_danger"><?php echo $support_list->ticket_id;?></div></td>
                                            <td><?php echo date("m/d/Y h:i a",$support_list->created_on);?></td>
                                            <td><?php echo ucfirst($support_list->subject); ?></td>
                                            <td class="<?php echo $ticket_type; ?>"><div class="cpm_repo_tbl_stat">
                                              <?php
                                              if($support_list->close==0){
                                                echo '<a style="color:#fff;" class="btn btn-sm btn-clr-blue waves-effect waves-light"  href='.base_url().'support_reply/'.$support_list->ticket_id.'>'.$this->lang->line('Open').'</a>';
                                              }
                                              else{
                                                echo "Closed";                   
                                              }
                                              ?></div>
                                            </td>
										
									  </tr>
									  <?php } } ?>
									
									</tbody>
								  </table>
								  
							  </div>
							  </div>
						  </div>


						</div>
					</div>
<?php 
$this->load->view('front/common/footer');
?>
<script type="text/javascript">
	 $('#support_form').validate({
        rules: {
            subject: {
                required: true
            },
            message: {
                required: true
            }
        },
        messages: {
            subject: {
                required: "Please enter subject"
            },
            message: {
                required: "Please enter message"
            }
        },
		invalidHandler: function(form, validator) {
		if (!validator.numberOfInvalids())
		{
		return;
		}
		else
		{
		var error_element=validator.errorList[0].element;
		error_element.focus();
		}
		},
		highlight: function (element) {
		$(element).parent().addClass('fail_vldr')
		},
		unhighlight: function (element) {
		$(element).parent().removeClass('error');
		$(element).parent().removeClass('fail_vldr');
		},
		submitHandler: function(form)
		{
			var $form = $(form);
         	form.submit();
		}	

    });
</script>