<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Artist_category
 */


class Artist_category extends CI_Controller
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

		/**
		 * It's Peter's way to comment next 4 lines
		 */
		// $sessionuser = $this->CI->User_model->get_by_token($token);
		// if ( ! element('user_id', $sessionuser)) {
		// 	$this->CI->apilib->make_error("로그인 한 회원만 열람할 수 있습니다.");
		// }

		/**
		 * Peter's way
		 */
		$list = config_item('portfolio_category');
    	$result = [];
    	foreach ($list as $key => $value) {
      		array_push($result, array(
        		"id" => $key,
        		"name" => $list[$key]
      		));
    	}

    	/**
    	 * Korean's way
    	 */
    	$data = config_item('portfolio_category');
		$list = array();
		foreach ($data as $key => $val) {
			$list[] = array(
				'id' => $key,
				'title' => $val,
			);
		}

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'list' => $list,	// 'list' => $result, // Peter's way
		);
		return $arr;

	}


}
