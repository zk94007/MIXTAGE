<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/font-awesome-4.3.0/css/font-awesome.css'); ?>" />
<!--<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" />-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugin/adminLTE/css/AdminLTE.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugin/adminLTE/css/skins/_all-skins.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('plugin/datepicker/datepicker3.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" />
<?php if (element('favicon', $layout)) { ?><link rel="shortcut icon" type="image/x-icon" href="<?php echo element('favicon', $layout); ?>" /><?php } ?>

<!--[if lt IE 9]>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('plugin/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('plugin/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('plugin/datepicker/locales/bootstrap-datepicker.kr.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('plugin/fastclick/fastclick.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo base_url('assets/js/html5shiv.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
<![endif]-->
<script type="text/javascript">
// 자바스크립트에서 사용하는 전역변수 선언
var ci_url = "<?php echo trim(site_url(), '/'); ?>";
var ci_admin_url = "<?php echo admin_url(); ?>";
var ci_charset = "<?php echo config_item('charset'); ?>";
var ci_time_ymd = "<?php echo cdate('Y-m-d'); ?>";
var ci_time_ymdhis = "<?php echo cdate('Y-m-d H:i:s'); ?>";
var admin_skin_path = "<?php echo element('layout_skin_path', $layout); ?>";
var admin_skin_url = "<?php echo element('layout_skin_url', $layout); ?>";
var is_user = "<?php echo $this->userlib->is_user() ? '1' : ''; ?>";
var is_admin = "<?php echo $this->userlib->is_admin(); ?>";
var ci_admin_url = <?php echo $this->userlib->is_admin() === 'super' ? 'ci_url + "/' . config_item('uri_segment_admin') . '"' : '""'; ?>;
var ci_board = "";
var ci_csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
var cookie_prefix = "<?php echo config_item('cookie_prefix'); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/sideview.js'); ?>"></script>
</head>
<body>
<!-- contents start -->
<?php
if (isset($yield)) {
    echo $yield;
}
?>
<!-- contents end -->

<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/js/app.min.js');?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets/js/demo.js');?>"></script>

</body>
</html>
