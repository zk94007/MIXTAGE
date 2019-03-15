<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_view
 */


class Portfolio_view extends CI_Controller
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
			$this->CI->apilib->make_error("로그인 한 회원만 포트폴리오를 열람하실 수 있습니다.");
		}
		
        $por_id = (int) trim($this->CI->input->get('por_id'));
        if (empty($por_id)) {
			$this->CI->apilib->make_error("포트폴리오 고유 PK 가 입력되지 않았습니다.");
        }
		$this->CI->load->model(array('Portfolio_model', 'Collaboration_model', 'Collaboration_user_model', 'Portfolio_blame_model', 'Portfolio_clip_model', 'Portfolio_like_model', 'Portfolio_tag_model', 'Follow_model'));
		$portfolio = $this->CI->Portfolio_model->get_one_data($por_id);
		if ( ! element('por_id', $portfolio)) {
			$this->CI->apilib->make_error("존재하지 않는 포트폴리오입니다.");
		}
		if ( ! element('por_open', $portfolio) && (element('user_id', $sessionuser) != element('user_id', $portfolio))) {
			$this->CI->apilib->make_error("공개되지 않은 포트폴리오입니다.");
		}

		$category = config_item('portfolio_category');

		$users = '';
		if (element('col_id', $portfolio)) {
            $users = $this->CI->Collaboration_user_model->get_user_list(element('col_id', $portfolio), 'user.user_id, user_userid, user_email, user_username, user_level, user_artist_category, user_homepage, user_instagram, user_facebook, user_photo, follow.fol_id', element('user_id', $sessionuser));
			if ($users) {
				foreach ($users as $ukey => $uval) {
					$users[$ukey]['user_photo'] = user_photo_url($uval['user_photo']);
					$users[$ukey]['user_artist_category_name'] = ($uval['user_level'] == '5') ? '멘토' : ($uval['user_artist_category'] ? element($uval['user_artist_category'], $category) : '');
					$users[$ukey]['i_follow'] = element('fol_id', $uval) ? 1 : 0;
					unset($users[$ukey]['fol_id']);
				}
			}
		}

		$where = array('por_id' => $por_id, 'user_id' => element('user_id', $sessionuser));
		$like = $this->CI->Portfolio_like_model->get_one('', '', $where);
		$clip = $this->CI->Portfolio_clip_model->get_one('', '', $where);
		$blame = $this->CI->Portfolio_blame_model->get_one('', '', $where);

		$followwhere = array(
			'user_id' => element('user_id', $sessionuser),
			'target_user_id' => element('user_id', $portfolio),
		);
 		$follow = $this->CI->Follow_model->get_one('', '', $followwhere);
 
		$i_like = (element('like_id', $like)) ? 1 : 0;
		$i_clip = (element('clip_id', $clip)) ? 1 : 0;
		$i_blame = (element('pbl_id', $blame)) ? 1 : 0;
		$i_follow = (element('fol_id', $follow)) ? 1 : 0;

		$tags = array();
		$taglist = $this->CI->Portfolio_tag_model->get_portfolio_tags($por_id);
		if ($taglist) {
			foreach ($taglist as $val) {
				$tags[] = element('tag_name', $val);
			}
		}


		$arr = array(
			'result' => 'ok',
			'token' => $token,
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
			'por_tags' => $tags,
			'por_open' => element('por_open', $portfolio),
			'por_url' => portfolio_url(element('por_id', $portfolio)),
			'i_like' => $i_like,
			'i_clip' => $i_clip,
			'i_blame' => $i_blame,
			'i_follow' => $i_follow,
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
		if ($users) {
			$arr['users'] = $users;
		}
		return $arr;

	}


}
