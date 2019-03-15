<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mixtageverify class
 */

/**
 * 이메일 인증 시 필요한 controller 입니다.
 */
class Mixtageverify extends MY_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('User_auth_email');

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring'));
        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }
    }


    /**
     * 이메일 인증 함수입니다.
     */
    public function loginemail()
    {

        $view = array();
        $view['view'] = array();


        if ( ! $this->input->get('code')) {
            show_404();
        }
        if ( ! $this->input->get('user')) {
            show_404();
        }

        $where = array(
            'uae_key' => $this->input->get('code'),
        );
        $result = $this->User_auth_email_model->get_one('', '', $where);

        if ( ! element('uae_id', $result)) {
            $view['view']['message'] = '잘못된 접근입니다1';
        } elseif (element('uae_type', $result) != '5') {
            $view['view']['message'] = '잘못된 접근입니다2';
        //} elseif ( ! empty($result['uae_use_datetime']) && element('uae_use_datetime', $result) !== '0000-00-00 00:00:00') {
        //    $view['view']['message'] = '회원님은 이미 이 링크를 이용해 로그인을 하셨습니다.';
        } elseif (strtotime(element('uae_generate_datetime', $result)) < ctimestamp()- 86400) {
            $view['view']['message'] = '24 시간 이내에 로그인을 하셔야 합니다';
        //} elseif (element('uae_expired', $result)) {
        //    $view['view']['message'] = '잘못된 접근입니다3';
        } else {

            $select = 'user_id, user_userid, user_denied, user_email_cert';
            $dbuser = $this->User_model->get_by_id(element('user_id', $result), $select);

            if ( ! element('user_id', $dbuser)) {
                $view['view']['message'] = '잘못된 접근입니다4';
            } elseif (element('user_userid', $dbuser) !== $this->input->get('user')) {
                $view['view']['message'] = '잘못된 접근입니다5';
            } elseif (element('user_denied', $dbuser)) {
                $view['view']['message'] = '접근이 금지된 아이디입니다';
            } else {


                $updateemail = array(
                    'uae_use_datetime' => cdate('Y-m-d H:i:s'),
                    'uae_expired' => 1
                );
                $view['view']['message'] = '인증을 위해 앱으로 이동합니다.';
                $this->User_auth_email_model->update(element('uae_id', $result), $updateemail);

				$uri = 'mixtage://verify/' . element('user_userid', $dbuser) . '/' . $this->input->get('code');
				echo '<script>document.location.href="' . $uri . '"</script>';

            }
        }


        /**
         * 레이아웃을 정의합니다
         */
        $page_title = '이메일 인증';
        $layoutconfig = array(
            'path' => 'findaccount',
            'layout' => 'layout',
            'skin' => 'loginemail',
            'layout_dir' => $this->configlib->item('layout_findaccount'),
            'mobile_layout_dir' => $this->configlib->item('mobile_layout_findaccount'),
            'use_sidebar' => $this->configlib->item('sidebar_findaccount'),
            'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_findaccount'),
            'skin_dir' => $this->configlib->item('skin_findaccount'),
            'mobile_skin_dir' => $this->configlib->item('mobile_skin_findaccount'),
            'page_title' => $page_title,
        );
        $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }


    /**
     * 패스워드 리셋위한 함수입니다.
     */
    public function resetpassword()
    {

        $view = array();
        $view['view'] = array();


        if ( ! $this->input->get('code')) {
            show_404();
        }
        if ( ! $this->input->get('user')) {
            show_404();
        }


        $where = array(
            'uae_key' => $this->input->get('code'),
        );
        $result = $this->User_auth_email_model->get_one('', '', $where);

        if ( ! element('uae_id', $result)) {
            $view['view']['message'] = '잘못된 접근입니다1';
        } elseif (element('uae_type', $result) != '6') {
            $view['view']['message'] = '잘못된 접근입니다2';
        } elseif ( ! empty($result['uae_use_datetime']) && element('uae_use_datetime', $result) !== '0000-00-00 00:00:00') {
            $view['view']['message'] = '회원님은 이미 이 링크를 이용해 패스워드를 변경하셨습니다.';
        } elseif (strtotime(element('uae_generate_datetime', $result)) < ctimestamp()- 86400) {
            $view['view']['message'] = '24 시간 이내에 패스워드를 변경하셔야 합니다';
        } elseif (element('uae_expired', $result)) {
            $view['view']['message'] = '잘못된 접근입니다3';
        } else {

            $select = 'user_id, user_userid, user_denied, user_email_cert';
            $dbuser = $this->User_model->get_by_id(element('user_id', $result), $select);

            if ( ! element('user_id', $dbuser)) {
                $view['view']['message'] = '잘못된 접근입니다4';
            } elseif (element('user_userid', $dbuser) !== $this->input->get('user')) {
                $view['view']['message'] = '잘못된 접근입니다5';
            } elseif (element('user_denied', $dbuser)) {
                $view['view']['message'] = '접근이 금지된 아이디입니다';
            } else {

                $view['view']['message'] = '패스워드 변경을 위해 앱으로 이동합니다.';
				$uri = 'mixtage://resetpassword/' . element('user_userid', $dbuser) . '/' . $this->input->get('code');
				echo '<script>document.location.href="' . $uri . '"</script>';
            }

        }

        /**
         * 레이아웃을 정의합니다
         */
        $page_title = '패스워드 변경';
        $layoutconfig = array(
            'path' => 'findaccount',
            'layout' => 'layout',
            'skin' => 'resetpassword',
            'layout_dir' => $this->configlib->item('layout_findaccount'),
            'mobile_layout_dir' => $this->configlib->item('mobile_layout_findaccount'),
            'use_sidebar' => $this->configlib->item('sidebar_findaccount'),
            'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_findaccount'),
            'skin_dir' => $this->configlib->item('skin_findaccount'),
            'mobile_skin_dir' => $this->configlib->item('mobile_skin_findaccount'),
            'page_title' => $page_title,
        );
        $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }
}
