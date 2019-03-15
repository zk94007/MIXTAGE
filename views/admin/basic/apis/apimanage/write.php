<div class="box">
    <div class="box-table">
        <?php
        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        $attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
        echo form_open(current_full_url(), $attributes);
        ?>
            <input type="hidden" name="<?php echo element('primary_key', $view); ?>"    value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">API 이름</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="api_name" value="<?php echo set_value('api_name', element('api_name', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">설명</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="5" name="api_exp"><?php echo set_value('api_exp', element('api_exp', element('data', $view))); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">호출방식</label>
                    <div class="col-sm-10">
                        <label class="radio-inline" for="api_method_GET" >
                            <input type="radio" name="api_method" id="api_method_GET" value="GET" <?php echo set_radio('api_method', 'GET', (element('api_method', element('data', $view)) != 'POST' ? true : false)); ?> /> GET
                        </label>
                        <label class="radio-inline" for="api_method_POST" >
                            <input type="radio" name="api_method" id="api_method_POST" value="POST" <?php echo set_radio('api_method', 'POST', (element('api_method', element('data', $view)) == 'POST' ? true : false)); ?> /> POST
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">사용여부</label>
                    <div class="col-sm-10">
                        <label class="radio-inline" for="api_use_1" >
                            <input type="radio" name="api_use" id="api_use_1" value="1" <?php echo set_radio('api_use', '1', (element('api_use', element('data', $view)) != '0' ? true : false)); ?> /> 사용
                        </label>
                        <label class="radio-inline" for="api_use_0" >
                            <input type="radio" name="api_use" id="api_use_0" value="0" <?php echo set_radio('api_use', '0', (element('api_use', element('data', $view)) == '0' ? true : false)); ?> /> 사용 안함
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">비고</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="5" name="api_bigo"><?php echo set_value('api_bigo', element('api_bigo', element('data', $view))); ?></textarea>
                    </div>
                </div>
                <div class="btn-group pull-right" role="group" aria-label="...">
                    <button type="button" class="btn btn-default btn-sm btn-history-back" >취소하기</button>
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
