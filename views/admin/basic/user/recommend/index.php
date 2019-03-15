<div class="box">
    <div class="box-table">
        <?php
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
        echo form_open(current_full_url(), $attributes);
		$portfolio_category = config_item('portfolio_category');


		if (element('list', $view)) {
			foreach (element('list', $view) as $result) {
        ?>

			<h2><?php echo html_escape(element('rec_title', $result));?></h2>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
					<thead>
					<tr>
						<th scope="col">User No</th>
						<th scope="col">User ID</th>
						<th scope="col">User name</th>
						<th scope="col">Select</th>
					</tr>
					</thead>
					<tbody>
						<?php
						$content = json_decode(element('rec_content', $result), 1);
						for ($i=1;$i<=10;$i++) {
						?>
								<input type="hidden" name="photos[<?php echo element('rec_id', $result); ?>][<?php echo $i; ?>]" id="photos_<?php echo element('rec_id', $result); ?>_<?php echo $i; ?>" value="<?=$content[$i]['photo']?>" readonly="readonly" />
								<tr>
									<td><input type="text" name="ids[<?php echo element('rec_id', $result); ?>][<?php echo $i; ?>]" id="ids_<?php echo element('rec_id', $result); ?>_<?php echo $i; ?>" value="<?=$content[$i]['id']?>" class="form-control" readonly="readonly" /></td>
									<td><input type="text" name="userids[<?php echo element('rec_id', $result); ?>][<?php echo $i; ?>]" id="userids_<?php echo element('rec_id', $result); ?>_<?php echo $i; ?>" value="<?=$content[$i]['userid']?>" class="form-control" readonly="readonly" /></td>
									<td><input type="text" name="usernames[<?php echo element('rec_id', $result); ?>][<?php echo $i; ?>]" id="usernames_<?php echo element('rec_id', $result); ?>_<?php echo $i; ?>" value="<?=$content[$i]['username']?>" class="form-control" readonly="readonly" /></td>
									<td>
										<button type="button" class="search_btn btn btn-xs btn-success" 
										data-category-id="<?php echo element('rec_category', $result); ?>" 
										data-id="<?php echo element('rec_id', $result); ?>" 
										data-num="<?php echo $i; ?>" >Select</button>
										<button type="button" class="delete_btn btn btn-xs btn-danger" 
										data-id="<?php echo element('rec_id', $result); ?>" 
										data-num="<?php echo $i; ?>" >Delete</button>
									</td>
								</tr>
						<?php
						}
						?>
				</tbody>
			</table>
		</div>

		<?php
			}
		}
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">Save</button>
			</div>
        <?php echo form_close(); ?>
    </div>
</div>

<link rel='stylesheet' href='<?php echo base_url('assets/colorbox/colorbox.css'); ?>'/>
<script src="<?php echo base_url('assets/colorbox/jquery.colorbox-min.js'); ?>"></script>
<script>
$(document).ready(function()
{
	$(".search_btn").colorbox(
	{
		href: function()
		{
			return "<?php echo admin_url('user/recommend/artistlist'); ?>/" + $(this).data('category-id') + "?num=" + $(this).data('num') + "&id=" + $(this).data('id');
		},
		iframe: true,
		width: "80%",
		height: "80%"
	});

	$(document).on('click', '.delete_btn', function () {
		$('#ids_' + $(this).data('id') + '_' + $(this).data('num')).val('');
		$('#userids_' + $(this).data('id') + '_' + $(this).data('num')).val('');
		$('#usernames_' + $(this).data('id') + '_' + $(this).data('num')).val('');
	});

});
</script>
 