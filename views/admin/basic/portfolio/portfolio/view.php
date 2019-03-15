<div class="box">
    <div class="box-table">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Cover image</label>
                    <div class="col-sm-10 form-control-static">
						<img src="<?php echo portfolio_image_url(element('por_cover_image_name', element('data', $view))); ?>" class="thumbnail mg0" style="width:80px;" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 form-control-static">
						<?php 
						$category = config_item('portfolio_category');
						echo element(element('por_category', element('data', $view)), $category);
						?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Portfolio</label>
                    <div class="col-sm-10 form-control-static">
						<?php  echo html_escape(element('por_content', element('data', $view))); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Collaboration</label>
                    <div class="col-sm-10 form-control-static">
						<?php echo html_escape(element('col_desc', element('data', $view))); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Attached image</label>
                    <div class="col-sm-10 form-control-static form-inline">
						<?php
						if (element('file', element('data', $view))) {
							foreach (element('file', element('data', $view)) as $file) {
						?>
						<img src="<?php echo portfolio_image_url(element('pfi_filename', $file)); ?>" class="thumbnail mg0" style="width:200px;display:inline;" />
						<?php
							}
						}
						?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Like (Mentor)</label>
                    <div class="col-sm-10 form-control-static">
						<a href="<?php echo admin_url('portfolio/like'); ?>?sfield=portfolio_like.por_id&amp;skeyword=<?php echo element('por_id', element('data', $view)); ?>"><?php echo number_format(element('por_like', element('data', $view))); ?></a>
						( <?php echo number_format(element('por_mentor_like', element('data', $view))); ?> )
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Comment (Mentor)</label>
                    <div class="col-sm-10 form-control-static">
						<a href="<?php echo admin_url('portfolio/comment'); ?>?sfield=portfolio_comment.por_id&amp;skeyword=<?php echo element('por_id', element('data', $view)); ?>"><?php echo number_format(element('por_comment', element('data', $view))); ?></a>
						( <?php echo number_format(element('por_mentor_comment', element('data', $view))); ?> )
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Clip (Mentor)</label>
                    <div class="col-sm-10 form-control-static">
						<a href="<?php echo admin_url('portfolio/clip'); ?>?sfield=portfolio_clip.por_id&amp;skeyword=<?php echo element('por_id', element('data', $view)); ?>"><?php echo number_format(element('por_clip', element('data', $view))); ?></a>
						( <?php echo number_format(element('por_mentor_clip', element('data', $view))); ?> )
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Blame</label>
                    <div class="col-sm-10 form-control-static">
						<a href="<?php echo admin_url('blame/portfolio'); ?>?sfield=portfolio_blame.por_id&amp;skeyword=<?php echo element('por_id', element('data', $view)); ?>"><?php echo number_format(element('por_blame', element('data', $view))); ?></a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 form-control-static">
						<?php 
						$tags = element('tags', element('data', $view)); 
						if ($tags) {
							$tagname = array();
							foreach ($tags as $val) {
								$tagname[] = element('tag_name', $val);
							}
							echo implode(', ', $tagname);
						}
						?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Made at</label>
                    <div class="col-sm-10 form-control-static">
						<?php echo display_datetime(element('por_datetime', element('data', $view)), 'full'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Updated at</label>
                    <div class="col-sm-10 form-control-static">
						<?php echo (element('por_datetime', element('data', $view)) != element('por_updated_datetime', element('data', $view))) ? display_datetime(element('por_updated_datetime', element('data', $view)), 'full') : ''; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Public or Not</label>
                    <div class="col-sm-10 form-control-static">
						<?php echo element('por_open', element('data', $view)) ? '<button type="button" class="btn btn-xs btn-primary">Public</button>' : '<button type="button" class="btn btn-xs btn-danger">Private</button>'; ?>
                    </div>
                </div>
				<div class="btn-group pull-right" role="group" aria-label="...">
                    <button type="button" class="btn btn-default btn-sm btn-history-back" >Previous page</button>
                </div>
            </div>
    </div>
</div>
