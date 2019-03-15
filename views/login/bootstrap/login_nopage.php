<?php $this->layoutlib->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="access col-md-6 col-md-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading">Notice</div>
        <div class="panel-body">
            <div class="alert alert-warning" role="alert">
				<p>Hello!</p>
				<p>You have no access permission to this site!</p>
				<p>Please contact the administrator!</p>
			</div>
        </div>
        <div class="panel-footer">
            <div class="pull-right">
                <a href="<?php echo site_url('login/logout'); ?>" class="btn btn-success btn-sm" title="Log out">Log out</a>
            </div>
        </div>
    </div>
</div>
