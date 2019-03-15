<div class="box">
    <div class="box-table">
		<div class="alert alert-info">
            This is the user list.
		</div>
            <div class="box-table-header">
            <?php
            ob_start();
            ?>
                <div class="btn-group pull-right" role="group" aria-label="...">
                    <a href="<?php echo element('listall_url', $view); ?>" class="btn btn-default btn-sm">Return to list</a>
                </div>
            <?php
            $buttons = ob_get_contents();
            ob_end_flush();
            ?>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User ID</th>
                            <th>User name</th>
                            <th>Join request date and time</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					$i = 1;
                    if (element('data', $view)) {
                        foreach (element('data', $view) as $result) {
                    ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo element('user_userid', $result); ?></td>
                            <td><?php echo element('display_name', $result); ?></td>
                            <td><?php echo display_datetime(element('seu_datetime', $result), 'full'); ?></td>
                        </tr>
                    <?php
							$i++;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="box-info">
                <?php echo $buttons; ?>
            </div>
    </div>
</div>
