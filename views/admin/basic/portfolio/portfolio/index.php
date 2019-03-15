<div class="box">
    <div class="box-table">
        <?php
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
        echo form_open(current_full_url(), $attributes);
		$portfolio_category = config_item('portfolio_category');
        ?>
            <div class="box-table-header">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="?" class="btn btn-sm <?php echo ( ! $this->input->get('por_category')) ? 'btn-primary' : 'btn-default'; ?>">All artist</a>
                    <?php
                    foreach ($portfolio_category as $gkey => $gval) {
                    ?>
                    <a href="?por_category=<?php echo $gkey; ?>" class="btn btn-sm <?php echo ($this->input->get('por_category') == $gkey) ? 'btn-primary' : 'btn-default'; ?>"><?php echo $gval; ?></a>
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
                            <th>Cover image</th>
                            <th>Portfolio</th>
                            <th>Category</th>
                            <th>Collaboration</th>
                            <th>Like (Mentor)</th>
                            <th>Comment (Mentor)</th>
                            <th>Clip (Mentor)</th>
                            <th>Blame</th>
                            <th>Made at</th>
                            <th>Updated at</th>
                            <th>Public or Not</th>
                            <th><input type="checkbox" name="chkall" id="chkall" /></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					$category = config_item('portfolio_category');
                    if (element('list', element('data', $view))) {
                        foreach (element('list', element('data', $view)) as $result) {
                    ?>
                        <tr>
                            <td><?php echo number_format(element('num', $result)); ?></td>
                            <td><a href="<?php echo admin_url($this->pagedir); ?>/view/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>"><img src="<?php echo portfolio_image_url(element('por_cover_image_name', $result)); ?>" class="thumbnail mg0" style="width:80px;" /></a></td>
                            <td><a href="<?php echo admin_url($this->pagedir); ?>/view/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>"><?php echo html_escape(element('por_content', $result)); ?></a></td>
                            <td><?php echo element(element('por_category', $result), $category); ?></td>
                            <td><a href="?col_id=<?php echo element('col_id', $result); ?>"><?php echo html_escape(element('col_desc', $result)); ?></a></td>
                            <td class="text-right"><a href="<?php echo admin_url('portfolio/like'); ?>?sfield=portfolio_like.por_id&amp;skeyword=<?php echo element('por_id', $result); ?>"><?php echo number_format(element('por_like', $result)); ?></a> ( <?php echo number_format(element('por_mentor_like', $result)); ?> )</td>
                            <td class="text-right"><a href="<?php echo admin_url('portfolio/comment'); ?>?sfield=portfolio_comment.por_id&amp;skeyword=<?php echo element('por_id', $result); ?>"><?php echo number_format(element('por_comment', $result)); ?></a> ( <?php echo number_format(element('por_mentor_comment', $result)); ?> )</td>
                            <td class="text-right"><a href="<?php echo admin_url('portfolio/clip'); ?>?sfield=portfolio_clip.por_id&amp;skeyword=<?php echo element('por_id', $result); ?>"><?php echo number_format(element('por_clip', $result)); ?></a> ( <?php echo number_format(element('por_mentor_clip', $result)); ?> )</td>
                            <td class="text-right"><a href="<?php echo admin_url('blame/portfolio'); ?>?sfield=portfolio_blame.por_id&amp;skeyword=<?php echo element('por_id', $result); ?>"><?php echo number_format(element('por_blame', $result)); ?></a></td>
                            <td><?php echo display_datetime(element('por_datetime', $result), 'full'); ?></td>
                            <td><?php echo (element('por_datetime', $result) != element('por_updated_datetime', $result)) ? display_datetime(element('por_updated_datetime', $result), 'full') : ''; ?></td>
                            <td><?php echo element('por_open', $result) ? '<button type="button" class="btn btn-xs btn-primary">Public</button>' : '<button type="button" class="btn btn-xs btn-danger">Private</button>'; ?></td>
                            <td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
                        </tr>
                    <?php
                        }
                    }
                    if ( ! element('list', element('data', $view))) {
                    ?>
                        <tr>
                            <td colspan="15" class="nopost">There is no data.</td>
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
