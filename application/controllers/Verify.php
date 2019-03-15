<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Verify class
 */

/**
 * 이메일 인증 시 필요한 controller 입니다.
 */
class Verify extends MY_Controller
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
    public function confirmemail()
    {

        $view = array();
        $view['view'] = array();


        if ( ! $this->input->get('code')) {
            show_404();
        }
        if ( ! $this->input->get('user')) {
            show_404();
        }
        if ($this->userlib->is_user()) {
            redirect();
        }
        if ( ! $this->configlib->item('use_register_email_auth')) {
            alert('이 웹사이트는 이메일 인증 기능을 사용하고 있지 않습니다');
        }

        $where = array(
            'uae_key' => $this->input->get('code'),
        );
        $result = $this->User_auth_email_model->get_one('', '', $where);

        if ( ! element('uae_id', $result)) {
            $view['view']['message'] = '잘못된 접근입니다';
        } elseif ( ! (element('uae_type', $result) === '1' OR element('uae_type', $result) === '2')) {
            $view['view']['message'] = '잘못된 접근입니다';
        } elseif ( ! empty($result['uae_use_datetime']) && element('uae_use_datetime', $result) !== '0000-00-00 00:00:00') {
            $view['view']['message'] = '회원님은 이미 인증을 받으셨습니다';
        } elseif (strtotime(element('uae_generate_datetime', $result)) < ctimestamp()- 86400) {
            $view['view']['message'] = '24 시간 이내에 인증을 받으셔야 합니다';
        } elseif (element('uae_expired', $result)) {
            $view['view']['message'] = '잘못된 접근입니다';
        } else {

            $select = 'user_id, user_userid, user_denied, user_email_cert';
            $dbuser = $this->User_model->get_by_id(element('user_id', $result), $select);

            if ( ! element('user_id', $dbuser)) {
                $view['view']['message'] = '잘못된 접근입니다';
            } elseif (element('user_userid', $dbuser) !== $this->input->get('user')) {
                $view['view']['message'] = '잘못된 접근입니다';
            } elseif (element('user_denied', $dbuser)) {
                $view['view']['message'] = '접근이 금지된 아이디입니다';
            } elseif (element('user_email_cert', $dbuser)) {
                $view['view']['message'] = '회원님은 이미 인증을 받으셨습니다';
            } else {

                $updatedata = array(
                    'user_email_cert' => 1,
                );
                $this->User_model->update(element('user_id', $result), $updatedata);
                $metadata = array(
                    'meta_email_cert_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->load->model('User_meta_model');
                $this->User_meta_model->save(element('user_id', $result), $metadata);

                $updateemail = array(
                    'uae_use_datetime' => cdate('Y-m-d H:i:s'),
                    'uae_expired' => 1
                );
                $view['view']['message'] = '이메일 인증이 완료되었습니다.<br />감사합니다';
                $this->User_auth_email_model->update(element('uae_id', $result), $updateemail);

                $this->userlib->update_login_log(element('user_id', $dbuser), element('user_userid', $dbuser), 1, '이메일 인증 후 로그인 성공');
                $this->session->set_userdata('user_id', element('user_id', $dbuser));
            }
        }


        /**
         * 레이아웃을 정의합니다
         */
        $page_title = '이메일 인증';
        $layoutconfig = array(
            'path' => 'findaccount',
            'layout' => 'layout',
            'skin' => 'verifyemail',
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
        if ($this->userlib->is_user()) {
            redirect();
        }

        $this->load->library(array('form_validation'));

        $password_length = $this->configlib->item('password_length');
        $view['view']['password_length'] = $password_length;

        $where = array(
            'uae_key' => $this->input->get('code'),
        );
        $result = $this->User_auth_email_model->get_one('', '', $where);

        $view['view']['error_message'] = '';
        $view['view']['successs_message'] = '';
        if ( ! element('uae_id', $result)) {
            $view['view']['error_message'] = '잘못된 접근입니다';
        } elseif ( ! empty($result['uae_use_datetime']) && element('uae_use_datetime', $result) !== '0000-00-00 00:00:00') {
            $view['view']['error_message'] = '회원님은 이미 패스워드 변경을 하셨습니다';
        } elseif (strtotime(element('uae_generate_datetime', $result)) < ctimestamp()- 86400) {
            $view['view']['message'] = '24 시간 이내에 인증을 받으셔야 합니다';
        } elseif (element('uae_type', $result) !== '3') {
            $view['view']['error_message'] = '잘못된 접근입니다';
        } else {
            $select = 'user_id, user_userid, user_denied, user_email_cert';
            $dbuser = $this->User_model->get_by_id(element('user_id', $result), $select);
            if ( ! element('user_id', $dbuser)) {
                $view['view']['error_message'] = '잘못된 접근입니다';
            } elseif (element('user_userid', $dbuser) !== $this->input->get('user')) {
                $view['view']['error_message'] = '잘못된 접근입니다';
            } elseif (element('user_denied', $dbuser)) {
                $view['view']['error_message'] = '회원님의 계정은 접근이 금지되어 있습니다';
            } elseif ($this->configlib->item('use_register_email_auth') && ! element('user_email_cert', $dbuser)) {
                $view['view']['error_message'] = '회원님은 회원가입 후, 또는 이메일 정보 변경후 아직 이메일 인증을 받지 않으셨습니다';
            }
            $view['view']['user_userid'] = element('user_userid', $dbuser);

        }

        $config = array(
            array(
                'field' => 'new_password',
                'label' => '패스워드',
                'rules' => 'trim|required|min_length[' . $password_length . ']|callback__user_password_check',
            ),
            array(
                'field' => 'new_password_re',
                'label' => '패스워드',
                'rules' => 'trim|required|min_length[' . $password_length . ']',
            ),
        );

        $this->form_validation->set_rules($config);
        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {


        } else {


            if (empty($view['view']['error_message'])) {

                $hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT);
                $updatedata = array(
                    'user_password' => $hash,
                );
                $this->User_model->update(element('user_id', $result), $updatedata);
                $metadata = array(
                    'meta_change_pw_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->load->model('User_meta_model');
                $this->User_meta_model->save(element('user_id', $result), $metadata);

                $updateemail = array(
                    'uae_use_datetime' => cdate('Y-m-d H:i:s'),
                    'uae_expired' => 1
                );
                $this->User_auth_email_model->update(element('uae_id', $result), $updateemail);

                $view['view']['success_message'] = '회원님의 패스워드가 변경되었습니다.<br />감사합니다';

                $this->userlib->update_login_log(element('user_id', $result), element('user_userid', $result), 1, '패스워드 변경 후 로그인 성공');
                $this->session->set_userdata('user_id', element('user_id', $result));
            }
        }

        $password_description = '비밀번호는 ' . $password_length . '자리 이상이어야 ';
        if ($this->configlib->item('password_uppercase_length') OR $this->configlib->item('password_numbers_length') OR $this->configlib->item('password_specialchars_length')) {
            $password_description .= '하며 ';
            if ($this->configlib->item('password_uppercase_length')) {
                $password_description .= ', ' . $this->configlib->item('password_uppercase_length') . '개의 대문자';
            }
            if ($this->configlib->item('password_numbers_length')) {
                $password_description .= ', ' . $this->configlib->item('password_numbers_length') . '개의 숫자';
            }
            if ($this->configlib->item('password_specialchars_length')) {
                $password_description .= ', ' . $this->configlib->item('password_specialchars_length') . '개의 특수문자';
            }
            $password_description .= '를 포함해야 ';
        }
        $password_description .= '합니다';

        $view['view']['info'] = $password_description;


        /**
         * 레이아웃을 정의합니다
         */
        $page_title = '패스워드 변경';
        $layoutconfig = array(
            'path' => 'findaccount',
            'layout' => 'layout',
            'skin' => 'findaccount_change_pw',
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
     * 새로 입력한 패스워드가 규약에 맞는지 체크합니다.
     */
    public function _user_password_check($str)
    {
        $uppercase = $this->configlib->item('password_uppercase_length');
        $number = $this->configlib->item('password_numbers_length');
        $specialchar = $this->configlib->item('password_specialchars_length');

        $this->load->helper('chkstring');
        $str_uc = count_uppercase($str);
        $str_num = count_numbers($str);
        $str_spc = count_specialchars($str);

        if ($str_uc < $uppercase OR $str_num < $number OR $str_spc < $specialchar) {

            $description = '비밀번호는 ';
            if ($str_uc < $uppercase) {
                $description .= ' ' . $uppercase . '개 이상의 대문자';
            }
            if ($str_num < $number) {
                $description .= ' ' . $number . '개 이상의 숫자';
            }
            if ($str_spc < $specialchar) {
                $description .= ' ' . $specialchar . '개 이상의 특수문자';
            }
            $description .= '를 포함해야 합니다';

            $this->form_validation->set_message(
                '_user_password_check',
                $description
            );
            return false;
        }

        return true;
    }
}
