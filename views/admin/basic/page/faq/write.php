<div class="box">
    <div class="box-table">
        <?php
        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        $attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
        echo form_open(current_full_url(), $attributes);
        ?>
            <input type="hidden" name="<?php echo element('primary_key', $view); ?>"    value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
            <input type="hidden" name="fgr_id"    value="<?php echo element('fgr_id', element('faqgroup', element('data', $view))); ?>" />
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Group</label>
                    <div class="col-sm-10">
                        <?php echo html_escape(element('fgr_title', element('faqgroup', element('data', $view)))); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Arrange order</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="faq_order" value="<?php echo set_value('faq_order', element('faq_order', element('data', $view))); ?>" />
                        <div class="help-inline">When arrange order of FAQ is late, it is outputted earlier</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Question</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="faq_title" value="<?php echo set_value('faq_title', element('faq_title', element('data', $view))); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Answer</label>
                    <div class="col-sm-10">
                        <?php echo display_dhtml_editor('faq_content', set_value('faq_content', element('faq_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = $this->configlib->item('use_faq_dhtml'), $editor_type = $this->configlib->item('faq_editor_type')); ?>
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
            faq_order: { required:true, number:true, min:0 },
            faq_title : { required:true },
            faq_content : {<?php echo ($this->configlib->item('use_faq_dhtml')) ? 'required_' . $this->configlib->item('faq_editor_type') : 'required'; ?> : true }
        }
    });
});
//]]>
</script>
