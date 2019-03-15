<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Join_request_list
 */


class Join_request_list extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 조회할 수 있습니다.");
		}
		
        $col_id = (int) trim($this->CI->input->get('col_id'));
        if (empty($col_id)) {
			$this->CI->apilib->make_error("콜라보레이션 고유 PK 가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Collaboration_model', 'Collaboration_user_model', 'Collaboration_request_model'));
		$collaboration = $this->CI->Collaboration_model->get_one($col_id);
		if ( ! element('col_id', $collaboration)) {
			$this->CI->apilib->make_error("존재하지 않는 콜라보레이션입니다.");
		}
		if (element('col_user_id', $collaboration) != element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인이 생성한 콜라보레이션이 아닙니다.");
		}

        $cre_response = '';
		$type = trim($this->CI->input->get('type'));

		if ($type == 'A' OR $type == 'Y' OR $type == 'N' OR $type == 'R') {

		} else {

			$this->CI->apilib->make_error("type 값이 잘못되었습니다.");
		}

		$list = $this->CI->Collaboration_request_model->get_list($col_id, $type);

		$count = count($list);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'col_id' => $col_id,
			'type' => $type,
			'count' => $count,
			'list' => $list,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}
}
