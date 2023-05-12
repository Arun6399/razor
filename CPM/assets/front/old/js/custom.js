$(".cpm_log_frm_s_input_pass_ico").click(function(){
$(this).toggleClass("fa-eye");
$(this).toggleClass("fa-eye-slash");
if($(this).closest(".cpm_log_frm_s").find(".cpm_log_frm_s_input").attr("type") === "password"){
$(this).closest(".cpm_log_frm_s").find(".cpm_log_frm_s_input").attr("type","text");
}else{
    $(this).closest(".cpm_log_frm_s").find(".cpm_log_frm_s_input").attr("type","password");
}

});


function cacheBooster(){
    var rep = /.*\?.*/;
    $('link').each(function(){
      var href = $(this).attr('href');
      if(rep.test(href)){
        $(this).attr('href', href+'&'+Date.now());
      }
      else{
        $(this).attr('href', href+'?'+Date.now());
      }
    });
    $('script').each(function(){
      var src = $(this).attr('src');
      if($(this).attr('src')){
        if(rep.test(src)){
          $(this).attr('src', src+'&'+Date.now());
        }
        else{
          $(this).attr('src', src+'?'+Date.now());
        }
      }
    });
  }

  $(function(){
    cacheBooster();
  });

  $(".cpm_exp_ch_text_fil").click(function(){
    $(".cpm_exp_ch_text_fil_in").click();
  
});
  $(".cpm_modal_buysell_ins_vidset_cls").click(function(){
    $(this).closest(".cpm_modal_buysell_ins").removeClass("cpm_modal_buysell_ins_act");
  
});
  $(".cpm_modal_buysell_in_cls").click(function(){
    $(this).closest(".cpm_modal_buysell").toggle();
  
});

// p2p modal video function
//   $(".cpm_buy_bgimg_img, .cpm_modal_buysell_ins_txt").click(function(){
//     $(this).closest(".cpm_modal_buysell_ins").addClass("cpm_modal_buysell_ins_act");
  
// });

$(".cpm_avtr_img").click(function(){
  if($(window).width() < 900){
    $(this).closest(".cpm_avtr_sec").find(".cpm_avtr_tot").slideToggle();
  }
})



  $(".cpm_sta_faq_li_hd").click(function(){
    $(this).closest(".cpm_sta_faq_li").find(".cpm_sta_faq_li_bdy").slideToggle(300);
    $(this).closest(".cpm_sta_faq_li").toggleClass("cpm_sta_faq_li_act");
});
$(".cpm_p2p_fi_lbl").click(function(){
  if($(this).find("input").prop("checked") == true){
    $(".cpm_p2p_fi_lbl").removeClass("cpm_p2p_fi_lbl_act");
    $(this).addClass("cpm_p2p_fi_lbl_act");
  }
  else{
    $(".cpm_p2p_fi_lbl").removeClass("cpm_p2p_fi_lbl_act");
  }
});

  $(".cpm_p2p_bs_li").click(function(){


    if($(this).find("input").prop("checked") == true){
      $(".cpm_p2p_bs_li").removeClass("cpm_inptchk");
      $(this).addClass("cpm_inptchk");
    }
    else{
      $(".cpm_p2p_bs_li").removeClass("cpm_inptchk");
    }

    if($(this).hasClass("cpm_bg_dang")){
    $(".Sellclass").show();
    $(".Buyclass").hide();
    }
    else{
      $(".Sellclass").hide();
      $(".Buyclass").show();
    }
  });




  $(document).ready(function(){

    // Manual Changes

     $(".Sellclass").hide();
      $(".Buyclass").show();

  });

  if(String($(window).width() > 900) == String($(window).width() < 1500)){
   
     $(".ordr_bk_in").detach().appendTo(".ordrbkin"); 
     $(".trade_ho").detach().appendTo(".tradein"); 
    }else{}

  if($(window).width() > 900){
  $(document).on('keypress',function(e) {


      if ($("input").is(":focus")) {
      
      }
      else{
        $(".cpm_key_press").each(function(){

          if(e.which == $(this).attr("data-keypr")) {
            $(this).click();
        }
          if(e.which == $(this).attr("data-keyprcap")) {
            $(this).click();
        }
        });
      }
});
}else{}




$(".cpm_mkts_tabs_hd").click(function(){
	var hdrli = $(this).attr("data-tabn");
	$(this).closest(".cpm_mkts_tabs_sets").find(".cpm_mkts_tabs_hd").removeClass("cpm_mkts_tabs_hd_act");
	$(this).addClass("cpm_mkts_tabs_hd_act");
	$(this).closest(".cpm_mkts_tabs_sets").find(".cpm_mkts_tabs_pane").removeClass("cpm_mkts_tabs_pane_act");
	$(this).closest(".cpm_mkts_tabs_sets").find(".cpm_mkts_tabs_pane").each(function(){
    var hdrlin = $(this).attr("data-tabname");
		if(hdrlin===hdrli){
			$(this).addClass("cpm_mkts_tabs_pane_act");
		}
	});

  });




$(".cpm_rep_hd_li").click(function(){
  var hdrli = $(this).attr("data-hdrname");
  $(".cpm_rep_hd_li").removeClass("cpm_rep_hd_li_act");
  $(this).addClass("cpm_rep_hd_li_act");
  $(".cpm_rep_body_set").removeClass("cpm_rep_body_act");
  $(".cpm_rep_body_set").each(function(){
      if($(this).attr("data-bdyname")===hdrli){
          $(this).addClass("cpm_rep_body_act");
      }
  });


  if($(window).width() < 800){
      $('html, body').animate({
          scrollTop: $(".cpm_rep_body_act").offset().top
      }, 1000);
  }
});



  $(".cpm_set_li").click(function(){
    var tname= $(this).attr("data-sett");
    $(".cpm_set_li").removeClass("act_th");
    $(this).addClass("act_th");
    $(".cpm_set_tabs").removeClass("act_tab");
    $(".cpm_set_tabs").each(function(){
        var thname = $(this).attr("data-settab");
      
       
        if(thname === tname){
            $(this).addClass("act_tab");
            if($(window).width() < 600){
                $('html,body').animate({
                    scrollTop: $(".cpm_setting_set").offset().top},
                    'fast');
            }
        }else{}
    });
});




  $(".cpm_kyc_img").click(function() {
    $(this).closest(".cpm_kyc_set").find(".cpm_kyc_filin").click();
});





$(".cpm_m_tab_hd_li").click(function(){
  var tname= $(this).attr("data-sett");
  $(this).siblings(".cpm_m_tab_hd_li").removeClass("cpm_m_tab_hd_li_act");
  $(this).addClass("cpm_m_tab_hd_li_act");
  $(this).closest(".cpm_m_tab_set").find(".cpm_m_tab_pan").removeClass("cpm_m_tab_pan_act");
  $(this).closest(".cpm_m_tab_set").find(".cpm_m_tab_pan").each(function(){
      var thname = $(this).attr("data-settab");
    
     
      if(thname === tname){
          $(this).addClass("cpm_m_tab_pan_act");
         
      }else{}
  });
});
$(".cpm_exp_bu_hd_li").click(function(){
  var tname= $(this).attr("data-sett");
  $(this).siblings(".cpm_exp_bu_hd_li").removeClass("cpm_exp_bu_hd_act");
  $(this).addClass("cpm_exp_bu_hd_act");
  $(this).closest(".cpm_exp_bu_sell_set").find(".cpm_exp_bu_set").removeClass("cpm_exp_bu_t_act");
  $(this).closest(".cpm_exp_bu_sell_set").find(".cpm_exp_bu_set").each(function(){
      var thname = $(this).attr("data-settab");
    
     
      if(thname === tname){
          $(this).addClass("cpm_exp_bu_t_act");
         
      }else{}
  });
});


$(document).ready(function(){
  // console.log($(".cpm_exp_pnl").length);
  if($(".cpm_exp_pnl").length > 0){
   $("footer").hide();
   $("body").addClass("cpm_main_exchange_page");
   $(".theme-main-menu").find(".container").removeClass("container").addClass("container-fluid");
  }
});






$(".cpm_exp_menu_li").click(function(){
  var tname= $(this).attr("data-mname");
  $(".cpm_exp_menu_li").removeClass("cpm_exp_menu_li_act");
  $(this).addClass("cpm_exp_menu_li_act");
  $(".cpm_exp_pnl").removeClass("cpm_mbl_d_act");
  $(".cpm_exp_pnl").each(function(){
      var thname = $(this).attr("data-mnmtab");
    
     
      if(thname === tname){
          $(this).addClass("cpm_mbl_d_act");
         
      }else{}
  });
});







$(".cpm_ex_lis_tab_li").click(function(){
  var tname= $(this).attr("data-sett");
  $(".cpm_ex_lis_tab_li").removeClass("cpm_ex_tab_sel");
  $(this).addClass("cpm_ex_tab_sel");
  $(".cpm_ex_lis_tab_pane").removeClass("cpm_act_lis_tab");
  $(".cpm_ex_lis_tab_pane").each(function(){
      var thname = $(this).attr("data-settab");
    
     
      if(thname === tname){
          $(this).addClass("cpm_act_lis_tab");
         
      }else{}
  });
});

$(document).ready(function(){
if($(window).width() < 1500){
  var cl = $(".cpm_exch_set_2").width() + 2;
$(".cpm_exp_pnl_op_box").css("width", cl);
$(".cpm_exp_pnl_op_box2").css("width", cl);}
});

$(".cpm_exp_pnl_op_btn").click(function(){
$(".cpm_exp_pnl_op_box").toggleClass("cpm_exch_t_act");
$(this).find("i").toggleClass("ti-close");
$(this).find("i").toggleClass("ti-angle-up");
});