<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Signup_check_email
 */


class Signup_check_email extends CI_Controller
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

		$this->CI->load->helper('email');
		
		$sessionuser = $this->CI->User_model->get_by_token($token);
		if (element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("이미 로그인 하신 회원은 접근하실 수 없습니다.");
		}

		$user_email = $this->CI->input->post('user_email');
		
		if ( ! $user_email) {
			$this->CI->apilib->make_error("회원 이메일이 입력되지 않았습니다.");
		}

		if ( ! valid_email($user_email) ) {
			$this->CI->apilib->make_error("이메일 형식에 맞지 않습니다.");
		}

		$userwhere = array(
			'user_email' => $user_email,
		);
		$row = $this->CI->User_model->get_one('', 'user_id', $userwhere);
		if (element('user_id', $row)) {
			$this->CI->apilib->make_error("이미 다른 회원이 사용하고 있는 이메일입니다.");
		}

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_email' => $user_email,
		);
		return $arr;
	}
}
