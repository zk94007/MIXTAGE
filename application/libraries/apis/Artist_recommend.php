<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Artist_recommend
 */


class Artist_recommend extends CI_Controller
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

		$this->CI->load->model(array('Recommend_artist_model'));

		$list = $this->CI->Recommend_artist_model->get_list();

		if ($list) {
			foreach ($list as $key => $val) {
				$list[$key]['rec_content'] = json_decode(element('rec_content', $val), '1');
			}
		}

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'list' => $list,
		);
		return $arr;

	}


}
