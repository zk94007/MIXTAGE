<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($this->configlib->get_device_view_type() === 'desktop' && $this->configlib->get_device_type() === 'mobile') { ?>
<meta name="viewport" content="width=1000">
<?php } else { ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php } ?>
<title><?php echo html_escape(element('page_title', $layout)); ?></title>
<?php if (element('meta_description', $layout)) { ?><meta name="description" content="<?php echo html_escape(element('meta_description', $layout)); ?>"><?php } ?>
<?php if (element('meta_keywords', $layout)) { ?><meta name="keywords" content="<?php echo html_escape(element('meta_keywords', $layout)); ?>"><?php } ?>
<?php if (element('meta_author', $layout)) { ?><meta name="author" content="<?php echo html_escape(element('meta_author', $layout)); ?>"><?php } ?>
<?php if (element('favicon', $layout)) { ?><link rel="shortcut icon" type="image/x-icon" href="<?php echo element('favicon', $layout); ?>" /><?php } ?>
<?php if (element('canonical', $view)) { ?><link rel="canonical" href="<?php echo element('canonical', $view); ?>" /><?php } ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap-theme.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/font-awesome-4.3.0/css/font-awesome.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.min.css'); ?>" />
<?php echo $this->layoutlib->display_css(); ?>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript">
// 자바스크립트에서 사용하는 전역변수 선언
var ci_url = "<?php echo trim(site_url(), '/'); ?>";
var ci_cookie_domain = "<?php echo config_item('cookie_domain'); ?>";
var ci_charset = "<?php echo config_item('charset'); ?>";
var ci_time_ymd = "<?php echo cdate('Y-m-d'); ?>";
var ci_time_ymdhis = "<?php echo cdate('Y-m-d H:i:s'); ?>";
var layout_skin_path = "<?php echo element('layout_skin_path', $layout); ?>";
var view_skin_path = "<?php echo element('view_skin_path', $layout); ?>";
var is_user = "<?php echo $this->userlib->is_user() ? '1' : ''; ?>";
var is_admin = "<?php echo $this->userlib->is_admin(); ?>";
var ci_admin_url = <?php echo $this->userlib->is_admin() === 'super' ? 'ci_url + "/' . config_item('uri_segment_admin') . '"' : '""'; ?>;
var ci_board = "<?php echo isset($view) ? element('board_key', $view) : ''; ?>";
var ci_board_url = <?php echo ( isset($view) && element('board_key', $view)) ? 'ci_url + "/' . config_item('uri_segment_board') . '/' . element('board_key', $view) . '"' : '""'; ?>;
var ci_device_type = "<?php echo $this->configlib->get_device_type() === 'mobile' ? 'mobile' : 'desktop' ?>";
var ci_csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
var cookie_prefix = "<?php echo config_item('cookie_prefix'); ?>";
</script>
<!--[if lt IE 9]>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/sideview.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/js.cookie.js'); ?>"></script>
<?php echo $this->layoutlib->display_js(); ?>
</head>
<body <?php echo isset($view) ? element('body_script', $view) : ''; ?>>
<div class="wrapper">

    <?php if ($this->configlib->get_device_view_type() !== 'mobile') {?>
        <!-- header start -->
        <header class="header">
            <div class="container">
                <ul class="header-top-menu">
                    <?php if ($this->userlib->is_admin() === 'super') { ?>
                        <li><i class="fa fa-cog"></i><a href="<?php echo site_url(config_item('uri_segment_admin')); ?>" title="Go to admin page">Admin</a></li>
                    <?php } ?>
                    <?php
                    if ($this->userlib->is_user()) {
                    ?>
                        <li><i class="fa fa-sign-out"></i><a href="<?php echo site_url('login/logout?url=' . urlencode(current_full_url())); ?>" title="Log out">Log out</a></li>
                    <?php } else { ?>
                        <li><i class="fa fa-sign-in"></i><a href="<?php echo site_url('login?url=' . urlencode(current_full_url())); ?>" title="Log in">Log In</a></li>
                    <?php } ?>
                </ul>
            </div>
        <!-- header-content end -->
        </header>

<?php } else {?>

    <div class="header_line"></div>

<?php } ?>

    <!-- nav start -->
    <nav class="navbar navbar-default">
        <div class="container">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a href="<?php echo site_url(); ?>" class="navbar-brand" title="<?php echo html_escape($this->configlib->item('site_title'));?>"><?php echo $this->configlib->item('site_logo'); ?></a>
            </div>
        </div>
    </nav>
    <!-- nav end --> <!-- header end -->

    <!-- main start -->
    <div class="main">
        <div class="container">
            <div class="row">

                <!-- 본문 시작 -->
                <?php if (isset($yield))echo $yield; ?>
                <!-- 본문 끝 -->

            </div>
        </div>
    </div>
    <!-- main end -->

    <!-- footer start -->
    <footer>
        <div class="container">Intranet</div>
    </footer>
    <!-- footer end -->
</div>
<?php echo $this->configlib->item('footer_script'); ?>
<!--
Layout Directory : <?php echo element('layout_skin_path', $layout); ?>,
Layout URL : <?php echo element('layout_skin_url', $layout); ?>,
Skin Directory : <?php echo element('view_skin_path', $layout); ?>,
Skin URL : <?php echo element('view_skin_url', $layout); ?>,
-->
</body>
</html>
