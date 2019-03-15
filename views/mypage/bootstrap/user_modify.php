<?php $this->layoutlib->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="mypage">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo site_url('mypage'); ?>" title="마이페이지">마이페이지</a></li>
        <li class="active"><a href="<?php echo site_url('usermodify'); ?>" title="정보수정">정보수정</a></li>
        <li><a href="<?php echo site_url('usermodify/userleave'); ?>" title="탈퇴하기">탈퇴하기</a></li>
    </ul>
    <div class="page-header">
        <h4>회원 정보 수정</h4>
    </div>
    <?php
    echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
    echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
    echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
    $attributes = array('class' => 'form-horizontal', 'name' => 'fregisterform', 'id' => 'fregisterform');
    echo form_open_multipart(current_url(), $attributes);
    ?>
        <div class="form-group">
            <label class="col-lg-3 control-label">회원아이디</label>
            <div class="col-lg-8"><p class="form-control-static"><?php echo $this->userlib->item('user_userid'); ?></p></div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">패스워드</label>
            <div class="col-lg-8"><a href="<?php echo site_url('usermodify/password_modify'); ?>" class="btn btn-default btn-sm" title="패스워드 변경">패스워드 변경</a></div>
        </div>
        <?php foreach (element('html_content', $view) as $key => $value) { ?>
            <div class="form-group">
                <label class="col-lg-3 control-label" for="<?php echo element('field_name', $value); ?>"><?php echo element('display_name', $value); ?></label>
                <div class="col-lg-8">
                    <?php echo element('input', $value); ?>
                    <?php if (element('description', $value)) { ?>
                        <p class="help-block"><?php echo element('description', $value); ?></p>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if ($this->configlib->item('use_user_photo') && $this->configlib->item('user_photo_width') > 0 && $this->configlib->item('user_photo_height') > 0) { ?>
            <div class="form-group">
                <label class="col-lg-3 control-label">프로필사진</label>
                <div class="col-lg-8">
                    <?php if ($this->userlib->item('user_photo')) { ?>
                        <img src="<?php echo user_photo_url($this->userlib->item('user_photo')); ?>" alt="프로필사진" title="프로필사진" />
                        <label for="user_photo_del">
                            <input type="checkbox" name="user_photo_del" id="user_photo_del" value="1" <?php echo set_checkbox('user_photo_del', '1'); ?> />
                            삭제
                        </label>
                    <?php } ?>
                    <input type="file" name="user_photo" id="user_photo" />
                    <p class="help-block">가로길이 : <?php echo number_format($this->configlib->item('user_photo_width')); ?>px, 세로길이 : <?php echo number_format($this->configlib->item('user_photo_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
                </div>
            </div>
        <?php } ?>
        <?php if ($this->configlib->item('use_user_icon') && $this->configlib->item('user_icon_width') > 0 && $this->configlib->item('user_icon_height') > 0) { ?>
            <div class="form-group">
                <label class="col-lg-3 control-label">회원아이콘</label>
                <div class="col-lg-8">
                    <?php if ($this->userlib->item('user_icon')) { ?>
                        <img src="<?php echo user_icon_url($this->userlib->item('user_icon')); ?>" alt="회원아이콘" title="회원아이콘" />
                        <label for="user_icon_del">
                            <input type="checkbox" name="user_icon_del" id="user_icon_del" value="1" <?php echo set_checkbox('user_icon_del', '1'); ?> />
                            삭제
                        </label>
                    <?php } ?>
                    <input type="file" name="user_icon" id="user_icon" />
                    <p class="help-block">가로길이 : <?php echo number_format($this->configlib->item('user_icon_width')); ?>px, 세로길이 : <?php echo number_format($this->configlib->item('user_icon_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
                </div>
            </div>
        <?php } ?>
        <div class="form-group">
            <label class="col-lg-3 control-label">정보공개</label>
            <div class="col-lg-8">
                <div class="checkbox">
                    <label for="user_open_profile">
                        <input type="checkbox" name="user_open_profile" id="user_open_profile" value="1" <?php echo set_checkbox('user_open_profile', '1', ($this->userlib->item('user_open_profile') ? true : false)); ?> <?php echo element('can_update_open_profile', $view) ? '' : 'disabled="disabled"'; ?> />
                        다른분들이 나의 정보를 볼 수 있도록 합니다.
                    </label>
                    <?php if (element('open_profile_description', $view)) { ?>
                        <p class="help-block"><?php echo element('open_profile_description', $view); ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if ($this->configlib->item('use_note')) { ?>
            <div class="form-group">
                <label class="col-lg-3 control-label">쪽지기능사용</label>
                <div class="col-lg-8">
                    <div class="checkbox">
                        <label for="user_use_note">
                            <input type="checkbox" name="user_use_note" id="user_use_note" value="1" <?php echo set_checkbox('user_use_note', '1', ($this->userlib->item('user_use_note') ? true : false)); ?> <?php echo element('can_update_use_note', $view) ? '' : 'disabled="disabled"'; ?> />
                            쪽지를 주고 받을 수 있습니다.
                        </label>
                        <?php if (element('use_note_description', $view)) { ?>
                            <p class="help-block"><?php echo element('use_note_description', $view); ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="form-group">
            <label class="col-lg-3 control-label">이메일 수신여부</label>
            <div class="col-lg-8">
                <div class="checkbox">
                    <label for="user_receive_email" >
                        <input type="checkbox" name="user_receive_email" id="user_receive_email" value="1" <?php echo set_checkbox('user_receive_email', '1', ($this->userlib->item('user_receive_email') ? true : false)); ?> /> 수신
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">SMS 문자 수신</label>
            <div class="col-lg-8    ">
                <div class="checkbox">
                    <label for="user_receive_sms">
                        <input type="checkbox" name="user_receive_sms" id="user_receive_sms" value="1" <?php echo set_checkbox('user_receive_sms', '1', ($this->userlib->item('user_receive_sms') ? true : false)); ?> /> 수신
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
                <button type="submit" class="btn btn-success btn-sm">수정하기</button>
            </div>
        </div>
    <?php echo form_close(); ?>
</div>

<?php
$this->layoutlib->add_css(base_url('assets/css/datepicker3.css'));
$this->layoutlib->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
$this->layoutlib->add_js(base_url('assets/js/bootstrap-datepicker.js'));
$this->layoutlib->add_js(base_url('assets/js/bootstrap-datepicker.kr.js'));
?>

<script type="text/javascript">
//<![CDATA[
$('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    language: 'kr',
    autoclose: true,
    todayHighlight: true
});
//]]>
</script>
