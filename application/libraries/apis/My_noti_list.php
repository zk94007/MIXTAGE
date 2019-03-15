<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * My_noti_list
 */


class My_noti_list extends CI_Controller
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
		
		$category = config_item('portfolio_category');
        
		$this->CI->load->model(array('Portfolio_model', 'Collaboration_model', 'Collaboration_user_model', 'Portfolio_clip_model', 'Portfolio_like_model', 'Portfolio_comment_model', 'Noti_model'));

		$not_type = '';
		if ($this->CI->input->get('not_type')) {
			$not_type = $this->CI->input->get('not_type');
		}
		$result = $this->CI->Noti_model->get_personal_list(element('user_id', $sessionuser), $not_type);

		$list = array();
		if ($result) {
			foreach ($result as $key => $value) {
				$list[$key] = array(
					'por_id' => element('por_id', $value),
					'por_category_id' => element('por_category', $value),
					'por_category_name' => element(element('por_category', $value), $category),
					'por_content' => html_escape(element('por_content', $value)),
					'por_content_short' => html_escape(cut_str(element('por_content', $value), 20)),
					'por_datetime' => element('por_datetime', $value),
					'por_cover_image' => portfolio_image_url(element('por_cover_image_name', $value)),
					'por_url' => portfolio_url(element('por_id', $value)),
					'user_id' => element('user_id', $value),
					'user_userid' => element('user_userid', $value),
					'user_artist_category' => element('user_artist_category', $value),
					'user_artist_category_name' => ($value['user_level'] == '5') ? '멘토' : (element('user_artist_category', $value) ? element(element('user_artist_category', $value), $category) : ''),
					'user_username' => element('user_username', $value),
					'user_level' => element('user_level', $value),
					'user_photo' => user_photo_url(element('user_photo', $value)),
					'not_type' => element('not_type', $value),
					'not_datetime' => element('not_datetime', $value),
				);

				if (element('not_type', $value) == 'clip') {
					$list[$key]['message'] = $list[$key]['user_artist_category_name'] . ' ' . element('user_username', $value) . '님이 아티스트님의 피드를 클립했습니다.';
				} else if (element('not_type', $value) == 'like') {
					$list[$key]['message'] = $list[$key]['user_artist_category_name'] . ' ' . element('user_username', $value) . '님이 아티스트님의 피드를 좋아합니다.';
				} else if (element('not_type', $value) == 'comment') {
					$list[$key]['message'] = $list[$key]['user_artist_category_name'] . ' ' . element('user_username', $value) . '님이 아티스트님의 피드에 코멘트를 남겼습니다.';
				} else if (element('not_type', $value) == 'comment_reply') {
					$list[$key]['message'] = $list[$key]['user_artist_category_name'] . ' ' . element('user_username', $value) . '님이 아티스트님의 코멘트에 답변댓글을 남겼습니다.';
				} else if (element('not_type', $value) == 'follow') {
					$list[$key]['message'] = $list[$key]['user_artist_category_name'] . ' ' . element('user_username', $value) . '님이 아티스트님을 팔로우하였습니다.';
				} else if (element('not_type', $value) == 'portfolio') {
					$list[$key]['message'] = $list[$key]['user_artist_category_name'] . ' ' . element('user_username', $value) . '님이 아티스트님과 함께한 콜라보레이션 작업을 업로드했습니다.';
				}

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
