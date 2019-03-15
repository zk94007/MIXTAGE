<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Profile_delete_photo
 */


class Profile_delete_photo extends CI_Controller
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
		if ( ! element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("로그인 한 회원만 접근이 가능합니다.");
		}

		$user_id = element('user_id', $sessionuser);

		$updatedata = array(
			'user_photo' => '',
		);
		$this->CI->User_model->update($user_id, $updatedata);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
		);
		return $arr;
	}
}
