<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Collaboration_view
 */


class Collaboration_view extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 콜라보레이션을 열람하실 수 있습니다.");
		}
		
        $col_id = (int) trim($this->CI->input->get('col_id'));
        if (empty($col_id)) {
			$this->CI->apilib->make_error("콜라보레이션 고유 PK 가 입력되지 않았습니다.");
        }
		$this->CI->load->model(array('Portfolio_model', 'Collaboration_model', 'Collaboration_user_model', 'Portfolio_blame_model', 'Portfolio_clip_model', 'Portfolio_like_model', 'Portfolio_tag_model', 'Follow_model'));
		$collaboration = $this->CI->Collaboration_model->get_one_data($col_id);
		if ( ! element('col_id', $collaboration)) {
			$this->CI->apilib->make_error("존재하지 않는 콜라보레이션입니다.");
		}

		$category = config_item('portfolio_category');

		$users = $this->CI->Collaboration_user_model->get_user_list($col_id, 'user.user_id, user_userid, user_email, user_username, user_level, user_artist_category, user_homepage, user_instagram, user_facebook, user_photo, follow.fol_id', element('user_id', $sessionuser));
		if ($users) {
			foreach ($users as $ukey => $uval) {
				$users[$ukey]['user_photo'] = user_photo_url($uval['user_photo']);
				$users[$ukey]['user_artist_category_name'] = ($uval['user_level'] == '5') ? '멘토' : ($uval['user_artist_category'] ? element($uval['user_artist_category'], $category) : '');
				$users[$ukey]['i_follow'] = element('fol_id', $uval) ? 1 : 0;
				unset($users[$ukey]['fol_id']);
			}
		}

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'col_id' => element('col_id', $collaboration),
			'col_desc' => element('col_desc', $collaboration),
			'col_desc_short' => html_escape(cut_str(element('col_desc', $collaboration), 20)),
			'col_datetime' => element('col_datetime', $collaboration),
			'col_user_id' => element('col_user_id', $collaboration),
			'col_user_count' => element('col_user_count', $collaboration),
			'col_image_name' => collaboration_image_url(element('col_image_name', $collaboration)),
			'col_blame' => element('col_blame', $collaboration),
			'user_userid' => element('user_userid', $collaboration),
			'user_artist_category' => element('user_artist_category', $collaboration),
			'user_artist_category_name' => ($collaboration['user_level'] == '5') ? '멘토' : (element('user_artist_category', $collaboration) ? element(element('user_artist_category', $collaboration), $category) : ''),
			'user_username' => element('user_username', $collaboration),
			'user_level' => element('user_level', $collaboration),
			'user_photo' => user_photo_url(element('user_photo', $collaboration)),
		);
		if ($users) {
			$arr['users'] = $users;
		}
		return $arr;

	}


}
