<script type="text/javascript" src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<div class="box">
    <div class="box-table">
        <?php
        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        echo show_alert_message(element('message', $view), '<div class="alert alert-warning">', '</div>');
        $attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
        echo form_open_multipart(current_full_url(), $attributes);
        ?>
            <input type="hidden" name="<?php echo element('primary_key', $view); ?>"    value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">User ID</label>
                    <div class="col-sm-10 form-inline">
                        <input type="text" class="form-control" name="user_userid" value="<?php echo set_value('user_userid', element('user_userid', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="user_password" value="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">User name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user_username" value="<?php echo set_value('user_username', element('user_username', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">User email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" name="user_email" value="<?php echo set_value('user_email', element('user_email', element('data', $view))); ?>" />
                    </div>
				</div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Phone number</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user_phone" value="<?php echo set_value('user_phone', element('user_phone', element('data', $view))); ?>" />
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-sm-2 control-label">Authority</label>
                    <div class="col-sm-10 form-inline">
                        <select name="user_level" class="form-control">
                        <?php
						$user_level = config_item('user_level');
						foreach ($user_level as $key => $val) { ?>
                            <option value="<?php echo $key; ?>" <?php echo set_select('user_level', $key, ((int) element('user_level', element('data', $view)) === $key ? true : false)); ?>><?php echo $val; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-sm-2 control-label">Artist field</label>
                    <div class="col-sm-10 form-inline">
                        <select name="user_artist_category" class="form-control">
                            <option value="">=select=</option>
                        <?php
						$portfolio_category = config_item('portfolio_category');
						foreach ($portfolio_category as $key => $val) { ?>
                            <option value="<?php echo $key; ?>" <?php echo set_select('user_artist_category', $key, ((int) element('user_artist_category', element('data', $view)) === $key ? true : false)); ?>><?php echo $val; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Homepage</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user_homepage" value="<?php echo set_value('user_homepage', element('user_homepage', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Instagram</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user_instagram" value="<?php echo set_value('user_instagram', element('user_instagram', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Facebook</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="user_facebook" value="<?php echo set_value('user_facebook', element('user_facebook', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Profile image</label>
                    <div class="col-sm-10">
                        <?php
                        if (element('user_photo', element('data', $view))) {
                        ?>
                            <img src="<?php echo user_photo_url(element('user_photo', element('data', $view))); ?>" alt="회원 사진" title="회원 사진" />
                            <label for="user_photo_del">
                                <input type="checkbox" name="user_photo_del" id="user_photo_del" value="1" <?php echo set_checkbox('user_photo_del', '1'); ?> /> Delete
                            </label>
                        <?php
                        }
                        ?>
                        <input type="file" name="user_photo" id="user_photo" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">cut/break</label>
                    <div class="col-sm-10 form-inline">
                        <select name="user_denied" class="form-control">
                            <option value="0" <?php echo set_select('user_denied', '0', ( ! element('user_denied', element('data', $view)) ? true : false)); ?>>accept</option>
                            <option value="1" <?php echo set_select('user_denied', '1', (element('user_denied', element('data', $view)) ? true : false)); ?>>cut/break</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Admin memo</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="5" name="user_adminmemo"><?php echo set_value('user_adminmemo', element('user_adminmemo', element('data', $view))); ?></textarea>
                    </div>
                </div>
                <?php
                if (element('html_content', $view)) {
                    foreach (element('html_content', $view) as $key => $value) {
                ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="<?php echo element('field_name', $value); ?>"><?php echo element('display_name', $value); ?></label>
                        <div class="col-sm-10"><?php echo element('input', $value); ?></div>
                    </div>
                <?php
                    }
                }
                ?>
                <div class="btn-group pull-right" role="group" aria-label="...">
                    <button type="button" class="btn btn-default btn-sm btn-history-back" >Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fadminwrite').validate({
        rules: {
            user_userid: { required: true, minlength:3, maxlength:25 },
            user_username: {required :true },
            user_email: {required :true, email:true },
            user_password: {minlength :4 }
        }
    });
});
//]]>
</script>
