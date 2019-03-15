<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Follow model class
 */

class Follow_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'follow';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'fol_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $result = $this->_get_list_common($select = '', $join = '', $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }


    public function get_following_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'follow.*, user.user_id, user.user_userid, user.user_level, user.user_nickname,
            user.user_is_admin, user.user_icon, user.user_lastlogin_datetime';
        $join[] = array('table' => 'user', 'on' => 'follow.target_user_id = user.user_id', 'type' => 'left');

        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }


    public function get_followed_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'follow.*, user.user_id, user.user_userid, user.user_level, user.user_nickname,
            user.user_is_admin, user.user_icon, user.user_lastlogin_datetime';
        $join[] = array('table' => 'user', 'on' => 'follow.user_id = user.user_id', 'type' => 'left');

        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }
}
