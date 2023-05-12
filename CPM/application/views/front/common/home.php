<?php 
$this->load->view('front/common/header');
?>
<div id="theme-banner" class="theme-banner-one">
					<img src="<?php echo $home_section->image;?>" alt="" class="illustration">
					<img src="<?php echo front_img();?>icon/1.png" alt="" class="icon-shape-one">
					<img src="<?php echo front_img();?>icon/2.png" alt="" class="icon-shape-two">
					<img src="<?php echo front_img();?>icon/3.png" alt="" class="icon-shape-three">
					<div class="round-shape-one"></div>
					<div class="round-shape-two"><img src="<?php echo front_img();?>icon/4.png" alt=""></div>
					<div class="round-shape-three"></div>
					<div class="container">
						<div class="main-text-wrapper">							
							<?php echo $home_section->english_content;?>
							<ul class="button-group clearfix">
								<li><a href="#">Get Start</a></li>

							</ul>
						</div>
					</div>

				</div>

			<div class="our-features-one" id="features">
				<div class="container">
					<div class="theme-title">
						<?php echo $feature->english_content;?>
					</div>

					
					<div class="row">
						<?php foreach($features as $feature_contents){?>
						<div class="col-md-4 col-xs-12">
							<div class="single-feature">
								<div class="icon-box">
									<img src="<?php echo $feature_contents->image;?>" alt="" class="primary-icon">
								</div>
								<?php echo $feature_contents->english_content;?>
							</div>
						</div>
						<?php } ?>						
					</div>

				</div>
			</div>





			<div class="apps-overview color-one" id="apps-review">
				<div class="overlay-bg" style="background-image:url(images/home/bg2.png);">
					<div class="container">
						<div class="inner-wrapper">
							<img src="<?php echo front_img();?>home/s8.png" alt="" class="s8-mockup" data-aos="fade-down"
								data-aos-duration="2500">
							<img src="<?php echo $overview->image;?>" alt="" class="x-mockup" data-aos="fade-up"
								data-aos-duration="2500">
							<div class="row">
								<div class="col-lg-5 offset-lg-7">
									<div class="text">
										<?php echo $overview->english_content;?>									
										<ul class="button-group">
											<li><a href="#"><i class="flaticon-apple"></i> Apple Store</a></li>
											<li><a href="#"><img src="<?php echo front_img();?>icon/playstore.png" alt=""> Google Play</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>




			<div class="theme-counter">
				<div class="container">
					<div class="bg-image">
						<div class="row theme-title">
							<div class="col-lg-6 order-lg-last">
								<h2><span>Fastest</span> Growing Global Network.</h2>
							</div>
							<div class="col-lg-6 order-lg-first">
								<?php echo $counter->english_content;?>
							</div>
						</div>

						<div class="counter-wrapper">
							<div class="row">
								<div class="col-sm-4">
									<h2 class="number"><span class="timer" data-from="0" data-to="120" data-speed="1200"
											data-refresh-interval="5">0</span>K</h2>
									<p>Global Customer</p>
								</div>
								<div class="col-sm-4">
									<h2 class="number"><span class="timer" data-from="0" data-to="3" data-speed="1200"
											data-refresh-interval="5">0</span>Y</h2>
									<p>Years Experience</p>
								</div>
								<div class="col-sm-4">
									<h2 class="number"><span class="timer" data-from="0" data-to="7" data-speed="1200"
											data-refresh-interval="5">0</span>B</h2>
									<p>Current Stock</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>



			<div class="our-work-progress bg-color" id="progress">
				<div class="main-wrapper clearfix">
					<div class="section-title-wrapper clearfix">
						<div class="theme-title">
							<div class="upper-heading"><?php echo $solutions->english_title;?></div>							
						 <?php echo $solutions->english_content;?>
						</div>
					</div>
					<div class="progress-slider-wrapper">
						<div class="progress-slider">
							<?php $i=1;
							foreach($progress as $progress_content){
								?>
							<div class="item">
								<div class="inner-block">
									<div class="icon"><img src="<?php echo $progress_content->image;?>" alt=""></div>
									<?php echo $progress_content->english_content;?>
									<div class="num"><?php echo $i;?></div>
								</div>
							</div>
						<?php $i++;} ?>
							
						</div>
					</div>
				</div>
			</div>

<?php 
$this->load->view('front/common/footer');
?>



