<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Get_config
 */


class Get_config extends CI_Controller
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

		if ( ! $this->CI->input->get('type')) {
			$this->CI->apilib->make_error("Type 을 입력하여 주십시오.");
		}

		$allow_type = array('user_register_policy1', 'user_register_policy2');
		if ( ! in_array($this->CI->input->get('type'), $allow_type)) {
			$this->CI->apilib->make_error("Type 을 올바르게 입력하여 주십시오.");
		}

		$data = $this->CI->configlib->item($this->CI->input->get('type'));

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'data' => $data,
		);
		return $arr;

	}


}
