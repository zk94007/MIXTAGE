<div class="box">
    <div class="box-table">
		<div class="alert alert-warning">
            It is outputted on the App the most recent seminar in the  agreed ones .<br />
            If there is no agreed seminar, there is no output seminar on the App .
		</div>
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
                    <a href="<?php echo element('write_url', $view); ?>" class="btn btn-danger btn-sm">Add seminar</a>
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
                            <th>No</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Total entry count</th>
                            <th>Current entry count</th>
                            <th>Agreed or not</th>
                            <th>Modify</th>
                            <th><input type="checkbox" name="chkall" id="chkall" /></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (element('list', element('data', $view))) {
                        foreach (element('list', element('data', $view)) as $result) {
                    ?>
                        <tr>
                            <td><?php echo number_format(element('num', $result)); ?></td>
                            <td><?php if(element('sem_image_name', $result)) { ?><img src="<?php echo seminar_image_url(element('sem_image_name', $result)); ?>" class="thumbnail" style="width:100px;" /><?php } ?></td>
                            <td><?php echo html_escape(element('sem_title', $result)); ?></td>
                            <td><?php echo html_escape(element('sem_total_user', $result)); ?></td>
                            <td><?php echo html_escape(element('sem_attend_user', $result)); ?> <a class="btn btn-xs bg-olive" href="<?php echo admin_url($this->pagedir); ?>/view/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>">Detail</a></td>
                            <td><?php echo element('sem_approve', $result) ? '<button type="button" class="btn btn-xs btn-primary">Agreed</button>' : '<button type="button" class="btn btn-xs btn-danger">Not agreed</button>'; ?></td>
                            <td><a href="<?php echo admin_url($this->pagedir); ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-default btn-xs">Modify</a></td>
                            <td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
                        </tr>
                    <?php
                        }
                    }
                    if ( ! element('list', element('data', $view))) {
                    ?>
                        <tr>
                            <td colspan="12" class="nopost">There is no data.</td>
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
