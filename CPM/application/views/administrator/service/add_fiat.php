<div class="panel-body">
    <?php 
        $attributes=array('class'=>'form-horizontal','id'=>'addfiat');
        echo form_open_multipart($action,$attributes);
    ?>
    <fieldset>
        <div class="form-group">
            <label class="col-md-4 control-label">Currency Name</label>
            <div class="col-md-4">
                <input type="text" name="currency_name" id="currency_name" class="form-control"
                    placeholder="Currency Name"  />
            </div>
        </div>
         <div class="form-group">
            <label class="col-md-4 control-label">Currency Symbol</label>
            <div class="col-md-4">
                <input type="text" name="currency_symbol" id="currency_symbol" class="form-control"
                    placeholder="Currency Symbol"  />
            </div>
        </div>

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
