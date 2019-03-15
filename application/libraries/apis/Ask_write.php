<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ask_write
 */


class Ask_write extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 문의를 작성할 수 있습니다.");
		}
		
        $col_id = (int) trim($this->CI->input->post('col_id'));
        if (empty($col_id)) {
			$this->CI->apilib->make_error("콜라보레이션 PK 가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Collaboration_model', 'User_model', 'Ask_model'));
		$collaboration = $this->CI->Collaboration_model->get_one($col_id);
		if ( ! element('col_id', $collaboration)) {
			$this->CI->apilib->make_error("존재하지 않는 콜라보레이션입니다.");
		}
		if (element('col_user_id', $collaboration) == element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인이 생성한 콜라보레이션이므로 문의를 작성할 수 없습니다.");
		}

		if ( ! $this->CI->input->post('ask_content')) {
			$this->CI->apilib->make_error("문의 내용을 입력하여 주십시오.");
		}

		$insertdata = array(
			'col_id' => $col_id,
			'col_user_id' => element('col_user_id', $collaboration),
			'ask_user_id' => element('user_id', $sessionuser),
			'user_id' => element('user_id', $sessionuser),
			'ask_type' => '1',
			'ask_content' => $this->CI->input->post('ask_content'),
			'ask_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->Ask_model->insert($insertdata);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}


}
