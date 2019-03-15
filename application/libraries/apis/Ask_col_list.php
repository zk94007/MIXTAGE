<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ask_col_list
 */


class Ask_col_list extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 열람할 수 있습니다.");
		}
		
        $col_id = (int) trim($this->CI->input->get('col_id'));
        if (empty($col_id)) {
			$this->CI->apilib->make_error("콜라보레이션 PK 가 입력되지 않았습니다.");
        }

		$this->CI->load->model(array('Collaboration_model', 'User_model', 'Ask_model'));
		$collaboration = $this->CI->Collaboration_model->get_one($col_id);
		if ( ! element('col_id', $collaboration)) {
			$this->CI->apilib->make_error("존재하지 않는 콜라보레이션입니다.");
		}
		if (element('col_user_id', $collaboration) != element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인이 생성한 콜라보레이션이 아닙니다.");
		}

    $category = config_item('portfolio_category');

		$list = $this->CI->Ask_model->ask_user_list(element('user_id', $sessionuser), $col_id);
		if ($list) {
			foreach ($list as $key => $val) {
				$where = array(
					'col_id' => $col_id,
					'col_user_id' => element('user_id', $sessionuser),
					'ask_user_id' => element('ask_user_id', $val),
					'ask_type' => '1',
					'ask_read_datetime' => null,
				);
				$unread_cnt = $this->CI->Ask_model->count_by($where);
				$list[$key]['unread_count'] = $unread_cnt;
        $list[$key]['user_artist_category_name'] = element(element('user_artist_category', $val), $category);
        $list[$key]['user_photo'] = user_photo_url(element('user_photo', $val));
			}
		}

		$count = count($list);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'count' => $count,
			'list' => $list,
		);
		return $arr;

	}


}
