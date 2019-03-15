<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Profile_check_userid
 */


class Profile_check_userid extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 접근할 수 있습니다.");
		}

		$user_id = element('user_id', $sessionuser);

		$user_userid = $this->CI->input->post('user_userid');
		
		if ( ! $user_userid) {
			$this->CI->apilib->make_error("회원 아이디가 입력되지 않았습니다.");
		}
		if ( ! ctype_alnum($user_userid) ) {
			$this->CI->apilib->make_error("회원 아이디는 알파벳과 숫자로만 구성되어야 합니다.");
		}
		if (mb_strlen($user_userid) < 3 OR mb_strlen($user_userid) > 25) {
			$this->CI->apilib->make_error("회원 아이디는 3~25 자 사이여야 합니다.");
		}
		if ($user_userid != element('user_userid', $sessionuser)) {
			$userwhere = array(
				'user_userid' => $user_userid,
			);
			$row = $this->CI->User_model->get_one('', 'user_id', $userwhere);
			if (element('user_id', $row)) {
				$this->CI->apilib->make_error("이미 다른 회원이 사용하고 있는 아이디이므로 이 아이디는 사용하실 수 없습니다.");
			}
		}

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'user_userid' => $user_userid,
			'msg' => '사용이 가능합니다',
		);
		return $arr;
	}
}
