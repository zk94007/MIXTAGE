<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comment_write
 */


class Comment_write extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 댓글을 작성할 수 있습니다.");
		}
		
        $por_id = (int) trim($this->CI->input->post('por_id'));
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

        $pco_id = (int) trim($this->CI->input->post('pco_id'));
        $reply_pco_id = (int) trim($this->CI->input->post('reply_pco_id'));
        $pco_content = trim($this->CI->input->post('pco_content'));
		
        if (empty($pco_content)) {
			$this->CI->apilib->make_error("댓글 내용이 입력되지 않았습니다.");
        }

		// mode : new, modify, reply
		$mode = 'new';
		if ($pco_id) {
			$mode = 'modify';
		}
		else if ($reply_pco_id) {
			$mode = 'reply';
		}

		$comment = '';
		if ($mode == 'modify') {
			$comment = $this->CI->Portfolio_comment_model->get_one($pco_id);
			if ( ! element('pco_id', $comment)) {
				$this->CI->apilib->make_error("존재하지 않는 댓글입니다.");
			}
			if (element('pco_user_id', $comment) != element('user_id', $sessionuser)) {
				$this->CI->apilib->make_error("본인의 댓글만 수정이 가능합니다.");
			}
		}

		$pco_reply = '';
		if ($mode == 'reply') {

			$origin = $this->CI->Portfolio_comment_model->get_one($reply_pco_id);

			if ( ! element('pco_id', $origin)) {
				$this->CI->apilib->make_error("존재하지 않는 댓글에 답변을 달려고 시도하고 있습니다.");
			}

			//if (element('pco_reply', $origin)) {
			//	$this->CI->apilib->make_error("답변댓글에는 더 이상 답변댓글을 입력하실 수가 없습니다.");
			//}

            $reply_len = strlen(element('pco_reply', $origin)) + 1;
            $begin_reply_char = 'A';
            $end_reply_char = 'Z';
            $reply_number = +1;
            $this->CI->db->select('MAX(SUBSTRING(pco_reply, ' . $reply_len . ', 1)) as reply', false);
            $this->CI->db->where('pco_num', element('pco_num', $origin));
            $this->CI->db->where('SUBSTRING(pco_reply, ' . $reply_len . ', 1) <>', '');
            if (element('pco_id', $origin)) {
                $this->CI->db->like('pco_reply', element('pco_reply', $origin), 'after');
            }
            $result = $this->CI->db->get('portfolio_comment');
            $row = $result->row_array();

            if ( ! element('reply', $row)) {
                $reply_char = $begin_reply_char;
            } elseif (element('reply', $row) === $end_reply_char) { // A~Z은 26 입니다.
				$this->CI->apilib->make_error("더 이상 답변하실 수 없습니다.\\n답변은 26개 까지만 가능합니다");
            } else {
                $reply_char = chr(ord(element('reply', $row)) + $reply_number);
            }
            $pco_reply = element('pco_reply', $origin) . $reply_char;

			$pco_reply_user_id = element('pco_user_id', $origin);
		}

		if ($mode == 'new') {
		
            $pco_num = $this->CI->Portfolio_comment_model->next_comment_num();
			$insertdata = array(
				'por_id' => $por_id,
				'por_user_id' => element('user_id', $portfolio),
				'pco_num' => $pco_num,
				'pco_reply' => '',
				'pco_content' => $pco_content,
				'pco_user_id' => element('user_id', $sessionuser),
				'pco_datetime' => cdate('Y-m-d H:i:s'),
				'pco_updated_datetime' => cdate('Y-m-d H:i:s'),
			);
			$pco_id = $this->CI->Portfolio_comment_model->insert($insertdata);

			$this->CI->mixtage->noti(element('user_id', $sessionuser), element('user_id', $portfolio), 'comment', $pco_id, $por_id);
		
		}
		else if ($mode == 'reply') {
		
            $pco_num = element('pco_num', $origin);
			$insertdata = array(
				'por_id' => $por_id,
				'por_user_id' => element('user_id', $portfolio),
				'pco_num' => $pco_num,
				'pco_reply' => $pco_reply,
				'pco_reply_user_id' => $pco_reply_user_id,
				'pco_content' => $pco_content,
				'pco_user_id' => element('user_id', $sessionuser),
				'pco_datetime' => cdate('Y-m-d H:i:s'),
				'pco_updated_datetime' => cdate('Y-m-d H:i:s'),
			);
			$pco_id = $this->CI->Portfolio_comment_model->insert($insertdata);

			$this->CI->mixtage->noti(element('user_id', $sessionuser), element('user_id', $portfolio), 'comment', $pco_id, $por_id);
			$this->CI->mixtage->noti(element('user_id', $sessionuser), $pco_reply_user_id, 'comment_reply', $pco_id, $por_id);

		}
		else if ($mode == 'modify') {
		
			$updatedata = array(
				'pco_content' => $pco_content,
				'pco_updated_datetime' => cdate('Y-m-d H:i:s'),
			);
			$this->CI->Portfolio_comment_model->update($pco_id, $updatedata);
		
		}

		$cwhere = array(
			'por_id' => element('por_id', $portfolio),
		);
		$comment_count = $this->CI->Portfolio_comment_model->count_by($cwhere);

		$mentor_comment_count = $this->CI->Portfolio_comment_model->count_mentor_comment(element('por_id', $portfolio));

		$updatedata = array(
			'por_comment' => $comment_count,
			'por_mentor_comment' => $mentor_comment_count,
		);
		$this->CI->Portfolio_model->update($por_id, $updatedata);


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
