<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Collaboration_blame
 */


class Collaboration_blame extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 신고할 수 있습니다.");
		}
		
        $col_id = (int) trim($this->CI->input->post('col_id'));
        if (empty($col_id)) {
			$this->CI->apilib->make_error("콜라보레이션 고유 PK 가 입력되지 않았습니다.");
        }
        $cbl_reason = trim($this->CI->input->post('reason'));
        if (empty($cbl_reason)) {
			$this->CI->apilib->make_error("신고 이유가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Collaboration_model', 'User_model', 'Collaboration_blame_model'));
		$collaboration = $this->CI->Collaboration_model->get_one($col_id);
		if ( ! element('col_id', $collaboration)) {
			$this->CI->apilib->make_error("존재하지 않는 콜라보레이션입니다.");
		}
		$target_user = $this->CI->User_model->get_one(element('col_user_id', $collaboration));


		if (element('user_level', $target_user) == '5') {
			$this->CI->apilib->make_error("멘토의 콜라보레이션은 신고하실 수 없습니다.");
		}
		if (element('user_level', $target_user) == '10') {
			$this->CI->apilib->make_error("관리자의 콜라보레이션은 신고하실 수 없습니다.");
		}
		if (element('user_id', $target_user) == element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인이 작성하신 콜라보레이션은 신고하실 수 없습니다.");
		}
		$blamewhere = array(
			'col_id' => $col_id,
			'user_id' => element('user_id', $sessionuser),
		);
		$blame = $this->CI->Collaboration_blame_model->get_one('', '', $blamewhere);
		if (element('cbl_id', $blame)) {
			$this->CI->apilib->make_error("이미 신고하신 콜라보레이션입니다.");
		}

		$insertdata = array(
			'col_id' => element('col_id', $collaboration),
			'user_id' => element('user_id', $sessionuser),
			'target_user_id' => element('user_id', $collaboration),
			'cbl_reason' => $cbl_reason,
			'cbl_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->Collaboration_blame_model->insert($insertdata);

		$cwhere = array(
			'col_id' => element('col_id', $collaboration),
		);
		$blame_count = $this->CI->Collaboration_blame_model->count_by($cwhere);

		$updatedata = array(
			'col_blame' => $blame_count,
		);
		$this->CI->Collaboration_model->update($col_id, $updatedata);


		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'col_id' => $col_id,
			'datetime' => cdate('Y-m-d H:i:s'),
			'blame_count' => $blame_count,
		);
		return $arr;

	}


}
