<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_myinfo
 */


class User_myinfo extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 회원정보를 열람할 수 있습니다.");
		}

		$user_id = element('user_id', $sessionuser);

		$category = config_item('portfolio_category');

		$this->CI->load->model(array('User_model'));

		$user = $this->CI->User_model->get_user_info(element('user_id', $sessionuser), $user_id);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_id' => element('user_id', $user),
			'user_userid' => element('user_userid', $user),
			'user_username' => element('user_username', $user),
			'user_level' => element('user_level', $user),
			'user_artist_category' => element('user_artist_category', $user),
			'user_artist_category_name' => element(element('user_artist_category', $user), $category),
			'user_homepage' => element('user_homepage', $user),
			'user_instagram' => element('user_instagram', $user),
			'user_facebook' => element('user_facebook', $user),
			'user_following' => element('user_following', $user),
			'user_followed' => element('user_followed', $user),
			'user_blame' => element('user_blame', $user),
			'user_photo' => user_photo_url(element('user_photo', $user)),
		);
		return $arr;
	}
}
