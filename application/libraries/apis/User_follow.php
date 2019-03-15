<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_follow
 */


class User_follow extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 Follow 할 수 있습니다.");
		}

		$target_user_id = (int) trim($this->CI->input->post('target_user_id'));
        if (empty($target_user_id)) {
			$this->CI->apilib->make_error("대상회원의 PK 가 입력되지 않았습니다.");
        }
        $type = trim($this->CI->input->post('type'));
        if ($type != 'Y' && $type != 'N') {
			$this->CI->apilib->make_error("Type 이 올바르지 않습니다.");
        }

		$this->CI->load->model(array('User_model', 'Follow_model'));
		$target_user = $this->CI->User_model->get_one($target_user_id);
		if ( ! element('user_id', $target_user)) {
			$this->CI->apilib->make_error("존재하지 않는 회원입니다.");
		}

		if (element('user_id', $target_user) == element('user_id', $sessionuser)) {
			$this->CI->apilib->make_error("본인을 Follow 하실 수 없습니다.");
		}
		$followwhere = array(
			'target_user_id' => $target_user_id,
			'user_id' => element('user_id', $sessionuser),
		);
		$follow = $this->CI->Follow_model->get_one('', '', $followwhere);
		if (element('fol_id', $follow) && $type == 'Y') {
			$this->CI->apilib->make_error("이미 Follow 하셨습니다.");
		}
		if ( ! element('fol_id', $follow) && $type == 'N') {
			$this->CI->apilib->make_error("이미 Follow 를 해제하셨습니다.");
		}

		if ($type == 'Y') {
			$insertdata = array(
				'user_id' => element('user_id', $sessionuser),
				'target_user_id' => $target_user_id,
				'fol_datetime' => cdate('Y-m-d H:i:s'),
			);
			$fol_id = $this->CI->Follow_model->insert($insertdata);

			$this->CI->User_model->plus_following(element('user_id', $sessionuser));
			$this->CI->User_model->plus_followed($target_user_id);
			$follow_count = element('user_followed', $target_user) + 1;

			$this->CI->mixtage->noti(element('user_id', $sessionuser), $target_user_id, 'follow', $fol_id);

		}

		if ($type == 'N') {
			$this->CI->Follow_model->delete(element('fol_id', $follow));

			$this->CI->User_model->minus_following(element('user_id', $sessionuser));
			$this->CI->User_model->minus_followed($target_user_id);
			$follow_count = element('user_followed', $target_user) - 1;

			$this->CI->mixtage->deletenoti(element('user_id', $sessionuser), $target_user_id, 'follow', element('fol_id', $follow));
		}

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'target_user_id' => $target_user_id,
			'datetime' => cdate('Y-m-d H:i:s'),
			'type' => $type,
			'follow_count' => $follow_count,
		);
		return $arr;

	}


}
