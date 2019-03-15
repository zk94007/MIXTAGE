<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Signup_check_password
 */


class Signup_check_password extends CI_Controller
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


		$user_password = $this->CI->input->post('user_password');
		$user_password2 = $this->CI->input->post('user_password2');
		
		if ( ! $user_password) {
			$this->CI->apilib->make_error("회원 패스워드가 입력되지 않았습니다.");
		}
		if ($user_password != $user_password2) {
			$this->CI->apilib->make_error("입력하신 패스워드가 서로 일치하지 않습니다.");
		}
		if (mb_strlen($user_password) < 7 OR mb_strlen($user_password) > 12) {
			$this->CI->apilib->make_error("회원 패스워드는 7~12 자 사이여야 합니다.");
		}

        $this->CI->load->helper('chkstring');
        $str_uc = count_uppercase($user_password);
        $str_num = count_numbers($user_password);

        if ( ! $str_uc OR ! $str_num) {
			$this->CI->apilib->make_error("회원 패스워드는 대문자와 숫자를 각각 하나 이상씩 포함하여야 합니다.");
        }

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_password' => $user_password,
		);
		return $arr;
	}
}
