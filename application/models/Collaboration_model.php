<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Collaboration model class
 */

class Collaboration_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'collaboration';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'col_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'collaboration.*, user.user_userid, user.user_username, user.user_is_admin, user.user_photo';
        $join[] = array('table' => 'user', 'on' => 'collaboration.col_user_id = user.user_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }


    public function get_personal_list($user_id = '')
    {
		if ( ! $user_id) return;

		$this->db->select('collaboration.*');
		$this->db->join('collaboration_user', 'collaboration.col_id = collaboration_user.col_id', 'inner');
		$this->db->where('collaboration_user.user_id', $user_id);
		$this->db->order_by('cou_id', 'asc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
    }


    public function get_one_data($col_id)
    {
        $select = 'collaboration.col_id, collaboration.col_desc, collaboration.col_datetime, collaboration.col_user_id, collaboration.col_user_count, collaboration.col_image_name, collaboration.col_blame, user.user_userid, user.user_username, user.user_artist_category, user.user_level, user.user_photo';
// corrected by Peter 2017-03-02
        $this->db->where('collaboration.col_id = ' . $col_id);
//--------------------------------------      
		$this->db->select($select);
		$this->db->join('user', 'collaboration.col_user_id = user.user_id', 'inner');
		$qry = $this->db->get($this->_table);
		$result = $qry->row_array();

        return $result;
    }

/**
 * created by Peter 2017-03-01
 * 특정 회원이 생성한 콜라보레이션 목록
 */
    public function get_my_list($user_id){
      if (!$user_id)
        return;
        $this->db->select('collaboration.*');
    		$this->db->join('collaboration_user', 'collaboration.col_id = collaboration_user.col_id', 'inner');
    		$this->db->where('collaboration.col_user_id', $user_id);
    		$this->db->order_by('cou_id', 'asc');
    		$qry = $this->db->get($this->_table);
    		$result = $qry->result_array();

    		return $result;
    }

}
