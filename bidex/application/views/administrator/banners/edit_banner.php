<div class="panel-body">
    <?php $attributes=array('class'=>'form-horizontal','id'=>'edit_banner');
                echo form_open_multipart($action,$attributes); ?>
    <fieldset>



        <div class="form-group">
            <label class="col-md-4 control-label">Banner Name</label>
            <div class="col-md-4">
                <input type="text" name="banner_name" class="form-control" id="banner_name" value="<?php echo $banners->title;?>"/>
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-4 control-label">Image</label>
            <div class="col-md-4">
                <input type="file" name="image" id="image" />
                <span> Upload Banner Image size(515*228 Pixels)</span>
                <br>
                <?php $im = $banners->image; ?>
                <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $banners->image; ?>" />
                <?php if($banners->image!='') { ?>
                <img src="<?php echo $im; ?>" style="width:65px;height:65px;" />
                <?php } ?>
            </div>
        </div>



        <div class="form-group">
            <label class="col-md-4 control-label">Status</label>
            <div class="col-md-4">
                <select id="status" name="status" class="form-control selectpicker" data-live-search="true">
                    <option <?php if($banners->status==1){echo 'selected';}?> value="1">Active</option>
                    <option <?php if($banners->status==0){echo 'selected';}?> value="0">De-active
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