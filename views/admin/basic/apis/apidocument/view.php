<div class="box">
    <div class="box-table">
		<div class="form-group">
			<h3>API 명</h3>
			<h5><?php echo html_escape(element('api_name', element('data', $view))); ?></h5>
		</div>
		<div class="form-group">
			<h3>API 설명</h3>
			<h5><?=nl2br(html_escape(element('api_exp', element('data', $view))))?></h5>
		</div>
		
		<div class="form-group">
			<h3>호출정보</h3>
			<table class="table table-bordered">
				<tr>
				<th class="bg bg-success">호출주소</th>
				<td>
					<a href="<?php echo site_url('api/' . element('api_name', element('data', $view))); ?>" target="_blank"><?php echo site_url('api/' . element('api_name', element('data', $view))); ?></a>
				</td>
				</tr>
				<tr>
				<th class="bg bg-success">호출방식</th>
				<td><?php echo html_escape(element('api_method', element('data', $view))); ?></td>
			</tr>
			</table>
		</div>

   <form name="test_form" method="<?php echo html_escape(element('api_method', element('data', $view))); ?>" enctype="multipart/form-data">
   <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
   <input type="hidden" name="api_idx" value="<?php echo html_escape(element('api_idx', element('data', $view))); ?>">
   <table class="table table-hover table-bordered" >
   <tr>

   	<th width="120" class="bg bg-warning">변수명</th>
   	<th width="100" class="bg bg-warning">타입</th>
   	<th width="80" class="bg bg-warning">종류</th>
   	<th class="bg bg-warning">설명</th>
	<th width="250" class="bg bg-warning">
		<button type="button" class="btn btn-xs btn-default" onClick="testResult('json2');">JSON_DECODED</button>
		<button type="button" class="btn btn-xs btn-default" onClick="testResult('xml');">XML</button>
		<button type="button" class="btn btn-xs btn-default" onClick="testResult('json');">JSON</button>
	</th>
   </tr>
   <tr>
                            <td>return_type</td>
                            <td>String</td>
                            <td>필수</td>
                            <td>결과값 출력방식 xml, json중 택일, 생략시 xml</td>
							<td></td>
   </tr>
   <tr>
                            <td>token</td>
                            <td>String</td>
                            <td>필수</td>
                            <td>디바이스 고유 token</td>
							<td><input name="token" type="text" style="width:220px;" class="form-control"></td>
   </tr>
	<?php
	if (element('input', $view)) {
		foreach (element('input', $view) as $result) {
	?>
   <tr>
                            <td><?php echo html_escape(element('ai_name', $result)); ?></td>
                            <td><?php echo html_escape(element('ai_type', $result)); ?></td>
                            <td><?php echo html_escape(element('ai_ness', $result)); ?></td>
                            <td><?php echo nl2br(html_escape(element('ai_exp', $result))); ?></td>
							<td>
							<?php
							if ((element('ai_type', $result) == 'MultiFile')) {
							?>
							<input name="<?php echo element('ai_name', $result); ?>[]" type="file" style="width:220px;" class="form-control">
							<input name="<?php echo element('ai_name', $result); ?>[]" type="file" style="width:220px;" class="form-control">
							<input name="<?php echo element('ai_name', $result); ?>[]" type="file" style="width:220px;" class="form-control">
							<input name="<?php echo element('ai_name', $result); ?>[]" type="file" style="width:220px;" class="form-control">
							<input name="<?php echo element('ai_name', $result); ?>[]" type="file" style="width:220px;" class="form-control">
							<?php
							} else {
							?>
							<input name="<?php echo element('ai_name', $result); ?>" type="<?php echo (element('ai_type', $result) == 'File') ? 'file' : 'text'; ?>" style="width:220px;" class="form-control">
							<?php
							}
							?>
							
							</td>
   </tr>
   <?php
	}
	}
	?>
 	</table>
	</form>
	<script>
		function testResult(arg)
		{
			var f = document.test_form;
			f.target = arg;
			f.action =  '<?php echo admin_url('apis/apidocument/'); ?>/' + arg + '/<?php echo element('api_idx', element('data', $view)); ?>' ;
			f.submit();
		}
	</script>
 	
 	<h3>리턴값</h3>
 	<div class="alert bg-danger">JSON은 urlencode를 사용,xml인 경우 &lt;![CDATA[]]&gt;사용</div>
   <table class="table table-hover table-bordered" >
   <tr>
   	<th width="120" class="bg bg-warning">변수명</th>

   	<th width="100" class="bg bg-warning">타입</th>
   	<th width="80" class="bg bg-warning">종류</th>
   	<th class="bg bg-warning">설명</th>
   </tr>

   <tr>
                            <td>result</td>
                            <td>String</td>
                            <td>필수</td>
                            <td>결과값 출력방식 xml, json중 택일, 생략시 xml</td>
   </tr>
   <tr>
                            <td>token</td>
                            <td>String</td>
                            <td>필수</td>
                            <td>디바이스 고유 token</td>
   </tr>
	<?php
	if (element('output', $view)) {
		foreach (element('output', $view) as $result) {
	?>
   <tr>
                            <td><?php echo html_escape(element('ai_name', $result)); ?></td>
                            <td><?php echo html_escape(element('ai_type', $result)); ?></td>
                            <td><?php echo html_escape(element('ai_ness', $result)); ?></td>
                            <td><?php echo nl2br(html_escape(element('ai_exp', $result))); ?></td>
   </tr>
   <?php
	}
	}
	?>

 	</table>
	<h2>JSON Decoded Sample</h2>
	<p>
	<iframe src="<?php echo admin_url('apis/apidocument/emptypage')?>" style="width:95%;height:500px;" name="json2"></iframe>
	</p>
	<h3>xml Sample</h3>
	<p>
	<iframe src="<?php echo admin_url('apis/apidocument/emptypage')?>" style="width:95%;height:500px;" name="xml"></iframe>
	</p>
	<h2>JSON Sample</h2>
	<p>
	<iframe src="<?php echo admin_url('apis/apidocument/emptypage')?>" style="width:95%;height:500px;" name="json"></iframe>
	</p>
