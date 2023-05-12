<div class="panel-body">
    <?php 
        $attributes=array('class'=>'form-horizontal','id'=>'service');
        echo form_open_multipart($action,$attributes);
    ?>
    <fieldset>
        <div class="form-group">
            <label class="col-md-4 control-label">Service Name</label>
            <div class="col-md-4">
                <input type="text" name="service_name" id="service_name" class="form-control"
                    placeholder="Service Name"  />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Currency</label>
            <div class="col-md-4">

                   <input type="hidden" name="service_commission" id="service_commission" class="form-control"
                 value="1" />

                <input type="text" name="service_currency" id="service_currency" class="form-control"
                    placeholder="Service Symbol"  />
            </div>
        </div>


      
  <!--       <div class="form-group">
            <label class="col-md-4 control-label">Commission</label>
            <div class="col-md-4">
                <input type="text" name="service_commission" id="service_commission" class="form-control"
                    placeholder="Currency Commission" />
            </div>
        </div> -->

        <div class="form-group">
            <label class="col-md-4 control-label">Status</label>
            <div class="col-md-4">
                <select id="status" name="status" class="form-control">
                    <option  value="1">Active</option>
                    <option  value="0">De-active
                    </option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
            </div>
        </div>
    </fieldset>
    <?php echo form_close(); ?>
</div>
