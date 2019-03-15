<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_clip_count
 */


class Portfolio_clip_count extends CI_Controller
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
		
		$this->CI->load->model(array('Portfolio_clip_model'));

		$is_collaboration = $this->CI->input->get('is_collaboration');
		if ($is_collaboration != 'Y' && $is_collaboration != 'N') {
			$is_collaboration = '';
		}
		
		$clipwhere = array(
			'user_id' => element('user_id', $sessionuser),
		);
		$data = $this->CI->Portfolio_clip_model->count_by_category(element('user_id', $sessionuser), $is_collaboration);

		$res = array();
		foreach($data as $dk => $dv) {
			$res[element('por_category', $dv)] = element('cnt', $dv);
		}
		
		$category = config_item('portfolio_category');
		
		$list = array();
		foreach ($category as $ckey => $cval) {
			$cnt = element($ckey, $res) + 0;
			// $list[$ckey] = array(
			// 	'category_id' => $ckey,
			// 	'category_name' => $cval,
			// 	'count' => $cnt,
			// );
/**
 *   corrected by peter 2017-02-26
 */
      array_push($list, array(
        'category_id' => $ckey,
        'category_name' => $cval,
        'count' => $cnt,
      ));
		}

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'is_collaboration' => $is_collaboration,
			'list' => $list,
		);
		return $arr;

	}


}
