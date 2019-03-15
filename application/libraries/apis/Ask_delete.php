<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ask_delete
 */


class Ask_delete extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 문의를 삭제할 수 있습니다.");
		}
		
        $ask_id = (int) trim($this->CI->input->post('ask_id'));
        if (empty($ask_id)) {
			$this->CI->apilib->make_error("삭제할 문의 PK 가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Collaboration_model', 'User_model', 'Ask_model'));
		$ask = $this->CI->Ask_model->get_one($ask_id);
		if ( ! element('ask_id', $ask)) {
			$this->CI->apilib->make_error("존재하지 않는 문의입니다.");
		}
		if (element('user_id', $ask) != element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인이 작성한 문의가 아니므로 삭제할 수 없습니다.");
		}

		$this->CI->Ask_model->delete($ask_id);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}


}
