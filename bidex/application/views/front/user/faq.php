<?php $this->load->view('front/common/header_cms')?>
		<!-- Fix Internet Explorer ______________________________________-->
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<script src="vendor/html5shiv.js"></script>
			<script src="vendor/respond.js"></script>
		<![endif]-->
		<style>
			.question {
  font-size: 1.2rem;
  font-weight: 600;
  padding: 20px 80px 20px 20px;
  position: relative;
  display: flex;
  align-items: center;
  cursor: pointer;
  box-shadow: 0 5px 10px 0 rgb(0,0,0,0.25);
  margin-bottom: 20px;
    border-radius: 20px;
}

ul.li {
  list-style-type: square;
}

.question::after {
  content: "\002B";
  font-size: 2.2rem;
  position: absolute;
  right: 20px;
  transition: 0.2s;
}

.question.active::after {
  transform: rotate(45deg);
}

.answercont {
  max-height: 0;
  overflow: hidden;
  transition: 0.3s;
}

.answer {
  padding: 0 20px 20px;
  line-height: 1.5rem;
}

.question.active + .answercont {
}

		</style>
	</head>

	<body>
		<div class="main-page-wrapper">

			<!-- ===================================================
				Loading Transition
			==================================================== -->
			<!-- Preloader -->
			<!-- <section>
				<div id="preloader">
					<div id="ctn-preloader" class="ctn-preloader">
						<div class="animation-preloader">
							<div class="spinner"></div>
							<div class="txt-loading">
								<span data-text-preloader="R" class="letters-loading">
									R
								</span>
								<span data-text-preloader="O" class="letters-loading">
									O
								</span>
								<span data-text-preloader="G" class="letters-loading">
									G
								</span>
								<span data-text-preloader="A" class="letters-loading">
									A
								</span>
								<span data-text-preloader="N" class="letters-loading">
									N
								</span>
							</div>
						</div>
					</div>
				</div>
			</section> -->


			<!--
			=============================================
				Theme Main Menu
			==============================================
			-->


			<!-- /.theme-main-menu -->




			<!--
			=============================================
				Theme Main Banner Two
			==============================================
			-->
			<div class="solid-inner-banner">
				<h2 class="page-title" style="color: white;">FAQ</h2>
				<ul class="page-breadcrumbs">
					<li><a href="<?php echo base_url(); ?>">Home</a></li>
					<li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
					<li>Faq</li>
				</ul>
			</div> <!-- /.solid-inner-banner -->



			<!--
			=============================================
				About Us Standard
			==============================================
			-->
			<div class="about-us-standard pb-150">
           <?php  foreach($faq as $row) {        ?>

				<div class="container">
					<div class="question">
				<?php echo  $row->english_question; ?>
					  </div>
					  <div class="answercont">
						<div class="answer">
						 <?php echo $row->english_description;?>
				  
						</div>
					  </div>
					</div>


				<?php }?>
					
		<!-- 			  <div class="container">
					  <div class="question">
						My Pen loads infinitely or crashes the browser.
					  </div>
					  <div class="answercont">
						<div class="answer">
						  It's likely an infinite loop in JavaScript that we could not catch. To fix, add ?turn_off_js=true to the end of the URL (on any page, like the Pen or Project editor, your Profile page, or the Dashboard) to temporarily turn off JavaScript. When you're ready to run the JavaScript again, remove ?turn_off_js=true and refresh the page.<br><br>
				  
				  <a href="https://blog.codepen.io/documentation/features/turn-off-javascript-in-previews/">How to Disable JavaScript Docs</a>
						</div>
					  </div>
					</div> -->
					
<!-- 						<div class="container">
					  <div class="question">
						How do I contact the creator of a Pen?
					  </div>
					  <div class="answercont">
						<div class="answer">
						  You can leave a comment on any public Pen. Click the "Comments" link in the Pen editor view, or visit the Details page.<br><br>
				  
				  <a href="https://blog.codepen.io/documentation/faq/how-do-i-contact-the-creator-of-a-pen/">How to Contact Creator of a Pen Docs</a>
						</div>
					  </div>
					</div>
				    -->
<!-- 					<div class="container">
					  <div class="question">
						What version of [library] does CodePen use?
					  </div>
					  <div class="answercont">
						<div class="answer">
						  We have our current list of library versions <a href="https://codepen.io/versions">here</a>
					 
						</div>
					  </div>
					</div> -->
					
<!-- 					<div class="container">
					  <div class="question">
						What are forks?
					  </div>
					  <div class="answercont">
						<div class="answer">
						  A fork is a complete copy of a Pen or Project that you can save to your own account and modify. Your forked copy comes with everything the original author wrote, including all of the code and any dependencies.<br><br>
				  
				  <a href="https://blog.codepen.io/documentation/features/forks/">Learn More About Forks</a>
						</div>
					  </div>
				</div> 
 -->
				<!-- /.container -->
			</div> <!-- /.about-us-standard -->

<?php $this->load->view('front/common/footer_cms');?>

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

let question = document.querySelectorAll(".question");

question.forEach(question => {
  question.addEventListener("click", event => {
    const active = document.querySelector(".question.active");
    if(active && active !== question ) {
      active.classList.toggle("active");
      active.nextElementSibling.style.maxHeight = 0;
    }
    question.classList.toggle("active");
    const answer = question.nextElementSibling;
    if(question.classList.contains("active")){
      answer.style.maxHeight = answer.scrollHeight + "px";
    } else {
      answer.style.maxHeight = 0;
    }
  })
})

		</script>

		</div> <!-- /.main-page-wrapper -->
	</body>

<!-- Mirrored from heloshape.com/html/rogan/rogan-c/html/index-seo.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 06 Jan 2022 13:19:09 GMT -->
</html>