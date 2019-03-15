<?php $this->layoutlib->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="mypage">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo site_url('mypage'); ?>" title="마이페이지">마이페이지</a></li>
        <li <?php if (uri_string() === 'usermodify') { ?>class="active" <?php } ?> ><a href="<?php echo site_url('usermodify'); ?>" title="정보수정">정보수정</a></li>
        <li <?php if (uri_string() === 'usermodify/userleave') { ?>class="active" <?php } ?>><a href="<?php echo site_url('usermodify/userleave'); ?>" title="탈퇴하기">탈퇴하기</a></li>
    </ul>
    <div class="page-header">
        <h4>관리자회원</h4>
    </div>
    <div class="alert alert-dismissible alert-info infoalert">
        <span id="return_message">
        관리자회원정보는 관리자페이지에서만 수정이 가능합니다.
        </span>
    </div>
</div>
