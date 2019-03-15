<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Seminar user model class
 */

class Seminar_user_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'seminar_user';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'seu_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $result = $this->_get_list_common('', '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }

    public function get_list($sem_id)
    {
        $select = 'seminar_user.seu_datetime, user.user_id, user.user_userid, user.user_username, user.user_is_admin, user.user_photo';
        $this->db->select($select);
		$this->db->join('user', 'seminar_user.user_id = user.user_id', 'inner');
		$this->db->where('sem_id', $sem_id);
		$this->db->order_by('seu_id', 'asc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

        return $result;
    }
}
