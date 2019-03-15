<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Deleteaccount
 */


class Deleteaccount extends CI_Controller
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
			$this->CI->apilib->make_error("로그인한 회원만 접근이 가능합니다.");
		}

		if (element('user_level', $sessionuser) >= 10) {
			$this->CI->apilib->make_error("관리자 계정은 삭제할 수 없습니다.");
		}
		$this->CI->mixtage->delete_user(element('user_id', $sessionuser));
        //$this->CI->session->sess_destroy();



		$arr = array(
			'result' => 'ok',
			'token' => $token,
		);
		return $arr;

	}


}
