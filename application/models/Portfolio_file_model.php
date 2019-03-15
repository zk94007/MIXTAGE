<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Portfolio_file model class
 */

class Portfolio_file_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'portfolio_file';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'pfi_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $result = $this->_get_list_common($select = '', $join = '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }
}
