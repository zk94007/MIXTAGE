<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Level History model class
 */

class User_level_history_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'user_level_history';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'ulh_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'user_level_history.*, user.user_id, user.user_userid, user.user_nickname, user.user_is_admin, user.user_icon';
        $join[] = array('table' => 'user', 'on' => 'user_level_history.user_id = user.user_id', 'type' => 'left');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }
}
