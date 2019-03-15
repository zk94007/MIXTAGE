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
                    <label class="col-sm-2 control-label">Content</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="15" name="gre_content"><?php echo set_value('gre_content', element('gre_content', element('data', $view))); ?></textarea>
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
            gre_content : {required}
        }
    });
});
//]]>
</script>
