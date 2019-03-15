<?php if ($this->userlib->is_user() === false) { ?>

    <!-- login start -->
    <?php
    $attributes = array('class' => 'form-horizontal', 'name' => 'fsidelogin', 'id' => 'fsidelogin');
    echo form_open(site_url('login'), $attributes);
    ?>
        <input type="hidden" name="url" value="<?php echo urlencode(current_full_url()); ?>" />
        <input type="hidden" name="returnurl" value="<?php echo urlencode(current_full_url()); ?>" />
        <div class="loginbox mb10">
            <div class="headline">
                <h3>로그인</h3>
            </div>
            <?php echo $this->session->flashdata('loginvalidationmessage'); ?>
            <input type="text" class="form-control mb10" name="user_userid" placeholder="Enter User ID" value="<?php echo $this->session->flashdata('loginuserid'); ?>" />
            <input type="password" class="form-control mb10" name="user_password" placeholder="Enter Password" />
            <button class="btn btn-primary btn-sm pull-left" type="submit">로그인</button>
            <ul class="text pull-right">
                <li><a href="<?php echo site_url('register'); ?>" title="회원가입">회원가입</a></li>
                <li>|</li>
                <li><a href="<?php echo site_url('findaccount'); ?>" title="회원정보찾기">회원정보찾기</a></li>
            </ul>

        </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
    //<![CDATA[
    $(function() {
        $('#fsidelogin').validate({
            rules: {
                user_userid: {required:true, minlength:3},
                user_password: {required:true, minlength:4}
            }
        });
    });
    //]]>
    </script>
    <!-- login end -->

<?php } else { ?>

    <!-- welcome start -->
    <div class="welcome mb10">
        <div class="headline">
            <h3><?php echo html_escape($this->userlib->item('user_nickname')); ?>님 어서오세요.</h3>
        </div>
        <ul class="mt20">
            <li><a href="javascript:;" onClick="open_profile('<?php echo $this->userlib->item('user_userid'); ?>');" class="btn btn-default btn-xs" title="나의 프로필">프로필</a></li>
            <li><a href="<?php echo site_url('mypage'); ?>" class="btn btn-default btn-xs" title="마이페이지">마이페이지</a></li>
            <li><a href="<?php echo site_url('usermodify'); ?>" class="btn btn-default btn-xs" title="정보수정">정보수정</a></li>
            <li><a href="<?php echo site_url('login/logout?url=' . urlencode(current_full_url())); ?>" class="btn btn-default btn-xs" title="로그아웃">로그아웃</a></li>
        </ul>
    </div>
    <!-- welcome end -->

<?php } ?>
