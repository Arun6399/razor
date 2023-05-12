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
    // cacheBooster();
  });

// function toLight(){
//   $("body").removeClass('cpm_dark_mode');
// }

// toLight();















function dark(){


  var darklight = '<div class=cpm_the_ch_st><div class="cpm_the_ch_li cpm_lgt_cpm_btns"><img src=assets/front/images/thico-1.png></div><div class="cpm_the_ch_li cpm_drk_cpm_btns"><img src=assets/front/images/thico-2.png></div></div>';

// $(darklight).appendTo("header");

  var mods = document.cookie.split("mode=")[1];

  if(mods ==="dark"){
      $("body").addClass("cpm_dark_mode");
  }else if(mods ==="light"){
      $("body").removeClass("cpm_dark_mode");
  }
  else if(mods === undefined){
    $("body").addClass("cpm_dark_mode");
  }

}
dark();


$(".cpm_drk_cpm_btns").click(function(){

 $("body").addClass("cpm_dark_mode");
  document.cookie = "mode=dark";
});

$(".cpm_lgt_cpm_btns").click(function(){
  $("body").removeClass("cpm_dark_mode");
  document.cookie = "mode=light";
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


      $(".Sellclass").hide();
      $(".Buyclass").show();


    var colr_cookie = getCookie('mode');
    if(colr_cookie=='dark')
    {
      $("body").addClass("cpm_dark_mode");
    }
    else if(colr_cookie=='' || colr_cookie==undefined)
    {
       $("body").addClass("cpm_dark_mode");
    }
    else
    {
        $("body").removeClass("cpm_dark_mode");
    }
    // console.log(colr_cookie);

  });


function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}


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
  console.log($(".cpm_exp_pnl").length);
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




// Swap Releated

$(document).ready(function(){
  $(".scpm_wt_coin_total_set_body_inp").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    
    $(this).closest(".scpm_wt_coin_total_set_center").find(".scpm_wt_coin_total_set_li").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});





$( "body" ).delegate( ".scpm_wt_coin_total_set_li", "click", function() {
  var coinname = $(this).find(".scpm_wt_coin_total_set_li_1").text();
  var coinimg = $(this).find(".scpm_wt_coin_total_set_li_img").attr("src");
  $(this).closest(".scpm_wt_inp_set").find(".scpm_wt_coin_lbl").text(coinname);
  $(this).closest(".scpm_wt_inp_set").find(".scpm_wt_coin_img").attr("src", coinimg);
  $(this).closest(".scpm_wt_coin_total_set").hide();
  
});


$( "body" ).delegate( ".scpm_wt_coin_set", "click", function() {


  $(this).closest(".scpm_wt_inp_set").find(".scpm_wt_coin_total_set").show();
});

$( "body" ).delegate( ".scpm_wt_coin_total_set_top", "click", function() {
  $(this).closest(".scpm_wt_coin_total_set").hide();
});

$( "body" ).delegate( ".scpm_wt_coin_total_set_top i", "click", function() {
  $(this).closest(".scpm_wt_coin_total_set").hide();
});



$( "body" ).delegate( ".scpm_wt_tab_head-1", "click", function() {

  $(this).addClass("scpm_wt_tab_head_act");
  $(".scpm_wt_tab_head-2").removeClass("scpm_wt_tab_head_act"); 
  $(".scmpwc-1").addClass("scpm_wt_tab_body_set_act");
  $(".scmpwc-2").removeClass("scpm_wt_tab_body_set_act");
});

$( "body" ).delegate( ".scpm_wt_tab_head-2", "click", function() {
  $(this).addClass("scpm_wt_tab_head_act");
  $(".scpm_wt_tab_head-1").removeClass("scpm_wt_tab_head_act"); 
  $(".scmpwc-2").addClass("scpm_wt_tab_body_set_act");
  $(".scmpwc-1").removeClass("scpm_wt_tab_body_set_act");
});


$( "body" ).delegate( ".scpmwtreth-1", "click", function() {

  $(this).addClass("scpm_wt_repo_tab_head_act");
  $(".scpmwtreth-2").removeClass("scpm_wt_repo_tab_head_act");  
  $(".scpmwtrebdy-1").addClass("scpm_wt_repo_tab_body_act");
  $(".scpmwtrebdy-2").removeClass("scpm_wt_repo_tab_body_act");
});

$( "body" ).delegate( ".scpmwtreth-2", "click", function() {

  $(this).addClass("scpm_wt_repo_tab_head_act");
  $(".scpmwtreth-1").removeClass("scpm_wt_repo_tab_head_act");  
  $(".scpmwtrebdy-2").addClass("scpm_wt_repo_tab_body_act");
  $(".scpmwtrebdy-1").removeClass("scpm_wt_repo_tab_body_act");
});




$( "body" ).delegate( ".scpm_new_modal_pane_cnt_h1_cls", "click", function() {
  $(this).closest(".scpm_new_modal_pane").removeClass("scpm_new_modal_pane_act");
});

$( "body" ).delegate( ".scpm_new_modal_btn", "click", function() {
  $(".scpm_new_modal_pane").removeClass("scpm_new_modal_pane_act");
  var nam = $(this).attr("data-modname");
  $(".scpm_new_modal_pane").each(function(){
    if($(this).attr("data-modname") == nam){
      $(".scpm_new_modal_pane").addClass("scpm_new_modal_pane_act");
    }
  });
});


$( "body" ).delegate( ".scpm_cr_new_modal_pane_cnt_h1_cls", "click", function() {
  $(this).closest(".scpm_cr_new_modal_pane").removeClass("scpm_cr_new_modal_pane_act");
}); 



// Chart Js

function chttmchk(){
  var inpf = '<input type="text" class="scpm_cht_inp_input" value="#1c1f2d"><input type="text" class="scpm_cht_inp_input2" value="dark2">';
  $("body").append(inpf);
};
// chttmchk();
function chtthem(){
  if($("body").hasClass("cpm_dark_mode")){
    $(".scpm_cht_inp_input").val("#1c1f2d");
    $(".scpm_cht_inp_input2").val("dark2");
    loadcht();
  }else{
    $(".scpm_cht_inp_input").val("#f1f1f1");  
    $(".scpm_cht_inp_input2").val("light2");
    loadcht();
  }
};

$( "body" ).delegate( ".cpm_the_ch_st", "click", function() {
// $(".cpm_the_ch_st").click(function(){
chtthem();
});



function loadcht() {
  var dataPoints = [];

  var stockChartOptions = {
  zoomEnabled: true,
  backgroundColor: $(".scpm_cht_inp_input").val(),
  theme: $(".scpm_cht_inp_input2").val(),
  navigator: {
enabled: false, 
},
slider: {
enabled: false, 
},
rangeSelector: {
label: "",
buttonStyle: {
      backgroundColor: "#1e90ff0",
  backgroundColorOnSelect: "#1e90ff",
  backgroundColorOnHover: "#166ec4",
  borderThickness: 0,
  width: 35,
  padding: 4,
    },

selectedRangeButtonIndex: 1,  
    buttons: [{
      range: 1, 
      rangeType: "week",
      label: "1W"
    },{            
      range: 2,
      rangeType: "month",
      label: "1M"
    },{        
  range: 3,    
      rangeType: "all",
      label: "All" //Change it to "All"
      
    }],
    inputFields: {
     enabled:false,
    }
  },
  charts: [{
  

    axisX: {
    crosshair: {
      enabled: false,
      snapToDataPoint: false,
      valueFormatString: "DD MMM YYYY",
    }
    },
    axisY:{
  lineThickness: 0,
  gridThickness: 0,
  tickLength: 0,
  labelFormatter: function(e) {
      return "";
  }
},
    toolTip: {
    shared: true
    },
    data: [{
    color: "#1e90ff",
    type: "splineArea",
    yValueFormatString: "$#,###.##",
    dataPoints : dataPoints
    }]
  }],
  

  }
  $.getJSON("https://canvasjs.com/data/docs/ltceur2018.json", function(data) {
  for(var i = 0; i < data.length; i++){
    dataPoints.push({x: new Date(data[i].date), y: Number(data[i].close)});
  }
  $("#scpm_chartContainer").CanvasJSStockChart(stockChartOptions);
  });
};
loadcht();