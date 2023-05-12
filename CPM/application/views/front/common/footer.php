			<footer class="theme-footer">
				<div class="container">
					<div class="inner-wrapper">
						<div class="top-footer-data-wrapper">
							<div class="row">

								<div class="col-lg-4 col-sm-6 footer-list">
									<h4 class="title">Quick Links</h4>
									<ul>
										<li><a href="<?php echo base_url();?>cms/how-it-works">How it Works</a></li>
										<!-- <li><a href="#">Security</a></li> -->
										<li><a href="<?php echo base_url();?>cms/about-us">About Us</a></li>
										<!-- <li><a href="#">Support</a></li> -->
										<li><a href="<?php echo base_url();?>faq">Faq</a></li>
									</ul>
								</div>
								<div class="col-lg-4 col-sm-6 footer-img-s text-right">
									<img src="<?php echo front_img();?>ftrico.png" class="footer-img">
								</div>
								<div class="col-lg-4 col-sm-6 footer-list text-right">
									<h4 class="title">Popular Links</h4>
									<ul>
										<li><a href="<?php echo base_url();?>cms/terms-and-conditions">Terms &amp; Conditions</a></li>
										<!-- <li><a href="#">Team</a></li> -->
										<!-- <li><a href="#">Blog</a></li> -->
										<li><a href="<?php echo base_url();?>cms/privacy-policy">Privacy Policy</a></li>
										<!-- <li><a href="#">Testimonials</a></li> -->
										<li><a href="<?php echo base_url();?>support">Support</a></li>

									</ul>
								</div>

							</div>
						</div>

						<div class="bottom-footer clearfix text-center">
							<p class="copyright text-center w-100">&copy; 2022 All Right Reserved</p>

						</div>
					</div>
				</div>
			</footer>

		</div>




		<button class="scroll-top tran3s color-one-bg">
			<i class="fa fa-arrow-up" aria-hidden="true"></i>
		</button>




		<script src="<?php echo front_vendor();?>jquery.2.2.3.min.js"></script>
		<script src="<?php echo front_vendor();?>popper.js/popper.min.js"></script>
		<script src="<?php echo front_vendor();?>bootstrap/js/bootstrap.min.js"></script>

		<script src="<?php echo front_vendor();?>jquery-easing/jquery.easing.min.js"></script>
		<script src="<?php echo front_vendor();?>jquery.appear.js"></script>
		<script src="<?php echo front_vendor();?>jquery.countTo.js"></script>
		<script src="<?php echo front_vendor();?>fancybox/dist/jquery.fancybox.min.js"></script>
		<script src="<?php echo front_vendor();?>owl-carousel/owl.carousel.min.js"></script>
		<script src="<?php echo front_vendor();?>aos-next/dist/aos.js"></script>


		<script src="<?php echo front_js();?>tata.js"></script>
		<script src="<?php echo front_js();?>theme.js"></script>
		
		<script src="<?php echo front_js();?>custom.js"></script>

		<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>

		<script src="<?php echo front_js();?>html2canvas.js"></script>
		

	</div>









 <?php
    $error      = $this->session->flashdata('error');
    $success    = $this->session->flashdata('success');
    $user_id    = $this->session->userdata('user_id');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $get_os     = $_SERVER['HTTP_USER_AGENT'];
?>

<script>

    
var base_url='<?php echo base_url();?>';
var front_url='<?php echo front_url();?>';
var user_id='<?php echo $user_id;?>';
var ip_address = '<?php echo $ip_address;?>';
var get_os     = '<?php echo $get_os;?>';
var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
var success = "<?php echo $this->session->flashdata('success')?>";
var error = "<?php echo $this->session->flashdata('error')?>";



$(document).ready(function() {





if(success!=''){
tata.success('CPM! '+success);

}
if(error!=''){
    tata.warn('CPM!', error);
}

  
  $('.datatable').DataTable();



});


  </script>

</body>

</html>