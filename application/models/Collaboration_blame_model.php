<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Collaboration_blame model class
 */

class Collaboration_blame_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'collaboration_blame';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'cbl_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }

    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'collaboration_blame.*, collaboration.col_desc, collaboration.col_blame, collaboration.col_datetime, user.user_id, user.user_userid, user.user_username, user.user_is_admin, user.user_photo';
        $join[] = array('table' => 'user', 'on' => 'collaboration_blame.user_id = user.user_id', 'type' => 'inner');
        $join[] = array('table' => 'collaboration', 'on' => 'collaboration_blame.col_id = collaboration.col_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

    public function get_blame_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';

        $this->db->select('count(*) as cnt, left(cbl_datetime, ' . $left . ') as day ', false);
        $this->db->where('left(cbl_datetime, 10) >=', $start_date);
        $this->db->where('left(cbl_datetime, 10) <=', $end_date);

        $this->db->group_by('day');
        $this->db->order_by('cbl_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;

    }
}
