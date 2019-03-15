<meta http-equiv="Content-Type" content="text/html; charset=<?php echo config_item('charset');?>" />
<style type="text/css">
th {font-weight:bold;padding:5px; min-width:120px; width:120px; _width:120px; text-align:center; line-height:18px; font-size:12px; color:#959595; font-family:dotum,돋움; border-right:1px solid #e4e4e4;}
td {text-align:center; line-height:40px; font-size:12px; color:#474747; font-family:gulim,굴림; border-right:1px solid #e4e4e4;}
</style>
<table width="100%" border="1" bordercolor="#E4E4E4" cellspacing="0" cellpadding="0">
        <tr>
            <th>아이디</th>
            <th>실명</th>
            <th>닉네임</th>
            <th>이메일</th>
            <th>가입일</th>
            <th>최근로그인</th>
            <th>회원그룹</th>
            <th>회원레벨</th>
            <th>메일인증/공개/메일/쪽지/문자</th>
            <th>승인</th>
        </tr>
    <?php
    if (element('list', element('data', $view))) {
        foreach (element('list', element('data', $view)) as $result) {
    ?>
            <tr>
                <td height="30"><?php echo html_escape(element('user_userid', $result)); ?></td>
                <td>
                    <span><?php echo html_escape(element('user_username', $result)); ?></span>
                    <?php echo element('user_is_admin', $result) ? '(최고관리자)' : ''; ?>
                    <?php echo element('user_denied', $result) ? '(차단회원)' : ''; ?>
                </td>
                <td><?php echo html_escape(element('user_nickname', $result)); ?></td>
                <td><?php echo html_escape(element('user_email', $result)); ?></td>
                <td><?php echo element('user_register_datetime', $result); ?></td>
                <td><?php echo element('user_lastlogin_datetime', $result); ?></td>
                <td><?php echo element('user_group', $result); ?></td>
                <td><?php echo element('user_level', $result); ?></td>
                <td>
                    <?php echo element('user_email_cert', $result) ? 'O' : 'X';; ?>
                    <?php echo element('user_open_profile', $result) ? 'O' : 'X';; ?>
                    <?php echo element('user_receive_email', $result) ? 'O' : 'X';; ?>
                    <?php echo element('user_use_note', $result) ? 'O' : 'X';; ?>
                    <?php echo element('user_receive_sms', $result) ? 'O' : 'X';; ?>
                </td>
                <td><?php echo element('user_denied', $result) ? '차단' : '승인'; ?></td>
            </tr>
        <?php
            }
        }
        ?>
</table>
