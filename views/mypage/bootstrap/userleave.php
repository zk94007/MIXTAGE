<?php $this->layoutlib->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="mypage">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo site_url('mypage'); ?>" title="마이페이지">마이페이지</a></li>
        <li><a href="<?php echo site_url('usermodify'); ?>" title="정보수정">정보수정</a></li>
        <li class="active"><a href="<?php echo site_url('usermodify/userleave'); ?>" title="탈퇴하기">탈퇴하기</a></li>
    </ul>
    <div class="page-header">
        <h4>회원탈퇴</h4>
    </div>
    <div class="mt20">
        <p style="padding:20px;">안녕하세요 <span class="text-primary"><?php echo html_escape($this->userlib->item('user_nickname')); ?></span>님, <br />
            회원님의 탈퇴가 정상적으로 진행되었습니다.<br />
            그 동안 저희 사이트를 이용해주셔서 감사합니다.
        </p>
    </div>
</div>
