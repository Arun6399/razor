<div class="panel-body">
    <?php $attributes=array('class'=>'form-horizontal','id'=>'edit_email');
				echo form_open_multipart($action,$attributes); ?>
    <fieldset>
        <div class="form-group">
            <label class="col-md-4 control-label">SMTP Email</label>
            <div class="col-md-4">
                <input type="text" name="smtp_email" id="smtp_email" class="form-control"
                    placeholder="SMTP Name" value="<?php echo decryptIt($email_list->smtp_email); ?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label">SMTP Password</label>
            <div class="col-md-4">
                <input type="text" name="smtp_password" id="smtp_password" class="form-control"
                    placeholder="SMTP Password" value="<?php echo decryptIt($email_list->smtp_password); ?>" />
            </div>
        </div>
       

        <div class="form-group">
            <label class="col-md-4 control-label">SMTP Host</label>
            <div class="col-md-4">
                <input type="text" name="smtp_host" id="smtp_host" class="form-control"
                    placeholder="SMTP Host" value="<?php echo decryptIt($email_list->smtp_host); ?>" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">SMTP Port</label>
            <div class="col-md-4">
                <input type="text" name="smtp_port" id="smtp_port" class="form-control"
                    placeholder="SMTP Port" value="<?php echo decryptIt($email_list->smtp_port); ?>" />
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