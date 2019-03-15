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
<!--<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" />-->
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
<body class="hold-transition sidebar-mini
<?php echo element('admin-skin', $layout) ? ' ' . element('admin-skin', $layout) : ' skin-blue ';?>
<?php echo ' ' . element('admin-layout-fixed', $layout);?>
<?php echo ' ' . element('admin-layout-layout-boxed', $layout);?>
<?php echo ' ' . element('admin-layout-sidebar-collapse', $layout);?>
<?php echo ' ' . element('admin-layout-control-sidebar-open', $layout);?>
">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo admin_url(); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Admin</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <li>
                        <a href="<?php echo site_url(); ?>" target="_blank">
                            <span class="hidden-xs">Home page</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('login/logout'); ?>">
                            <span class="hidden-xs">Log out</span>
                        </a>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
            <?php
            foreach (element('admin_page_menu', $layout) as $__akey => $__aval) {
            ?>
                <li class="treeview <?php echo $__akey; ?> <?php echo (element('menu_dir1', $layout) === $__akey) ? 'active' : ''; ?>">
                    <a href="#">
                        <i class="fa <?php echo element(1, element('__config', $__aval)); ?>"></i>
                        <span><?php echo element(0, element('__config', $__aval)); ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                        foreach (element('menu', $__aval) as $menukey => $menuvalue) {
                            if (element(2, $menuvalue) === 'hide') {
                                continue;
                            }
                        ?>
                            <li <?php echo (element('menu_dir1', $layout) === $__akey && element('menu_dir2', $layout) === $menukey) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url($__akey . '/' . $menukey); ?>" <?php echo element(1, $menuvalue); ?> ><i class="fa fa-circle-o"></i> <?php echo element(0, $menuvalue); ?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
            <?php
            }
            ?>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?php echo element('menu_title', $layout);?>
            </h1>
			<?php
			if (isset($layout['menu_dir1']) && $layout['menu_dir1'] ) {
			?>
            <ol class="breadcrumb">
                <li><a href="<?php echo admin_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><?php echo $layout['admin_page_menu'][$layout['menu_dir1']]['__config'][0];?></li>
                <li class="active"><?php echo element('menu_title', $layout);?></li>
            </ol>
			<?php
			}
			?>
        </section>
		<section class="content">

        <!-- contents start -->
<?php
if (isset($yield)) {
    echo $yield;
}
?>
        <!-- contents end -->
		</section>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; WEBHUB</strong> All rights reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar <?php echo element('admin-layout-sidebarskin', $layout);?>">
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab"></div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/js/app.min.js');?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets/js/demo.js');?>"></script>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
    $(function() {
        $('#fsearch').validate({
            rules: {
                skeyword: { required:true, minlength:2}
            }
        });
    });
});
//]]>
</script>

</body>
</html>
