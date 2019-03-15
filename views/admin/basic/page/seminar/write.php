<div class="box">
    <div class="box-table">
        <?php
        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        $attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
        echo form_open_multipart(current_full_url(), $attributes);
        ?>
            <input type="hidden" name="<?php echo element('primary_key', $view); ?>"    value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="sem_title" value="<?php echo set_value('sem_title', element('sem_title', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Total joint member count</label>
                    <div class="col-sm-10 form-inline">
                        <input type="number" class="form-control" name="sem_total_user" value="<?php echo set_value('sem_total_user', element('sem_total_user', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Content</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="15" name="sem_content"><?php echo set_value('sem_content', element('sem_content', element('data', $view))); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Agree or not</label>
                    <div class="col-sm-10">
                        <label class="radio-inline" for="sem_approve_1">
                            <input type="radio" name="sem_approve" id="sem_approve_1" value="1" <?php echo set_radio('sem_approve', '1', (element('sem_approve', element('data', $view)) === '1' ? true : false)); ?> /> agree
                        </label>
                        <label class="radio-inline" for="sem_approve_0">
                            <input type="radio" name="sem_approve" id="sem_approve_0" value="0" <?php echo set_radio('sem_approve', '0', (element('sem_approve', element('data', $view)) !== '1' ? true : false)); ?> /> not
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Attach image</label>
                    <div class="col-sm-10">
                        <?php
                        if (element('sem_image_name', element('data', $view))) {
                        ?>
                            <img src="<?php echo seminar_image_url(element('sem_image_name', element('data', $view))); ?>" style="width:150px;" class="thumbnail"/>
                        <?php
                        }
                        ?>
                        <input type="file" name="sem_file" id="sem_file" />
                    </div>
                </div>
                <div class="btn-group pull-right" role="group" aria-label="...">
                    <button type="button" class="btn btn-default btn-sm btn-history-back" >Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm">Save</button>
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
            doc_key: {required:true, minlength:3, maxlength:50, alpha_dash : true},
            doc_title: 'required',
            doc_content : {<?php echo ($this->configlib->item('use_document_dhtml')) ? 'required_' . $this->configlib->item('document_editor_type') : 'required'; ?> : true },
            doc_mobile_content : {<?php echo ($this->configlib->item('use_document_dhtml')) ? 'valid_' . $this->configlib->item('document_editor_type') : ''; ?> : true }
        }
    });
});
//]]>
</script>
