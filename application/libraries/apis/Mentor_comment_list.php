<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mentor_comment_list
 */


class Mentor_comment_list extends CI_Controller
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
        
		$this->CI->load->model(array('Portfolio_model', 'Collaboration_model', 'Collaboration_user_model', 'Portfolio_blame_model', 'Portfolio_clip_model', 'Portfolio_like_model'));

		$result = $this->CI->Portfolio_model->get_mentor_comment_list_data();

		$list = array();
		$mentor = array();
		if ($result) {
			foreach ($result as $portfolio) {

				$list[element('mentor_user_id', $portfolio)][] = array(
					'por_id' => element('por_id', $portfolio),
					'por_category_id' => element('por_category', $portfolio),
					'por_category_name' => element(element('por_category', $portfolio), $category),
					'user_id' => element('user_id', $portfolio),
					'por_content' => html_escape(element('por_content', $portfolio)),
					'por_content_short' => html_escape(cut_str(element('por_content', $portfolio), 20)),
					'por_like' => element('por_like', $portfolio),
					'por_mentor_like' => element('por_mentor_like', $portfolio),
					'por_comment' => element('por_comment', $portfolio),
					'por_mentor_comment' => element('por_mentor_comment', $portfolio),
					'por_clip' => element('por_clip', $portfolio),
					'por_mentor_clip' => element('por_mentor_clip', $portfolio),
					'por_blame' => element('por_blame', $portfolio),
					'por_datetime' => element('por_datetime', $portfolio),
					'por_updated_datetime' => element('por_updated_datetime', $portfolio),
					'por_cover_image' => portfolio_image_url(element('por_cover_image_name', $portfolio)),
					'por_open' => element('por_open', $portfolio),
					'por_url' => portfolio_url(element('por_id', $portfolio)),
					'user_userid' => element('user_userid', $portfolio),
					'user_artist_category' => element('user_artist_category', $portfolio),
					'user_artist_category_name' => ($portfolio['user_level'] == '5') ? '멘토' : (element('user_artist_category', $portfolio) ? element(element('user_artist_category', $portfolio), $category) : ''),
					'user_username' => element('user_username', $portfolio),
					'user_level' => element('user_level', $portfolio),
					'user_photo' => user_photo_url(element('user_photo', $portfolio)),
					'mentor_user_id' => element('mentor_user_id', $portfolio),
					'mentor_user_userid' => element('mentor_user_userid', $portfolio),
					'mentor_user_username' => element('mentor_user_username', $portfolio),
					'mentor_user_photo' => user_photo_url(element('mentor_user_photo', $portfolio)),
				);
				$mentor[element('mentor_user_id', $portfolio)] = array(
					'mentor_user_id' => element('mentor_user_id', $portfolio),
					'mentor_user_userid' => element('mentor_user_userid', $portfolio),
					'mentor_user_username' => element('mentor_user_username', $portfolio),
					'mentor_user_photo' => user_photo_url(element('mentor_user_photo', $portfolio)),
				);
			}
		}

		$count = count($list);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'list' => $list,
			'mentor' => array_values($mentor),
		);
		return $arr;

	}


}
