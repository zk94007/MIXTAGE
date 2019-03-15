<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Seminar model class
 */

class Seminar_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'seminar';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'sem_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $result = $this->_get_list_common('', '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

	public function get_approved_data()
	{
		$this->db->where('sem_approve', '1');
		$this->db->order_by('sem_id', 'desc');
		$this->db->limit('1');
		$qry = $this->db->get($this->_table);
		$result = $qry->row_array();

		return $result;
	}
}
