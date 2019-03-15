<div class="box">
    <div class="box-table">
        <?php
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
        echo form_open(current_full_url(), $attributes);
        ?>
            <div class="box-table-header">
                <?php
                ob_start();
                ?>
                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <a href="<?php echo element('listall_url', $view); ?>" class="btn btn-default btn-sm">All list</a>
                        <button type="button" class="btn btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >Delete selected</button>
                    </div>
                <?php
                $buttons = ob_get_contents();
                ob_end_flush();
                ?>
            </div>
            <div class="row">All : <?php echo element('total_rows', element('data', $view), 0); ?></div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><a href="<?php echo element('cmt_id', element('sort', $view)); ?>">No</a></th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Created at</th>
                            <th>Kind of request</th>
                            <th>Requester</th>
                            <th>Respondent</th>
                            <th>Kind of response</th>
                            <th>Response date</th>
                            <th><input type="checkbox" name="chkall" id="chkall" /></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					$request_type = config_item('request_type');
					$request_type_class = config_item('request_type_class');
					$request_response_type = config_item('request_response_type');
					$request_response_type_class = config_item('request_response_type_class');
                    if (element('list', element('data', $view))) {
                        foreach (element('list', element('data', $view)) as $result) {
                    ?>
                        <tr>
                            <td><?php echo number_format(element('num', $result)); ?></td>
                            <td><img src="<?php echo collaboration_image_url(element('col_image_name', $result)); ?>" class="thumbnail mg0" style="width:80px;" /></td>
                            <td><?php echo html_escape(element('col_desc', $result)); ?></td>
                            <td><?php echo display_datetime(element('col_datetime', $result), 'full'); ?></td>
                            <td><button class="btn btn-xs <?php echo element(element('cre_type', $result), $request_type_class); ?>"><?php echo element(element('cre_type', $result), $request_type); ?></button></td>
                            <td><?php echo element('req_display_name', $result); ?></td>
                            <td><?php echo element('res_display_name', $result); ?></td>
                            <td><button class="btn btn-xs <?php echo element(element('cre_response', $result), $request_response_type_class); ?>"><?php echo element(element('cre_response', $result), $request_response_type); ?></button></td>
                            <td><?php echo display_datetime(element('cre_response_datetime', $result), 'full'); ?></td>
                            <td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
                        </tr>
                    <?php
                        }
                    }
                    if ( ! element('list', element('data', $view))) {
                    ?>
                        <tr>
                            <td colspan="11" class="nopost">There is no data.</td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="box-info">
                <?php echo element('paging', $view); ?>
                <div class="pull-left ml20"><?php echo admin_listnum_selectbox();?></div>
                <?php echo $buttons; ?>
            </div>
        <?php echo form_close(); ?>
    </div>
    <form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">
        <div class="box-search">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <select class="form-control" name="sfield" >
                        <?php echo element('search_option', $view); ?>
                    </select>
                    <div class="input-group">
                        <input type="text" class="form-control" name="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." />
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-sm" name="search_submit" type="submit">Search!</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
