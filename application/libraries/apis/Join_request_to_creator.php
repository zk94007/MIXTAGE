<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Join_request_to_creator
 */


class Join_request_to_creator extends CI_Controller
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
		
        $col_id = (int) trim($this->CI->input->post('col_id'));
        if (empty($col_id)) {
			$this->CI->apilib->make_error("콜라보레이션 고유 PK 가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Collaboration_model', 'Collaboration_user_model', 'Collaboration_request_model'));
		$collaboration = $this->CI->Collaboration_model->get_one($col_id);
		if ( ! element('col_id', $collaboration)) {
			$this->CI->apilib->make_error("존재하지 않는 콜라보레이션입니다.");
		}

		$userwhere = array(
			'col_id' => $col_id,
			'user_id' => element('user_id', $sessionuser),
		);
		$user = $this->CI->Collaboration_user_model->get_one('', '', $userwhere);
		if (element('cou_id', $user)) {
			$this->CI->apilib->make_error("회원님은 이미 이 콜라보레이션에 참여하고 있습니다.");
		}

		$reqwhere = array(
			'col_id' => $col_id,
			'artist_user_id' => element('user_id', $sessionuser),
			'cre_response' => '0',
		);
		$req = $this->CI->Collaboration_request_model->get_one('', '', $reqwhere);
		if (element('cre_id', $req)) {
			if (element('cre_type', $req) == '1') {
				$this->CI->apilib->make_error("이 콜라보레이션에서 이미 회원님에게 참여요청을 하셨습니다. 요청 목록에서 승인하여주세요..");
			} else {
				$this->CI->apilib->make_error("이미 이 콜라보레이션에 참여 요청을 하셨습니다");
			}
		}

		$insertdata = array(
			'col_id' => $col_id,
			'col_user_id' => element('col_user_id', $collaboration),
			'artist_user_id' => element('user_id', $sessionuser),
			'cre_type' => '2',
			'cre_datetime' => cdate('Y-m-d H:i:s'),
			'cre_response' => '0',
		);
		$this->CI->Collaboration_request_model->insert($insertdata);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'col_id' => $col_id,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}
}
