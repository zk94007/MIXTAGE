<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Api_list model class
 */

class Api_list_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'api_list';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'api_idx'; // 사용되는 테이블의 프라이머리키

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
