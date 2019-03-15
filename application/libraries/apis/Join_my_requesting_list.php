<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Join_my_requesting_list
 */


class Join_my_requesting_list extends CI_Controller
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

		$this->CI->load->model(array('Collaboration_request_model', 'Collaboration_model'));

		$category = config_item('portfolio_category');

		$type = $this->CI->input->get('type');
		if ($type == 'A' OR $type == 'Y' OR $type == 'N' OR $type == 'R') {
		} else {
			$this->CI->apilib->make_error("type 값이 잘못되었습니다.");
		}

		$list = $this->CI->Collaboration_request_model->get_requesting_list(element('user_id', $sessionuser), $type);

		if ($list) {
			foreach ($list as $key => $val) {
				$list[$key]['user_photo'] = user_photo_url(element('user_photo', $val));
				$list[$key]['user_artist_category_name'] = ($val['user_level'] == '5') ? '멘토' : (element('user_artist_category', $val) ? element(element('user_artist_category', $val), $category) : '');

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
