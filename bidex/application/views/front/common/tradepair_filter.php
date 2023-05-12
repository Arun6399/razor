 <?php 
if(isset($currency_pair) && !empty($currency_pair)){
 
    foreach ($currency_pair as $key => $pair_details) {
      if($pair_details->status==1) {
    $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol_id))->row();
    $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol_id))->row();
    $pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;
    $pair_url = $from_currency->currency_symbol.'_'.$to_currency->currency_symbol;
    $class=($pair_details->priceChangePercent>0)?'grn':'rdn';
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
      <td><a href="<?php echo base_url();?>

exchange/#/<?=$pair_url?>" style="display: flex;"><img width="20px;" src="<?=$currency->image;?>" alt="<?=$currency->image;?>" class="table-cryp">&nbsp;&nbsp; <?=$pair_symbol?></a></td>
      <td><?php echo TrimTrailingZeroes($pair_details->lastPrice);?></td>
      <td><span class="grn"><span class="<?php echo($pair_details->priceChangePercent>0)?'grn':'rdn';?>"><?php echo number_format($pair_details->priceChangePercent,2);?>%</span></span></td>
      <td><?php echo TrimTrailingZeroes($pair_details->change_high);?></td>
      <td><?php echo TrimTrailingZeroes($pair_details->change_low);?></td>
      <td><?php echo TrimTrailingZeroes($pair_details->volume);?></td>
      <td><a href="<?php echo base_url();?>exchange#/<?=$pair_url?>"><span    class="btn btn-primary">Trade</span></a></td>

     
    </tr>     

<?php }}} else {?>
    <tr>
      <td colspan="7" style="text-align:center;">No record found...</td>
    </tr>
<?php } ?>
