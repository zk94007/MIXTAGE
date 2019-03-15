<?php $this->layoutlib->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<!-- main start -->
<div class="register col-md-10 col-md-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading">회원 가입</div>
        <div class="panel-body">
            <?php
            echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
            echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
            $attributes = array('class' => 'form-horizontal', 'name' => 'fregisterform', 'id' => 'fregisterform');
            echo form_open_multipart(current_full_url(), $attributes);
                foreach (element('html_content', $view) as $key => $value) {
            ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="<?php echo element('field_name', $value); ?>"><?php echo element('display_name', $value); ?></label>
                        <div class="col-lg-8">
                            <?php echo element('input', $value); ?>
                            <?php if (element('description', $value)) { ?>
                                <p class="help-block"><?php echo element('description', $value); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                <?php
                }
                if ($this->configlib->item('use_user_photo') && $this->configlib->item('user_photo_width') > 0 && $this->configlib->item('user_photo_height') > 0) {
                ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="user_photo">프로필사진</label>
                        <div class="col-lg-8">
                            <input type="file" name="user_photo" id="user_photo" />
                            <p class="help-block">가로길이 : <?php echo number_format($this->configlib->item('user_photo_width')); ?>px, 세로길이 : <?php echo number_format($this->configlib->item('user_photo_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
                        </div>
                    </div>
                <?php
                }
                if ($this->configlib->item('use_user_icon') && $this->configlib->item('user_icon_width') > 0 && $this->configlib->item('user_icon_height') > 0) {
                ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="user_icon">회원아이콘</label>
                        <div class="col-lg-8">
                            <input type="file" name="user_icon" id="user_icon" />
                            <p class="help-block">가로길이 : <?php echo number_format($this->configlib->item('user_icon_width')); ?>px, 세로길이 : <?php echo number_format($this->configlib->item('user_icon_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="form-group">
                    <label class="col-lg-3 control-label" for="user_open_profile">정보공개</label>
                    <div class="col-lg-8">
                        <div class="checkbox">
                            <label for="user_open_profile">
                                <input type="checkbox" name="user_open_profile" id="user_open_profile" value="1" <?php echo set_checkbox('user_open_profile', '1', true); ?> />
                                다른분들이 나의 정보를 볼 수 있도록 합니다.
                            </label>
                            <?php
                            if (element('open_profile_description', $view)) {
                            ?>
                                <p class="help-block"><?php echo element('open_profile_description', $view); ?></p>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                if ($this->configlib->item('use_note')) {
                ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">쪽지기능사용</label>
                        <div class="col-lg-8">
                            <div class="checkbox">
                                <label for="user_use_note">
                                    <input type="checkbox" name="user_use_note" id="user_use_note" value="1" <?php echo set_checkbox('user_use_note', '1', true); ?> />
                                    쪽지를 주고 받을 수 있습니다.
                                </label>
                                <?php if (element('use_note_description', $view)) { ?>
                                    <p class="help-block"><?php echo element('use_note_description', $view); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="form-group">
                    <label class="col-lg-3 control-label">이메일 수신여부</label>
                    <div class="col-lg-8">
                        <div class="checkbox">
                            <label for="user_receive_email" >
                                <input type="checkbox" name="user_receive_email" id="user_receive_email" value="1" <?php echo set_checkbox('user_receive_email', '1', true); ?> /> 수신
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">SMS 문자 수신</label>
                    <div class="col-lg-8    ">
                        <div class="checkbox">
                            <label for="user_receive_sms">
                                <input type="checkbox" name="user_receive_sms" id="user_receive_sms" value="1" <?php echo set_checkbox('user_receive_sms', '1', true); ?> /> 수신
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php if ($this->configlib->item('use_recaptcha')) { ?>
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-8    captcha" id="recaptcha"><button type="button" id="captcha"></button></div>
                        <input type="hidden" name="recaptcha" />
                    <?php } else { ?>
                        <label class="col-lg-3 control-label"><img src="<?php echo base_url('assets/images/preload.png'); ?>" width="160" height="40" id="captcha" alt="captcha" title="captcha" /></label>
                        <div class="col-lg-8    ">
                            <input type="text" name="captcha_key" id="captcha_key" class="form-control px150" value="" />
                            <p class="help-block">좌측에 보이는 문자를 입력해주세요</p>
                        </div>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <div class="col-lg-9 col-lg-offset-3">
                        <button type="submit" class="btn btn-success btn-sm">회원가입</button>
                        <a href="<?php echo site_url(); ?>" class="btn btn-default btn-sm">취소</a>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<?php
$this->layoutlib->add_css(base_url('assets/css/datepicker3.css'));
$this->layoutlib->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
$this->layoutlib->add_js(base_url('assets/js/bootstrap-datepicker.js'));
$this->layoutlib->add_js(base_url('assets/js/bootstrap-datepicker.kr.js'));
$this->layoutlib->add_js(base_url('assets/js/user_register.js'));
if ($this->configlib->item('use_recaptcha')) {
    $this->layoutlib->add_js(base_url('assets/js/recaptcha.js'));
} else {
    $this->layoutlib->add_js(base_url('assets/js/captcha.js'));
}
?>

<script type="text/javascript">
//<![CDATA[
$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    language: 'kr',
    autoclose: true,
    todayHighlight: true
});
$(function() {
    $('#fregisterform').validate({
        onkeyup: false,
        onclick: false,
        rules: {
            user_userid: {required :true, minlength:3, maxlength:20, is_userid_available:true},
            user_email: {required :true, email:true, is_email_available:true},
            user_password: {required :true, is_password_available:true},
            user_password_re : {required: true, equalTo : '#user_password' },
            user_nickname: {required :true, is_nickname_available:true}
            <?php if ($this->configlib->item('use_recaptcha')) { ?>
                , recaptcha : {recaptchaKey:true}
            <?php } else { ?>
                , captcha_key : {required: true, captchaKey:true}
            <?php } ?>
        },
        messages: {
            recaptcha: '',
            captcha_key: '자동등록방지용 코드가 올바르지 않습니다.'
        }
    });
});
//]]>
</script>
