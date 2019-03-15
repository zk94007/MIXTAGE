<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_personal_list
 */


class Portfolio_personal_list extends CI_Controller
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
        
		$is_collaboration = $this->CI->input->get('is_collaboration');
		if ($is_collaboration != 'Y' && $is_collaboration != 'N') {
			$is_collaboration = '';
		}

		$is_mentor_pick = $this->CI->input->get('is_mentor_pick');
		if ($is_mentor_pick != 'Y') {
			$is_mentor_pick = '';
		}

		$user_id = (int) $this->CI->input->get('user_id');

		if ( ! $user_id) {
			$this->CI->apilib->make_error("회원 아이디가 입력되지 않았습니다.");
		}

		$this->CI->load->model(array('Portfolio_model', 'Collaboration_model', 'Collaboration_user_model', 'Portfolio_blame_model', 'Portfolio_clip_model', 'Portfolio_like_model', 'Portfolio_comment_model'));

		$result = $this->CI->Portfolio_model->get_personal_list_data(element('user_id', $sessionuser), $user_id, $is_collaboration);


		$list = array();
		$is_picked = '';
		if ($result) {
			foreach ($result as $portfolio) {

				if ($is_mentor_pick == 'Y') {
					$is_picked = '';

					if ( ! $is_picked) {
						$cnt = $this->CI->Portfolio_like_model->count_mentor($user_id);
						if ($cnt) {
							$is_picked = '1';
						}
					}

					if ( ! $is_picked) {
						$cnt = $this->CI->Portfolio_comment_model->count_mentor($user_id);
						if ($cnt) {
							$is_picked = '1';
						}
					}

					if ( ! $is_picked) {
						$cnt = $this->CI->Portfolio_clip_model->count_mentor($user_id);
						if ($cnt) {
							$is_picked = '1';
						}
					}

					if ( ! $is_picked) {
						continue;
					}
				}

				$list[] = array(
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
					'col_id' => element('col_id', $portfolio),
					'col_desc' => html_escape(element('col_desc', $portfolio)),
					'col_desc_short' => html_escape(cut_str(element('col_desc', $portfolio), 20)),
					'col_datetime' => element('col_datetime', $portfolio),
					'col_user_id' => element('col_user_id', $portfolio),
					'col_user_count' => element('col_user_count', $portfolio),
					'col_image_name' => collaboration_image_url(element('col_image_name', $portfolio)),
					'col_blame' => element('col_blame', $portfolio),
					'user_userid' => element('user_userid', $portfolio),
					'user_artist_category' => element('user_artist_category', $portfolio),
					'user_artist_category_name' => ($portfolio['user_level'] == '5') ? '멘토' : (element('user_artist_category', $portfolio) ? element(element('user_artist_category', $portfolio), $category) : ''),
					'user_username' => element('user_username', $portfolio),
					'user_level' => element('user_level', $portfolio),
					'user_photo' => user_photo_url(element('user_photo', $portfolio)),
				);
			}
		}

		$count = count($list);

		$arr = array(
			'result' => 'ok',
			'token' => $token,
			'count' => $count,
			'is_collaboration' => $is_collaboration,
			'is_mentor_pick' => $is_mentor_pick,
			'list' => $list,
		);
		return $arr;

	}


}
