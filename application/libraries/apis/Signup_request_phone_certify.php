<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Signup_request_phone_certify
 */


class Signup_request_phone_certify extends CI_Controller
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

		$sessionuser = $this->CI->User_model->get_by_token($token);
		if (element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("이미 로그인 하신 회원은 접근하실 수 없습니다.");
		}

		$user_phone = $this->CI->input->post('user_phone');
		$user_phone = str_replace('-', '', $user_phone);
		
		if ( ! $user_phone) {
			$this->CI->apilib->make_error("휴대폰 번호가 입력되지 않았습니다.");
		}
		if ( ! is_phone($user_phone) ) {
			$this->CI->apilib->make_error("올바른 휴대폰 번호가 아닙니다.");
		}

		$this->CI->load->model(array('User_phone_certify_model'));

		$deletewhere = array(
			'pce_phone' => $user_phone,
			'pce_activated' => 0,
		);
		$this->CI->User_phone_certify_model->delete('', $deletewhere);

		$pce_code = rand(100000,999999);
		
		$msg = '[믹스테이지] 본인인증번호는 ' . $pce_code . ' 입니다. 정확히 입력해주세요';

		// 문자 발송
		$send_result = send_sms($user_phone, $msg);
		//if ($send_result) echo $send_result;

		$insertdata = array(
			'pce_phone' => $user_phone,
			'pce_code' => $pce_code,
			'pce_datetime' => cdate('Y-m-d H:i:s'),
			'pce_activated' => 0,
		);
		$this->CI->User_phone_certify_model->insert($insertdata);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_phone' => $user_phone . " ",
			'verify_code' => $pce_code,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;
	}
}
