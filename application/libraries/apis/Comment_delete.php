<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comment_delete
 */


class Comment_delete extends CI_Controller
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


		$this->CI->load->model(array('Portfolio_model', 'Portfolio_comment_model'));
		
		$sessionuser = $this->CI->User_model->get_by_token($token);
		if ( ! element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("로그인 한 회원만 댓글을 삭제할 수 있습니다.");
		}
		
        $por_id = (int) trim($this->CI->input->post('por_id'));
        if (empty($por_id)) {
			$this->CI->apilib->make_error("포트폴리오 고유 PK 가 입력되지 않았습니다.");
        }
		$portfolio = $this->CI->Portfolio_model->get_one($por_id);
		if ( ! element('por_id', $portfolio)) {
			$this->CI->apilib->make_error("존재하지 않는 포트폴리오입니다.");
		}

        $pco_id = (int) trim($this->CI->input->post('pco_id'));
		$comment = $this->CI->Portfolio_comment_model->get_one($pco_id);
		if ( ! element('pco_id', $comment)) {
			$this->CI->apilib->make_error("존재하지 않는 댓글입니다.");
		}
		if (element('pco_user_id', $comment) != element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인의 댓글만 삭제가 가능합니다.");
		}
		
		$this->CI->mixtage->delete_portfolio_comment($pco_id);

		$this->CI->mixtage->deletenoti(element('user_id', $sessionuser), element('user_id', $portfolio), 'comment', $pco_id);
		$this->CI->mixtage->deletenoti(element('user_id', $sessionuser), element('pco_reply_user_id', $comment), 'comment_reply', $pco_id);
		
		
		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'por_id' => $por_id,
			'pco_id' => $pco_id,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;

	}


}
