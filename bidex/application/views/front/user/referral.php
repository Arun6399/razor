<?php $this->load->view('front/common/headerlogin')?>
        <style>
          .refer {
            height: 80vh;
    width: 100%;
    box-shadow: 0 10px 20px #1a488e0f;
    padding: 9vh 3.5vw 4vh;
    background-size: cover;
          }
          
form {
  position: relative;
  width: 100%; }
  
  form input {
    display: block;  
    width: 100%;
    border: 3px solid;
    outline: 0;
    background: #FFF;
    font-size: 25px;
    padding: 5px 4px;
    margin-bottom: 20px;
    border-radius: 20px;
  }
  
  form button {
    display: block;
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    border: 0;
    outline: 0;
    color: #FFF;
    background: #262261;
    font-family: 'VT323', monospace;
    font-size: 25px;
    text-transform: uppercase;
    padding: 0.08em 0.8em;
    cursor: pointer;
    border-radius: 20px;
  }

        </style>
    

     <div class="verification section-padding mb-5 " style="padding:40px 0px">
            <div class="container h-100">
              <div class="col-12">
                <div  style="background: #fff url(<?php echo base_url(); ?>assets/images/refer.png) 50% no-repeat;        height: 80vh;
    width: 100%;
    box-shadow: 0 10px 20px #1a488e0f;
    padding: 9vh 3.5vw 4vh;
    background-size: cover;"     class="refer">
                  <div class="row">
                  <div class="col-12 col-lg-6">
                    <h5>Invite Friends &</h5>
                    <h3>Start Earnings</h3>

                    <div style="padding-top: 20px;"></div>
<p>Invite a friend to Bidex, and enjoy a lifetime of earnings<br>
  from their activity. Earn</p>
  <div style="padding-top: 20px;"></div>
                  </div>
              
                  <div class="col-12 col-lg-6">

                  </div>
                  <div class="col-12 col-lg-6">
                    <div class="row">
                      <div class="col-12 col-lg-4">
                        <h3><?php  echo  $site_common['site_settings']->referral_bonus;?>%</h3>
                          <p>of their trading fees under Pro trading</p>

                      </div>
                   <div class="col-12 col-lg-4">
<h4>Referral : <?php echo $referral_count;?></h4>
<!-- <p>of the interest paid out on their assets</p> -->

                      </div> -
<!--                       <div class="col-12 col-lg-4">
<h3>5%</h3>
<p>of the interest accrued on their loans</p>
                        
                      </div> -->
<div style="padding-top: 20px;"></div>
                      <div class="mb-3 col-xl-12">
                        <h3 class="form-label">Invite a friend </h3>
                        <!-- <div class="file-upload-wrapper" data-text="www.bidexcrypto.com/jksdhodos">
                            <input name="file-upload-field" type="text" class="file-upload-field" value="www.bidexcrypto.com/jksdhodos" disabled>
                        </div> -->
                        <div class="copy">

                        <form>
                          <input type="text" value="<?php echo base_url();?>invite?ref=<?php echo $users->referralid;?>">
                          <button type="button"> <img src="<?php echo base_url();?>assets/images/copy.png" width="16px"></button>
                        </form>
                      </div>
                    </div>
          <!--           <div class="invite">
                      <p style="text-align: center;"><a href="#" style="background-color: #262261; padding: 10px 50px;border-radius: 20px;color: white;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><g transform="translate(1.9)"><rect width="20" height="20" transform="translate(-1.9)" fill="none"/><g transform="translate(0 1)"><path d="M27.9,5.7A2.7,2.7,0,1,1,25.2,3a2.7,2.7,0,0,1,2.7,2.7Z" transform="translate(-11.7 -3)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/><path d="M9.9,16.2a2.7,2.7,0,1,1-2.7-2.7A2.7,2.7,0,0,1,9.9,16.2Z" transform="translate(-4.5 -7.2)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/><path d="M27.9,26.7A2.7,2.7,0,1,1,25.2,24,2.7,2.7,0,0,1,27.9,26.7Z" transform="translate(-11.7 -11.4)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/><path d="M12.885,20.265l6.147,3.582" transform="translate(-7.854 -9.906)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/><path d="M19.023,9.765l-6.138,3.582" transform="translate(-7.854 -5.706)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/></g></g></svg><span style=" margin-left: 10px;">Invite</span></a></p>
                    </div> -->
                    </div>
                  </div>
                </div>
                </div>
              </div>

            </div>
        </div>
        <div class="footer dashboard">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-12 col-lg-4"></div>
                    <div class="col-sm-4 col-12 col-lg-4">
                        <div class="copyright">
                            <p>© Copyright
                                <script>
                                    var CurrentYear = new Date().getFullYear()
                                    document.write(CurrentYear)
                                </script> <a href="#">Bidex</a> I All
                                Rights Reserved
                            </p>
                        </div>
                        <div class="footer-social">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12 col-lg-4">
                        <!-- <div class="footer-social">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>assets/js/global.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
    <script>
      (function() {
  var copyButton = document.querySelector('.copy button');
  var copyInput = document.querySelector('.copy input');
  copyButton.addEventListener('click', function(e) {
    e.preventDefault();
    var text = copyInput.select();
    document.execCommand('copy');
  });

  copyInput.addEventListener('click', function() {
    this.select();
  });
})();
    </script>
</body>


</html>