<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Generalrequest model class
 */

class Generalrequest_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'generalrequest';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'gre_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'generalrequest.*, user.user_id, user.user_userid, user.user_username, user.user_level, user.user_photo';
        $join[] = array('table' => 'user', 'on' => 'generalrequest.user_id = user.user_id', 'type' => 'left');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
        return $result;
    }
}
