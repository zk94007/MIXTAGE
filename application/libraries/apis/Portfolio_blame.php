<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_blame
 */


class Portfolio_blame extends CI_Controller
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
		
        $por_id = (int) trim($this->CI->input->post('por_id'));
        if (empty($por_id)) {
			$this->CI->apilib->make_error("포트폴리오 고유 PK 가 입력되지 않았습니다.");
        }
        $pbl_reason = trim($this->CI->input->post('reason'));
        if (empty($pbl_reason)) {
			$this->CI->apilib->make_error("신고 이유가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Portfolio_model', 'User_model', 'Portfolio_blame_model'));
		$portfolio = $this->CI->Portfolio_model->get_one($por_id);
		if ( ! element('por_id', $portfolio)) {
			$this->CI->apilib->make_error("존재하지 않는 포트폴리오입니다.");
		}
		if ( ! element('por_open', $portfolio)) {
			$this->CI->apilib->make_error("공개되지 않은 포트폴리오입니다.");
		}
		$target_user = $this->CI->User_model->get_one(element('user_id', $portfolio));


		if (element('user_level', $target_user) == '5') {
			$this->CI->apilib->make_error("멘토의 포트폴리오는 신고하실 수 없습니다.");
		}
		if (element('user_level', $target_user) == '10') {
			$this->CI->apilib->make_error("관리자의 포트폴리오는 신고하실 수 없습니다.");
		}
		if (element('user_id', $target_user) == element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인이 작성하신 포트폴리오는 신고하실 수 없습니다.");
		}
		$blamewhere = array(
			'por_id' => $por_id,
			'user_id' => element('user_id', $sessionuser),
		);
		$blame = $this->CI->Portfolio_blame_model->get_one('', '', $blamewhere);
		if (element('pbl_id', $blame)) {
			$this->CI->apilib->make_error("이미 신고하신 포트폴리오입니다.");
		}

		$insertdata = array(
			'por_id' => element('por_id', $portfolio),
			'user_id' => element('user_id', $sessionuser),
			'target_user_id' => element('user_id', $portfolio),
			'pbl_reason' => $pbl_reason,
			'pbl_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->Portfolio_blame_model->insert($insertdata);

		$cwhere = array(
			'por_id' => element('por_id', $portfolio),
		);
		$blame_count = $this->CI->Portfolio_blame_model->count_by($cwhere);

		$updatedata = array(
			'por_blame' => $blame_count,
		);
		$this->CI->Portfolio_model->update($por_id, $updatedata);


		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'por_id' => $por_id,
			'datetime' => cdate('Y-m-d H:i:s'),
			'blame_count' => $blame_count,
		);
		return $arr;

	}


}
