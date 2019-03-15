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
                        <a href="<?php echo element('write_url', $view); ?>" class="btn btn-danger btn-sm">Create FAQ</a>
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
                            <th><a href="<?php echo element('fgr_id', element('sort', $view)); ?>">No</a></th>
                            <th><a href="<?php echo element('fgr_title', element('sort', $view)); ?>">Title</a></th>
                            <th><a href="<?php echo element('fgr_datetime', element('sort', $view)); ?>">Made at</a></th>
                            <th>Creator</th>
                            <th>FAQ count</th>
                            <th>Add content</th>
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
                            <td><a href="<?php echo admin_url('page/faq'); ?>/?fgr_id=<?php echo element(element('primary_key', $view), $result); ?>" ><?php echo html_escape(element('fgr_title', $result)); ?></a></td>
                            <td><?php echo display_datetime(element('fgr_datetime', $result), 'full'); ?></td>
                            <td><?php echo element('display_name', $result); ?> <?php if (element('user_userid', $result)) { ?> ( <a href="?sfield=faq_group.user_id&amp;skeyword=<?php echo element('user_id', $result); ?>"><?php echo html_escape(element('user_userid', $result)); ?></a> ) <?php } ?></td>
                            <td><a href="<?php echo admin_url('page/faq'); ?>/?fgr_id=<?php echo element(element('primary_key', $view), $result); ?>" ><?php echo element('faqcount', $result) + 0; ?></a></td>
                            <td><a href="<?php echo admin_url('page/faq'); ?>/write/?fgr_id=<?php echo element(element('primary_key', $view), $result); ?>" class="btn btn-primary btn-xs">Add content</a></td>
                            <td><a href="<?php echo admin_url($this->pagedir); ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-default btn-xs">Modify</a></td>
                            <td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
                        </tr>
                    <?php
                        }
                    }
                    if ( ! element('list', element('data', $view))) {
                    ?>
                        <tr>
                            <td colspan="13" class="nopost">There is no data.</td>
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
