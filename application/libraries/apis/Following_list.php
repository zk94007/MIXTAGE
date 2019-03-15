<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Following_list
 */


class Following_list extends CI_Controller
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
			$this->CI->apilib->make_error("로그인한 회원만 접근이 가능합니다.");
		}

		$this->CI->load->model('Follow_model');

		$category = config_item('portfolio_category');
		
		$user_id = (int) $this->CI->input->get('user_id');

		$list = $this->CI->User_model->get_following_list($user_id);

		if ($list) {
			foreach ($list as $key => $val) {
				$list[$key]['user_photo'] = user_photo_url(element('user_photo', $val));

				$followwhere = array(
					'user_id' => element('user_id', $sessionuser),
					'target_user_id' => element('user_id', $val),
				);
				$follow = $this->CI->Follow_model->get_one('', 'fol_id', $followwhere);

 				$list[$key]['i_follow'] = element('fol_id', $follow) ? 1 : 0;;
				$list[$key]['user_artist_category_name'] = element(element('user_artist_category', $val), $category);
				unset($list[$key]['fol_id']);
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
