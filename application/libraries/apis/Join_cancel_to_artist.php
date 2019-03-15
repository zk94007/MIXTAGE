<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Join_cancel_to_artist
 */


class Join_cancel_to_artist extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 요청할 수 있습니다.");
		}
		
        $cre_id = (int) trim($this->CI->input->post('cre_id'));

		if (empty($cre_id)) {
			$this->CI->apilib->make_error("요청 고유 PK 가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Collaboration_model', 'Collaboration_user_model', 'Collaboration_request_model'));
		$req = $this->CI->Collaboration_request_model->get_one($cre_id);
		if ( ! element('cre_id', $req)) {
			$this->CI->apilib->make_error("잘못된 요청입니다..");
		}
		if (element('cre_type', $req) != '1') {
			$this->CI->apilib->make_error("잘못된 요청입니다..");
		}
		if (element('col_user_id', $req) != element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("잘못된 요청입니다..");
		}
		if (element('cre_response', $req)) {
			$this->CI->apilib->make_error("이미 응답한 요청은 취소할 수 없습니다");
		}

		$this->CI->Collaboration_request_model->delete($cre_id);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'cre_id' => $cre_id,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}
}
