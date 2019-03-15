<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Login_by_verify_code
 */


class Login_by_verify_code extends CI_Controller
{

    private $CI;

    function __construct()
    {
        $this->CI = & get_instance();

    }

	function main()
	{

		if ( ! $this->CI->input->post_get('token')) {
			$this->CI->apilib->make_error("토큰 값이 넘어오지 않았습니다.");
        }
		$token = $this->CI->input->post_get('token');

        if ( ! function_exists('password_hash')) {
            $this->load->helper('password');
        }

		if ( ! $this->CI->input->post('userid')) {
			$this->CI->apilib->make_error("회원 아이디가 입력되지 않았습니다.");
		}
		if ( ! $this->CI->input->post('verify_code')) {
			$this->CI->apilib->make_error("인증코드가 않았습니다.");
		}

		$userid = trim($this->CI->input->post('userid'));
		$verify_code = trim($this->CI->input->post('verify_code'));

		if ( ! ctype_alnum($userid) ) {
			$this->CI->apilib->make_error("회원 아이디는 알파벳과 숫자로만 구성되어야 합니다.");
		}

		$this->CI->load->model('User_auth_email_model');

		$certinfo = $this->CI->User_auth_email_model->get_one('', '', array('uae_key' => $verify_code));


		$user_id = element('user_id', $certinfo);


        $userinfo = $this->CI->User_model->get_by_id($user_id, 'user_id, user_userid, user_denied');

		if ( ! element('user_id', $userinfo)) {
			$this->CI->apilib->make_error("존재하지 않는 회원정보입니다.");
        }
        if (element('user_denied', $userinfo)) {
			$this->CI->apilib->make_error("회원님의 아이디는 접근이 금지된 아이디입니다");
        }

		$updatedata = array(
			'user_token' => '',
		);
		$upwhere = array(
			'user_token' => $token,
		);
		$this->CI->User_model->update('', $updatedata, $upwhere);

		$updatedata = array(
			'user_token' => $token,
		);
		$this->CI->User_model->update(element('user_id', $userinfo), $updatedata);


		/*
		$this->CI->session->set_userdata(
			'user_id',
			element('user_id', $userinfo)
		);
		*/

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_id' => element('user_id', $userinfo),
		);
		return $arr;

	}

}
