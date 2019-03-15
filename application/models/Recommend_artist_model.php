<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Recommend_artist model class
 */

class Recommend_artist_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'recommend_artist';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'rec_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

	public function get_list()
	{
		$this->db->order_by('rec_order', 'asc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}

}
