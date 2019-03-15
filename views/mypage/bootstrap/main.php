<?php $this->layoutlib->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="mypage">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo site_url('mypage'); ?>" title="마이페이지">마이페이지</a></li>
        <li><a href="<?php echo site_url('usermodify'); ?>" title="정보수정">정보수정</a></li>
        <li><a href="<?php echo site_url('usermodify/userleave'); ?>" title="탈퇴하기">탈퇴하기</a></li>
    </ul>
    <div class="form-horizontal">
        <div class="page-header">
            <h4>마이페이지</h4>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">아이디</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php echo html_escape($this->userlib->item('user_userid')); ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">이메일 주소</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php echo html_escape($this->userlib->item('user_email')); ?></p>
            </div>
        </div>
        <?php if (element('use', element('user_username', element('userform', $view)))) { ?>
            <div class="form-group">
                <label class="col-sm-3 control-label">이름</label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo html_escape($this->userlib->item('user_username')); ?></p>
                </div>
            </div>
        <?php } ?>
        <div class="form-group">
            <label class="col-sm-3 control-label">닉네임</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php echo html_escape($this->userlib->item('user_nickname')); ?></p>
            </div>
        </div>
        <?php if (element('use', element('user_homepage', element('userform', $view)))) { ?>
            <div class="form-group">
                <label class="col-sm-3 control-label">홈페이지</label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo $this->userlib->item('user_homepage') ? html_escape($this->userlib->item('user_homepage')) : '미등록'; ?></p>
                </div>
            </div>
        <?php } ?>
        <?php if (element('use', element('user_birthday', element('userform', $view)))) { ?>
            <div class="form-group">
                <label class="col-sm-3 control-label">생일</label>
                <div class="col-sm-9">
                    <p class="form-control-static"><?php echo html_escape($this->userlib->item('user_birthday')); ?></p>
                </div>
            </div>
        <?php } ?>
        <div class="form-group">
            <label class="col-sm-3 control-label">가입일</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php echo display_datetime($this->userlib->item('user_register_datetime'), 'full'); ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">최근 로그인</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php echo display_datetime($this->userlib->item('user_lastlogin_datetime'), 'full'); ?></p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3 mt20">
                <a href="<?php echo site_url('usermodify'); ?>" class="btn btn-default btn-sm" title="회원정보 변경">회원정보 변경</a>
            </div>
        </div>
    </div>
</div>
