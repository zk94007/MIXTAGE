<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Login Log model class
 */

class User_login_log_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'user_login_log';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'ull_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'user_login_log.*, user.user_id, user.user_userid, user.user_nickname, user.user_is_admin, user.user_icon';
        $join[] = array('table' => 'user', 'on' => 'user_login_log.user_id = user.user_id', 'type' => 'left');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }


    public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $result = $this->_get_list_common($select = '', $join = '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }


    public function get_login_success_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';

        $this->db->select('count(*) as cnt, left(ull_datetime, ' . $left . ') as day ', false);
        $this->db->where('ull_success', 1);
        $this->db->where('left(ull_datetime, 10) >=', $start_date);
        $this->db->where('left(ull_datetime, 10) <=', $end_date);
        $this->db->group_by('day');
        $this->db->order_by('ull_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }


    public function get_login_fail_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';

        $this->db->select('count(*) as cnt, left(ull_datetime, ' . $left . ') as day ', false);
        $this->db->where('ull_success', 0);
        $this->db->where('left(ull_datetime, 10) >=', $start_date);
        $this->db->where('left(ull_datetime, 10) <=', $end_date);
        $this->db->group_by('day');
        $this->db->order_by('ull_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }
}
