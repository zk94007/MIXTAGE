<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Request_mixtage_artist
 */


class Request_mixtage_artist extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 접근하실 수 있습니다.");
		}
		if (element('user_level', $sessionuser) > 1) {
			$this->CI->apilib->make_error("일반 회원만 접근하실 수 있습니다.");
		}

		$this->CI->load->model(array('User_want_artist_model'));

		$where = array(
			'user_id' => element('user_id', $sessionuser),
		);
		$want = $this->CI->User_want_artist_model->get_one('', '', $where);
		if (element('uwa_id', $want)) {
			$this->CI->apilib->make_error("회원님은 이미 지원하셨습니다.");
		}

		$insertdata = array(
			'user_id' => element('user_id', $sessionuser),
			'uwa_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->CI->User_want_artist_model->insert($insertdata);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'datetime' => cdate('Y-m-d H:i:s'),
		);
		return $arr;
	}
}
