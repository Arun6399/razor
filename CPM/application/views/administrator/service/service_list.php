<div class="panel-body">
    <div class="table-responsive">
        <table id="data-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center">Service Name</th>
                    <th class="text-center">Currency</th>
                    <th class="text-center">Commission</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($service->num_rows() > 0) {
                    $i = 1;
                    foreach($service->result() as $result) {
                        echo '<tr>';
                        echo '<td>' . $i . '</td>';
                        echo '<td>' . $result->service_name .'</td>';
                        echo '<td>' . $result->currency .'</td>';
                        echo '<td>' . $result->commission .'</td>';
                        
                        if ($result->status == 1) {
                            $status = '<label class="label label-info">Activated</label>';
                            $extra = array('title' => 'De-activate this service');
                            $changeStatus = anchor(admin_url().'service/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
                        } else {
                            $status = '<label class="label label-danger">De-Activated</label>';
                            $extra = array('title' => 'Activate this currency');
                            $changeStatus = anchor(admin_url().'service/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
                        }
                        echo '<td>'.$status.'</td>';
                        echo '<td>';
                        echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
                        echo '<a href="' . admin_url() . 'service/edit/' . $result->id . '" title="Edit this service"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                        echo '<a href="' . admin_url() . 'service/delete/' . $result->id . '" title="Delete this service"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
                        echo '</td>';
                        echo '</tr>';
                        $i++;
                    }					
                } else {
                    echo '<tr><td></td><td></td><td></td><td colspan="2" class="text-center">No currency added yet!</td><td></td><td></td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>