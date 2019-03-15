<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Generalrequest
 */


class Generalrequest extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 문제 신고할 수 있습니다.");
		}
		
		$this->CI->load->model(array('Generalrequest_model'));


		if ( ! $this->CI->input->post('content')) {
			$this->CI->apilib->make_error("내용을 입력하여 주십시오.");
		}

		$insertdata = array(
			'gre_content' => $this->CI->input->post('content'),
			'user_id' => element('user_id', $sessionuser),
			'gre_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->Generalrequest_model->insert($insertdata);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}


}
