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
				<li class="active">Site Settings</li>
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
                            <h4 class="panel-title">Site Settings</h4>
                        </div>
                        <div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'site_settings');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                  
									 <h4 style="text-align: center;">General Settings</h4>
                                     
                                      <div class="english">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Site Name</label>
                                        <div class="col-md-4">
                                            <input type="text" id="site_name" name="site_name" class="form-control" placeholder="Site Name" value="<?php echo $siteSettings->site_name; ?>" />
                                        </div>
                                    </div>
                                </div>

                                
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Site Email</label>
                                        <div class="col-md-4">
                                            <input type="email" name="site_email" id="site_email" class="form-control" placeholder="Site Email" value="<?php echo $siteSettings->site_email; ?>" />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Site Logo</label>
                                        <div class="col-md-4">
                                            <input type="file" name="site_logo" id="site_logo" class=""  />
                                            <div class="img_stre">
                                            <img src="<?php echo getSiteLogo(); ?>" class="img-responsive logo_im"  />
                                            </div>
                                        </div>										
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Site Favicon</label>
                                        <div class="col-md-4">
                                            <input type="file" name="site_favicon" id="site_favicon" class=""  />
                                            <div class="img_stre">
                                            <img src="<?php echo getSiteFavIcon(); ?>" class="img-responsive logo_im"  />
                                            </div>
                                        </div>                                      
                                    </div>
                                      <div class="english">
									 <div class="form-group">
                                        <label class="col-md-4 control-label">CopyRight Text</label>
                                        <div class="col-md-6">
                                            <input type="text" name="copy_right_text" id="copy_right_text" class="form-control" placeholder="CopyRight Text" value="<?php echo $siteSettings->copy_right_text; ?>" />
                                        </div>
                                    </div>
                                </div>
                                
									 <div class="form-group set-pattern-width patt-mobile-width">
                                        <label class="col-md-12 col-lg-12 col-xl-4 control-label text-center">Pattern Code</label>
                                        <div class="col-md-12 col-lg-12 col-xl-5 mobile-pad-0">
										<div id="patternContainer"></div>
                                            <input type="hidden" value="<?php echo strrev(getAdminDetails($admin_id,'code')); ?>" name="patterncode" id="patterncode" />
                                        </div>
										<label class="col-md-12 col-lg-12 col-xl-3 text-center">Change Pattern If You Need</label>
                                    </div>
                                    
                                    <h4 style="text-align: center;">Contact Settings</h4>
                                      <div class="english">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Address</label>
                                        <div class="col-md-4">
                                            <input type="text" name="address" id="address" class="form-control" placeholder="Address" value="<?php echo $siteSettings->address; ?>" />
                                        </div>
                                    </div>
                                       <div class="form-group">
                                        <label class="col-md-4 control-label">City</label>
                                        <div class="col-md-4">
                                            <input type="text" name="city" id="city" class="form-control" placeholder="City" value="<?php echo $siteSettings->city; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">State</label>
                                        <div class="col-md-4">
                                            <input type="text" name="state" id="state" class="form-control" placeholder="State" value="<?php echo $siteSettings->state; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Country</label>
                                        <div class="col-md-4">
                                            <input type="text" name="country" id="country" class="form-control" placeholder="Country" value="<?php echo $siteSettings->country; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Zip Code</label>
                                        <div class="col-md-4">
                                            <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="<?php echo $siteSettings->zip; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Contact Number</label>
                                        <div class="col-md-4">
                                            <input type="text" name="contactno" id="contactno" class="form-control" placeholder="Contact Number" value="<?php echo $siteSettings->contactno; ?>" /><span>(prefix <b>+</b> symbol added automatically and add country code with contact number)</span>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Alternative Contact Number</label>
                                        <div class="col-md-4">
                                            <input type="text" name="altcontactno" id="altcontactno" class="form-control" placeholder="Alternative Contact Number" value="<?php echo $siteSettings->altcontactno; ?>" />
                                        </div>
                                    </div>
                                </div>

                                    <!-- New code 7-5-18 end -->
                                    <!-- New code 10-5-18 -->
                                   <!--  <div class="form-group">
                                        <label class="col-md-4 control-label">Cash in/out status for verified Users</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="verify_user_cash_status" id="verify_user_cash_status">
                                            <option value="1" <?php if($siteSettings->verify_user_cash_status==1){echo 'selected';} ?>>Enable</option>
                                            <option value="0" <?php if($siteSettings->verify_user_cash_status==0){echo 'selected';} ?>>Disable</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Cash in/out status for Unverified Users</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="unverify_user_cash_status" id="unverify_user_cash_status">
                                            <option value="1" <?php if($siteSettings->unverify_user_cash_status==1){echo 'selected';} ?>>Enable</option>
                                            <option value="0" <?php if($siteSettings->unverify_user_cash_status==0){echo 'selected';} ?>>Disable</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <!-- End 10-5-18 -->


									<!-- <h4 style="text-align: center;">Google Captcha</h4>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Google Captcha Secret Key</label>
                                        <div class="col-md-4">
                                            <input type="text" name="google_captcha_secretkey" id="google_captcha_secretkey" class="form-control" placeholder="Google Captcha Secret Key" value="<?php echo $siteSettings->google_captcha_secretkey; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Google Captcha SiteKey</label>
                                        <div class="col-md-4">
                                            <input type="text" name="google_captcha_sitekey" id="google_captcha_sitekey" class="form-control" placeholder="Google Captcha SiteKey" value="<?php echo $siteSettings->google_captcha_sitekey; ?>" />
                                        </div>
                                    </div> -->
                                    <h4 style="text-align: center;">Social Links</h4>

                                   <!--  <div class="form-group">
                                        <label class="col-md-4 control-label">Status</label>
                                        <div class="col-md-4">
                                            <select data-live-search="true" class="selectpicker form-control" name="social_profile" id="social_profile">
                                            <option value="1" <?php if($siteSettings->social_profile==1){echo 'selected';} ?>>Enable</option>
                                            <option value="0" <?php if($siteSettings->social_profile==0){echo 'selected';} ?>>Disable</option>
                                            </select>
                                        </div>
                                    </div> -->

                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Facebook Url</label>
                                        <div class="col-md-4">
                                            <input type="url" name="facebooklink" id="facebooklink" class="form-control" placeholder="Facebook Url" value="<?php echo $siteSettings->facebooklink; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Twitter Url</label>
                                        <div class="col-md-4">
                                            <input type="url" name="twitterlink" id="twitterlink" class="form-control" placeholder="Twitter Url" value="<?php echo $siteSettings->twitterlink; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Linkedin Url</label>
                                        <div class="col-md-4">
                                            <input type="url" name="linkedin_link" id="linkedin_link" class="form-control" placeholder="Linkedin Url" value="<?php echo $siteSettings->linkedin_link; ?>" />
                                        </div>
                                    </div>

                                  <div class="form-group">
                                        <label class="col-md-4 control-label">Telegram Url</label>
                                        <div class="col-md-4">
                                            <input type="url" name="telegramlink" id="telegramlink" class="form-control" placeholder="Telegram Url" value="<?php echo $siteSettings->telegramlink; ?>" />
                                        </div>
                                    </div>

                                     <!-- <div class="form-group">
                                        <label class="col-md-4 control-label">Pinterest Url</label>
                                        <div class="col-md-4">
                                            <input type="url" name="pinterest_link" id="pinterest_link" class="form-control" placeholder="Pinterest Url" value="<?php echo $siteSettings->pinterest_link; ?>" />
                                        </div>
                                    </div>-->

                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Instagram Url</label>
                                        <div class="col-md-4">
                                            <input type="url" name="instagram_link" id="instagram_link" class="form-control" placeholder="Instagram Url" value="<?php echo $siteSettings->instagram_link; ?>" />
                                        </div>
                                    </div>

                                   <!--<div class="form-group">
                                        <label class="col-md-4 control-label">Dribble Url</label>
                                        <div class="col-md-4">
                                            <input type="url" name="dribble_link" id="dribble_link" class="form-control" placeholder="Dribble Url" value="<?php echo $siteSettings->dribble_link; ?>" />
                                        </div>
                                    </div>-->

                                 

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Youtube Url</label>
                                        <div class="col-md-4">
                                            <input type="url" name="youtube_link" id="youtube_link" class="form-control" placeholder="Youtube Url" value="<?php echo $siteSettings->youtube_link; ?>" />
                                        </div>
                                    </div>

                                   <div class="form-group">
                                        <label class="col-md-4 control-label">Medium Url</label>
                                        <div class="col-md-4">
                                            <input type="url" name="medium_link" id="medium_link" class="form-control" placeholder="Medium Url" value="<?php echo $siteSettings->medium_link; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Reddit Url</label>
                                        <div class="col-md-4">
                                            <input type="url" name="reddit_link" id="reddit_link" class="form-control" placeholder="Reddit Url" value="<?php echo $siteSettings->reddit_link; ?>" />
                                        </div>
                                    </div>
                                     <h4 style="text-align: center;">SMTP Settings</h4> 
                                     <!--<div class="form-group">
                                        <label class="col-md-4 control-label">Limit</label>
                                        <div class="col-md-4">
                                            <input type="text" name="withdraw_limit_1" id="withdraw_limit_1" class="form-control" placeholder="Withdraw Limit 1" value="<?php echo $siteSettings->withdraw_limit_1; ?>" />
                                        </div>
                                    </div>--> 


                                    <!-- New code for smtp settings 1-6-18 -->
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">SMTP User Id:</label>
                                        <div class="col-md-4">
                                            <input type="email" name="smtp_user" class="form-control" value="<?php echo decryptIt($siteSettings->smtp_email); ?>" id="smtp_user" autocomplete="off" required>
                                        </div>    
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">SMTP Password:</label>
                                        <div class="col-md-4">
                                            <input type="text" name="smtp_pass" class="form-control" value="<?php echo decryptIt($siteSettings->smtp_password); ?>" id="smtp_pass" autocomplete="off" required >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">SMTP Host:</label>
                                        <div class="col-md-4">
                                            <input type="text" name="smtp_host" class="form-control" value="<?php echo decryptIt($siteSettings->smtp_host); ?>" id="smtp_host" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">SMTP Port:</label>
                                        <div class="col-md-4">
                                            <input type="text" name="smtp_port" class="form-control" value="<?php echo decryptIt($siteSettings->smtp_port); ?>" id="smtp_port" autocomplete="off" required>
                                        </div>
                                    </div>

                                  

                                      
                                   <!--  <h4 style="text-align: center;">PAYPAL Settings</h4>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Paypal Username:</label>
                                        <div class="col-md-4">
                                            <input type="type" name="paypal_username" class="form-control" value="<?php echo decryptIt($siteSettings->paypal_username); ?>" id="paypal_username" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Paypal Secret Id:</label>
                                        <div class="col-md-4">
                                            <input type="password" name="paypal_secretid" class="form-control" value="<?php echo decryptIt($siteSettings->paypal_secretid); ?>" id="paypal_secretid" autocomplete="off" required>
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Paypal Client Id:</label>
                                        <div class="col-md-4">
                                            <input type="type" name="paypal_clientid" class="form-control" value="<?php echo decryptIt($siteSettings->paypal_clientid); ?>" id="paypal_clientid" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Paypal Mode</label>
                                        <div class="col-md-4">
                                            <select data-live-search="true" class="selectpicker form-control" name="paypal_mode" id="paypal_mode">
                                            <option value="1" <?php if($siteSettings->paypal_mode==1){echo 'selected';} ?>>Live</option>
                                            <option value="0" <?php if($siteSettings->paypal_mode==0){echo 'selected';} ?>>Test</option>
                                            </select>
                                        </div>
                                    </div>
                                    <h4 style="text-align: center;">PAYPRO Payment Settings</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">PAYPRO Mode</label>
                                        <div class="col-md-4">
                                            <select data-live-search="true" class="selectpicker form-control" name="paypro_mode" id="paypro_mode">
                                            <option value="false" <?php if($siteSettings->paypro_mode==false){echo 'selected';} ?>>Live</option>
                                            <option value="true" <?php if($siteSettings->paypro_mode==true){echo 'selected';} ?>>Test</option>
                                            </select>
                                        </div>
                                    </div>

                                   
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Paypro  API Key:</label>
                                        <div class="col-md-4">
                                            <input type="text" name="paypro_key" class="form-control" value="<?php echo decryptIt($siteSettings->paypro_key); ?>" id="paypro_key" autocomplete="off" required>
                                        </div>
                                    </div> -->
                                    <!-- h4 style="text-align: center;">Signup Bonus</h4>
                                <div class="form-group">
                                        <label class="col-md-4 control-label">Signup Bonus Type </label>
                                        <div class="col-md-4">
                                            <select data-live-search="true" class="selectpicker form-control" name="signup_bonus_type" id="signup_bonus_type">
                                            <option value="1" <?php if($siteSettings->signup_bonus_type==1){echo 'selected';} ?>>Flat</option>
                                            <option value="2" <?php if($siteSettings->signup_bonus_type==2){echo 'selected';} ?>>Percentage</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Signup Bonus USD</label>
                                        <div class="col-md-4">
                                            <input type="text" name="signup_bonus" class="form-control" value="<?php echo $siteSettings->signup_bonus; ?>" id="signup_bonus" autocomplete="off" required >
                                        </div>
                                    </div>
 -->
                                    <h4 style="text-align: center;">Referral Commission</h4>
                                   <!--        <div class="form-group">
                                        <label class="col-md-4 control-label">Referral Bonus Type </label>
                                        <div class="col-md-4">
                                            <select data-live-search="true" class="selectpicker form-control" name="referral_commission_type" id="referral_commission_type">
                                            <option value="1" <?php if($siteSettings->referral_bonus_type==1){echo 'selected';} ?>>Flat</option>
                                            <option value="2" <?php if($siteSettings->referral_bonus_type==2){echo 'selected';} ?>>Percentage</option>
                                            </select>
                                        </div>
                                    </div> -->

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Referral commission %</label>
                                        <div class="col-md-4">
                                            <input type="text" name="referral_commission" oninput="process(this)" class="form-control" value="<?php echo $siteSettings->referral_commission; ?>" id="referral_commission" autocomplete="off" required >
                                        </div>
                                    </div>


                                
									<h4 style="text-align: center;">Trading</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Trading Execution Type</label>
                                        <div class="col-md-4">
                                            <select data-live-search="true" class="selectpicker form-control" name="trade_execution_type" id="trade_execution_type">
											<option value="1" <?php if($siteSettings->trade_execution_type==1){echo 'selected';} ?>>Filled</option>
											<option value="2" <?php if($siteSettings->trade_execution_type==2){echo 'selected';} ?>>Partially</option>
											</select>
                                        </div>
                                    </div>
                                    <h4 style="text-align: center;">API Settings</h4>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Cryptocompare API Key</label>
                                        <div class="col-md-4">
                                            <input type="text" name="cryptocompare_apikey" id="cryptocompare_apikey" class="form-control" placeholder="Cryptocompare API Key" value="<?php echo $siteSettings->cryptocompare_apikey; ?>" />
                                        </div>
                                    </div>

<!-- 
                                       <div class="form-group">
                                        <label class="col-md-4 control-label">Twilio Sender Id</label>
                                        <div class="col-md-4">
                                            <input type="text" name="twilio_sender" id="twilio_sender" class="form-control" placeholder="Twilio Sender ID" value="<?php echo $siteSettings->twilio_sender ?>" />
                                        </div>
                                    </div> -->

<!-- 
                           <div class="form-group">
                                        <label class="col-md-4 control-label">Twilio Token Id</label>
                                        <div class="col-md-4">
                                            <input type="text" name="twilio_token" id="twilio_token" class="form-control" placeholder="Twilio  Token Key" value="<?php echo $siteSettings->twilio_token ?>" />
                                        </div>
                                    </div> -->


                                     <!--   <div class="form-group">
                                        <label class="col-md-4 control-label">Twilio Number</label>
                                        <div class="col-md-4">
                                            <input type="text" name="twilio_number" id="twilio_number" class="form-control" placeholder="Twilio  Number" value="<?php echo $siteSettings->twilio_number ?>" />
                                        </div> -->
                                   <!--  </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Filter Minimum Balance</label>
                                        <div class="col-md-4">
                                            <input type="text" name="min_balance" id="min_balance" class="form-control" placeholder="Twilio  Number" value="<?php echo $siteSettings->min_balance ?>" />
                                        </div>
                                    </div> -->

                                     <h4 style="text-align: center;">Trade Chart Settings</h4>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Interval for create candle</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="chart_interval" id="chart_interval">
                                            <option value="">Select interval</option>
                                            <option value="5" <?php if($siteSettings->chart_interval==5){echo 'selected';} ?>>5 Minutes</option>
                                            <option value="10" <?php if($siteSettings->chart_interval==10){echo 'selected';} ?>>10 Minutes</option>
                                            <option value="15" <?php if($siteSettings->chart_interval==15){echo 'selected';} ?>>15 Minutes</option>
                                            <option value="30" <?php if($siteSettings->chart_interval==30){echo 'selected';} ?>>30 Minutes</option>
                                            <option value="60" <?php if($siteSettings->chart_interval==60){echo 'selected';} ?>>1 Hour</option>
                                            </select>
                                        </div>
                                    </div>
                                   

                           
                                    
                                    <!-- <h4 style="text-align: center;">Paypal Settings</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Paypal Id</label>
                                        <div class="col-md-4">
                                            <input type="text" name="paypal_id" id="paypal_id" class="form-control" placeholder="Paypal Id" value="<?php echo decryptIt($siteSettings->paypal_id); ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Paypal mode</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="paypal_mode" id="paypal_mode">
                                            <option value="1" <?php if($siteSettings->paypal_mode==1){echo 'selected';} ?>>Live</option>
                                            <option value="0" <?php if($siteSettings->paypal_mode==0){echo 'selected';} ?>>Sanbox</option>
                                            </select>
                                        </div>
                                    </div> -->

                                    <!-- <h4 style="text-align: center;">Token Settings</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Token Fees</label>
                                        <div class="col-md-4">
                                            <input type="text" name="token_fees" id="token_fees" class="form-control" placeholder="Token Fees (%)" value="<?php echo $siteSettings->token_fees; ?>" />
                                        </div>
                                    </div> -->

                                    <!--<h4 style="text-align: center;">Shasta Payment Settings</h4>
                                    

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Project Id</label>
                                        <div class="col-md-4">
                                            <input type="text" name="shasta_project_id" id="shasta_project_id" class="form-control" placeholder="Shasta Project Id" value="<?php echo $siteSettings->shasta_project_id; ?>" />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">API Private Key</label>
                                        <div class="col-md-4">
                                            <input type="text" name="shasta_privatekey" id="shasta_privatekey" class="form-control" placeholder="API Private Key" value="<?php echo $siteSettings->shasta_privatekey; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">API Public Key</label>
                                        <div class="col-md-4">
                                            <input type="text" name="shasta_publickey" id="shasta_publickey" class="form-control" placeholder="API Public Key" value="<?php echo $siteSettings->shasta_publickey; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Endpoint</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="shasta_endpoint" id="shasta_endpoint">
											<option value="live" <?php if($siteSettings->shasta_endpoint=="live"){echo 'selected';} ?>>Live</option>
											<option value="sandbox" <?php if($siteSettings->shasta_endpoint=="sandbox"){echo 'selected';} ?>>Sandbox</option>
											</select>
                                        </div>
                                    </div>-->

									<!-- <div class="form-group">
                                        <label class="col-md-4 control-label">Re-Market Integration</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="remarket_concept" id="remarket_concept">
											<option value="1" <?php if($siteSettings->remarket_concept==1){echo 'selected';} ?>>Enable</option>
											<option value="0" <?php if($siteSettings->remarket_concept==0){echo 'selected';} ?>>Disable</option>
											</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Liquidity Integration</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="liquidity_concept" id="liquidity_concept">
											<option value="1" <?php if($siteSettings->liquidity_concept==1){echo 'selected';} ?>>Enable</option>
											<option value="0" <?php if($siteSettings->liquidity_concept==0){echo 'selected';} ?>>Disable</option>
											</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Poloniex Balance Alert Via SMS</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="balance_alert" id="balance_alert">
											<option value="1" <?php if($siteSettings->balance_alert==1){echo 'selected';} ?>>Enable</option>
											<option value="0" <?php if($siteSettings->balance_alert==0){echo 'selected';} ?>>Disable</option>
											</select>
                                        </div>
                                    </div> -->
									<!-- <h4 style="text-align: center;">Margin Trading</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Margin Trading Limit Percentage</label>
                                        <div class="col-md-4">
                                            <input type="text" name="margin_trading_percentage" id="margin_trading_percentage" class="form-control" placeholder="Margin Trading Limit Percentage" value="<?php echo $siteSettings->margin_trading_percentage; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Margin/lending Minimum Loan Rate</label>
                                        <div class="col-md-4">
                                            <input type="text" name="lending_min_loan_rate" id="lending_min_loan_rate" class="form-control" placeholder="Margin Trading Limit Percentage" value="<?php echo $siteSettings->lending_min_loan_rate; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Margin Base Price Calculation Rate</label>
                                        <div class="col-md-4">
                                            <input type="text" name="base_price_calculation" id="base_price_calculation" class="form-control" placeholder="Margin Trading Limit Percentage" value="<?php echo $siteSettings->base_price_calculation; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Margin Liquidation Price Calculation Rate( Less than Margin Trading Limit Percentage )</label>
                                        <div class="col-md-4">
                                            <input type="text" name="liquidation_price_calculation" id="liquidation_price_calculation" class="form-control" placeholder="Margin Trading Limit Percentage" value="<?php echo $siteSettings->liquidation_price_calculation; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Lending Fees ( Admin Profit )</label>
                                        <div class="col-md-4">
                                            <input type="text" name="lending_fees" id="lending_fees" class="form-control" placeholder="Margin Trading Limit Percentage" value="<?php echo $siteSettings->lending_fees; ?>" />
                                        </div>
                                    </div> -->
									<!-- <h4 style="text-align: center;">Plivo Api Details</h4>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Authentication Id</label>
                                        <div class="col-md-4">
                                            <input type="text" name="auth_id" id="auth_id" class="form-control" placeholder="Authentication Id" value="<?php echo $siteSettings->auth_id; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Authentication Token</label>
                                        <div class="col-md-4">
                                            <input type="text" name="auth_token" id="auth_token" class="form-control" placeholder="Authentication Token" value="<?php echo $siteSettings->auth_token; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">From Number</label>
                                        <div class="col-md-4">
                                            <input type="text" name="from_number" id="from_number" class="form-control" placeholder="From Number" value="<?php echo $siteSettings->from_number; ?>" />
                                        </div>
                                    </div> -->

                               <!--  <h4 style="text-align: center;">User settings</h4>    
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Login</label>
                                    <div class="col-md-4">
                                        <div class="form-check-inline">
                                            <label class="customradio">
                                                <span class="radiotextsty">Enable</span>
                                                <input type="radio" name="login_status" value="1"  <?php if($siteSettings->login_status==1){echo 'checked';} ?>>
                                                <span class="checkmark"></span>
                                            </label> 
                                            <label class="customradio">
                                                <span class="radiotextsty">Disable</span>
                                                <input type="radio" name="login_status" value="0" <?php if($siteSettings->login_status==0){echo 'checked';} ?> >
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Register</label>
                                    <div class="col-md-4">
                                        <div class="form-check-inline">
                                            <label class="customradio">
                                                <span class="radiotextsty">Enable</span>
                                                <input type="radio" name="newuser_reg_status" value="1"  <?php if($siteSettings->newuser_reg_status==1){echo 'checked';} ?>>
                                                <span class="checkmark"></span>
                                            </label> 
                                            <label class="customradio">
                                                <span class="radiotextsty">Disable</span>
                                                <input type="radio" name="newuser_reg_status" value="0" <?php if($siteSettings->newuser_reg_status==0){echo 'checked';} ?> >
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div> -->  

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
		$('#site_settings').validate({
			rules: {
				site_name: {
					required: true
				},
				smtp_host: {
					required: true
				},
				smtp_port: {
					required: true,
					number: true
				},
				smtp_email: {
					required: true,
					email: true
				},
				smtp_password: {
					required: true
				},
				copy_right_text: {
					required: true
				},
				address: {
					required: true
				},
				city: {
					required: true
				},
				state: {
					required: true
				},
				country: {
					required: true
				},
				contactno: {
					required: true,
                    number: true,
					minlength: 10,
					maxlength: 20
				},
				altcontactno: {
					required: true,
                    number: true,
					minlength: 10,
					maxlength: 20
				},
				/*twitterlink: {
					required: true
				},
				facebooklink: {
					required: true
				},
				googlelink: {
					required: true
				},*/
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

 <script type="text/javascript">
     
function process(input){
let value = input.value;
let numbers = value.replace(/[^0-9]/g, "");
input.value = numbers;
}


 </script>