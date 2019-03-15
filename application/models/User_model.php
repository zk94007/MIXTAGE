<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User model class
 */

class User_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'user';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'user_id'; // 사용되는 테이블의 프라이머리키

    public $search_sfield = '';

    function __construct()
    {
        parent::__construct();
    }


    public function get_by_id($id = 0, $select = '')
    {
        $id = (int) $id;
        if (empty($id) OR $id < 1) {
            return false;
        }
        $where = array('user_id' => $id);
        return $this->get_one('', $select, $where);
    }


    public function get_by_userid($userid = '', $select = '')
    {
        if (empty($userid)) {
            return false;
        }
        $where = array('user_userid' => $userid);
        return $this->get_one('', $select, $where);
    }


	public function get_by_token($token = '', $select = '')
    {
        if (empty($token)) {
            return false;
        }
        $where = array('user_token' => $token);
        return $this->get_one('', $select, $where);
    }


    public function get_by_email($email = '', $select = '')
    {
        if (empty($email)) {
            return false;
        }
        $where = array('user_email' => $email);
        return $this->get_one('', $select, $where);
    }

    public function get_by_phone($phone = '', $select = '')
    {
        if (empty($phone)) {
            return false;
        }
        $where = array('user_phone' => $phone);
        return $this->get_one('', $select, $where);
    }

    public function get_by_both($str = '', $select = '')
    {
        if (empty($str)) {
            return false;
        }
        if ($select) {
            $this->db->select($select);
        }
        $this->db->from($this->_table);
        $this->db->where('user_userid', $str);
        $this->db->or_where('user_email', $str);
        $result = $this->db->get();
        return $result->row_array();
    }


    public function get_superadmin_list($select='')
    {
        $where = array(
            'user_is_admin' => 1,
            'user_denied' => 0,
        );
        $result = $this->get('', $select, $where);

        return $result;
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $join = array();
        if (isset($where['ugr_id'])) {
            $select = 'user.*';
            $join[] = array('table' => 'user_group_user', 'on' => 'user.user_id = user_group_user.user_id', 'type' => 'left');
        }
        $result = $this->_get_list_common($select = '', $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

        return $result;
    }


    public function get_register_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';

        $this->db->select('count(*) as cnt, left(user_register_datetime, ' . $left . ') as day ', false);
        $this->db->where('left(user_register_datetime, 10) >=', $start_date);
        $this->db->where('left(user_register_datetime, 10) <=', $end_date);
        $this->db->where('user_denied', 0);
        $this->db->group_by('day');
        $this->db->order_by('user_register_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }


    public function update_blame($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('user_blame', 'user_blame+1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

    public function plus_followed($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('user_followed', 'user_followed+1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

    public function minus_followed($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('user_followed', 'user_followed-1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

    public function plus_following($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('user_following', 'user_following+1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

    public function minus_following($primary_value = '')
    {
        if (empty($primary_value)) {
            return false;
        }

        $this->db->where($this->primary_key, $primary_value);
        $this->db->set('user_following', 'user_following-1', false);
        $result = $this->db->update($this->_table);

        return $result;
    }

	public function get_search_list($q, $user_id)
	{
		$this->db->select('user.user_id, user_userid, user_username, user_level, user_artist_category, user_homepage, user_instagram, user_facebook, user_photo, follow.fol_id');
		//$this->db->where('user_level', '3');
		$this->db->like('user_userid', $q, 'after');
		$this->db->join('follow', "user.user_id = follow.target_user_id AND follow.user_id = '" . $user_id . "'", 'left');
		$this->db->order_by('user.user_userid', 'asc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}

/**
 * created by Peter 2017-03-01
 * except_list에 있는 회원들을 제외하고 회원전체리스트를 얻는다.
 **/
  public function get_list_except_some($except_pks,$user_id) {

    $this->db->select('user.user_id, user_userid, user_username, user_level, user_artist_category, user_homepage, user_instagram, user_facebook, user_photo, follow.fol_id');
    $this->db->where("user.user_denied < 1");
    if($except_pks)
		  $this->db->where("user.user_id not in ($except_pks)");
		$this->db->join('follow', "user.user_id = follow.target_user_id AND follow.user_id = '" . $user_id . "'", 'left');
		$this->db->order_by('user.user_userid', 'asc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
  }

	public function get_following_list($user_id)
	{
		$this->db->select('user.user_id, user_userid, user_username, user_level, user_artist_category, user_homepage, user_instagram, user_facebook, user_photo, follow.fol_id');
		$this->db->join('follow', 'user.user_id = follow.target_user_id', 'inner');
		$this->db->where('follow.user_id', $user_id);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}

	public function get_followed_list($user_id)
	{
		$this->db->select('user.user_id, user_userid, user_username, user_level, user_artist_category, user_homepage, user_instagram, user_facebook, user_photo, follow.fol_id');
		$this->db->join('follow', 'user.user_id = follow.user_id', 'inner');
		$this->db->where('follow.target_user_id', $user_id);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}

	public function get_user_info($my_user_id, $user_id)
	{
		
		$this->db->select('user.user_id, user_userid, user_username, user_level, user_artist_category, user_homepage, user_instagram, user_facebook, user_following, user_followed, user_blame, user_photo, follow.fol_id, user_blame.ubl_id');
		$this->db->where('user.user_id', $user_id);
		$this->db->from('user');
		$this->db->join('follow', "user.user_id = follow.target_user_id AND follow.user_id = '" . $my_user_id . "'", 'left');
		$this->db->join('user_blame', "user.user_id = user_blame.target_user_id AND user_blame.user_id = '" . $my_user_id . "'", 'left');
		$qry = $this->db->get();
		$result = $qry->row_array();
		
		return $result;
	}

}
