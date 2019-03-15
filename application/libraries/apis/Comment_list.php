<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comment_list
 */


class Comment_list extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 댓글을 보실 수 있습니다.");
		}
		
        $por_id = (int) trim($this->CI->input->get('por_id'));
        if (empty($por_id)) {
			$this->CI->apilib->make_error("포트폴리오 고유 PK 가 입력되지 않았습니다.");
        }
		$portfolio = $this->CI->Portfolio_model->get_one($por_id);
		if ( ! element('por_id', $portfolio)) {
			$this->CI->apilib->make_error("존재하지 않는 포트폴리오입니다.");
		}
		if ( ! element('por_open', $portfolio)) {
			$this->CI->apilib->make_error("공개되지 않은 포트폴리오입니다.");
		}

		$type = $this->CI->input->get('type');
		if ($type != 'M' && $type != 'G') $type = '';

		$count = $this->CI->Portfolio_comment_model->get_count($por_id, $type );
		$result = $this->CI->Portfolio_comment_model->get_api_list($por_id, $type);

		if ($result) {
			foreach ($result as $key => $val) {
				$result[$key]['pco_content'] = html_escape(element('pco_content', $val));
				$result[$key]['i_blame'] = element('pcb_id', $val) ? 1 : 0;
				unset($result[$key]['ubl_id']);

				$result[$key]['pco_reply_user_userid'] = '';
				if (element('pco_reply_user_id', $val)) {
					$reply_user = $this->CI->User_model->get_one(element('pco_reply_user_id', $val), 'user_userid');
					$result[$key]['pco_reply_user_userid'] = element('user_userid', $reply_user);
				}


			}
		}

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'count' => $count,
			'type' => $type,
			'list' => $result,
		);
		return $arr;

	}


}
