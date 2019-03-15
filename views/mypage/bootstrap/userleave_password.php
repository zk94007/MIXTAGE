<?php $this->layoutlib->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="mypage">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo site_url('mypage'); ?>" title="마이페이지">마이페이지</a></li>
        <li <?php if (uri_string() === 'usermodify') { ?>class="active" <?php } ?> ><a href="<?php echo site_url('usermodify'); ?>" title="정보수정">정보수정</a></li>
        <li <?php if (uri_string() === 'usermodify/userleave') { ?>class="active" <?php } ?>><a href="<?php echo site_url('usermodify/userleave'); ?>" title="탈퇴하기">탈퇴하기</a></li>
    </ul>
    <div class="page-header">
        <h4>회원 비밀번호 확인</h4>
    </div>

    <?php
    echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
    echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-warning"><button type="button" class="close alertclose" >&times;</button>', '</div>');
    ?>

    <div class="form-horizontal mt20">
        <?php
        $attributes = array('class' => 'form-horizontal', 'name' => 'fconfirmpassword', 'id' => 'fconfirmpassword', 'onsubmit' => 'return confirmleave()');
        echo form_open(current_url(), $attributes);
        ?>
            <div class="alert alert-dismissible alert-danger infoalert">
                회원 탈퇴를 위한 패스워드 입력페이지입니다.<br />
                패스워드를 입력하시면 회원탈퇴가 정상적으로 진행됩니다.<br />
                탈퇴한 회원정보는 복구할 수 없으므로, 신중히 선택해주시기 바랍니다.
            </div>
            <div class="form-group">
                <label for="user_userid" class="col-sm-3 control-label">아이디</label>
                <div class="col-sm-9">
                    <p class="form-control-static"><strong><?php echo $this->userlib->item('user_userid'); ?></strong></p>
                </div>
            </div>
            <div class="form-group">
                <label for="user_password" class="col-sm-3 control-label">비밀번호</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control px150" id="user_password" name="user_password" />
                    <button type="submit" class="btn btn-primary btn-sm">확인</button>
                    <span class="help-block">
                        <span class="fa fa-exclamation-circle"></span>
                        외부로부터 회원님의 정보를 안전하게 보호하기 위해 비밀번호를 확인하셔야 합니다.
                    </span>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fconfirmpassword').validate({
        rules: {
            user_password : { required:true, minlength:4 }
        }
    });
});
function confirmleave() {
    if (confirm('정말 회원 탈퇴를 하시겠습니까? 탈퇴한 회원정보는 복구할 수 없으므로 신중히 선택하여주세요. 확인을 누르시면 탈퇴가 완료됩니다 ')) {
        return true;
    } else {
        return false;
    }
}
//]]>
</script>
