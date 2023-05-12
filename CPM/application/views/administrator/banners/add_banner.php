<div class="panel-body">
    <?php 
        $attributes=array('class'=>'form-horizontal','id'=>'banners','autocomplete'=>'off');
        echo form_open_multipart($action,$attributes);
    ?>
    <fieldset>

        <div class="form-group">
            <label class="col-md-4 control-label">Banner Title</label>
            <div class="col-md-4">
                <input type="text" name="banner_name" class="form-control" id="banner_name" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">Image</label>
            <div class="col-md-4">
                <input type="file" name="image" id="image" />
                <span> Upload Banner Image size(515*228 Pixels)</span>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-md-4 control-label">Status</label>
            <div class="col-md-4">
                <select id="status" name="status" class="selectpicker form-control" data-live-search="true">
                    <option value="1">Active</option>
                    <option value="0">De-active</option>
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
