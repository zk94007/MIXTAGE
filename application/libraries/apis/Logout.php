<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Logout
 */


class Logout extends CI_Controller
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

        //$this->CI->session->sess_destroy();

		$updatedata = array(
			'user_token' => '',
		);
		$upwhere = array(
			'user_token' => $token,
		);
		$this->CI->User_model->update('', $updatedata, $upwhere);


		$arr = array(
			'result' => 'ok',
			'token' => $token,
		);
		return $arr;

	}


}
