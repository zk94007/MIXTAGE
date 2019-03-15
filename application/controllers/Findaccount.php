<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Findaccount class
 */

/**
 * 회원정보 찾기에 관련도니 controller 입니다.
 */
class Findaccount extends MY_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array( 'User_auth_email');

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'string');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('querystring', 'email'));
    }


    /**
     * 아이디/패스워드찾기 페이지입니다
     */
    public function index()
    {
        if ($this->userlib->is_user() !== false
            && ! (
                $this->userlib->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin')
            )) {
            redirect();
        }

        $view = array();
        $view['view'] = array();


        $this->load->library(array('form_validation'));

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array();
        if ($this->input->post('findtype') === 'findidpw') {
            $config[] = array(
                'field' => 'idpw_email',
                'label' => '이메일',
                'rules' => 'trim|required|valid_email|callback__existemail',
            );
        } elseif ($this->input->post('findtype') === 'verifyemail') {
            $config[] = array(
                'field' => 'verify_email',
                'label' => '이메일',
                'rules' => 'trim|required|valid_email|callback__verifyemail',
            );
        } elseif ($this->input->post('findtype') === 'changeemail') {
            $config[] = array(
                'field' => 'change_userid',
                'label' => '아이디',
                'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]',
            );
            $config[] = array(
                'field' => 'change_password',
                'label' => '패스워드',
                'rules' => 'trim|required|callback__check_id_pw[' . $this->input->post('change_userid') . ']',
            );
            $config[] = array(
                'field' => 'change_email',
                'label' => '이메일',
                'rules' => 'trim|required|valid_email|callback__change_email',
            );
        }

        $this->form_validation->set_rules($config);
        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {


        } else {


            if ($this->input->post('findtype') === 'findidpw') {

                $mb = $this->User_model->get_by_email($this->input->post('idpw_email'));

                $user_id = (int) element('user_id', $mb);
                $uae_type = 3;

                $vericode = array('$', '/', '.');
                $verificationcode = str_replace(
                    $vericode,
                    '',
                    password_hash($user_id . '-' . $this->input->post('idpw_email') . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
                );

                $beforeauthdata = array(
                    'user_id' => $user_id,
                    'uae_type' => $uae_type,
                );
                $this->User_auth_email_model->delete_where($beforeauthdata);
                $authdata = array(
                    'user_id' => $user_id,
                    'uae_key' => $verificationcode,
                    'uae_type' => $uae_type,
                    'uae_generate_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->User_auth_email_model->insert($authdata);

                $verify_url = site_url('verify/resetpassword?user=' . element('user_userid', $mb) . '&code=' . $verificationcode);

                $searchconfig = array(
                    '{홈페이지명}',
                    '{회사명}',
                    '{홈페이지주소}',
                    '{회원아이디}',
                    '{회원닉네임}',
                    '{회원실명}',
                    '{회원이메일}',
                    '{메일수신여부}',
                    '{쪽지수신여부}',
                    '{문자수신여부}',
                    '{회원아이피}',
                    '{패스워드변경주소}',
                );
                $receive_email = element('user_receive_email', $mb) ? '동의' : '거부';
                $receive_note = element('user_use_note', $mb) ? '동의' : '거부';
                $receive_sms = element('user_receive_sms', $mb) ? '동의' : '거부';
                $replaceconfig = array(
                    $this->configlib->item('site_title'),
                    $this->configlib->item('company_name'),
                    site_url(),
                    element('user_userid', $mb),
                    element('user_nickname', $mb),
                    element('user_username', $mb),
                    element('user_email', $mb),
                    $receive_email,
                    $receive_note,
                    $receive_sms,
                    $this->input->ip_address(),
                    $verify_url,
                );
                $replaceconfig_escape = array(
                    html_escape($this->configlib->item('site_title')),
                    html_escape($this->configlib->item('company_name')),
                    site_url(),
                    element('user_userid', $mb),
                    html_escape(element('user_nickname', $mb)),
                    html_escape(element('user_username', $mb)),
                    html_escape(element('user_email', $mb)),
                    $receive_email,
                    $receive_note,
                    $receive_sms,
                    $this->input->ip_address(),
                    $verify_url,
                );

                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->configlib->item('send_email_findaccount_user_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->configlib->item('send_email_findaccount_user_content')
                );

                $this->email->clear(true);
                $this->email->from($this->configlib->item('webmaster_email'), $this->configlib->item('webmaster_name'));
                $this->email->to($this->input->post('idpw_email'));
                $this->email->subject($title);
                $this->email->message($content);
                $this->email->send();

                $view['view']['message'] = $this->input->post('idpw_email') . '로 인증메일이 발송되었습니다. <br />발송된 인증메일을 확인하신 후에 회원님의 정보 확인이 가능합니다';

            } elseif ($this->input->post('findtype') === 'verifyemail') {

                $mb = $this->User_model->get_by_email($this->input->post('verify_email'));
                $user_id = (int) element('user_id', $mb);
                $uae_type = 2;

                $vericode = array('$', '/', '.');
                $verificationcode = str_replace(
                    $vericode,
                    '',
                    password_hash($user_id . '-' . $this->input->post('verify_email') . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
                );

                $beforeauthdata = array(
                    'user_id' => $user_id,
                    'uae_type' => $uae_type,
                );
                $this->User_auth_email_model->delete_where($beforeauthdata);
                $authdata = array(
                    'user_id' => $user_id,
                    'uae_key' => $verificationcode,
                    'uae_type' => $uae_type,
                    'uae_generate_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->User_auth_email_model->insert($authdata);

                $verify_url = site_url('verify/confirmemail?user=' . element('user_userid', $mb) . '&code=' . $verificationcode);

                $searchconfig = array(
                    '{홈페이지명}',
                    '{회사명}',
                    '{홈페이지주소}',
                    '{회원아이디}',
                    '{회원닉네임}',
                    '{회원실명}',
                    '{회원이메일}',
                    '{메일수신여부}',
                    '{쪽지수신여부}',
                    '{문자수신여부}',
                    '{회원아이피}',
                    '{메일인증주소}',
                );
                $receive_email = element('user_receive_email', $mb) ? '동의' : '거부';
                $receive_note = element('user_use_note', $mb) ? '동의' : '거부';
                $receive_sms = element('user_receive_sms', $mb) ? '동의' : '거부';
                $replaceconfig = array(
                    $this->configlib->item('site_title'),
                    $this->configlib->item('company_name'),
                    site_url(),
                    element('user_userid', $mb),
                    element('user_nickname', $mb),
                    element('user_username', $mb),
                    element('user_email', $mb),
                    $receive_email,
                    $receive_note,
                    $receive_sms,
                    $this->input->ip_address(),
                    $verify_url,
                );
                $replaceconfig_escape = array(
                    html_escape($this->configlib->item('site_title')),
                    html_escape($this->configlib->item('company_name')),
                    site_url(),
                    element('user_userid', $mb),
                    html_escape(element('user_nickname', $mb)),
                    html_escape(element('user_username', $mb)),
                    html_escape(element('user_email', $mb)),
                    $receive_email,
                    $receive_note,
                    $receive_sms,
                    $this->input->ip_address(),
                    $verify_url,
                );

                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->configlib->item('send_email_resendverify_user_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->configlib->item('send_email_resendverify_user_content')
                );

                $this->email->clear(true);
                $this->email->from($this->configlib->item('webmaster_email'), $this->configlib->item('webmaster_name'));
                $this->email->to($this->input->post('verify_email'));
                $this->email->subject($title);
                $this->email->message($content);
                $this->email->send();

                $view['view']['message'] = $this->input->post('verify_email') . '로 인증메일이 발송되었습니다. <br />발송된 인증메일을 확인하신 후에 사이트 이용이 가능합니다';

            } elseif ($this->input->post('findtype') === 'changeemail') {

                $mb = $this->User_model->get_by_userid($this->input->post('change_userid'));

                $user_id = (int) element('user_id', $mb);
                $uae_type = 2;

                $vericode = array('$', '/', '.');
                $verificationcode = str_replace(
                    $vericode,
                    '',
                    password_hash($user_id . '-' . $this->input->post('change_email') . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
                );

                $beforeauthdata = array(
                    'user_id' => $user_id,
                    'uae_type' => $uae_type,
                );
                $this->User_auth_email_model->delete_where($beforeauthdata);
                $authdata = array(
                    'user_id' => $user_id,
                    'uae_key' => $verificationcode,
                    'uae_type' => $uae_type,
                    'uae_generate_datetime' => cdate('Y-m-d H:i:s'),
                );
                $this->User_auth_email_model->insert($authdata);

                $searchconfig = array(
                    '{홈페이지명}',
                    '{회사명}',
                    '{홈페이지주소}',
                    '{회원아이디}',
                    '{회원닉네임}',
                    '{회원실명}',
                    '{회원이메일}',
                    '{메일수신여부}',
                    '{쪽지수신여부}',
                    '{문자수신여부}',
                    '{회원아이피}',
                    '{패스워드변경주소}',
                );
                $receive_email = element('user_receive_email', $mb) ? '동의' : '거부';
                $receive_note = element('user_use_note', $mb) ? '동의' : '거부';
                $receive_sms = element('user_receive_sms', $mb) ? '동의' : '거부';
                $replaceconfig = array(
                    $this->configlib->item('site_title'),
                    $this->configlib->item('company_name'),
                    site_url(),
                    element('user_userid', $mb),
                    element('user_nickname', $mb),
                    element('user_username', $mb),
                    element('user_email', $mb),
                    $receive_email,
                    $receive_note,
                    $receive_sms,
                    $this->input->ip_address(),
                    $verify_url,
                );
                $replaceconfig_escape = array(
                    html_escape($this->configlib->item('site_title')),
                    html_escape($this->configlib->item('company_name')),
                    site_url(),
                    element('user_userid', $mb),
                    html_escape(element('user_nickname', $mb)),
                    html_escape(element('user_username', $mb)),
                    html_escape(element('user_email', $mb)),
                    $receive_email,
                    $receive_note,
                    $receive_sms,
                    $this->input->ip_address(),
                    $verify_url,
                );

                $title = str_replace(
                    $searchconfig,
                    $replaceconfig,
                    $this->configlib->item('send_email_findaccount_user_title')
                );
                $content = str_replace(
                    $searchconfig,
                    $replaceconfig_escape,
                    $this->configlib->item('send_email_findaccount_user_content')
                );

                $this->email->clear(true);
                $this->email->from($this->configlib->item('webmaster_email'), $this->configlib->item('webmaster_name'));
                $this->email->to($this->input->post('change_email'));
                $this->email->subject($title);
                $this->email->message($content);
                $this->email->send();

                $view['view']['message'] = $this->input->post('change_email') . '로 인증메일이 발송되었습니다. <br />발송된 인증메일을 확인하신 후에 사이트 이용이 가능합니다';

            }
        }

        $view['view']['canonical'] = site_url('findaccount');


        /**
         * 레이아웃을 정의합니다
         */
        $page_title = $this->configlib->item('site_meta_title_findaccount');
        $meta_description = $this->configlib->item('site_meta_description_findaccount');
        $meta_keywords = $this->configlib->item('site_meta_keywords_findaccount');
        $meta_author = $this->configlib->item('site_meta_author_findaccount');
        $page_name = $this->configlib->item('site_page_name_findaccount');

        $layoutconfig = array(
            'path' => 'findaccount',
            'layout' => 'layout',
            'skin' => 'findaccount',
            'layout_dir' => $this->configlib->item('layout_findaccount'),
            'mobile_layout_dir' => $this->configlib->item('mobile_layout_findaccount'),
            'use_sidebar' => $this->configlib->item('sidebar_findaccount'),
            'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_findaccount'),
            'skin_dir' => $this->configlib->item('skin_findaccount'),
            'mobile_skin_dir' => $this->configlib->item('mobile_skin_findaccount'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        $view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }


    /**
     * 존재하는 이메일인지 체크합니다
     */
    public function _existemail($str)
    {
        $userinfo = $this->User_model
            ->get_by_email($str, 'user_id, user_email, user_denied, user_email_cert');
        if ( ! element('user_id', $userinfo)) {
            $this->form_validation->set_message(
                '_existemail',
                '존재하지 않는 이메일주소입니다'
            );
            return false;
        }
        if (element('user_denied', $userinfo)) {
            $this->form_validation->set_message(
                '_existemail',
                '회원님의 계정은 접근이 금지되어 있습니다'
            );
            return false;
        } elseif ($this->configlib->item('use_register_email_auth') && ! element('user_email_cert', $userinfo)) {
            $this->form_validation->set_message(
                '_existemail',
                '회원님은 아직 이메일 인증을 받지 않으셨습니다<br> 아래 인증메일 재발송 란에서 이메일을 받아 인증해주시기 바랍니다'
            );
            return false;
        }

        return true;
    }


    /**
     * 이메일이 실제 디비에 존재하는지 체크합니다
     */
    public function _verifyemail($str)
    {
        if ( ! $this->configlib->item('use_register_email_auth')) {
            $this->form_validation->set_message(
                '_verifyemail',
                '이 웹사이트는 이메일 인증 기능을 사용하고 있지 않습니다'
            );
            return false;
        }

        $userinfo = $this->User_model
            ->get_by_email($str, 'user_id, user_email, user_denied, user_email_cert');
        if ( ! element('user_id', $userinfo)) {
            $this->form_validation->set_message(
                '_verifyemail',
                '존재하지 않는 이메일주소입니다'
            );
            return false;
        }
        if (element('user_denied', $userinfo)) {
            $this->form_validation->set_message(
                '_verifyemail',
                '회원님의 계정은 접근이 금지되어 있습니다'
            );
            return false;
        }
        if (element('user_email_cert', $userinfo)) {
            $this->form_validation->set_message(
                '_verifyemail',
                '회원님의 계정은 이미 인증이 완료되어 있습니다'
            );
            return false;
        }

        return true;
    }


    /**
     * 이메일 변경시 기존에 다른 회원에 의해 사용되고 있는 이메일인지 체크합니다
     */
    public function _change_email($str)
    {
        if ( ! $this->configlib->item('use_register_email_auth')) {
            $this->form_validation->set_message(
                '_change_email',
                '이 웹사이트는 이메일 인증 기능을 사용하고 있지 않습니다'
            );
            return false;
        }

        $userinfo = $this->User_model
            ->get_by_email($str, 'user_id, user_email, user_denied, user_email_cert');
        if (element('user_id', $userinfo)) {
            $this->form_validation->set_message(
                '_change_email',
                '이 이메일은 이미 다른 회원에 의해 사용되어지고 있는 이메일입니다'
            );
            return false;
        }
        if (element('user_denied', $userinfo)) {
            $this->form_validation->set_message(
                '_change_email',
                '회원님의 계정은 접근이 금지되어 있습니다'
            );
            return false;
        }
        if (element('user_email_cert', $userinfo)) {
            $this->form_validation->set_message(
                '_change_email',
                '회원님의 계정은 이미 인증이 완료되어 있습니다'
            );
            return false;
        }
    }


    /**
     * 아이디와 패스워드가 일치하는지 체크합니다
     */
    public function _check_id_pw($password, $userid)
    {
        if ( ! $this->configlib->item('use_register_email_auth')) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '이 웹사이트는 이메일 인증 기능을 사용하고 있지 않습니다'
            );
            return false;
        }

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        $max_login_try_count = (int) $this->configlib->item('max_login_try_count');
        $max_login_try_limit_second = (int) $this->configlib->item('max_login_try_limit_second');

        $loginfailnum = 0;
        $loginfailmessage = '';
        if ($max_login_try_count && $max_login_try_limit_second) {
            $select = 'ull_id, ull_success, user_id, ull_ip, ull_datetime';
            $where = array(
                'ull_ip' => $this->input->ip_address(),
                'ull_datetime > ' => strtotime(ctimestamp() - 86400 * 30),
            );
            $findex = 'ull_id';
            $forder = 'DESC';
            $this->load->model('User_login_log_model');
            $logindata = $this->User_login_log_model
                ->get('', $select, $where, '', '', $findex, $forder);

            if ($logindata && is_array($logindata)) {
                foreach ($logindata as $key => $val) {
                    if (element('ull_success', $val) === '0') {
                        $loginfailnum++;
                    }
                    if (element('ull_success', $val) === '1') {
                        break;
                    }
                }
            }
            if ($loginfailnum > 0 && $loginfailnum % $max_login_try_count === 0) {
                $lastlogintrydatetime = $logindata[0]['ull_datetime'];
                $next_login = strtotime($lastlogintrydatetime)
                    + $max_login_try_limit_second
                    - ctimestamp();
                if ($next_login > 0) {
                    $this->form_validation->set_message(
                        '_check_id_pw',
                        '회원님은 패스워드를 연속으로 ' . $loginfailnum . '회 잘못 입력하셨기 때문에 '
                        . $next_login . '초 후에 다시 시도가 가능합니다'
                    );
                    return false;
                }
            }
            $loginfailmessage = '<br />회원님은 ' . ($loginfailnum + 1)
                . '회 연속으로 패스워드를 잘못입력하셨습니다. ';
        }

        $userselect = 'user_id, user_password, user_denied';
        $userinfo = $this->User_model->get_by_userid($userid, $userselect);

        $hash = password_hash($password, PASSWORD_BCRYPT);

        if ( ! element('user_id', $userinfo) OR ! element('user_password', $userinfo)) {

            $this->form_validation->set_message(
                '_check_id_pw',
                '회원 아이디와 패스워드가 서로 맞지 않습니다' . $loginfailmessage
            );
            $this->userlib->update_login_log(0, $userid, 0, '회원아이디가 존재하지 않습니다');

            return false;

        } elseif ( ! password_verify($password, element('user_password', $userinfo))) {
            $this->form_validation->set_message(
                '_check_id_pw',
                '회원 아이디와 패스워드가 서로 맞지 않습니다' . $loginfailmessage
            );
            $this->userlib->update_login_log(element('user_id', $userinfo), $userid, 0, '패스워드가 올바르지 않습니다');

            return false;

        } elseif (element('user_denied', $userinfo)) {
            if (element('user_denied', $userinfo)) {
                $this->form_validation->set_message(
                    '_check_id_pw',
                    '회원님의 계정은 접근이 금지되어 있습니다'
                );
                $this->userlib->update_login_log(element('user_id', $userinfo), $userid, 0, '차단된 회원아이디입니다');

                return false;
            }
        }

        return true;
    }
}
