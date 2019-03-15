<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Layoutlib class
 */

/**
 * 레이아웃을 관리하는 class 입니다.
 */
class Layoutlib
{

    private $css = array();
    private $js = array();

    function __construct()
    {

    }


    /**
     * 관리자페이지 레이아웃관리합니다
     */
    function admin($config = array(), $device_view_type = '')
    {
        $data = array();
        $CI = & get_instance();
        $CI->load->config('ci_admin_menu');
        $data['admin_page_menu'] = config_item('admin_page_menu');

        if (uri_string() !== config_item('uri_segment_admin')) {
            list($data['menu_dir1'], $data['menu_dir2']) = explode('/', str_replace(config_item('uri_segment_admin') . '/', '', uri_string()));
            $data['menu_title'] = element(0, element(element('menu_dir2', $data), element('menu', element(element('menu_dir1', $data), element('admin_page_menu', $data)))));
        } else {
            $data['menu_dir1'] = '';
            $data['menu_dir2'] = '';
        }

        $layout_skin_file = element('layout', $config);
        $skin_file = element('skin', $config);

        $data['layout_skin_path'] = 'admin/' . ADMIN_SKIN;
        $data['layout_skin_url'] = base_url( VIEW_DIR . element('layout_skin_path', $data));
        $data['index_url'] = admin_url($data['menu_dir1'] . '/' . $data['menu_dir2']);
        $data['layout_skin_file'] = $data['layout_skin_path'] . '/' . $layout_skin_file;
        $data['view_skin_file'] = $data['layout_skin_path'] . '/';
        if ($data['menu_dir1']) {
            $data['view_skin_file'] .= $data['menu_dir1'] . '/';
        }
        if ($data['menu_dir2']) {
            $data['view_skin_file'] .= $data['menu_dir2'] . '/';
        }
        $data['view_skin_path'] = $data['view_skin_file'];
        $data['view_skin_url'] = base_url( VIEW_DIR . $data['view_skin_path']);
        $data['view_skin_file'] .= $skin_file;

        $data['favicon'] = $CI->configlib->item('site_favicon') ? site_url(config_item('uploads_dir') . '/favicon/' . $CI->configlib->item('site_favicon')) : '';

        $data['admin-skin']                        = $CI->userlib->item('admin-skin') ? $CI->userlib->item('admin-skin') : 'skin-blue';
        $data['admin-layout-fixed']                = $CI->userlib->item('admin-layout-fixed') == 'fixed' ? $CI->userlib->item('admin-layout-fixed') : '';
        $data['admin-layout-layout-boxed']         = $CI->userlib->item('admin-layout-layout-boxed') == 'layout-boxed' ? $CI->userlib->item('admin-layout-layout-boxed') : '';
        $data['admin-layout-sidebar-collapse']     = $CI->userlib->item('admin-layout-sidebar-collapse') == 'sidebar-collapse' ? $CI->userlib->item('admin-layout-sidebar-collapse') : '';
        $data['admin-layout-control-sidebar-open'] = $CI->userlib->item('admin-layout-control-sidebar-open') == 'control-sidebar-open' ? $CI->userlib->item('admin-layout-control-sidebar-open') : '';
        $data['admin-layout-sidebarskin']          = $CI->userlib->item('admin-layout-sidebarskin') == 'control-sidebar-light' ? 'control-sidebar-light' : 'control-sidebar-dark';

        return $data;
    }


    /**
     * 프론트페이지 레이아웃관리합니다
     */
    function front($config = array(), $device_view_type = '')
    {
        $data = array();
        $CI = & get_instance();

        if ($CI->uri->segment(1) === config_item('uri_segment_admin') && $CI->uri->segment(2) === 'preview') {
            return $this->preview($config);
        }

        $searchconfig = array(
            '{홈페이지제목}',
            '{현재주소}',
            '{회원아이디}',
            '{회원닉네임}',
            '{회원레벨}',
        );
        $replaceconfig = array(
            $CI->configlib->item('site_title'),
            current_full_url(),
            $CI->userlib->item('user_userid'),
            $CI->userlib->item('user_nickname'),
            $CI->userlib->item('user_level'),
        );

        $page_title = element('page_title', $config) ? element('page_title', $config) : $CI->configlib->item('site_meta_title_default');
        $meta_description = element('meta_description', $config) ? element('meta_description', $config) : $CI->configlib->item('site_meta_description_default');
        $meta_keywords = element('meta_keywords', $config) ? element('meta_keywords', $config) : $CI->configlib->item('site_meta_keywords_default');
        $meta_author = element('meta_author', $config) ? element('meta_author', $config) : $CI->configlib->item('site_meta_author_default');
        $page_name = element('page_name', $config) ? element('page_name', $config) : $CI->configlib->item('site_page_name_default');
        
        $data['page_title'] = $page_title = str_replace($searchconfig, $replaceconfig, $page_title);
        $data['meta_description'] = $meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
        $data['meta_keywords'] = $meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
        $data['meta_author'] = $meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
        $data['page_name'] = $page_name = str_replace($searchconfig, $replaceconfig, $page_name);

        $layoutdirname = $device_view_type === 'mobile' ? element('mobile_layout_dir', $config) : element('layout_dir', $config);
        if (empty($layoutdirname)) {
            $layoutdirname = $device_view_type === 'mobile' ? $CI->configlib->item('mobile_layout_default') : $CI->configlib->item('layout_default');
        }
        if (empty($layoutdirname)) {
            $layoutdirname = 'basic';
        }
        $layout = '_layout/' . $layoutdirname;
        $data['layout_skin_path'] = $layout;
        $data['layout_skin_url'] = base_url( VIEW_DIR . $data['layout_skin_path']);
        $layout .= '/';
        if (element('layout', $config)) {
            $layout .= element('layout', $config);
        }
        $data['layout_skin_file'] = $layout;

        $skindir = $device_view_type === 'mobile' ? element('mobile_skin_dir', $config) : element('skin_dir', $config);
        if (empty($skindir)) {
            $skindir = $device_view_type === 'mobile' ? $CI->configlib->item('mobile_skin_default') : $CI->configlib->item('skin_default');
        }
        if (empty($skindir)) {
            $skindir = 'basic';
        }
        $skin = '';
        if (element('path', $config)) {
            $skin .= element('path', $config) . '/';
        }
        $skin .= $skindir;
        $data['view_skin_path'] = $skin;
        $data['view_skin_url'] = base_url( VIEW_DIR . $data['view_skin_path']);
        $skin .= '/';
        if (element('skin', $config)) {
            $skin .= element('skin', $config);
        }
        $data['view_skin_file'] = $skin;

        $user_sidebar = $device_view_type === 'mobile' ? element('use_mobile_sidebar', $config) : element('use_sidebar', $config);
        if ($user_sidebar === '1') {
            $data['use_sidebar'] = '1';
        } elseif ($user_sidebar === '2') {
            $data['use_sidebar'] = '';
        } else {
            $user_sidebar = $device_view_type === 'mobile' ? $CI->configlib->item('mobile_sidebar_default') : $CI->configlib->item('sidebar_default');
            if ($user_sidebar === '1') {
                $data['use_sidebar'] = '1';
            } elseif ($user_sidebar === '2') {
                $data['use_sidebar'] = '';
            } else {
                $data['use_sidebar'] = '';
            }
        }

        $data['favicon'] = $CI->configlib->item('site_favicon') ? site_url(config_item('uploads_dir') . '/favicon/' . $CI->configlib->item('site_favicon')) : '';

        $user_id = (int) $CI->userlib->item('user_id');

        if ($CI->input->is_ajax_request() === false) {

            // 알림
            $data['notification_num'] = 0;
            if ($CI->configlib->item('use_notification')) {
                if ($CI->userlib->is_user()) {
                    $CI->load->model('Notification_model');
                    $data['notification_num'] = $CI->Notification_model->unread_notification_num($user_id);
                }
            }

        }

        return $data;
    }

    /**
     * css를 추가합니다
     */
    function add_css($file = '')
    {
        if (empty($file)) {
            return;
        }
        array_push($this->css, $file);
    }


    /**
     * js를 추가합니다
     */
    function add_js($file = '')
    {
        if (empty($file)) {
            return;
        }
        array_push($this->js, $file);
    }


    /**
     * 추가된 css를 리턴합니다
     */
    function display_css()
    {
        $return = '';
        $_css = $this->css;
        if ($_css) {
            foreach ($_css as $val) {
                $return .= '<link rel="stylesheet" type="text/css" href="' . $val . '" />';
            }
        }
        return $return;
    }


    /**
     * 추가된 js를 리턴합니다
     */
    function display_js()
    {
        $return = '';
        $_js = $this->js;
        if ($_js) {
            foreach ($_js as $val) {
                $return .= '<script type="text/javascript" src="' . $val . '"></script>';
            }
        }
        return $return;
    }
}
