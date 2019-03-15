<?php $this->layoutlib->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="access col-md-6 col-md-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading">Admin Log in </div>
        <div class="panel-body">
            <?php
            echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
            echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
            echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
            $attributes = array('class' => 'form-horizontal', 'name' => 'flogin', 'id' => 'flogin');
            echo form_open(current_full_url(), $attributes);
            ?>
                <input type="hidden" name="url" value="<?php echo html_escape($this->input->get_post('url')); ?>" />
                <div class="form-group">
                    <label class="col-lg-4 control-label"><?php echo element('userid_label_text', $view);?></label>
                    <div class="col-lg-7">
                        <input type="text" name="user_userid" class="form-control" value="<?php echo set_value('user_userid'); ?>" accesskey="L" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Password</label>
                    <div class="col-lg-7">
                        <input type="password" class="form-control" name="user_password" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-2 col-lg-offset-4">
                        <button type="submit" class="btn btn-primary btn-sm">Log In</button>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#flogin').validate({
        rules: {
            user_userid : { required:true, minlength:3 },
            user_password : { required:true, minlength:4 }
        }
    });
});
//]]>
</script>
