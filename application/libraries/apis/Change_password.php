<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Change_password
 */


class Change_password extends CI_Controller
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

		$user_id = $this->CI->input->post('user_id');
		$verify_code = $this->CI->input->post('verify_code');
		$user_password = $this->CI->input->post('user_password');
		$user_password2 = $this->CI->input->post('user_password2');

		if ( ! $user_id) {
			$this->CI->apilib->make_error("회원 고유 키값이 입력되지 않았습니다.");
		}
		if ( ! $verify_code) {
			$this->CI->apilib->make_error("회원 인증번호가 입력되지 않았습니다.");
		}

		$this->CI->load->model(array('User_auth_email_model'));

        $where = array(
            'user_id' => $user_id,
            'uae_key' => $verify_code,
            'uae_type' => '6',
        );
        $result = $this->CI->User_auth_email_model->get_one('', '', $where);
		if ( ! element('uae_id', $result)) {
			$this->CI->apilib->make_error("일치하는 회원 정보가 없습니다..");
		}



		if ( ! $user_password) {
			$this->CI->apilib->make_error("회원 패스워드가 입력되지 않았습니다.");
		}
		if ($user_password != $user_password2) {
			$this->CI->apilib->make_error("패스워드가 일치하지 않습니다.");
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

		$updatedata = array(
			'user_password' => password_hash($user_password, PASSWORD_BCRYPT),
		);

		$user = $this->CI->User_model->update($user_id, $updatedata);

        $where = array(
            'user_id' => $user_id,
            'uae_key' => $verify_code,
            'uae_type' => '6',
        );
        $result = $this->CI->User_auth_email_model->delete('', $where);


		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_id' => $user_id,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;
	}
}
