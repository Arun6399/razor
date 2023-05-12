<div class="footer dashboard" style="margin-top: 200px;">
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
                                <li><a href="<?php echo $site_common['site_settings']->facebooklink;?>"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="<?php echo $site_common['site_settings']->twitterlink;?>"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="<?php echo $site_common['site_settings']->linkedin_link;?>"><i class="fa fa-linkedin"></i></a></li>
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



    <script src="<?php echo base_url();?>assets/js/global.js"></script>

    <script src="<?php echo base_url();?>assets/vendor/waves/waves.min.js"></script>
    <script src="<?php echo base_url();?>assets/vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/plugins/jquery-ui-init.js"></script>
    <script src="<?php echo base_url();?>assets/vendor/validator/jquery.validate.js"></script>
    <script src="<?php echo base_url();?>assets/vendor/validator/validator-init.js"></script>

    <script src="<?php echo base_url();?>assets/js/scripts.js"></script>
    

    <script src="<?php echo base_url();?>assets/front/js/jquery.growl.js"></script>
    <script type="text/javascript">
        var BASE_URL = '<?=base_url()?>';
    </script>

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

    // hide all contents accept from the first div
    $('.tabContent div:not(:first)').toggle();

    // hide the previous button
    $('.previous').hide();

    $('.tabs li').click(function () {

        if ($(this).is(':last-child')) {
            $('.next').hide();
        } else {
            $('.next').show();
        }

        if ($(this).is(':first-child')) {
            $('.previous').hide();
        } else {
            $('.previous').show();
        }

        var position = $(this).position();
        var corresponding = $(this).data("id");

        // scroll to clicked tab with a little gap left to show previous tabs
        scroll = $('.tabs').scrollLeft();
        $('.tabs').animate({
            'scrollLeft': scroll + position.left - 30
        }, 200);

        // hide all content divs
        $('.tabContent div').hide();

        // show content of corresponding tab
        $('div.' + corresponding).toggle();

        // remove active class from currently not active tabs
        $('.tabs li').removeClass('active');

        // add active class to clicked tab
        $(this).addClass('active');
    });

$('div a').click(function(e){
    // e.preventDefault();
    $('li.active').next('li').trigger('click');
});
$('.next').click(function(e){
    // e.preventDefault();
    $('li.active').next('li').trigger('click');
});
$('.previous').click(function(e){
    // e.preventDefault();
    $('li.active').prev('li').trigger('click');
});
</script>



</body>


<!-- Mirrored from demo.quixlab.com/tradio-html/account-overview.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 22 Jan 2022 05:43:48 GMT -->
</html>