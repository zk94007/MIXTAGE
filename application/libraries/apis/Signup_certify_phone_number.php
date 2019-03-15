<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Signup_certify_phone_number
 */


class Signup_certify_phone_number extends CI_Controller
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

		$verify_code = $this->CI->input->post('verify_code');
		if ( ! $verify_code) {
			$this->CI->apilib->make_error("인증 코드가 입력되지 않았습니다.");
		}

		$this->CI->load->model(array('User_phone_certify_model'));

		$getwhere = array(
			'pce_phone' => $user_phone,
			'pce_activated' => 0,
		);
		$result = $this->CI->User_phone_certify_model->get_one('', '', $getwhere);
		if ( ! element('pce_id', $result)) {
			$this->CI->apilib->make_error("인증은 받으신 후에 인증받으신 코드를 입력하여주세요.");
		}
		if (element('pce_code', $result) != $verify_code) {
			$this->CI->apilib->make_error("인증번호가 올바르지 않습니다.");
		}
		if (strtotime(element('pce_datetime', $result)) < time() - 180 ) {
			$this->CI->apilib->make_error("인증 문자를 받으신지 3분이 지났습니다. 다시 시도하여주세요.");
		}

		$updatedata = array(
			'pce_activated' => '1',
			'pce_activated_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->User_phone_certify_model->update(element('pce_id', $result), $updatedata);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;
	}
}
