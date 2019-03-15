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
    <div class="row">
        <div class="final col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php echo element('result_message', $view); ?>
                    <p class="btn_final mt20"><a href="<?php echo site_url(); ?>" class="btn btn-danger btn-sm" title="<?php echo html_escape($this->configlib->item('site_title'));?>">홈페이지로 이동</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
