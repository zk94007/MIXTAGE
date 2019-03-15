<div class="box">
    <div class="box-table">
        <?php
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
        echo form_open(current_full_url(), $attributes);

		$portfolio_category = config_item('portfolio_category');
		$user_level = config_item('user_level');
        ?>
            <div class="box-table-header">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="?user_artist_category=<?php echo $this->input->get('user_artist_category'); ?>" class="btn btn-sm <?php echo ( ! $this->input->get('user_level')) ? 'btn-success' : 'btn-default'; ?>">All authority</a>
                    <?php
                    foreach ($user_level as $gkey => $gval) {
                    ?>
                    <a href="?user_artist_category=<?php echo $this->input->get('user_artist_category'); ?>&amp;user_level=<?php echo $gkey; ?>" class="btn btn-sm <?php echo ($this->input->get('user_level') == $gkey) ? 'btn-success' : 'btn-default'; ?>"><?php echo $gval; ?></a>
                    <?php
                    }
                    ?>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <a href="?user_level=<?php echo $this->input->get('user_level'); ?>" class="btn btn-sm <?php echo ( ! $this->input->get('user_artist_category')) ? 'btn-primary' : 'btn-default'; ?>">All artist</a>
                    <?php
                    foreach ($portfolio_category as $gkey => $gval) {
                    ?>
                    <a href="?user_level=<?php echo $this->input->get('user_level'); ?>&amp;user_artist_category=<?php echo $gkey; ?>" class="btn btn-sm <?php echo ($this->input->get('user_artist_category') == $gkey) ? 'btn-primary' : 'btn-default'; ?>"><?php echo $gval; ?></a>
                    <?php
                    }
                    ?>
                </div>
                <?php
                ob_start();
                ?>
                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <a href="<?php echo element('listall_url', $view); ?>" class="btn btn-default btn-sm">All list</a>
                        <button type="button" class="btn btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >Delete selected</button>
                        <a href="<?php echo element('write_url', $view); ?>" class="btn btn-danger btn-sm">Create user</a>
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
                            <th><a href="<?php echo element('user_id', element('sort', $view)); ?>">No</a></th>
                            <th><a href="<?php echo element('user_userid', element('sort', $view)); ?>">User ID</a></th>
                            <th><a href="<?php echo element('user_username', element('sort', $view)); ?>">User name</a></th>
                            <th><a href="<?php echo element('user_email', element('sort', $view)); ?>">Email</a></th>
                            <th>Mobile phone</th>
                            <th>Artist field</th>
                            <th><a href="<?php echo element('user_register_datetime', element('sort', $view)); ?>">Sign up date</a></th>
                            <th><a href="<?php echo element('user_lastlogin_datetime', element('sort', $view)); ?>">Last log in</a></th>
                            <th>Authority</th>
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
                            <td><?php echo html_escape(element('user_userid', $result)); ?></td>
                            <td>
                                <span><?php echo html_escape(element('user_username', $result)); ?></span>
                                <?php echo element('user_level', $result) == '10' ? '<span class="label label-primary">Super</span>' : ''; ?>
                                <?php echo element('user_level', $result) == '5' ? '<span class="label label-success">Mentor</span>' : ''; ?>
                                 <?php echo element('user_denied', $result) ? '<span class="label label-danger">cut/break</span>' : ''; ?>
                            </td>
                            <td><?php echo html_escape(element('user_email', $result)); ?></td>
                            <td><?php echo html_escape(element('user_phone', $result)); ?></td>
                            <td><?php echo element(element('user_artist_category', $result), $portfolio_category); ?></td>
                            <td><?php echo display_datetime(element('user_register_datetime', $result), 'full'); ?></td>
                            <td><?php echo display_datetime(element('user_lastlogin_datetime', $result), 'full'); ?></td>
                            <td><?php echo element(element('user_level', $result), $user_level); ?></td>
                            <td><a href="<?php echo admin_url($this->pagedir); ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-default btn-xs">Modify</a></td>
                            <td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
                        </tr>
                    <?php
                        }
                    }
                    if ( ! element('list', element('data', $view))) {
                    ?>
                        <tr>
                            <td colspan="17" class="nopost">There is no data.</td>
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
