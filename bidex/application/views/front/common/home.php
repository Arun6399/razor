<?php 
    $this->load->view('front/common/header');
    $user_id = $this->session->userdata('user_id');
    $sitelogo = $site_common['site_settings']->site_logo;


?>

      <!--
      =============================================
        Theme Main Banner Two
      ==============================================
      -->
      <div id="theme-banner-two">
        <div class="container">

        </div> <!-- /.container -->
        <div class="bg-round-one wow zoomIn animated" data-wow-duration="5s"></div>
        <div class="bg-round-two wow zoomIn animated" data-wow-duration="5s"></div>
        <div class="bg-round-three wow zoomIn animated" data-wow-duration="5s"></div>
        <div class="bg-round-four wow zoomIn animated" data-wow-duration="5s"></div>
        <div class="bg-round-five wow zoomIn animated" data-wow-duration="5s"></div>
        <div class="illustration wow fadeInRight animated" data-wow-duration="2s" data-wow-delay="0.4s"><img src="<?php echo base_url();?>assets/images/ban.png" style="width: 485px;" alt=""></div>
        <div  style="color: white;font-weight: 800;"   class="container">
          <div class="main-wrapper" style="background-image: url('<?php echo base_url();?>assets/images/map.png');background-repeat: no-repeat;background-position: left;">
            <?php echo $main_banner->english_content;?>
            
            <ul class="button-group" style="padding-left:0px;">
              <?php if($user_id=='') {?>
              <li><a href="<?php echo base_url();?>register" class="contact-button wow fadeInRight animated" data-wow-delay="1.5s">Register Here <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
            <?php }?>
            </ul>
          </div>
        </div>
        <!-- /.container -->
      </div> <!-- /#theme-banner-two -->


      <main id="main">

        <!-- <i class="bi bi-list mobile-nav-toggle d-xl-none"></i> -->
        <!-- ======= Header ======= -->
        <header id="side-header" class="sd flex-column justify-content-center " style="display: none;">

          <nav id="navbar" class="navbar nav-menu">
          <ul>
            <li><a href="#" class="nav-link scrollto"><i class="bx bx-home"></i> <span>Home</span></a></li>
            <li><a href="#about" class="nav-link scrollto"><i class="bx bx-user"></i> <span>Crypto </span></a></li>
            <li><a href="#resume" class="nav-link scrollto"><i class="bx bx-file-blank"></i> <span>Trade</span></a></li>
            <li><a href="#portfolio" class="nav-link scrollto"><i class="bx bx-book-content"></i> <span>Market</span></a></li>
            <li><a href="#services" class="nav-link scrollto"><i class="bx bx-server"></i> <span>Services</span></a></li>
            <li><a href="#contact" class="nav-link scrollto"><i class="bx bx-envelope"></i> <span>Contact</span></a></li>
          </ul>
          </nav><!-- .nav-menu -->

        </header>


        <!-- ======= About Section ======= -->
        <section id="about" class="about">
          <div class="container" data-aos="fade-up">

          <div class="section-title">
            <?php echo $about->english_content;?>
          </div>

          <div class="row">
            <div class="col-lg-4">
            <img src="<?php echo base_url();?>assets/images/side.png" class="img-fluid" alt="">
            </div>
            <div class="col-lg-8 pt-4 pt-lg-0 content">

            <div class="row">
              <div class="col-lg-12">
                <div class="row">
                <div class="col-6 col-lg-6">
              <div style="padding-bottom: 20px;">
                <?php echo $about_payout->english_content;?>
              </div>
              <div style="padding-bottom: 20px;">
                <?php echo $about_deposit->english_content;?>
              </div>
                </div>
                <div class="col-6 col-lg-6">
                  <div style="padding-bottom: 20px;">
              <?php echo $about_withdraw->english_content;?>
</div>
              <div style="padding-bottom: 20px;">
                <?php echo $about_compounded->english_content;?>
              </div>
                </div>
              </div>

              </div>

            </div>
            <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/coinPrice.js"></script><div id="coinmarketcap-widget-coin-price-block" coins="1,1027,825,1958" currency="USD" theme="light" transparent="false" show-symbol-logo="true"></div>
            <div style="padding:80px"></div>
            </div>
          </div>

          </div>
        </section><!-- End About Section -->

        <!-- ======= Facts Section ======= -->
        <!-- <section id="facts" class="facts">
          <div class="container" data-aos="fade-up">

          <div class="section-title">
            <h2>Facts</h2>
            <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
          </div>

          <div class="row">

            <div class="col-lg-3 col-md-6">
            <div class="count-box">
              <i class="bi bi-emoji-smile"></i>
              <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
              <p>Happy Clients</p>
            </div>
            </div>

            <div class="col-lg-3 col-md-6 mt-5 mt-md-0">
            <div class="count-box">
              <i class="bi bi-journal-richtext"></i>
              <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
              <p>Projects</p>
            </div>
            </div>

            <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
            <div class="count-box">
              <i class="bi bi-headset"></i>
              <span data-purecounter-start="0" data-purecounter-end="1463" data-purecounter-duration="1" class="purecounter"></span>
              <p>Hours Of Support</p>
            </div>
            </div>

            <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
            <div class="count-box">
              <i class="bi bi-award"></i>
              <span data-purecounter-start="0" data-purecounter-end="25" data-purecounter-duration="1" class="purecounter"></span>
              <p>Awards</p>
            </div>
            </div>

          </div>

          </div>
        </section> -->

        <!-- End Facts Section -->

        <!-- ======= Skills Section ======= -->
        <!-- <section id="skills" class="skills section-bg">
          <div class="container" data-aos="fade-up">

          <div class="section-title">
            <h2>Skills</h2>
            <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
          </div>

          <div class="row skills-content">

            <div class="col-lg-6">

            <div class="progress">
              <span class="skill">HTML <i class="val">100%</i></span>
              <div class="progress-bar-wrap">
              <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <div class="progress">
              <span class="skill">CSS <i class="val">90%</i></span>
              <div class="progress-bar-wrap">
              <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <div class="progress">
              <span class="skill">JavaScript <i class="val">75%</i></span>
              <div class="progress-bar-wrap">
              <div class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            </div>

            <div class="col-lg-6">

            <div class="progress">
              <span class="skill">PHP <i class="val">80%</i></span>
              <div class="progress-bar-wrap">
              <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <div class="progress">
              <span class="skill">WordPress/CMS <i class="val">90%</i></span>
              <div class="progress-bar-wrap">
              <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <div class="progress">
              <span class="skill">Photoshop <i class="val">55%</i></span>
              <div class="progress-bar-wrap">
              <div class="progress-bar" role="progressbar" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            </div>

          </div>

          </div>
        </section> -->

        <!-- End Skills Section -->

        <!-- ======= Resume Section ======= -->
        <section id="resume" class="resume">
          <div class="container" data-aos="fade-up">

          <div class="section-title">
             <?php echo $resume->english_content;?>
          </div>

          <div class="seo-our-goal">
            <div class="container">
              <div class="row">
                <div class="col-lg-6">
                  <div class="text-wrapper">
                    <div class="theme-title-one title-underline">
                      <?php echo $resume_crypto->english_content;?>
                    </div> <!-- /.theme-title-one -->
                    <?php echo $resume_goal->english_content;?>
                    <?php if($user_id=='') {?>
                    <a href="<?php echo base_url();?>register" class="request-button">Register Here<i class="fa fa-angle-right" aria-hidden="true"></i></a>
                  <?php } ?>
                  </div>
                </div>
              </div>

              <div class="right-shape aos-init aos-animate" data-aos="fade-left"></div>
            </div> <!-- /.container -->
          </div>

          </div>
        </section><!-- End Resume Section -->

        <!-- ======= Portfolio Section ======= -->
        <section id="portfolio" class="portfolio section-bg">

          <div class="row">
            <div class="col-lg-12">
            <div class="card">
            <div class="card-header">
            <h4 class="card-title">Markets</h4>
            </div>
            <div class="card-body">
            <div class="table-responsive">
              <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                  <tr>
                  <th class="tabhead">Name <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="6 15 12 9 18 15"></polyline></svg> -->
                  </th>
                  <th>Price</th>
                  <th>24H Change</th>
                  <th>24H High</th>
                  <th>24H Low</th>
                  <th>Volume</th>


                  </tr>
                </thead>
                <tbody>
                  <?php if(isset($pairs) && !empty($pairs)){
                        foreach($pairs as $key=> $pair_details){
                          if($pair_details->to_symbol_id==1) {
                    $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol_id))->row();
                    $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol_id))->row();
                    $pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;
                    $pair_url = $from_currency->currency_symbol.'_'.$to_currency->currency_symbol;
                    $currency = getcryptocurrencydetail($from_currency->id);
                    
                        ?>  
                  <tr>
                    <td class="d-flex"><img src="<?=$currency->image;?>" alt="<?=$currency->image;?>" class="table-cryp" width="20px;">&nbsp;&nbsp; <?=$pair_symbol?></td>
                    <td><?php echo TrimTrailingZeroes($pair_details->lastPrice);?>   </td>
                    <td><span class="grn"><span class="<?php echo($pair_details->priceChangePercent>0)?'grn':'rdn';?>"><?php echo number_format($pair_details->priceChangePercent,2);?>%</span></span></td>
                    <td><?php echo TrimTrailingZeroes($pair_details->change_high);?></td>
                    <td><?php echo TrimTrailingZeroes($pair_details->change_low);?></td>
                    <td><?php echo TrimTrailingZeroes($pair_details->volume);?></td>
                  </tr>
                <?php }}}?>

                </tbody>
                </table>
            </div>
            </div>
            </div>
            </div>



            </div>

<div class="padding:50px"></div>
          <div class="our-feature-sass" style="margin-top: 100px;">
            <div class="section-shape-one"></div>
            <div class="section-shape-two"><img src="<?php echo base_url();?>assets/images/shape/shape-18.svg" alt=""></div>
            <img src="<?php echo base_url();?>assets/images/shape/shape-18.svg" alt="" class="section-shape-three">
            <div class="section-shape-four"></div>
            <img src="<?php echo base_url();?>assets/images/shape/shape-20.svg" alt="" class="section-shape-five">
            <img src="<?php echo base_url();?>assets/images/shape/shape-21.svg" alt="" class="section-shape-six">
            <img src="<?php echo base_url();?>assets/images/shape/shape-22.svg" alt="" class="section-shape-seven">
            <img src="<?php echo base_url();?>assets/images/shape/shape-19.svg" alt="" class="section-shape-eight">
            <!-- <a href="#feature-sass" class="down-arrow scroll-target"><span><i class="flaticon-back"></i></span></a> -->
            <div class="feature-wrapper" id="feature-sass">
              <div class="single-feature-block">
                <div class="container clearfix">
                  <div class="text-box">
                    <div class="theme-title-one hide-pr show-pr">
                      <div class="icon-box hide-pr show-pr">
                        <img src="<?php echo base_url();?>assets/images/shape/bg-shape2.svg" alt="" class="bg-shape">
                        <img src="<?php echo base_url();?>assets/images/icon/icon24.svg" alt="" class="icon">
                      </div>
                      <?php echo $feature->english_content;?>
                    </div> <!-- /.theme-title-one -->
                    <?php echo $feature_sass->english_content;?>
                    <!-- <a href="#" class="read-more">Read More</a> -->
                  </div> <!-- /.text-box -->
                </div> <!-- /.container -->

                <div class="img-box">
                  <img src="<?php echo base_url();?>assets/images/shape/shape-15.svg" alt="" class="main-shape aos-init aos-animate" data-aos="fade-right" data-aos-delay="200">
                  <img src="<?php echo base_url();?>assets/images/shape/shape-16.svg" alt="" class="bg-shape aos-init" data-aos="fade-right" data-aos-delay="400">
                  <img src="<?php echo base_url();?>assets/images/home/screen1.png" alt="" class="screen-one aos-init aos-animate" data-aos="fade-down" data-aos-delay="600">
                  <img src="<?php echo base_url();?>assets/images/home/screen2.png" alt="" class="screen-two aos-init aos-animate" data-aos="fade-down" data-aos-delay="800">
                  <img src="<?php echo base_url();?>assets/images/home/screen3.png" alt="" class="screen-three aos-init" data-aos="fade-down" data-aos-delay="1000">
                  <img src="<?php echo base_url();?>assets/images/home/screen4.png" alt="" class="screen-four aos-init" data-aos="fade-down" data-aos-delay="1200">
                </div>
              </div> <!-- /.single-feature-block -->


              <div class="single-feature-block">
                <div class="container clearfix">
                  <div class="text-box">
                    <div class="theme-title-one hide-pr">
                      <div class="icon-box hide-pr">
                        <img src="<?php echo base_url();?>assets/images/shape/bg-shape3.svg" alt="" class="bg-shape">
                        <img src="<?php echo base_url();?>assets/images/icon/icon25.svg" alt="" class="icon">
                      </div>
                      <?php echo $feature_1->english_content;?>
                    </div> <!-- /.theme-title-one -->
                    <?php echo $feature_2->english_content;?>
                    <!-- <a href="#" class="read-more">Read More</a> -->
                  </div> <!-- /.text-box -->
                </div> <!-- /.container -->

                <div class="img-box">
                  <img src="<?php echo base_url();?>assets/images/shape/shape-17.svg" alt="" class="main-shape aos-init" data-aos="fade-left" data-aos-delay="200">
                  <img src="<?php echo base_url();?>assets/images/home/screen5.png" alt="" class="screen-one aos-init" data-aos="fade-down" data-aos-delay="400">
                  <img src="<?php echo base_url();?>assets/images/home/screen6.png" alt="" class="screen-two aos-init" data-aos="zoom-in" data-aos-delay="600">
                </div>
              </div> <!-- /.single-feature-block -->
            </div> <!-- /.feature-wrapper -->
          </div>

          <!-- <div class="container" data-aos="fade-up">

          <div class="section-title">
            <h2>Portfolio</h2>
            <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
          </div>

          <div class="row">
            <div class="col-lg-12 d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <ul id="portfolio-flters">
              <li data-filter="*" class="filter-active">All</li>
              <li data-filter=".filter-app">App</li>
              <li data-filter=".filter-card">Card</li>
              <li data-filter=".filter-web">Web</li>
            </ul>
            </div>
          </div>

          <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

            <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-1.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
              <h4>App 1</h4>
              <p>App</p>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-1.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="App 1"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
              </div>
              </div>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-2.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
              <h4>Web 3</h4>
              <p>Web</p>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-2.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Web 3"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
              </div>
              </div>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-3.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
              <h4>App 2</h4>
              <p>App</p>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-3.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="App 2"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
              </div>
              </div>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-4.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
              <h4>Card 2</h4>
              <p>Card</p>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-4.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Card 2"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
              </div>
              </div>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-5.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
              <h4>Web 2</h4>
              <p>Web</p>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-5.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Web 2"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
              </div>
              </div>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-6.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
              <h4>App 3</h4>
              <p>App</p>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-6.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="App 3"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
              </div>
              </div>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-7.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
              <h4>Card 1</h4>
              <p>Card</p>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-7.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Card 1"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
              </div>
              </div>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-8.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
              <h4>Card 3</h4>
              <p>Card</p>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-8.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Card 3"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
              </div>
              </div>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <div class="portfolio-wrap">
              <img src="assets/img/portfolio/portfolio-9.jpg" class="img-fluid" alt="">
              <div class="portfolio-info">
              <h4>Web 3</h4>
              <p>Web</p>
              <div class="portfolio-links">
                <a href="assets/img/portfolio/portfolio-9.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Web 3"><i class="bx bx-plus"></i></a>
                <a href="portfolio-details.html" class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><i class="bx bx-link"></i></a>
              </div>
              </div>
            </div>
            </div>

          </div>

          </div> -->
        </section><!-- End Portfolio Section -->

        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
          <div class="container" data-aos="fade-up">

          <div class="section-title">
            <?php echo $service->english_content;?>
          </div>

          <div class="row">

            <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box iconbox-blue">
              <div class="icon">
              <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174"></path>
              </svg>
              <i class="bx bxl-dribbble"></i>
              </div>
              <?php echo $service_blue->english_content;?>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
            <div class="icon-box iconbox-orange ">
              <div class="icon">
              <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,582.0697525312426C382.5290701553225,586.8405444964366,449.9789794690241,525.3245884688669,502.5850820975895,461.55621195738473C556.606425686781,396.0723002908107,615.8543463187945,314.28637112970534,586.6730223649479,234.56875336149918C558.9533121215079,158.8439757836574,454.9685369536778,164.00468322053177,381.49747125262974,130.76875717737553C312.15926192815925,99.40240125094834,248.97055460311594,18.661163978235184,179.8680185752513,50.54337015887873C110.5421016452524,82.52863877960104,119.82277516462835,180.83849132639028,109.12597500060166,256.43424936330496C100.08760227029461,320.3096726198365,92.17705696193138,384.0621239912766,124.79988738764834,439.7174275375508C164.83382741302287,508.01625554203684,220.96474134820875,577.5009287672846,300,582.0697525312426"></path>
              </svg>
              <i class="bx bx-file"></i>
              </div>
              <?php echo $service_orange->english_content;?>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4 mt-lg-0" data-aos="zoom-in" data-aos-delay="300">
            <div class="icon-box iconbox-pink">
              <div class="icon">
              <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,541.5067337569781C382.14930387511276,545.0595476570109,479.8736841581634,548.3450877840088,526.4010558755058,480.5488172755941C571.5218469581645,414.80211281144784,517.5187510058486,332.0715597781072,496.52539010469104,255.14436215662573C477.37192572678356,184.95920475031193,473.57363656557914,105.61284051026155,413.0603344069578,65.22779650032875C343.27470386102294,18.654635553484475,251.2091493199835,5.337323636656869,175.0934190732945,40.62881213300186C97.87086631185822,76.43348514350839,51.98124368387456,156.15599469081315,36.44837278890362,239.84606092416172C21.716077023791087,319.22268207091537,43.775223500013084,401.1760424656574,96.891909868211,461.97329694683043C147.22146801428983,519.5804099606455,223.5754009179313,538.201503339737,300,541.5067337569781"></path>
              </svg>
              <i class="bx bx-tachometer"></i>
              </div>
              <?php echo $service_pink->english_content;?>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box iconbox-yellow">
              <div class="icon">
              <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,503.46388370962813C374.79870501325706,506.71871716319447,464.8034551963731,527.1746412648533,510.4981551193396,467.86667711651364C555.9287308511215,408.9015244558933,512.6030010748507,327.5744911775523,490.211057578863,256.5855673507754C471.097692560561,195.9906835881958,447.69079081568157,138.11976852964426,395.19560036434837,102.3242989838813C329.3053358748298,57.3949838291264,248.02791733380457,8.279543830951368,175.87071277845988,42.242879143198664C103.41431057327972,76.34704239035025,93.79494320519305,170.9812938413882,81.28167332365135,250.07896920659033C70.17666984294237,320.27484674793965,64.84698225790005,396.69656628748305,111.28512138212992,450.4950937839243C156.20124167950087,502.5303643271138,231.32542653798444,500.4755392045468,300,503.46388370962813"></path>
              </svg>
              <i class="bx bx-layer"></i>
              </div>
              <?php echo $service_yellow->english_content;?>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="icon-box iconbox-red">
              <div class="icon">
              <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,532.3542879108572C369.38199826031484,532.3153073249985,429.10787420159085,491.63046689027357,474.5244479745417,439.17860296908856C522.8885846962883,383.3225815378663,569.1668002868075,314.3205725914397,550.7432151929288,242.7694973846089C532.6665558377875,172.5657663291529,456.2379748765914,142.6223662098291,390.3689995646985,112.34683881706744C326.66090330228417,83.06452184765237,258.84405631176094,53.51806209861945,193.32584062364296,78.48882559362697C121.61183558270385,105.82097193414197,62.805066853699245,167.19869350419734,48.57481801355237,242.6138429142374C34.843463184063346,315.3850353017275,76.69343916112496,383.4422959591041,125.22947124332185,439.3748458443577C170.7312796277747,491.8107796887764,230.57421082200815,532.3932930995766,300,532.3542879108572"></path>
              </svg>
              <i class="bx bx-slideshow"></i>
              </div>
              <?php echo $service_red->english_content;?>
            </div>
            </div>

            <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4" data-aos="zoom-in" data-aos-delay="300">
            <div class="icon-box iconbox-teal">
              <div class="icon">
              <svg width="100" height="100" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg">
                <path stroke="none" stroke-width="0" fill="#f5f5f5" d="M300,566.797414625762C385.7384707136149,576.1784315230908,478.7894351017131,552.8928747891023,531.9192734346935,484.94944893311C584.6109503024035,417.5663521118492,582.489472248146,322.67544863468447,553.9536738515405,242.03673114598146C529.1557734026468,171.96086150256528,465.24506316201064,127.66468636344209,395.9583748389544,100.7403814666027C334.2173773831606,76.7482773500951,269.4350130405921,84.62216499799875,207.1952322260088,107.2889140133804C132.92018162631612,134.33871894543012,41.79353780512637,160.00259165414826,22.644507872594943,236.69541883565114C3.319112789854554,314.0945973066697,72.72355303640163,379.243833228382,124.04198916343866,440.3218312028393C172.9286146004772,498.5055451809895,224.45579914871206,558.5317968840102,300,566.797414625762"></path>
              </svg>
              <i class="bx bx-arch"></i>
              </div>
              <?php echo $service_teal->english_content;?>
            </div>
            </div>

          </div>

          </div>
        </section><!-- End Services Section -->

        <!-- ======= Testimonials Section ======= -->
        <section id="testimonials" class="testimonials section-bg">
          <div class="container" data-aos="fade-up">

          <div class="section-title">
            <h2>Testimonials</h2>
          </div>

          <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
<?php foreach($testimonial as $testimonials){?>
            <div class="swiper-slide">
              <div class="testimonial-item">
              <img src="<?php echo $testimonials->image;?>" class="testimonial-img" alt="">
              <h3><?php echo $testimonials->english_name;?></h3>
              <h4><?php echo $testimonials->english_position;?></h4>
              <p>
                <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                <?php echo $testimonials->english_comments;?>
                <i class="bx bxs-quote-alt-right quote-icon-right"></i>
              </p>
              </div>
            </div><!-- End testimonial item -->
<?php }?>
            <!-- <div class="swiper-slide">
              <div class="testimonial-item">
              <img src="<?php echo front_source();?>img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
              <h3>Sara Wilsson</h3>
              <h4>Designer</h4>
              <p>
                <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.
                <i class="bx bxs-quote-alt-right quote-icon-right"></i>
              </p>
              </div>
            </div> --><!-- End testimonial item -->

            <!-- <div class="swiper-slide">
              <div class="testimonial-item">
              <img src="<?php echo front_source();?>img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
              <h3>Jena Karlis</h3>
              <h4>Store Owner</h4>
              <p>
                <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.
                <i class="bx bxs-quote-alt-right quote-icon-right"></i>
              </p>
              </div>
            </div> --><!-- End testimonial item -->

            <!-- <div class="swiper-slide">
              <div class="testimonial-item">
              <img src="<?php echo front_source();?>img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
              <h3>Matt Brandon</h3>
              <h4>Freelancer</h4>
              <p>
                <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.
                <i class="bx bxs-quote-alt-right quote-icon-right"></i>
              </p>
              </div>
            </div> --><!-- End testimonial item -->

            <!-- <div class="swiper-slide">
              <div class="testimonial-item">
              <img src="<?php echo front_source();?>img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
              <h3>John Larson</h3>
              <h4>Entrepreneur</h4>
              <p>
                <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.
                <i class="bx bxs-quote-alt-right quote-icon-right"></i>
              </p>
              </div>
            </div> --><!-- End testimonial item -->

            </div>
            <div class="swiper-pagination"></div>
          </div>

          </div>
        </section><!-- End Testimonials Section -->

        <!-- ======= Contact Section ======= -->
        <?php $this->load->view('front/common/contac_home');?>
        <!-- End Contact Section -->

        </main><!-- End #main -->




      <!--
      =============================================
        Contact Banner
      ==============================================
      -->
      <div class="seo-contact-banner">
        <div class="round-shape-one"></div>
        <div class="round-shape-two"></div>
        <div class="d-shape">D</div>
        <div class="container">
          <h2 class="title">Do you have any Queries? <br>Contact us.</h2>
          <a href="<?php echo base_url();?>contact_us" class="contact-button">Contact Us</a>
        </div> <!-- /.contianer -->
      </div> <!-- /.seo-contact-banner -->
    
<?php  $this->load->view('front/common/footer_cms'); ?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

    

    <script type="text/javascript">
    $(document).ready(function() {

        $('#contact-form').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                },
                subject: {
                    required: true
                },
                message: {
                    required: true,
                    rangelength:[0,900]
                }
            },
            messages: {
               name: {
                    required: "Please enter name"
                },
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email address"
                },
                subject: {
                    required: "Please enter subject"
                },
                message: {
                    required: "Please enter comments",
                    rangelength: "You are allow upto 900 characters."
                }
            },
            submitHandler: function(form) {
                var response = grecaptcha.getResponse(); 
               // console.log(response);
                //recaptcha failed validation
                if (response.length == 0 || response.length=='') {
                 $('#cp_error').css('display','block');
                    $('#cp_error').html('Please Verify here');
                    return false;
                }
                //recaptcha passed validation
                else {
                    $('#cp_error').html('');
                    form.submit();
                }
                //
            }
            
        });
    });

  
</script>