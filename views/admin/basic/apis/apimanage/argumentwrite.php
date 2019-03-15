<div class="box">
    <div class="box-table">
        <h3><?php echo html_escape(element('api_name', element('apidata', $view)));?> : <?php echo element('type', $view)?> 등록</h3>
        <?php
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        $attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
        echo form_open(current_full_url(), $attributes);
        ?>
            <input type="hidden" name="<?php echo element('primary_key', $view); ?>"    value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
            <input type="hidden" name="api_idx"    value="<?php echo element('api_idx', element('apidata', $view)); ?>" />
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">변수명</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="ai_name" value="<?php echo set_value('ai_name', element('ai_name', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">타입</label>
                    <div class="col-sm-10">
                        <label class="radio-inline" for="ai_type_1" >
                            <input type="radio" name="ai_type" id="ai_type_1" value="String" <?php echo set_radio('ai_type', 'String', (element('ai_type', element('data', $view)) == 'String' ? true : false)); ?> /> String
                        </label>
                        <label class="radio-inline" for="ai_type_2" >
                            <input type="radio" name="ai_type" id="ai_type_2" value="Integer" <?php echo set_radio('ai_type', 'Integer', (element('ai_type', element('data', $view)) == 'Integer' ? true : false)); ?> /> Integer
                        </label>
                        <label class="radio-inline" for="ai_type_3" >
                            <input type="radio" name="ai_type" id="ai_type_3" value="Object Arr" <?php echo set_radio('ai_type', 'Object Arr', (element('ai_type', element('data', $view)) == 'Object Arr' ? true : false)); ?> /> Object Arr
                        </label>
                        <label class="radio-inline" for="ai_type_4" >
                            <input type="radio" name="ai_type" id="ai_type_4" value="File" <?php echo set_radio('ai_type', 'File', (element('ai_type', element('data', $view)) == 'File' ? true : false)); ?> /> File
                        </label>
                        <label class="radio-inline" for="ai_type_5" >
                            <input type="radio" name="ai_type" id="ai_type_5" value="MultiFile" <?php echo set_radio('ai_type', 'MultiFile', (element('ai_type', element('data', $view)) == 'MultiFile' ? true : false)); ?> /> Multi Files
                        </label>
                        <label class="radio-inline" for="ai_type_6" >
                            <input type="radio" name="ai_type" id="ai_type_6" value="boolean" <?php echo set_radio('ai_type', 'boolean', (element('ai_type', element('data', $view)) == 'boolean' ? true : false)); ?> /> boolean
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">종류</label>
                    <div class="col-sm-10">
                        <label class="radio-inline" for="ai_ness_1" >
                            <input type="radio" name="ai_ness" id="ai_ness_1" value="필수" <?php echo set_radio('ai_ness', '필수', (element('ai_ness', element('data', $view)) == '필수' ? true : false)); ?> /> 필수
                        </label>
                        <label class="radio-inline" for="ai_ness_2" >
                            <input type="radio" name="ai_ness" id="ai_ness_2" value="성공시" <?php echo set_radio('ai_ness', '성공시', (element('ai_ness', element('data', $view)) == '성공시' ? true : false)); ?> /> 성공시
                        </label>
                        <label class="radio-inline" for="ai_ness_3" >
                            <input type="radio" name="ai_ness" id="ai_ness_3" value="오류시" <?php echo set_radio('ai_ness', '오류시', (element('ai_ness', element('data', $view)) == '오류시' ? true : false)); ?> /> 오류시
                        </label>
                        <label class="radio-inline" for="ai_ness_4" >
                            <input type="radio" name="ai_ness" id="ai_ness_4" value="" <?php echo set_radio('ai_ness', '', (element('ai_ness', element('data', $view)) == '' ? true : false)); ?> /> 빈값
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">설명</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="10" name="ai_exp"><?php echo set_value('ai_exp', element('ai_exp', element('data', $view))); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">순서</label>
                    <div class="col-sm-10 form-inline">
                        <input type="text" class="form-control" name="ai_sort" value="<?php echo set_value('ai_sort', element('ai_sort', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="btn-group pull-right" role="group" aria-label="...">
					<?php if ( ! element(element('primary_key', $view), element('data', $view))) { ?>
					<label class="checkbox-inline" style="padding:10px 10px 10px 120px;" for="reinput" >
						<input type="checkbox" name="reinput" id="reinput" value="1" checked="checked" /> 다시 등록 페이지로
					</label>
					<br />
					<?php } ?>
					<a href="<?php echo admin_url('apis/apimanage');?>" class="btn btn-default btn-sm">API 목록</a>
                    <a href="<?php echo element('input_list_url', $view); ?>" class="btn btn-default btn-sm">Input 목록</a>
                    <a href="<?php echo element('output_list_url', $view); ?>" class="btn btn-default btn-sm">Output 목록</a>
					<a href="<?php echo admin_url('apis/apimanage/argumentlist/' . element('type', $view) . '/' . element('api_idx', element('apidata', $view)));?>" class="btn btn-default btn-sm"><?php echo element('type', $view); ?> 변수 목록</a>
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
            api_name: {required:true}
        }
    });
});
//]]>
</script>
