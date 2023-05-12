<?php $this->load->view('front/common/header');?>
<div class="solid-inner-banner" >
        <h2 class="page-title" style="color: white;">Markets</h2>
        <!-- <ul class="page-breadcrumbs">
          <li><a href="index.html">Home</a></li>
          <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
          <li>Markets</li>
        </ul> -->
      </div> <!-- /.solid-inner-banner -->



      <!--
      =============================================
        Contact Us
      ==============================================
      -->
        <!-- ======= Portfolio Section ======= -->
        <section id="portfolio" class="portfolio section-bg">

          <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-8">
              <ul class="nav nav-tabs sym-pair" role="tablist">
                <li class="nav-item">
                  <a class="nav-link " data-toggle="tab" href="#tabs-1" role="tab" onclick="reloadFav()">Fav</a>
                </li>
                <?php $i=2; foreach ($currency_symbol as $currencypair) {
                  if($i==2){ $cls="active"; } else { $cls="";}
                ?>
                <li class="nav-item <?=$cls;?>">
                  <a class="nav-link" data-toggle="tab" href="#tabs-<?php echo $i;?>" onclick="get_currency('<?=$currencypair->currency_symbol?>')" role="tab"><?=$currencypair->currency_symbol;?></a>
                </li>
                <?php $i++;}?>  
                <!-- <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">BTC</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab">USD</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#tabs-5" role="tab">ALL</a>
                </li> -->
              </ul><!-- Tab panes -->
</div>
<div class="col-lg-2">
  <div class="search">
    <input type="text" class="searchTerm" placeholder="Search" onkeyup="filterPair(this)">
    <button type="submit" class="searchButton">
      <i class="fa fa-search"></i>
     </button>
   </div>
</div>
<div class="col-lg-1">
<!--   <select name="fiat" id="fiat" style="height: 36px;">
    <option value="usd">USD</option>
    <option value="inr">INR</option>
    <option value="eur">EUR</option>
    </select> -->
</div>

<!-- <div class="col-lg-1"></div> -->

<div class="col-lg-1"></div>
 <div class="col-12 col-lg-12" id="preloader" style="display:none;text-align:center;">
      
        </div>
            <div class="col-lg-10">

              <div class="tab-content">
                <div class="tab-pane " id="tabs-1" role="tabpanel">
                <div class="card">
                <!-- <div class="card-header">
                <h4 class="card-title">Markets</h4>
                </div> -->
                <div class="card-body">
                <div class="table-responsive">
                  <table class="table card-table table-vcenter text-nowrap datatable" id="Fav-table">
                    <thead>
                    <tr>
                      <!-- <th></th> -->
                      <th class="tabhead">Name <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                      <!-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="6 15 12 9 18 15"></polyline></svg> -->
                      </th>
                      <th>Price</th>
                      <th>24H Change</th>
                      <th>24H High</th>
                      <th>24H Low</th>
                      <th>Volume</th>
                      <th>Trade</th>


                    </tr>
                    </thead>
                    <tbody id="Fav-pair">
                       <?php if(isset($favpairs) && !empty($favpairs)){
                        foreach($favpairs as $key=> $pair_details){

                    //$from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol))->row();

                    //$to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol))->row();
                    //$pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;
                    
                    $currency = getcryptocurrencydetail($from_currency->id);

                    $from_id = $this->common_model->getTableData('trade_pairs',array('id' => $pair_details->pair_id))->row();
                    $from_currency = $this->common_model->getTableData('currency',array('id' => $from_id->from_symbol_id))->row();
                    $to_id = $this->common_model->getTableData('trade_pairs',array('id' => $pair_details->pair_id))->row();
                    $to_currency = $this->common_model->getTableData('currency',array('id' => $to_id->to_symbol_id))->row();
                    $pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;
                    $currency = getcryptocurrencydetail($from_currency->id);
                    if($from_id->status==1){
                    // $to_id = $this->common_model->getTableData('trade_pairs',array('to_symbol_id' => $pair_details->to_symbol))->row();

                      $pair_url = $from_currency->currency_symbol.'_'.$to_currency->currency_symbol;
                        ?>  
                      <tr>
                        <!-- <td><img src="images/star.png"  width="16px"> </td> -->
                        <td><a href="<?php echo base_url();?>exchange/#/<?php echo $pair_url; ?>" style="display: flex;"><img src="<?=$currency->image;?>" alt="<?=$currency->image;?>" class="table-cryp" width="20px;">&nbsp;&nbsp; <?=$pair_symbol?></a></td>


                        <td><?php echo TrimTrailingZeroes($from_id->lastPrice);?> </td>
                        <td><span class="grn"><span class="<?php echo($pair_details->priceChangePercent>0)?'grn':'rdn';?>"><?php echo number_format($from_id->priceChangePercent,2);?>%</span></span></td>
                        <td><?php echo TrimTrailingZeroes($from_id->change_high);?></td>
                        <td><?php echo TrimTrailingZeroes($from_id->change_low);?></td>
                        <td><?php echo TrimTrailingZeroes($from_id->volume);?></td>
                        <td><a href="<?php echo base_url();?>exchange/#/<?=$pair_url;?>"><span    class="btn btn-primary">Trade</span></a></td>
                      </tr>
<?php }}} else{?>
  <tr>
    <td colspan="8" style="text-align: center;">No Records Found</td>
  </tr>
<?php }?>
                    </tbody>
                  </table>
                </div>
                </div>
                </div>
                </div>

                <div class="tab-pane active" id="tabs-2" role="tabpanel">
                  <div class="card">
                  <!-- <div class="card-header">
                  <h4 class="card-title">Markets</h4>
                  </div> -->

                  <div class="card-body">
                  <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable" id="BTC-table">
                      <thead>
                      <tr>
                        <th></th> 
                        <th class="tabhead">Name <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-dark icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><polyline points="6 15 12 9 18 15"></polyline></svg> -->
                        </th>
                        <th>Price</th>
                        <th>24H Change</th>
                        <th>24H High</th>
                        <th>24H Low</th>
                        <th>Volume</th>
                         <th>Trade</th>


                      </tr>
                      </thead>
                      <tbody id="BTC-pair">
                        <?php if(isset($currency_pair) && !empty($currency_pair)){
                        foreach($currency_pair as $key=> $pair_details){

                          if(($pair_details->to_symbol_id==1) && ($pair_details->status==1)) {
                    $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol_id))->row();
                    $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol_id))->row();
                    $pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;
                    $pair_url = $from_currency->currency_symbol.'_'.$to_currency->currency_symbol;
                    $currency = getcryptocurrencydetail($from_currency->id);
                    
                        ?>  
                            <tr class="table-row">
                              <?php 
                              $check = check_fav($pair_details->id);
                              if($check){
                                $class="";

                                }else{
                                 $class="-o";
                                }


                              ?>
                              <td id="<?php echo $pair_details->id;?>"><a href="#" class="star-check" onclick="favorite_trade(this,'<?php echo $pair_details->id;?>','<?php echo $pair_symbol;?>')"><i class="myclass1 fa fa-star<?php echo $class;?>"></i></a></td>

                              <td ><a class="d-flex" href="<?php echo base_url();?>exchange/#/<?=$pair_url;?>"><img src="<?=$currency->image;?>" alt="<?=$currency->image;?>" class="table-cryp" width="20px;"><span>&nbsp;&nbsp;  <?=$pair_symbol?></a></span></td>

                              <td><?php echo TrimTrailingZeroes($pair_details->lastPrice);?></td>
                              <td><span class="grn"><span class="<?php echo($pair_details->priceChangePercent>0)?'grn':'rdn';?>"><?php echo number_format($pair_details->priceChangePercent,2);?>%</span></span></td>
                              <td><?php echo TrimTrailingZeroes($pair_details->change_high);?></td>
                              <td><?php echo TrimTrailingZeroes($pair_details->change_low);?></td>
                              <td><?php echo TrimTrailingZeroes($pair_details->volume);?></td>
                               <td><a href="<?php echo base_url();?>exchange/#/<?=$pair_url;?>"><span    class="btn btn-primary">Trade</span></a></td>
                            
                              </tr>
                          <?php }}}?>   

                      </tbody>
                    </table>
                  </div>
                  </div>
                  </div>
                  </div>
             <?php 
             $getCurrencies = $this->common_model->getTableData('currency',array('status' =>1))->result(); 
             if($getCurrencies): $i =3; foreach($getCurrencies as $getCur){
              if($getCur->currency_symbol !='BTC'){
             ?>    
             <div class="tab-pane" id="tabs-<?=$i++?>" role="tabpanel">
                    <div class="card">
                    <div class="card-body">
                    <div class="table-responsive">
                      <table class="table card-table table-vcenter text-nowrap datatable" id="<?=$getCur->currency_symbol?>-table">
                        <thead>
                        <tr>
                          <th></th> 
                          <th class="tabhead">Name</th>
                          <th>Price</th>
                          <th>24H Change</th>
                          <th>24H High</th>
                          <th>24H Low</th>
                          <th>Volume</th>
                          <th>Trade</th>
                        </tr>
                        </thead>
                        <tbody id="<?=$getCur->currency_symbol?>-pair">
                        </tbody>
                      </table>
                    </div>
                    </div>
                    </div>
                    </div>
                  <?php } } endif;?>

            </div>



            </div>

<div class="padding:50px"></div>


         
        </section><!-- End Portfolio Section -->

<?php $this->load->view('front/common/footer_cms');?>

<script type="text/javascript">
function filterPair(flter) {
  val = $(flter).val();
  sym = $('ul.sym-pair').find('li.active a').html();
  $("#"+sym+"-table > tbody > tr").filter(function() {  $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
    });
}

  base_url = '<?=base_url()?>';  
function get_currency(coin) {
  
  $.ajax({
  url: base_url+"get_pairinfo", 
  type: "POST",
  data: {coin:coin},    
  beforeSend: function() {
    // $("#preloader").css('display','block');
    // $("#preloader").fadeIn("slow");
  },
  success: function(res) {
    // console.log(res)
    if(res!=0) {
      $('#'+coin+'-pair').html(res);
    } else {
      tr = '<tr><td colspan="7" style="text-align:center;">No record found...</td></tr>';
      // console.log('#'+coin+'-pair');
      $('#'+coin+'-pair').html(tr);
    }
    
    $("#preloader").css('display','none');
    // $("#preloader").fadeOut("slow");
    
  }
});
  
}

$(".sym-pair li a").click(function() {
  $(".sym-pair li").removeClass('active');
  $(this).parent().addClass('active');
});


function favorite_trade(e,id,pair)
{
   var front='<?php echo front(); ?>';
  i = $(e).find('i');
  

  itag1 = $(e).closest('a').find('.myclass1').attr('class');
  itag = $(e).closest('a').find('.myclass1');
  if($(itag).hasClass("fa-star")) {
  	$(itag).removeClass("fa-star");
  	$(itag).addClass("fa-star-o");
  } else {
  	$(itag).removeClass("fa-star-o");
  	$(itag).addClass("fa-star");
  }  
     callAjaxFav(id,pair);
}

function callAjaxFav(id,pair) {
var front='<?php echo front(); ?>';	
  $.ajax({
  url: front+"common/favorite_adds", 
  type: "POST",
  data: {id:id, pair:pair},    
  
  success: function(res) {
   
  }
});

}

// $(document).on('click', '.star-check', function(e){
// e.preventDefault();
// var _this = $(this);
// var _class = _this.closest('tr.table-row').find('i.myclass1');
// // console.log(_class.html());
// // debugger;
// if(_class.hasClass('fa-star-o')){
// _class.removeClass('fa-star-o').addClass('fa-star');
// }else{
// _class.removeClass('fa-star').addClass('fa-star-o');

// }

// // console.log(_this.closest('tr.table-row').find('i.fa').removeClass('fa-star-o').addClass('fa-star'));
// });

function reloadFav()
{
	$("#Fav-table").load(location.href + " #Fav-table>*", "");
	$("#BTC-table").load(location.href + " #BTC-table>*", "");
}

</script>