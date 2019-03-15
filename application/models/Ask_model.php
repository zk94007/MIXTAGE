<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ask model class
 */

class Ask_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'ask';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'ask_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {

        $result = $this->_get_list_common('', '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

	public function ask_user_list($my_user_id, $col_id)
	{
		$this->db->select('count(*) total_cnt, ask_user_id, user.user_id, user.user_userid, user.user_username, user.user_photo, user.user_artist_category', false);
		$where = array(
			'col_id' => $col_id,
			'col_user_id' => $my_user_id,
		);
		$this->db->where($where);
		$this->db->join('user', 'ask.ask_user_id = user.user_id', 'inner');
		$this->db->group_by('ask_user_id');
		$qry = $this->db->get($this->_table);
		
		$result = $qry->result_array();

		return $result;
	}

	public function ask_list($col_id, $col_user_id, $ask_user_id)
	{
		$where = array(
			'col_id' => $col_id,
			'col_user_id' => $col_user_id,
			'ask_user_id' => $ask_user_id,
		);
		$this->db->where($where);
		$this->db->order_by('ask_id', 'asc');
		$qry = $this->db->get($this->_table);
		
		$result = $qry->result_array();

		return $result;
	
	}


	public function ask_list_update_readtime($col_id, $col_user_id, $ask_user_id, $ask_type)
	{
		$where = array(
			'col_id' => $col_id,
			'col_user_id' => $col_user_id,
			'ask_user_id' => $ask_user_id,
			'ask_type' => $ask_type,
		);
		$this->db->where($where);
		$updatedata = array(
			'ask_read_datetime' => cdate('Y-m-d H:i:s'),
		);
        $this->db->set($updatedata);
		$this->db->update('ask');
		
		return true;
	}
}
