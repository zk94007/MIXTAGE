<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_blame
 */


class User_blame extends CI_Controller
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

		$target_user_id = (int) trim($this->CI->input->post('target_user_id'));
        if (empty($target_user_id)) {
			$this->CI->apilib->make_error("대상회원의 PK 가 입력되지 않았습니다.");
        }
        $ubl_reason = trim($this->CI->input->post('reason'));
        if (empty($ubl_reason)) {
			$this->CI->apilib->make_error("신고 이유가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('User_model', 'User_blame_model'));
		$target_user = $this->CI->User_model->get_one($target_user_id);
		if ( ! element('user_id', $target_user)) {
			$this->CI->apilib->make_error("존재하지 않는 회원입니다.");
		}

		if (element('user_level', $target_user) == '5') {
			$this->CI->apilib->make_error("멘토를 신고하실 수 없습니다.");
		}
		if (element('user_level', $target_user) == '10') {
			$this->CI->apilib->make_error("관리자를 신고하실 수 없습니다.");
		}
		if (element('user_id', $target_user) == element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인을 신고하실 수 없습니다.");
		}
		$blamewhere = array(
			'target_user_id' => $target_user_id,
			'user_id' => element('user_id', $sessionuser),
		);
		$blame = $this->CI->User_blame_model->get_one('', '', $blamewhere);
		if (element('ubl_id', $blame)) {
			$this->CI->apilib->make_error("이미 이 회원을 신고하셨습니다.");
		}

		$insertdata = array(
			'user_id' => element('user_id', $sessionuser),
			'target_user_id' => element('user_id', $target_user),
			'ubl_reason' => $ubl_reason,
			'ubl_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->User_blame_model->insert($insertdata);

		$this->CI->User_model->update_blame($target_user_id);


		$blame_count = element('user_blame', $target_user) + 1;
		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'target_user_id' => $target_user_id,
			'datetime' => cdate('Y-m-d H:i:s'),
			'blame_count' => $blame_count,
		);
		return $arr;

	}


}
