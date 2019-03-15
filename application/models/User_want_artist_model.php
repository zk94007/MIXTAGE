<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Want Artist model class
 */

class User_want_artist_model extends MY_Model
{

    /**
     * 테이블명
     */
    public $_table = 'user_want_artist';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'uwa_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'user_want_artist.*, user.user_id, user.user_userid, user.user_username, user.user_nickname, user.user_level, user.user_photo';
        $join[] = array('table' => 'user', 'on' => 'user_want_artist.user_id = user.user_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

        return $result;
    }
}
