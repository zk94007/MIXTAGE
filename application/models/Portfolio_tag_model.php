<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_tag model class
 */

class Portfolio_tag_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'portfolio_tag';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'tag_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $result = $this->_get_list_common($select = '', $join = '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

	public function get_portfolio_tags($por_id)
	{
		$this->db->select('tag_name');
		$this->db->where('por_id', $por_id);
		$this->db->order_by('tag_id', 'asc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
