<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comment_blame
 */


class Comment_blame extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 댓글을 신고할 수 있습니다.");
		}
		
        $pco_id = (int) trim($this->CI->input->post('pco_id'));
        if (empty($pco_id)) {
			$this->CI->apilib->make_error("댓글 고유  PK 가 입력되지 않았습니다.");
        }
        $pcb_reason = trim($this->CI->input->post('reason'));
        if (empty($pcb_reason)) {
			$this->CI->apilib->make_error("댓글 신고 이유가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Portfolio_comment_model', 'User_model', 'Portfolio_comment_blame_model'));
		$comment = $this->CI->Portfolio_comment_model->get_one($pco_id);
		if ( ! element('pco_id', $comment)) {
			$this->CI->apilib->make_error("존재하지 않는 댓글입니다.");
		}
		$target_user = $this->CI->User_model->get_one(element('pco_user_id', $comment));


		if (element('user_level', $target_user) == '5') {
			$this->CI->apilib->make_error("멘토의 댓글은 신고하실 수 없습니다.");
		}
		if (element('user_level', $target_user) == '10') {
			$this->CI->apilib->make_error("관리자의 댓글은 신고하실 수 없습니다.");
		}
		if (element('user_id', $target_user) == element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인이 작성하신 댓글은 신고하실 수 없습니다.");
		}
		$blamewhere = array(
			'pco_id' => $pco_id,
			'user_id' => element('user_id', $sessionuser),
		);
		$blame = $this->CI->Portfolio_comment_blame_model->get_one('', '', $blamewhere);
		if (element('pcb_id', $blame)) {
			$this->CI->apilib->make_error("이미 신고하신 댓글입니다.");
		}

		$insertdata = array(
			'por_id' => element('por_id', $comment),
			'pco_id' => $pco_id,
			'user_id' => element('user_id', $sessionuser),
			'target_user_id' => element('pco_user_id', $comment),
			'pcb_reason' => $pcb_reason,
			'pcb_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->Portfolio_comment_blame_model->insert($insertdata);

		$this->CI->Portfolio_comment_model->update_blame($pco_id);


		$blame_count = element('pco_blame', $comment) + 1;
		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'pco_id' => $pco_id,
			'datetime' => cdate('Y-m-d H:i:s'),
			'blame_count' => $blame_count,
		);
		return $arr;

	}


}
