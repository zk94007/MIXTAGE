<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_info
 */


class User_info extends CI_Controller
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

		$user_id = (int) trim($this->CI->input->get('user_id'));
        if (empty($user_id)) {
			$this->CI->apilib->make_error("대상회원의 PK 가 입력되지 않았습니다.");
        }

		$category = config_item('portfolio_category');

		$this->CI->load->model(array('User_model'));

		$user = $this->CI->User_model->get_user_info(element('user_id', $sessionuser), $user_id);
		if ( ! element('user_id', $user)) {
			$this->CI->apilib->make_error("회원정보가 검색되지 않습니다.");
		}

		$i_follow = element('fol_id', $user) ? 1 : 0;
		$i_blame = element('ubl_id', $user) ? 1 : 0;

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
			'i_follow' => $i_follow,
			'i_blame' => $i_blame,
		);
		return $arr;

	}


}
