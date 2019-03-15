<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Url libraries helper
 */


/**
 * query string 을 포함한 현재페이지 주소 전체를 return 합니다
 */
if ( ! function_exists('current_full_url')) {
    function current_full_url()
    {
        $CI =& get_instance();

        $url = $CI->config->site_url($CI->uri->uri_string());
        $return = ($CI->input->server('QUERY_STRING'))
            ? $url . '?' . $CI->input->server('QUERY_STRING') : $url;
        return $return;
    }
}


/**
 * 페이지 이동시 이 함수를 이용하면, gotourl 페이지를 거쳐가므로 referer 를 숨길 수 있습니다
 */
if ( ! function_exists('goto_url')) {
    function goto_url($url = '')
    {
        if (empty($url)) {
            return false;
        }
        $result = site_url('gotourl/?url=' . urlencode($url));
        return $result;
    }
}


/**
 * Admin 페이지 주소를 return 합니다
 */
if ( ! function_exists('admin_url')) {
    function admin_url($url = '')
    {
        $url = trim($url, '/');
        return site_url(config_item('uri_segment_admin') . '/' . $url);
    }
}

/**
 * FAQ 페이지 주소를 return 합니다
 */
if ( ! function_exists('faq_url')) {
    function faq_url($key = '')
    {
        $key = trim($key, '/');
        return site_url(config_item('uri_segment_faq') . '/' . $key);
    }
}


/**
 * 일반문서 페이지 주소를 return 합니다
 */
if ( ! function_exists('document_url')) {
    function document_url($key = '')
    {
        $key = trim($key, '/');
        return site_url(config_item('uri_segment_document') . '/' . $key);
    }
}
