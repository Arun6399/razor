<div class="panel-body">
    <div class="table-responsive">
        <table id="data-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center">Currency Name</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($fiat->num_rows() > 0) {
                    $i = 1;
                    foreach($fiat->result() as $result) {
                        echo '<tr>';
                        echo '<td>' . $i . '</td>';
                        echo '<td>' . $result->currency_name .'</td>';
                        echo '<td>';
                        echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
                        echo '<a href="' . admin_url() . 'service/delete_fiat/' . $result->id . '" title="Delete this service"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
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