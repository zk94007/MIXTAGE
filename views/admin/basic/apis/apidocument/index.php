<div class="box">
    <div class="box-table">
	
	<h2>API Document</h2>
	<div class="alert alert-dismissible alert-info">
		<p>문자 입력 및 리턴 값은 모두 UTF-8입니다.</p>

		<p>리턴 값을 요하는 모든 API에 return_type 변수는 공통으로 사용될 수 있으며 xml 또는 json 중에 하나로 입력되어야 합니다. 기본값은 xml입니다.</p>
	</div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
				   <tr>
					<th width="100">이름</th>
					<th width="300">설명</th>
					<th>URL</th>

				   </tr>

				<?php
				if (element('data', $view)) {
					foreach (element('data', $view) as $result) {
				?>
				   <tr>
					<td><a href="<?php echo admin_url('apis/apidocument/view/' . element('api_idx', $result)); ?>"><?php echo html_escape(element('api_name', $result)); ?></a></td>
					<td><?php echo html_escape(element('api_exp', $result)); ?></td>
					<td><a href="<?php echo site_url('api/' . element('api_name', $result)); ?>" target="_blank"><?php echo site_url('api/' . element('api_name', $result)); ?></a></li>
				   </tr>
				<?php
					}
				}
				?>
		 	</table>
		</div>
	</div>
</div>
