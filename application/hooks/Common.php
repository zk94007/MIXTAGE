<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Common hook class
 */

class _Common
{

    function init()
    {
        $CI =& get_instance();

        if ($CI->uri->segment(1) === 'install') {
            return;
        }

        if (config_item('use_lock_ip') && $CI->configlib->item('site_ip_whitelist')) {
            $whitelist = $CI->configlib->item('site_ip_whitelist');
            $whitelist = preg_replace("/[\r|\n|\r\n]+/", ',', $whitelist);
            $whitelist = preg_replace("/\s+/", '', $whitelist);
            if (preg_match('/(<\?|<\?php|\?>)/xsm', $whitelist)) {
                $whitelist = '';
            }
            if ($whitelist) {
                $whitelist = explode(',', trim($whitelist, ','));
                $whitelist = array_unique($whitelist);
                if (is_array($whitelist)) {
                    $CI->load->library('Ipfilter');
                    $ipfilter = new Ipfilter();
                    if ( ! $ipfilter->filter($whitelist)) {
                        $title = ($CI->configlib->item('site_blacklist_title'))
                            ? $CI->configlib->item('site_blacklist_title')
                            : 'Maintenance in progress...';
                        $message = $CI->configlib->item('site_blacklist_content');

                        show_error($message, '500', $title);

                        exit;
                    }
                }
            }
        }
        if (config_item('use_lock_ip') && $CI->configlib->item('site_ip_blacklist')) {
            $blacklist = $CI->configlib->item('site_ip_blacklist');
            $blacklist = preg_replace("/[\r|\n|\r\n]+/", ',', $blacklist);
            $blacklist = preg_replace("/\s+/", '', $blacklist);
            if (preg_match('/(<\?|<\?php|\?>)/xsm', $blacklist)) {
                $blacklist = '';
            }
            if ($blacklist) {
                $blacklist = explode(',', trim($blacklist, ','));
                $blacklist = array_unique($blacklist);
                if (is_array($blacklist)) {
                    $CI->load->library('Ipfilter');
                    $ipfilter = new Ipfilter();
                    if ($ipfilter->filter($blacklist)) {
                        $title = ($CI->configlib->item('site_blacklist_title'))
                            ? $CI->configlib->item('site_blacklist_title')
                            : 'Maintenance in progress...';
                        $message = $CI->configlib->item('site_blacklist_content');
                        show_error($message, '500', $title);
                        exit;
                    }
                }
            }
        }

        $CI->load->library('Mobile_detect');
        $detect = new Mobile_detect();

        $device_view_type = (get_cookie('device_view_type') === 'desktop' OR get_cookie('device_view_type') === 'mobile')
                ? get_cookie('device_view_type') : '';
        if (empty($device_view_type)) {
            $device_view_type = $detect->isMobile() ? 'mobile' : 'desktop';
        }
        $CI->configlib->set_device_view_type($device_view_type);

        $device_type = $detect->isMobile() ? 'mobile' : 'desktop';
        $CI->configlib->set_device_type($device_type);

        if ($CI->userlib->is_user()) {
            if ($CI->userlib->item('user_id') === false) {
                unset($CI->user);
                $CI->session->sess_destroy();
                redirect(current_full_url(), 'refresh');
            }
            $user_id = (int) $CI->userlib->item('user_id');
            if ($CI->userlib->item('user_denied')) {
                unset($CI->user);
                $CI->session->sess_destroy();
                redirect(current_full_url(), 'refresh');
            } else {
                if (substr($CI->userlib->item('user_lastlogin_datetime'), 0, 10) !== cdate('Y-m-d')) {
                    $updatedata = array(
                        'user_lastlogin_datetime' => cdate('Y-m-d H:i:s'),
                        'user_lastlogin_ip' => $CI->input->ip_address(),
                    );
                    $CI->User_model->update($user_id, $updatedata);
                }
            }
        }

        // 관리자 페이지
        if ($CI->userlib->is_admin() !== 'super'
            && $CI->uri->segment(1) === config_item('uri_segment_admin')) {
            redirect('login?url=' . urlencode(current_full_url()));
        }

        if (config_item('use_lock_ip')
            && $CI->uri->segment(1) === config_item('uri_segment_admin')
            && $CI->configlib->item('admin_ip_whitelist')) {

            $whitelist = $CI->configlib->item('admin_ip_whitelist');
            $whitelist = preg_replace("/[\r|\n|\r\n]+/", ',', $whitelist);
            $whitelist = preg_replace("/\s+/", '', $whitelist);
            if (preg_match('/(<\?|<\?php|\?>)/xsm', $whitelist)) {
                $whitelist = '';
            }
            if ($whitelist) {
                $whitelist = explode(',', trim($whitelist, ','));
                $whitelist = array_unique($whitelist);
                if (is_array($whitelist)) {
                    $CI->load->library('Ipfilter');
                    if ( ! Ipfilter::filter($whitelist)) {
                        $title = '관리자 페이지';
                        $message = '현재 접속하신 아이피는 관리자 페이지 접근이 차단되었습니다';
                        show_error($message, '500', $title);
                        exit;
                    }
                }
            }
        }
    }
}
