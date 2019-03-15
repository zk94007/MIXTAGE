<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Send_reset_password_phone
 */


class Send_reset_password_phone extends CI_Controller
{

    private $CI;

    function __construct()
    {
        $this->CI = & get_instance();

        $this->CI->load->library(array('email'));
    }

	function main()
	{

		if ( ! $this->CI->input->post_get('token')) {
			$this->CI->apilib->make_error("토큰 값이 넘어오지 않았습니다.");
        }
		$token = $this->CI->input->post_get('token');

		$sessionuser = $this->CI->User_model->get_by_token($token);

		$phone = trim($this->CI->input->post('phone'));
		if ( ! $phone) {
			$this->CI->apilib->make_error("휴대폰 번호가 입력되지 않았습니다.");
		}

        $this->CI->load->helper('string');
		if ( ! function_exists('password_hash')) {
            $this->CI->load->helper('password');
        }

		$this->CI->load->model(array('User_auth_email_model'));

        $userinfo = $this->CI->User_model->get_by_phone($phone, 'user_id, user_userid, user_email, user_phone, user_denied, user_username, user_password');

        if (element('user_denied', $userinfo)) {
			$this->CI->apilib->make_error("회원님의 아이디는 접근이 금지된 아이디입니다");
        }
        if ( ! element('user_id', $userinfo)) {
			$this->CI->apilib->make_error("일치하는 회원정보가 없습니다.");
        }

		$user_id = element('user_id', $userinfo);

		$vericode = array('$', '/', '.');
		$verificationcode = str_replace(
			$vericode,
			'',
			password_hash($user_id . '-' . element('user_email', $userinfo) . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
		);
		$verificationcode = substr($verificationcode, 0, 15);

		$beforeauthdata = array(
			'user_id' => $user_id,
			'uae_type' => 6,
		);
		$this->CI->User_auth_email_model->delete_where($beforeauthdata);
		$authdata = array(
			'user_id' => $user_id,
			'uae_key' => $verificationcode,
			'uae_type' => 6,
			'uae_generate_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->User_auth_email_model->insert($authdata);


        $verify_url = site_url('mixtageverify/resetpassword?user=' . element('user_userid', $userinfo) . '&code=' . $verificationcode);

		$msg = "[믹스테이지] 다음 링크를 통해 패스워드를 변경하실 수 있습니다.\n" . $verify_url;           // 메시지

		// 문자 발송
		$send_result = send_sms($phone, $msg);


		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_id' => element('user_id', $userinfo),
			'user_phone' => element('user_phone', $userinfo),
		);
		return $arr;

	}
}
