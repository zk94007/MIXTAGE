<div class="col-sm-6">
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">최근등록회원
            <div class="view-all">
                <a href="<?php echo admin_url('user/users'); ?>">More <i class="fa fa-angle-right"></i></a>
            </div>
        </div>
        <!-- Table -->
        <table class="table table-hover table-striped">
            <colgroup>
                <col class="col-md-6">
                <col class="col-md-3">
                <col class="col-md-3">
            </colgroup>
            <tbody>
            <?php
            if (element('list', element('latest_user', $view))) {
                foreach (element('list', element('latest_user', $view)) as $key => $value) {
            ?>
                <tr>
                    <td><?php echo html_escape(element('user_userid', $value)); ?></td>
                    <td><?php echo element('display_name', $value); ?></td>
                    <td class="text-right"><?php echo display_datetime(element('user_register_datetime', $value)); ?></td>
                </tr>
            <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
