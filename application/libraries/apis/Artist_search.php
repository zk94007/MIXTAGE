<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Artist_search
 */


class Artist_search extends CI_Controller
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

		$q = $this->CI->input->get('q');

		$category = config_item('portfolio_category');
		
		$list = array();

		if ($q) {
			$list = $this->CI->User_model->get_search_list($q, element('user_id', $sessionuser));
		}

		//$result = '';
    // peter 2017-02-22
    $result = array();

		$myresult = '';

		if ($list) {
			foreach ($list as $key => $val) {
				$list[$key]['user_photo'] = user_photo_url(element('user_photo', $val));
				$list[$key]['i_follow'] = element('fol_id', $val) ? 1 : 0;
				$list[$key]['user_artist_category_name'] = element(element('user_artist_category', $val), $category);
				unset($list[$key]['fol_id']);
				if (element('user_id', $sessionuser) == element('user_id', $val)) {
					$myresult = $list[$key];
				} else {
					$key2 = $key + 1;
					$result[$key2] = $list[$key];
 				}
			}
		}

		if ($myresult) {
			$result[0] = $myresult;
		}
		ksort($result);

		//$real = '';
    // peter 2017-02-22
    $real = array();

		foreach ($result as $key => $value) {
			$real[] = $value;
		}
		$count = count($real);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'count' => $count,
			'list' => $real,
		);
		return $arr;

	}


}
