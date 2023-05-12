<div class="panel-body">
    <?php $attributes=array('class'=>'form-horizontal','id'=>'edit_service');
				echo form_open_multipart($action,$attributes); ?>
    <fieldset>
        <div class="form-group">
            <label class="col-md-4 control-label">Service Name</label>
            <div class="col-md-4">
                <input type="text" name="service_name" id="service_name" class="form-control"
                    placeholder="Service Name" value="<?php echo $service->service_name; ?>"  />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Currency</label>
            <div class="col-md-4">
                <input type="text" name="service_currency" id="service_currency" class="form-control"
                    placeholder="Service Symbol" value="<?php echo $service->currency; ?>" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Commission</label>
            <div class="col-md-4">
                <input type="text" name="service_commission" id="service_commission" class="form-control"
                    placeholder="Currency Commission" value="<?php echo $service->commission; ?>" />
            </div>
        </div>

        <!--<div class="form-group">
            <label class="col-md-4 control-label">Image</label>
            <div class="col-md-4">
                <input type="file" name="image" id="image" />
                <?php $im = $currency->image; ?>
                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $currency->image; ?>" />
                <?php if($currency->image!='') { ?>
                <img src="<?php echo $im; ?>" style="width:65px;height:65px;" />
                <?php } ?>
            </div>
        </div>-->
        <div class="form-group">
            <label class="col-md-4 control-label">Status</label>
            <div class="col-md-4">
                <select id="status" name="status" class="form-control">
                    <option <?php if($service->status==1){echo 'selected';}?> value="1">Active</option>
                    <option <?php if($service->status==0){echo 'selected';}?> value="0">De-active
                    </option>
                </select>
            </div>
        </div>
<div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
            </div>
        </div>

        </div>

    </fieldset>
    <?php echo form_close(); ?>
</div>