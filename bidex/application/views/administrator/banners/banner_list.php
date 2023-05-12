<div class="panel-body">
    <div class="table-responsive">
        <table id="datas-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center">Title</th>
                    <!-- <th class="text-center">Expiry Date</th> -->
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /*if ($banners->num_rows() > 0) {
                    $i = 1;
                    foreach($banners->result() as $result) {
                        echo '<tr>';
                        echo '<td>' . $i . '</td>';
                        echo '<td>' . $result->position .'</td>';
                        if ($result->status == 1) {
                            $status = '<label class="label label-info">Activated</label>';
                            $extra = array('title' => 'De-activate this Partner');
                            $changeStatus = anchor(admin_url().'banner/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
                        } else {
                            $status = '<label class="label label-danger">De-Activated</label>';
                            $extra = array('title' => 'Activate this Partner');
                            $changeStatus = anchor(admin_url().'banner/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
                        }
                        echo '<td>'.$status.'</td>';
                        echo '<td>';
                        echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
                        echo '<a href="' . admin_url() . 'banner/edit/' . $result->id . '" title="Edit this Partner"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                        echo '<a href="' . admin_url() . 'banner/delete/' . $result->id . '" title="Delete this Partner"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
                        echo '</td>';
                        echo '</tr>';
                        $i++;
                    }                   
                } else {
                    echo '<tr><td colspan="4" class="text-center">No Partners added yet!</td></tr>';
                }*/
                ?>
            </tbody>
        </table>
    </div>
</div>