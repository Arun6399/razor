<!-- begin #content -->
        <div id="content" class="content">
            <?php 
        $error = $this->session->flashdata('error');
        if($error != '') {
            echo '<div class="alert alert-danger">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>'.$error.'</div>';
        }
        $success = $this->session->flashdata('success');
        if($success != '') {
            echo '<div class="alert alert-success">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>'.$success.'</div>';
        } 
        ?>
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="<?php echo admin_url();?>">Home</a></li>
                <li class="active">Sub Admin</li>
            </ol>

            <h1 class="page-header">Sub Admin</h1>
            <p class="text-right m-b-10">
    
            <div class="row">
                <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                             
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">View Sub Admin</h4>
                        </div>
                    <?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                        <div class="clearfix">
                        <div class="input-group inp_grp1" style="float: left">
                      <a href="<?php echo admin_url(); ?>subadmin/add" title="Add Sudadmin" class="btn btn-success"><i class="fa fa-pencil"></i> Add Sub Admin</a>
                        </div>
                        </div>
                        <br/><br/>
                            <div class="table-responsive">
                                <table  class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Privilege</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                            <?php
                                    if ($subadmin->num_rows() > 0) {
                                    if($this->uri->segment(4)!=''){ $i = $this->uri->segment(4)+1; }else { $i = 1; }
                                    foreach($subadmin->result() as $result) {

                                        if($result->status=='1'){
                                            $status = 'Active';
                                        }
                                        else if($result->status=='0'){
                                            $status = 'Inactive';
                                        }
                                        else{
                                            $status = '---';
                                        }

                                $perm  = explode('&&&',$result->permissions);

                                $ord = array_map('ucfirst', $perm);

                                $test = str_replace('_',' ',$ord);

                                $permissions = implode(', ', $test);


                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . ucfirst($result->admin_name). '</td>';
                                        echo '<td>' . $permissions . '</td>';
                                        echo '<td>' . $status . '</td>';
                                        echo '<td><a href="' . admin_url() . 'subadmin/edit/' . $result->id . '" title="Edit Sudadmin"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                                        echo '</td></tr>';
                                        $i++;
                                    }                   
                                } else {
                                    echo '<tr>';
                                    echo '<td colspan="7">' . 'No Records Found!!' . '</td>';
                                    echo '</tr>';
                                } ?>

                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    <?php } ?>
             
                            </form>
                        </div>
              
                    </div>
                    <!-- end panel -->
                </div>
            </div>
            <!-- end row -->

    
        </div>
        <!-- end #content -->
<!-- ================== BEGIN BASE JS ================== -->
    <script src="<?php echo admin_source();?>/plugins/jquery/jquery-1.9.1.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/ckeditor/ckeditor.js"></script>
 
    <script src="<?php echo admin_source();?>/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/jquery-cookie/jquery.cookie.js"></script>
    <!-- ================== END BASE JS ================== -->
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="<?php echo admin_source();?>/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.time.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.resize.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.js"></script>
    <script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
    <script src="<?php echo admin_source();?>/js/apps.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
   
    <script>
        $(document).ready(function() {
            App.init();
        });

        function search() {
            var search = $('#search_string').val();
            var url = '<?php echo admin_url(); ?>';
            if(search!=''){
            window.location.href=url+'deposit/crypto_deposit?search_string='+search; }
            else { window.location.href=url+'deposit/crypto_deposit'; }
        }
    </script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','<?php echo admin_source()."www.google-analytics.com/analytics.js";?>','ga');
      ga('create', 'UA-53034621-1', 'auto');
      ga('send', 'pageview');
    </script>