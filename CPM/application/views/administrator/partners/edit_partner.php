<div class="panel-body">
    <?php $attributes=array('class'=>'form-horizontal','id'=>'edit_partner');
				echo form_open_multipart($action,$attributes); ?>
    <fieldset>
        <div class="form-group">
            <label class="col-md-4 control-label">Name</label>
            <div class="col-md-4">
                <input type="text" name="name" id="name" class="form-control"
                    placeholder="Partner Name" value="<?php echo $partners->name; ?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Link</label>
            <div class="col-md-4">
                <input type="text" name="link" id="link" class="form-control"
                    placeholder="Partner Link" value="<?php echo $partners->link; ?>" />
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-4 control-label">Image</label>
            <div class="col-md-4">
                <input type="file" name="image" id="image" />
                <?php $im = $partners->image; ?>
                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $partners->image; ?>" />
                <?php if($partners->image!='') { ?>
                <img src="<?php echo $im; ?>" style="width:65px;height:65px;" />
                <?php } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">Status</label>
            <div class="col-md-4">
                <select data-live-search="true" id="status" name="status" class="form-control selectpicker">
                    <option <?php if($partners->status==1){echo 'selected';}?> value="1">Active</option>
                    <option <?php if($partners->status==0){echo 'selected';}?> value="0">De-active
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