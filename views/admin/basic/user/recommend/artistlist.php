<div class="box">
    <div class="box-table">
        <?php
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
        echo form_open(current_full_url(), $attributes);

		$portfolio_category = config_item('portfolio_category');
		$user_level = config_item('user_level');
        ?>
            <h2><?php echo html_escape(element('artist_category', $view)); ?></h2>
            <div class="row">All : <?php echo element('total_rows', element('data', $view), 0); ?></div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><a href="<?php echo element('user_id', element('sort', $view)); ?>">No</a></th>
                            <th><a href="<?php echo element('user_userid', element('sort', $view)); ?>">ID</a></th>
                            <th><a href="<?php echo element('user_username', element('sort', $view)); ?>">Name</a></th>
                            <th><a href="<?php echo element('user_email', element('sort', $view)); ?>">Email</a></th>
                            <th>Artist field</th>
                            <th><a href="<?php echo element('user_register_datetime', element('sort', $view)); ?>">Sign up date </a></th>
                            <th><a href="<?php echo element('user_lastlogin_datetime', element('sort', $view)); ?>">Last log in</a></th>
                            <th>Authority</th>
                            <th>Select</th>
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
                                 <?php echo element('user_denied', $result) ? '<span class="label label-danger">Cut/Break</span>' : ''; ?>
                            </td>
                            <td><?php echo html_escape(element('user_email', $result)); ?></td>
                            <td><?php echo element(element('user_artist_category', $result), $portfolio_category); ?></td>
                            <td><?php echo display_datetime(element('user_register_datetime', $result), 'full'); ?></td>
                            <td><?php echo display_datetime(element('user_lastlogin_datetime', $result), 'full'); ?></td>
                            <td><?php echo element(element('user_level', $result), $user_level); ?></td>
                            <td>
								<button 
									class="select btn btn-xs btn-default" 
									data-id="<?php echo element('user_id', $result); ?>" 
									data-userid="<?php echo element('user_userid', $result); ?>" 
									data-username="<?php echo element('user_username', $result); ?>" 
									data-photo="<?php echo user_photo_url(element('user_photo', $result)); ?>" 
									>Select</button>
							</td>
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

<script>
$(document).ready(function(){
    $(document).on('click', '.select', function(){
        $('#ids_<?=$_GET['id']?>_<?=$_GET['num']?>',parent.document).val($(this).data('id'));
        $('#userids_<?=$_GET['id']?>_<?=$_GET['num']?>',parent.document).val($(this).data('userid'));
        $('#usernames_<?=$_GET['id']?>_<?=$_GET['num']?>',parent.document).val($(this).data('username'));
        $('#photos_<?=$_GET['id']?>_<?=$_GET['num']?>',parent.document).val($(this).data('photo'));
        parent.$.fn.colorbox.close();
    });

});
</script>
