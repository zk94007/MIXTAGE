<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Collaboration_user model class
 */

class Collaboration_user_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'collaboration_user';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'cou_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $result = $this->_get_list_common($select = '', $join = '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

	public function get_user_list($col_id = '', $select='*', $user_id='')
	{
		$this->db->select($select);
		$this->db->where(array('col_id' => $col_id));
		$this->db->join('user', 'collaboration_user.user_id = user.user_id', 'inner');
		if ($user_id) {
			$this->db->join('follow', "collaboration_user.user_id = follow.target_user_id AND follow.user_id = '" . $user_id . "'" , 'left');
		}
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function is_joined_user($col_id = '', $user_id='')
	{
		if ( ! $col_id) return false;
		if ( ! $user_id) return false;

		$this->db->where(array('collaboration.col_id' => $col_id));
		$this->db->where(array('collaboration_user.user_id' => $user_id));
		$this->db->join('collaboration', 'collaboration.col_id = collaboration_user.col_id', 'inner');
		$count = $this->db->count_all_results($this->_table);

		if ($count) {
			return true;
		} else {
			return false;
		}
	}

}
