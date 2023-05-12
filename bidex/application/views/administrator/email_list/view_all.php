<div class="panel-body">
    <div class="table-responsive">
        <table id="datas-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /*if ($email_list->num_rows() > 0) {
                    $i = 1;
                    foreach($email_list->result() as $result) {
                        echo '<tr>';
                        echo '<td>' . $i . '</td>';
                        echo '<td>' . $result->type .'</td>';
                        echo '<td>' . decryptIt($result->smtp_email) .'</td>';
                        
                        echo '<td>';
                        echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
                        echo '<a href="' . admin_url() . 'email_list/edit/' . $result->id . '" title="Edit this Email"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                        echo '</td>';
                        echo '</tr>';
                        $i++;
                    }                   
                } else {
                    echo '<tr><td colspan="4 class="text-center">No Emails added yet!</td></tr>';
                }*/
                ?>
            </tbody>
        </table>
    </div>
</div>