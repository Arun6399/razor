<?php
$user_id = $this->session->userdata('user_id');
$favicon = $site_common['site_settings']->site_favicon;
    $sitelogo = $site_common['site_settings']->site_logo;

    $seg1 = $this->uri->segment(1);
    $seg2 = $this->uri->segment(2);
       
?>

<!--
			=====================================================
				Footer
			=====================================================
			-->

			<footer class="theme-footer-one">
				<div class="shape-one" data-aos="zoom-in-right"></div>
				<img src="<?php echo base_url();?>assets/images/shape/shape-67.svg" alt="" class="shape-two">
				<div class="top-footer">
					<div class="container">
						<div class="row">
							<div class="col-lg-2 col-sm-6 col-12 about-widget" data-aos="fade-up">


							</div> <!-- /.about-widget -->
							<div class="col-lg-8 col-sm-6 col-12 footer-list" data-aos="fade-up" style="text-align: center; margin-top: 30px;">
								<p style="text-align: center;margin-bottom: 0rem;"><a href="<?php echo base_url();?>" class="logo"><img src="<?php echo $sitelogo;?>" alt="<" style="width: 200px;"></a></p>
								<p style="text-align: center;"><a href="#" class="email" style="color: #fff;">support@bidexcrypto.com</a></p>
							<!-- 	<p style="text-align: center;"><a href="#" class="phone" style="color: #fff;">720.661.2231</a></p> -->
								<div class="footer-information" data-aos="fade-up">
								<ul style="text-align: center;padding-left: 0rem;">
									<li><a href="<?php echo $site_common['site_settings']->facebooklink;?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
									<li><a href="<?php echo $site_common['site_settings']->twitterlink;?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
									<li><a href="<?php echo $site_common['site_settings']->linkedin_link;?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
								</ul>
							</div>


								<ul style="padding-left: 0rem;" class="disp" >
									<li style="padding-right: 20px;"><a href="<?php echo base_url();?>">Home</a></li>
									<li style="padding-right: 20px;"><a href="<?php echo base_url();?>contact_us">contact</a></li>
									<!-- <li style="padding-right: 20px;"><a href="#">FAQ</a></li> -->
									<!-- <li style="padding-right: 20px;"><a href="#">Company</a></li> -->
									<li style="padding-right: 20px;"><a href="<?php echo base_url();?>cms/privacy-policy">Privacy Policy</a></li>
										<li style="padding-right: 20px;"><a href="<?php echo base_url();?>faq_list">FAQ</a></li>

									<li style="padding-right: 20px;"><a href="<?php echo base_url();?>cms/terms-and-conditions">Terms & Conditions</a></li>
									<!-- <li><a href="#">Market</a></li>
									<li><a href="#">Exchange</a></li> -->
								</ul>
							</div> <!-- /.footer-recent-post -->
							<div class="col-lg-2 col-sm-6 col-12 about-widget" data-aos="fade-up"></div>

						</div> <!-- /.row -->
					</div> <!-- /.container -->
				</div> <!-- /.top-footer -->

				<div class="container">
					<div class="bottom-footer">
						<div class="clearfix">
							<p style="text-align: center;margin-bottom: 0rem;">&copy; 2022 copyright all right reserved by Bidex</p>
							<!-- <h5 class="title">Our Address</h5> -->
							<!-- 	<p style="text-align: center;margin-bottom: 0rem;">00 Orville Road Apt. 728, California, USA</p> -->
							<p style="text-align: center;color: white;margin-bottom: 0rem;"><a href="<?php echo base_url();?>cms/privacy-policy" style="color: white;padding-right: 10px;">Privacy & Policy | </a><!-- <a href="#" style="color: white;padding-right: 10px;">FAQ | </a> --><a href="<?php echo base_url();?>cms/terms-and-conditions" style="color: white;padding-right: 10px;">Terms & Conditions</a></p>
							<!-- <ul>
								<li><a href="#">Privace & Policy.</a></li>
								<li><a href="faq.html">Faq.</a></li>
								<li><a href="#">Terms.</a></li>
							</ul> -->
						</div>
					</div> <!-- /.bottom-footer -->
				</div>
			</footer>



	        <!-- Scroll Top Button -->
			<button class="scroll-top tran3s" style="display: block;">
				<a href="#"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
			</button>



		<!-- Optional JavaScript _____________________________  -->

    	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
    	<!-- jQuery -->
		<script src="<?php echo base_url();?>assets/vendor/jquery.2.2.3.min.js"></script>
		<!-- Popper js -->
		<script src="<?php echo base_url();?>assets/vendor/popper.js/popper.min.js"></script>
		<!-- Bootstrap JS -->
		<script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	    <!-- menu  -->
		<script src="<?php echo base_url();?>assets/vendor/mega-menu/assets/js/custom.js"></script>
		<!-- AOS js -->
		<script src="<?php echo base_url();?>assets/vendor/aos-next/dist/aos.js"></script>
		<!-- WOW js -->
		<script src="<?php echo base_url();?>assets/vendor/WOW-master/dist/wow.min.js"></script>
		<!-- owl.carousel -->
		<script src="<?php echo base_url();?>assets/vendor/owl-carousel/owl.carousel.min.js"></script>
		<!-- js count to -->
		<script src="<?php echo base_url();?>assets/vendor/jquery.appear.js"></script>
		<script src="<?php echo base_url();?>assets/vendor/jquery.countTo.js"></script>
		<!-- Fancybox -->
		<script src="<?php echo base_url();?>assets/vendor/fancybox/dist/jquery.fancybox.min.js"></script>



		<!-- Vendor JS Files -->
		<script src="<?php echo base_url();?>assets/front/vendor/purecounter/purecounter.js"></script>
		<script src="<?php echo base_url();?>assets/front/vendor/aos/aos.js"></script>
		<script src="<?php echo base_url();?>assets/front/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="<?php echo base_url();?>assets/front/vendor/glightbox/js/glightbox.min.js"></script>
		<script src="<?php echo base_url();?>assets/front/vendor/isotope-layout/isotope.pkgd.min.js"></script>
		<script src="<?php echo base_url();?>assets/front/vendor/swiper/swiper-bundle.min.js"></script>
		<script src="<?php echo base_url();?>assets/front/vendor/typed.js/typed.min.js"></script>
		<script src="<?php echo base_url();?>assets/front/vendor/waypoints/noframework.waypoints.js"></script>
		<!-- <script src="<?php echo base_url();?>assets/front/vendor/php-email-form/validate.js"></script> -->

		<!-- Template Main JS File -->
		<script src="<?php echo base_url();?>assets/front/js/main.js"></script>

		<!-- Language js -->
		<!-- <script src="../../../../../translate.google.com/translate_a/elementa0d8.js?cb=googleTranslateElementInit"></script> -->


		<!-- Theme js -->
		<script src="<?php echo base_url();?>assets/js/lang.js"></script>
		<script src="<?php echo base_url();?>assets/js/theme.js"></script>
		<script src="<?php echo base_url();?>assets/front/js/jquery.growl.js"></script>
<!--
		<script>
			$(window).scroll(function(){
  var sideheader = $('.sd'),
      scroll = $(window).scrollTop();

  if (scroll >= 100) sideheader.addClass('show');
  else sideheader.removeClass('show');
});
		</script> -->


<?php
    $error      = $this->session->flashdata('error');
    $success    = $this->session->flashdata('success');
    $user_id    = $this->session->userdata('user_id');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $get_os     = $_SERVER['HTTP_USER_AGENT'];
?>

<script>
  var user_id='<?php echo $user_id;?>';
  $(document).ready(function() {
    var success = "<?php echo $this->session->flashdata('success')?>";
    var error = "<?php echo $this->session->flashdata('error')?>";
  
        if(success!=''){

          $.growl.notice({title: "Bidex", message: success });
// $.growl.notice({title: "SMdex", message: success });
//alert(success);
}
if(error!=''){
$.growl.error({title: "Bidex", message: error });
}
});
</script>



		<script>
			$(window).scroll(function(){
//   var sideheader = $('.sd');
      scroll = $(window).scrollTop();

  if (scroll >=500) $('#side-header').show();
  else $('#side-header').hide();
});

$(window).scroll(function () {
    var sc = $(window).scrollTop()
    if (sc > 100) {
        $("#header-sroll").addClass("small")
    } else {
        $("#header-sroll").removeClass("small")
    }
});
		</script>

		</div> <!-- /.main-page-wrapper -->
	</body>

<!-- Mirrored from heloshape.com/html/rogan/rogan-c/html/index-seo.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 06 Jan 2022 13:19:09 GMT -->
</html>