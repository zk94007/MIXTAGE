<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Register model class
 */

class User_register_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'user_register';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'urg_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'user_register.*, user.user_id, user.user_userid, user.user_username, user.user_nickname, user.user_is_admin, user.user_icon';
        $join[] = array('table' => 'user', 'on' => 'user_register.user_id = user.user_id', 'type' => 'left');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

        return $result;
    }


    public function get_graph($start_date = '', $end_date = '')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }

        $this->db->where('left(urg_datetime, 10) >=', $start_date);
        $this->db->where('left(urg_datetime, 10) <=', $end_date);
        $this->db->select('urg_referer');
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }
}
