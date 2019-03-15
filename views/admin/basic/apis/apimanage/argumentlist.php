<div class="box">
    <div class="box-table">
        <?php
        echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
        echo form_open(current_full_url(), $attributes);
        ?>
            <div class="box-table-header">
            <h3><?php echo html_escape(element('api_name', element('apidata', $view)));?> : <?php echo element('type', $view)?> 목록</h3>
			<?php
            ob_start();
            ?>
                <div class="btn-group pull-right" role="group" aria-label="...">
                    <a href="<?php echo element('listall_url', $view); ?>" class="btn btn-default btn-sm">API 목록으로 돌아가기</a>
                    <a href="<?php echo element('input_list_url', $view); ?>" class="btn btn-<?php echo element('type', $view) == 'input' ? 'success' : 'default'; ?> btn-sm">Input 목록</a>
                    <a href="<?php echo element('output_list_url', $view); ?>" class="btn btn-<?php echo element('type', $view) == 'output' ? 'success' : 'default'; ?> btn-sm">Output 목록</a>
                    <button type="button" class="btn btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
                    <a href="<?php echo element('write_url', $view); ?>" class="btn btn-danger btn-sm"><?php echo element('type', $view)?> 변수 추가</a>
                </div>
            <?php
            $buttons = ob_get_contents();
            ob_end_flush();
            ?>
            </div>
            <div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>변수명</th>
                            <th>타입</th>
                            <th>종류</th>
                            <th>설명</th>
                            <th>순서</th>
                            <th>수정</th>
                            <th><input type="checkbox" name="chkall" id="chkall" /></th>
                        </tr>
                    </thead>
                    <tbody>
					<?php if (element('type', $view) == 'input') { ?>
                        <tr>
                            <td>return_type</td>
                            <td>String</td>
                            <td>필수</td>
                            <td>결과값 출력방식 xml, json중 택일, 생략시 xml</td>
                            <td>0</td>
                            <td></td>
                            <td></td>
                        </tr>
					<?php } ?>
					<?php if (element('type', $view) == 'output') { ?>
                        <tr>
                            <td>result</td>
                            <td>String</td>
                            <td>필수</td>
                            <td>결과값 출력방식 xml, json중 택일, 생략시 xml</td>
                            <td>0</td>
                            <td></td>
                            <td></td>
                        </tr>
					<?php } ?>
                    <?php
                    if (element('list', element('data', $view))) {
                        foreach (element('list', element('data', $view)) as $result) {
                    ?>
                        <tr>
                            <td><?php echo html_escape(element('ai_name', $result)); ?></td>
                            <td><?php echo html_escape(element('ai_type', $result)); ?></td>
                            <td><?php echo html_escape(element('ai_ness', $result)); ?></td>
                            <td><?php echo nl2br(html_escape(element('ai_exp', $result))); ?></td>
                            <td><?php echo number_format(element('ai_sort', $result)); ?></td>
                            <td><a href="<?php echo admin_url($this->pagedir); ?>/argumentwrite/<?php echo element('type', $view); ?>/<?php echo element('api_idx', element('apidata', $view)); ?>/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-default btn-xs">수정</a></td>
                            <td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
                        </tr>
                    <?php
                        }
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
                            <button class="btn btn-default btn-sm" name="search_submit" type="submit">검색!</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
