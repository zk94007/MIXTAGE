<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_like
 */


class Portfolio_like extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 Like 할 수 있습니다.");
		}
		
        $por_id = (int) trim($this->CI->input->post('por_id'));
        if (empty($por_id)) {
			$this->CI->apilib->make_error("포트폴리오 고유 PK 가 입력되지 않았습니다.");
        }
        $type = trim($this->CI->input->post('type'));
        if ($type != 'Y' && $type != 'N') {
			$this->CI->apilib->make_error("Type 이 올바르지 않습니다.");
        }

		$this->CI->load->model(array('Portfolio_model', 'User_model', 'Portfolio_like_model'));
		$portfolio = $this->CI->Portfolio_model->get_one($por_id);
		if ( ! element('por_id', $portfolio)) {
			$this->CI->apilib->make_error("존재하지 않는 포트폴리오입니다.");
		}
		if ( ! element('por_open', $portfolio)) {
			$this->CI->apilib->make_error("공개되지 않은 포트폴리오입니다.");
		}

		$likewhere = array(
			'por_id' => $por_id,
			'user_id' => element('user_id', $sessionuser),
		);
		$like = $this->CI->Portfolio_like_model->get_one('', '', $likewhere);
		if (element('like_id', $like) && $type == 'Y') {
			$this->CI->apilib->make_error("이미 Like 하셨습니다.");
		}
		if ( ! element('like_id', $like) && $type == 'N') {
			$this->CI->apilib->make_error("이미 Like 를 해제하셨습니다.");
		}

		if ($type == 'Y') {
			$insertdata = array(
				'por_id' => element('por_id', $portfolio),
				'user_id' => element('user_id', $sessionuser),
				'target_user_id' => element('user_id', $portfolio),
				'like_datetime' => cdate('Y-m-d H:i:s'),
			);
			$like_id = $this->CI->Portfolio_like_model->insert($insertdata);

			$this->CI->mixtage->noti(element('user_id', $sessionuser), element('user_id', $portfolio), 'like', $like_id, $por_id);
		}

		if ($type == 'N') {
			$this->CI->Portfolio_like_model->delete(element('like_id', $like));

			$this->CI->mixtage->deletenoti(element('user_id', $sessionuser), element('user_id', $portfolio), 'like', element('like_id', $like));
		}

		$cwhere = array(
			'por_id' => element('por_id', $portfolio),
		);
		$like_count = $this->CI->Portfolio_like_model->count_by($cwhere);

		$mentor_like_count = $this->CI->Portfolio_like_model->count_mentor_like(element('por_id', $portfolio));

		$updatedata = array(
			'por_like' => $like_count,
			'por_mentor_like' => $mentor_like_count,
		);
		$this->CI->Portfolio_model->update($por_id, $updatedata);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'por_id' => $por_id,
			'datetime' => cdate('Y-m-d H:i:s'),
			'type' => $type,
			'like_count' => $like_count,
		);
		return $arr;

	}


}
