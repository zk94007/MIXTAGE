<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Api_input model class
 */

class Api_input_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'api_input';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'ai_idx'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $result = $this->_get_list_common($select = '', $join = '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

	public function select_max_sort($api_idx)
	{
		$this->db->select_max('ai_sort');
		$this->db->where('api_idx', $api_idx);
		$qry = $this->db->get($this->_table);
		$result = $qry->row_array();
		return $result['ai_sort'];
	}

	public function update_sort($api_idx, $ai_sort)
	{
		$this->db->where('api_idx', $api_idx);
		$this->db->where('ai_sort >=', $ai_sort);
        $this->db->set('ai_sort', 'ai_sort+1', false);
        $result = $this->db->update($this->_table);
		return $result;
	}
}
