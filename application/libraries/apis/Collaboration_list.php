<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Collaboration_list
 */


class Collaboration_list extends CI_Controller
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

		$this->CI->load->model('Collaboration_model');

		$user_id = (int) $this->CI->input->get('user_id');

		$list = $this->CI->Collaboration_model->get_personal_list($user_id);

		if ($list) {
			foreach ($list as $key => $val) {
				$list[$key]['collaboration_image_url'] = collaboration_image_url(element('col_image_name', $val));
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
