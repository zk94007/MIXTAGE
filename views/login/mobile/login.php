<?php $this->layoutlib->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="access">
    <div class="table-box">
        <div class="table-heading">로그인</div>
        <div class="table-body">
            <?php
            echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
            echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
            echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
            $attributes = array('class' => 'form-horizontal', 'name' => 'flogin', 'id' => 'flogin');
            echo form_open(current_full_url(), $attributes);
            ?>
                <input type="hidden" name="url" value="<?php echo html_escape($this->input->get_post('url')); ?>" />
                <ol class="loginform">
                    <li>
                        <span><?php echo element('userid_label_text', $view);?></span>
                        <input type="text" name="user_userid" class="input" value="<?php echo set_value('user_userid'); ?>" accesskey="L" />
                    </li>
                    <li>
                        <span>비밀번호</span>
                        <input type="password" class="input" name="user_password" />
                    </li>
                    <li>
                        <span></span>
                        <button type="submit" class="btn btn-primary btn-sm">로그인</button>
                    </li>
                </ol>
            <?php echo form_close(); ?>
        </div>
        <div class="table-footer">
            <a href="<?php echo site_url('register'); ?>" class="btn btn-success btn-sm" title="회원가입">회원가입</a>
            <a href="<?php echo site_url('findaccount'); ?>" class="btn btn-default btn-sm" title="아이디 패스워드 찾기">아이디 패스워드 찾기</a>
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
