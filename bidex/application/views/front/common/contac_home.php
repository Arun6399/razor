<?php  $site_common = site_common();
$user_id = $this->session->userdata('user_id');
$sitelogo = $site_common['site_settings']->site_logo;
$favicon = $site_common['site_settings']->site_favicon;

?>


<!-- ======= Contact Section ======= -->
        <section id="contact" class="contact">
          <div class="container" data-aos="fade-up">

          <div class="section-title">
            <h2>Contact</h2>
          </div>

          <div class="row mt-1">

            <div class="col-lg-4">
            <div class="info">
              <div class="address">
              <i class="bi bi-geo-alt"></i>
              <h4>Location:</h4>
              <p><?php echo $site_common['site_settings']->address;?></p>
              </div>

              <div class="email">
              <i class="bi bi-envelope"></i>
              <h4>Email:</h4>
              <p><?php echo $site_common['site_settings']->site_email;?></p>
              </div>

              <div class="phone">
              <i class="bi bi-phone"></i>
              <h4>Call:</h4>
              <p><?php echo $site_common['site_settings']->contactno;?></p>
              </div>

            </div>

            </div>

            <div class="col-lg-8 mt-5 mt-lg-0">


              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2482.9078770408487!2d-0.12576968460740792!3d51.51490607963636!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487604ccabd74e61%3A0x9f541e2bfa56e4dd!2s71%20Shelton%20St%2C%20London%20WC2H%209JQ%2C%20UK!5e0!3m2!1sen!2sin!4v1650951199598!5m2!1sen!2sin" width="600" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>





<!--             <form action="<?php echo base_url();?>contac_home" method="post" role="form" class="php-email-form" id="contact-form">
              <div class="row">
              <div class="col-md-6 form-group">
                <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" >
              </div>
              <div class="col-md-6 form-group mt-3 mt-md-0">
                <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" >
              </div>
              </div>
              <div class="form-group mt-3">
              <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" >
              </div>
              <div class="form-group mt-3">
              <textarea class="form-control" name="message" rows="5" placeholder="Message" ></textarea>
              </div>


              <div class="form-group text-left">
                  <div class="g-recaptcha" id="g-recaptcha" data-sitekey="6LdKDyEfAAAAABa2nRE4xeBT1ml4ggKrR4J2R6uk"></div>
              </div>

              <label id="cp_error" class="error"></label>

              <div class="text-center"><button type="submit" name="submit" id="submit">Send Message</button></div>
            </form> -->

            </div>

          </div>

          </div>
        </section><!-- End Contact Section -->

        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script> -->

    
