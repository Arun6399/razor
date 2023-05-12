<div class="panel-body">
    <div class="table-responsive">
        <table id="datas-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center">User Email</th> 
                    <th class="text-center">Bonus From</th>                    
                    <th class="text-center">Transferred Bonus</th>
                    <th class="text-center">Currency</th>
                    <th class="text-center">Date Time</th>                    
                </tr>
            </thead>
            <tbody> 
             <?php
                                                if(isset($referral_history) && !empty($referral_history)){
                                                    $r=0;
                                                    foreach($referral_history as $referral){

                                                       $userrecs = $this->common_model->getTableData('users',array('id'=>$referral->userId))->row();
                                                        $referral_recs = $this->common_model->getTableData('users',array('parent_referralid'=>$userrecs->referralid))->row();

                                                        $ref_mail = getUserEmail($referral_recs->id);
                                                        $mail = getUserEmail($referral->userId);
 
                                                        $r++;
                                            ?>

                                                        <tr>
                                                            <td><?php echo $r;?></td>
                                                             
                                                             <td><?php echo $mail;?></td>
                                                             <td><?php echo $ref_mail;?></td>
                                                             <td><?php echo $referral->amount;?></td>
                                                             <td><?php echo strtoupper(getcryptocurrency($referral->currency));?></td>
                                                            <td><?php echo $referral->datetime;?></td>
                                                            
                                                            
                                                            
                                                          
                                                        </tr>   
                                            <?php
                                                    }
                                                } 
                                            ?>         
            </tbody>
        </table>
    </div>
</div>