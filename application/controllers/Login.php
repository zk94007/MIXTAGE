<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Login class
 */

/**
 * 로그인 페이지와 관련된 controller 입니다.
 */
class Login extends MY_Controller
{

    /**
     * 모델을 로딩합니다
     */
    protected $models = array();

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'string');

    function __construct()
    {
        parent::__construct();

    }


    /**
     * 로그인 페이지입니다
     */
    public function index()
    {

        if ($this->userlib->is_user() !== false && ! ($this->userlib->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
            redirect();
        }

        $view = array();
        $view['view'] = array();


        $this->load->library(array('form_validation'));

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

        $use_login_account = $this->configlib->item('use_login_account');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        if ($use_login_account === 'both') {
            $config[] = array(
                'field' => 'user_userid',
                'label' => 'User ID or Email',
                'rules' => 'trim|required',
            );
            $view['view']['userid_label_text'] = 'User ID or Email';
        } elseif ($use_login_account === 'email') {
            $config[] = array(
                'field' => 'user_userid',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email',
            );
            $view['view']['userid_label_text'] = 'Email';
        } else {
            $config[] = array(
                'field' => 'user_userid',
                'label' => 'User ID',
                'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]',
            );
            $view['view']['userid_label_text'] = 'User ID';
        }
        $config[] = array(
            'field' => 'user_password',
            'label' => 'Password',
            'rules' => 'trim|required|min_length[4]|callback__check_id_pw[' . $this->input->post('user_userid') . ']',
        );

        $this->form_validation->set_rules($config);
        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {


            if ($this->input->post('returnurl')) {
                if (validation_errors('<div class="alert alert-warning" role="alert">', '</div>')) {
                    $this->session->set_flashdata(
                        'loginvalidationmessage',
                        validation_errors('<div class="alert alert-warning" role="alert">', '</div>')
                    );
                }
                $this->session->set_flashdata(
                    'loginuserid',
                    $this->input->post('user_userid')
                );
                redirect(urldecode($this->input->post('returnurl')));
            }

            $view['view']['canonical'] = site_url('login');

        
            /**
             * 레이아웃을 정의합니다
             */
            $page_title = $this->configlib->item('site_meta_title_login');
            $meta_description = $this->configlib->item('site_meta_description_login');
            $meta_keywords = $this->configlib->item('site_meta_keywords_login');
            $meta_author = $this->configlib->item('site_meta_author_login');
            $page_name = $this->configlib->item('site_page_name_login');

            $layoutconfig = array(
                'path' => 'login',
                'layout' => 'layout',
                'skin' => 'login',
                'layout_dir' => $this->configlib->item('layout_login'),
                'mobile_layout_dir' => $this->configlib->item('mobile_layout_login'),
                'use_sidebar' => $this->configlib->item('sidebar_login'),
                'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_login'),
                'skin_dir' => $this->configlib->item('skin_login'),
                'mobile_skin_dir' => $this->configlib->item('mobile_skin_login'),
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
        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */


            if ($use_login_account === 'both') {
                $userinfo = $this->User_model->get_by_both($this->input->post('user_userid'), 'user_id, user_userid');
            } elseif ($use_login_account === 'email') {
                $userinfo = $this->User_model->get_by_email($this->input->post('user_userid'), 'user_id, user_userid');
            } else {
                $userinfo = $this->User_model->get_by_userid($this->input->post('user_userid'), 'user_id, user_userid');
            }
            $this->userlib->update_login_log(element('user_id', $userinfo), $this->input->post('user_userid'), 1, '로그인 성공');
            $this->session->set_userdata(
                'user_id',
                element('user_id', $userinfo)
            );

            $change_password_date = $this->configlib->item('change_password_date');
            $site_title = $this->configlib->item('site_title');
            if ($change_password_date) {
                
                $meta_change_pw_datetime = $this->userlib->item('meta_change_pw_datetime');
                if ( ctimestamp() - strtotime($meta_change_pw_datetime) > $change_password_date * 86400) {
                    $this->session->set_userdata(
                        'usermodify',
                        '1'
                    );
                    $this->session->set_flashdata(
                        'message',
                         'It is recommended that you change your password periodically in' . html_escape($site_title) .
                        '<br /> It is recommended that you change your password for safe service when you use old password'
                    );
                    redirect('usermodify/password_modify');
                }
            }

            $url_after_login = $this->configlib->item('url_after_login');
            if ($url_after_login) {
                $url_after_login = site_url($url_after_login);
            }
            if (empty($url_after_login)) {
                $url_after_login = $this->input->get_post('url') ? urldecode($this->input->get_post('url')) : site_url();
            }

            redirect($url_after_login);
        }
    }


    /**
     * 로그인시 아이디와 패스워드가 일치하는지 체크합니다
     */
    public function _check_id_pw($password, $userid)
    {
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
            $this->load->model('User_login_log_model');
            $logindata = $this->User_login_log_model
                ->get('', $select, $where, '', '', 'ull_id', 'DESC');

            if ($logindata && is_array($logindata)) {
                foreach ($logindata as $key => $val) {
                    if ((int) $val['ull_success'] === 0) {
                        $loginfailnum++;
                    } elseif ((int) $val['ull_success'] === 1) {
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
                        'You input the wrong password continuously ' . $loginfailnum .' times, 
                        so you can try to log in again after ' . $next_login . 'seconds.'
                    );
                    return false;
                }
            }
            $loginfailmessage = '<br />You input the wrong password continuously ' .
                ($loginfailnum + 1) . ' times.';
        }

        $use_login_account = $this->configlib->item('use_login_account');

        $userselect = 'user_id, user_password, user_denied, user_email_cert, user_is_admin';
        if ($use_login_account === 'both') {
            $userinfo = $this->User_model->get_by_both($userid, $userselect);
        } elseif ($use_login_account === 'email') {
            $userinfo = $this->User_model->get_by_email($userid, $userselect);
        } else {
            $userinfo = $this->User_model->get_by_userid($userid, $userselect);
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);

        if ( ! element('user_id', $userinfo) OR ! element('user_password', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                'There is no User ID.' . $loginfailmessage
            );
            $this->userlib->update_login_log(0, $userid, 0, 'There is no User ID.');
            return false;
        } elseif ( ! password_verify($password, element('user_password', $userinfo))) {
            $this->form_validation->set_message(
                '_check_id_pw',
                'Wrong password!' . $loginfailmessage
            );
            $this->userlib->update_login_log(element('user_id', $userinfo), $userid, 0, 'Wrong password!');
            return false;
        } elseif (element('user_denied', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                'Your ID is denied.'
            );
            $this->userlib->update_login_log(element('user_id', $userinfo), $userid, 0, 'Your ID is denied.');
            return false;
        } elseif ($this->configlib->item('use_register_email_auth') && ! element('user_email_cert', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                'You have not been authenticated yet.'
            );
            $this->userlib->update_login_log(element('user_id', $userinfo), $userid, 0, 'Email-unauthenticated User ID.');
            return false;
        }
		if ( ! element('user_is_admin', $userinfo)) {
            $this->form_validation->set_message(
                '_check_id_pw',
                'You can not log in.'
            );
            return false;
        }

        return true;
    }


    /**
     * 로그인 페이지입니다
     */
    public function nopage()
    {
		$view = array();
		$view['view'] = array();

		$layoutconfig = array(
			'path' => 'login',
			'layout' => 'layout',
			'skin' => 'login_nopage',
			'layout_dir' => $this->configlib->item('layout_login'),
			'mobile_layout_dir' => $this->configlib->item('mobile_layout_login'),
			'use_sidebar' => $this->configlib->item('sidebar_login'),
			'use_mobile_sidebar' => $this->configlib->item('mobile_sidebar_login'),
			'skin_dir' => $this->configlib->item('skin_login'),
			'mobile_skin_dir' => $this->configlib->item('mobile_skin_login'),
		);
		$view['layout'] = $this->layoutlib->front($layoutconfig, $this->configlib->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));

	}

    /**
     * 로그아웃합니다
     */
    public function logout()
    {

        if ($this->userlib->is_user() === false) {
            redirect();
        }

        $where = array(
            'user_id' => $this->userlib->item('user_id'),
        );

        $this->session->sess_destroy();
        $url_after_logout = $this->configlib->item('url_after_logout');
        if ($url_after_logout) {
            $url_after_logout = site_url($url_after_logout);
        }
        if (empty($url_after_logout)) {
            $url_after_logout = $this->input->get_post('url') ? $this->input->get_post('url') : site_url();
        }


        redirect($url_after_logout, 'refresh');
    }
}
