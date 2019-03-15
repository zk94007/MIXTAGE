<div class="box">
    <div class="box-header">
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">접근기능</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/point'); ?>" onclick="return check_form_changed();">포인트기능</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/general'); ?>" onclick="return check_form_changed();">일반기능 / 에디터</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/note'); ?>" onclick="return check_form_changed();">쪽지기능</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/notification'); ?>" onclick="return check_form_changed();">알림기능</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/company'); ?>" onclick="return check_form_changed();">회사정보</a></li>
        </ul>
    </div>
    <div class="box-table">
        <?php
        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
        echo form_open(current_full_url(), $attributes);
        ?>
            <input type="hidden" name="is_submit" value="1" />
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">홈페이지 제목</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="site_title" value="<?php echo set_value('site_title', element('site_title', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">홈페이지 로고</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="site_logo" value="<?php echo set_value('site_logo', element('site_logo', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">관리자페이지 로고</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="admin_logo" value="<?php echo set_value('admin_logo', element('admin_logo', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">하단스크립트</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="5" name="footer_script"><?php echo set_value('footer_script', element('footer_script', element('data', $view))); ?></textarea>
                        <span class="help-inline">페이지 하단에 스크립트를 삽입합니다. 통계 스크립트 등을 입력해주시면 됩니다. 관리자 페이지에는 반영되지 않습니다</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">웹마스터 이름</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="webmaster_name" id="webmaster_name" value="<?php echo set_value('webmaster_name', element('webmaster_name', element('data', $view))); ?>" />
                        <span class="help-inline">이메일 발송시 보내는 이름입니다</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">웹마스터 이메일주소</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="webmaster_email" id="webmaster_email" value="<?php echo set_value('webmaster_email', element('webmaster_email', element('data', $view))); ?>" />
                            <span class="help-inline">메일 발송시 보내는 메일 주소입니다</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">단어 필터링</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="5" name="spam_word"><?php echo set_value('spam_word', element('spam_word', element('data', $view))); ?></textarea>
                        <span class="help-inline">필터링하고 싶은 단어를 쉼표로 구분하여 입력해주세요, 해당 단어는 원글작성, 댓글작성의 경우에 입력이 제한되어있습니다</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">허용하는 아이프레임</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="10" name="white_iframe"><?php echo set_value('white_iframe', element('white_iframe', element('data', $view))); ?></textarea>
                        <span class="help-inline">&lt;iframe&gt; 태그에 허용 할 URL을 지정할 수 있습니다. 주소 맨 앞에 http://, https:// 를 제외하고 입력합니다</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">JWPLAYER6 KEY</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="jwplayer6_key" id="jwplayer6_key" value="<?php echo set_value('jwplayer6_key', element('jwplayer6_key', element('data', $view))); ?>" />
                        <span class="help-inline">JW Player 동영상 재생시 API KEY 를 입력합니다</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">최고레벨</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="max_level" id="max_level" value="<?php echo set_value('max_level', element('max_level', element('data', $view)) + 0); ?>" />
                        <span class="help-inline">최고레벨을 지정할 수 있습니다. 최대 1000이 한계입니다</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">IP 공개시 표시형식</label>
                    <div class="col-sm-10 form-inline">
                        <select name="ip_display_style" class="form-control">
                            <option value="0001" <?php echo set_select('ip_display_style', '0001', (element('ip_display_style', element('data', $view)) === '0001' ? true : false)); ?> >&#9825;.&#9825;.&#9825;.127</option>
                            <option value="0010" <?php echo set_select('ip_display_style', '0010', (element('ip_display_style', element('data', $view)) === '0010' ? true : false)); ?> >&#9825;.&#9825;.127.&#9825;</option>
                            <option value="0011" <?php echo set_select('ip_display_style', '0011', (element('ip_display_style', element('data', $view)) === '0011' ? true : false)); ?> >&#9825;.&#9825;.127.127</option>
                            <option value="0100" <?php echo set_select('ip_display_style', '0100', (element('ip_display_style', element('data', $view)) === '0100' ? true : false)); ?> >&#9825;.127.&#9825;.&#9825;</option>
                            <option value="0101" <?php echo set_select('ip_display_style', '0101', (element('ip_display_style', element('data', $view)) === '0101' ? true : false)); ?> >&#9825;.127.&#9825;.127</option>
                            <option value="0110" <?php echo set_select('ip_display_style', '0110', (element('ip_display_style', element('data', $view)) === '0110' ? true : false)); ?> >&#9825;.127.127.&#9825;</option>
                            <option value="0111" <?php echo set_select('ip_display_style', '0111', (element('ip_display_style', element('data', $view)) === '0111' ? true : false)); ?> >&#9825;.127.127.127</option>
                            <option value="1000" <?php echo set_select('ip_display_style', '1000', (element('ip_display_style', element('data', $view)) === '1000' ? true : false)); ?> >127.&#9825;.&#9825;.&#9825;</option>
                            <option value="1001" <?php echo set_select('ip_display_style', '1001', (element('ip_display_style', element('data', $view)) === '1001' ? true : false)); ?> >127.&#9825;.&#9825;.127</option>
                            <option value="1010" <?php echo set_select('ip_display_style', '1010', (element('ip_display_style', element('data', $view)) === '1010' ? true : false)); ?> >127.&#9825;.127.&#9825;</option>
                            <option value="1011" <?php echo set_select('ip_display_style', '1011', (element('ip_display_style', element('data', $view)) === '1011' ? true : false)); ?> >127.&#9825;.127.127</option>
                            <option value="1100" <?php echo set_select('ip_display_style', '1100', (element('ip_display_style', element('data', $view)) === '1100' ? true : false)); ?> >127.127.&#9825;.&#9825;</option>
                            <option value="1101" <?php echo set_select('ip_display_style', '1101', (element('ip_display_style', element('data', $view)) === '1101' ? true : false)); ?> >127.127.&#9825;.127</option>
                            <option value="1110" <?php echo set_select('ip_display_style', '1110', (element('ip_display_style', element('data', $view)) === '1110' ? true : false)); ?> >127.127.127.&#9825;</option>
                        </select>
                        <span class="help-inline">IP 를 일부공개하는 페이지에서 공개하는 형식입니다., 관리자에게는 IP 전체가 보입니다.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">한페이지에 보이는 게시물수</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="list_count" id="list_count" value="<?php echo set_value('list_count', element('list_count', element('data', $view)) + 0); ?>" />개
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">구글 reCaptcah 사용여부</label>
                    <div class="col-sm-10">
                        <label for="use_recaptcha" class="checkbox-inline">
                            <input type="checkbox" name="use_recaptcha" id="use_recaptcha" value="1" <?php echo set_checkbox('use_recaptcha', '1', (element('use_recaptcha', element('data', $view)) ? true : false)); ?> /> 사용합니다
                        </label>
                        <span class="help-inline">이 기능을 사용하시게 되면 코드이그나이터 내장 Captcha 기능 대신 구글 reCaptcha 기능을 사용하게 됩니다</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">구글 reCaptcha Sitekey</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="recaptcha_sitekey" id="recaptcha_sitekey" value="<?php echo set_value('recaptcha_sitekey', element('recaptcha_sitekey', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">구글 reCaptcha Secret</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="recaptcha_secret" id="recaptcha_secret" value="<?php echo set_value('recaptcha_secret', element('recaptcha_secret', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="btn-group pull-right" role="group" aria-label="...">
                    <button type="submit" class="btn btn-success btn-sm">저장하기</button>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fadminwrite').validate({
        rules: {
            site_title: {required :true},
            site_logo: {required :true},
            admin_logo: {required :true},
            webmaster_name: {required :true},
            webmaster_email: {required :true, email : true },
            new_post_second: {required :true, number : true, min:0 },
            max_level: {required :true, number : true, min:1, max:1000 },
            ip_display_style: {required :true},
            list_count: {required :true, number : true, min:0 }
        }
    });
});

var form_original_data = $('#fadminwrite').serialize();
function check_form_changed() {
    if ($('#fadminwrite').serialize() !== form_original_data) {
        if (confirm('저장하지 않은 정보가 있습니다. 저장하지 않은 상태로 이동하시겠습니까?')) {
            return true;
        } else {
            return false;
        }
    }
    return true;
}
//]]>
</script>
