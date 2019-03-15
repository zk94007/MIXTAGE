<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Note model class
 */

class Note_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'note';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'nte_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_note($where = '')
    {
        if (empty($where)) {
            return;
        }
        $select = 'note.*, user.user_id, user.user_userid, user.user_nickname, user.user_is_admin, user.user_icon';
        $this->db->select($select);
        $this->db->from($this->_table);
        if (isset($where['send_user_id']) && $where['send_user_id']) {
            $this->db->join('user', 'note.recv_user_id = user.user_id', 'left');
        } elseif (isset($where['recv_user_id']) && $where['recv_user_id']) {
            $this->db->join('user', 'note.send_user_id = user.user_id', 'left');
        }
        $this->db->where($where);
        $result = $this->db->get();

        return $result->row_array();
    }


    public function get_send_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'note.*, user.user_id, user.user_userid, user.user_nickname, user.user_is_admin, user.user_icon';
        $join[] = array('table' => 'user', 'on' => 'note.recv_user_id = user.user_id', 'type' => 'left');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

        return $result;
    }


    public function get_recv_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'note.*, user.user_id, user.user_userid, user.user_nickname, user.user_is_admin, user.user_icon';
        $join[] = array('table' => 'user', 'on' => 'note.send_user_id = user.user_id', 'type' => 'left');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

        return $result;
    }
}
