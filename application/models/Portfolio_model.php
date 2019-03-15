<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio model class
 */

class Portfolio_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'portfolio';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'por_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'portfolio.*, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_image_name, user.user_userid, user.user_username, user.user_is_admin, user.user_photo';
        $join[] = array('table' => 'user', 'on' => 'portfolio.user_id = user.user_id', 'type' => 'inner');
        $join[] = array('table' => 'collaboration', 'on' => 'portfolio.col_id = collaboration.col_id', 'type' => 'left');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

	public function get_personal_clip_list_data($user_id, $category_id, $is_collaboration = '')
	{
        $select = 'portfolio.*, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_user_count, collaboration.col_image_name, collaboration.col_blame, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo';
        
		$this->db->select($select);
		$this->db->join('user', 'portfolio.user_id = user.user_id', 'inner');
		$this->db->join('portfolio_clip', 'portfolio.por_id = portfolio_clip.por_id', 'inner');
		$this->db->join('collaboration', 'portfolio.col_id = collaboration.col_id', 'left');
		$this->db->where('portfolio_clip.user_id', $user_id);
		$this->db->where('portfolio.por_open', '1');
		if ($category_id) {
			$this->db->where('portfolio.por_category', $category_id);
		}
		if ($is_collaboration == 'Y') {
			$this->db->where('portfolio.col_id >', 0);
		}
		if ($is_collaboration == 'N') {
			$this->db->where('portfolio.col_id', 0);
		}
		$this->db->order_by('portfolio_clip.clip_id', 'DESC');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	
	}

	public function get_mentor_clip_list_data()
	{
        $select = 'portfolio.*, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo, mentor.user_id mentor_user_id, mentor.user_userid mentor_user_userid, mentor.user_username mentor_user_username, mentor.user_photo mentor_user_photo';
        
		$this->db->select($select);
		$this->db->join('user', 'portfolio.user_id = user.user_id', 'inner');
		$this->db->join('portfolio_clip', 'portfolio.por_id = portfolio_clip.por_id', 'inner');
		$this->db->join('user as mentor', 'portfolio_clip.user_id = mentor.user_id AND mentor.user_level = 5', 'inner');
		$this->db->where('portfolio.por_open', '1');
		$this->db->order_by('portfolio_clip.clip_id', 'DESC');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	
	}

	public function get_personal_like_list_data($user_id, $category_id, $is_collaboration = '')
	{
        $select = 'portfolio.*, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_user_count, collaboration.col_image_name, collaboration.col_blame, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo';
        
		$this->db->select($select);
		$this->db->join('user', 'portfolio.user_id = user.user_id', 'inner');
		$this->db->join('portfolio_like', 'portfolio.por_id = portfolio_like.por_id', 'inner');
		$this->db->join('collaboration', 'portfolio.col_id = collaboration.col_id', 'left');
		$this->db->where('portfolio_like.user_id', $user_id);
		$this->db->where('portfolio.por_open', '1');
		if ($category_id) {
			$this->db->where('portfolio.por_category', $category_id);
		}
		if ($is_collaboration == 'Y') {
			$this->db->where('portfolio.col_id >', 0);
		}
		if ($is_collaboration == 'N') {
			$this->db->where('portfolio.col_id', 0);
		}
		$this->db->order_by('portfolio_like.like_id', 'DESC');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	
	}

	public function get_mentor_like_list_data()
	{
        $select = 'portfolio.*, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo, mentor.user_id mentor_user_id, mentor.user_userid mentor_user_userid, mentor.user_username mentor_user_username, mentor.user_photo mentor_user_photo';
        
		$this->db->select($select);
		$this->db->join('user', 'portfolio.user_id = user.user_id', 'inner');
		$this->db->join('portfolio_like', 'portfolio.por_id = portfolio_like.por_id', 'inner');
		$this->db->join('user as mentor', 'portfolio_like.user_id = mentor.user_id AND mentor.user_level = 5', 'inner');
		$this->db->where('portfolio.por_open', '1');
		$this->db->order_by('portfolio_like.like_id', 'DESC');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	
	}


	public function get_personal_comment_list_data($user_id, $category_id, $is_collaboration = '')
	{
        $select = 'portfolio.*, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_user_count, collaboration.col_image_name, collaboration.col_blame, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo';
        
		$this->db->select($select);
		$this->db->join('user', 'portfolio.user_id = user.user_id', 'inner');
		$this->db->join('portfolio_comment', 'portfolio.por_id = portfolio_comment.por_id', 'inner');
		$this->db->join('collaboration', 'portfolio.col_id = collaboration.col_id', 'left');
		$this->db->where('portfolio_comment.pco_user_id', $user_id);
		$this->db->where('portfolio.por_open', '1');
		if ($category_id) {
			$this->db->where('portfolio.por_category', $category_id);
		}
		if ($is_collaboration == 'Y') {
			$this->db->where('portfolio.col_id >', 0);
		}
		if ($is_collaboration == 'N') {
			$this->db->where('portfolio.col_id', 0);
		}
		$this->db->order_by('portfolio_comment.pco_id', 'DESC');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	
	}

	public function get_mentor_comment_list_data()
	{
        $select = 'portfolio.*, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo, mentor.user_id mentor_user_id, mentor.user_userid mentor_user_userid, mentor.user_username mentor_user_username, mentor.user_photo mentor_user_photo';
        
		$this->db->select($select);
		$this->db->join('user', 'portfolio.user_id = user.user_id', 'inner');
		$this->db->join('portfolio_comment', 'portfolio.por_id = portfolio_comment.por_id', 'inner');
		$this->db->join('user as mentor', 'portfolio_comment.pco_user_id = mentor.user_id AND mentor.user_level = 5', 'inner');
		$this->db->where('portfolio.por_open', '1');
		$this->db->order_by('portfolio_comment.pco_id', 'DESC');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	
	}

	public function get_personal_list_data($my_user_id, $user_id, $is_collaboration = '')
	{
        $select = 'portfolio.*, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_user_count, collaboration.col_image_name, collaboration.col_blame, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo';
        
		$this->db->select($select);
		$this->db->join('user', 'portfolio.user_id = user.user_id', 'inner');
		$this->db->join('collaboration', 'portfolio.col_id = collaboration.col_id', 'left');
		$this->db->where('portfolio.user_id', $user_id);
		if ($my_user_id != $user_id) {
			$this->db->where('portfolio.por_open', '1');
		}
		if ($is_collaboration == 'Y') {
			$this->db->where('portfolio.col_id >', 0);
		}
		if ($is_collaboration == 'N') {
			$this->db->where('portfolio.col_id', 0);
		}
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	
	}


	public function get_all_list_data($category_id ='', $is_collaboration = '')
	{
        $select = 'portfolio.*, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_user_count, collaboration.col_image_name, collaboration.col_blame, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo';
        
		$this->db->select($select);
		$this->db->join('user', 'portfolio.user_id = user.user_id', 'inner');
		$this->db->join('collaboration', 'portfolio.col_id = collaboration.col_id', 'left');
		$this->db->where('portfolio.por_open', '1');
		if ($is_collaboration == 'Y') {
			$this->db->where('portfolio.col_id >', 0);
		}
		if ($is_collaboration == 'N') {
			$this->db->where('portfolio.col_id', 0);
		}
		if ($category_id) {
			$this->db->where('portfolio.por_category', $category_id);
		}
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	
	}


	public function get_tag_list_data($tag = '')
	{
        $select = 'portfolio.*, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_user_count, collaboration.col_image_name, collaboration.col_blame, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo';
        
		$this->db->select($select);
		$this->db->join('user', 'portfolio.user_id = user.user_id', 'inner');
		$this->db->join('portfolio_tag', 'portfolio.por_id = portfolio_tag.por_id', 'inner');
		$this->db->join('collaboration', 'portfolio.col_id = collaboration.col_id', 'left');
		$this->db->like('tag_name', $tag, 'after');
		$this->db->where('portfolio.por_open', '1');
		$this->db->group_by('portfolio.por_id');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	
	}

    public function get_one_data($por_id)
    {
        $select = 'portfolio.*, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_user_count, collaboration.col_image_name, collaboration.col_blame, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo';
        
		$this->db->select($select);
		$this->db->join('user', 'portfolio.user_id = user.user_id', 'inner');
		$this->db->join('collaboration', 'portfolio.col_id = collaboration.col_id', 'left');
		$this->db->where('portfolio.por_id', $por_id);
		$qry = $this->db->get($this->_table);
		$result = $qry->row_array();

		$this->db->where('por_id', $por_id);
		$this->db->where('pfi_is_cover', 0);
		$this->db->order_by('pfi_id', 'asc');
		$qry = $this->db->get('portfolio_file');
		$result['file'] = $qry->result_array();

        return $result;
    }

    public function update_blame($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('por_blame', 'por_blame+1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

    public function update_like($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('por_like', 'por_like+1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

    public function update_unlike($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('por_like', 'por_like-1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

    public function update_clip($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('por_clip', 'por_clip+1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

    public function update_unclip($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('por_clip', 'por_clip-1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

    public function plus_comment($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('por_comment', 'por_comment+1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

    public function minus_comment($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('por_comment', 'por_comment-1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

}
